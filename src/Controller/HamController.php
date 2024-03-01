<?php

namespace App\Controller;

use App\Entity\Ham;
use App\Form\HamType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HamController extends AbstractController
{
    #[Route('/', name: 'app_ham')]
    public function index(EntityManagerInterface $em, Request $r, SluggerInterface $slugger): Response
    {

        $un_ham = new Ham();
        $form = $this->createForm(HamType::class, $un_ham);
        $form->handleRequest($r);

        if($form->isSubmitted() && $form->isValid()){
            $slug = $slugger->slug($un_ham->getTitle()) . '-' . uniqid();
            $un_ham->setSlug($slug);

            $em->persist($un_ham);
            $em->flush();
        }

        $hams = $em->getRepository(Ham::class)->findAll();

        return $this->render('ham/index.html.twig', [
            'hams' => $hams,
            'form' => $form->createView()
        ]);
    }

    #[Route('/ham/delete/{id}', name: 'ham_delete')]
    public function delete(Request $r, EntityManagerInterface $em, Ham $ham) 
    {
        if($this->isCsrfTokenValid('delete'.$ham->getId(), $r->request->get('csrf'))){
            $em->remove($ham);
            $em->flush();
        }

        return $this->redirectToRoute('app_ham');
    }

    #[Route('/ham/{slug}', name: 'ham_show')]
    public function show(Ham $ham) 
    {
        return $this->render('ham/show.html.twig', [
            'ham' => $ham
        ]);
    }

    #[Route('/ham/edit/{slug}', name: 'ham_edit')]
    public function edit(Ham $ham, Request $r, EntityManagerInterface $em) 
    {
        $form = $this->createForm(HamType::class, $ham);
        $form->handleRequest($r);

        if($form->isSubmitted() && $form->isValid()){
            $em->flush();
            return $this->redirectToRoute('app_ham');
        }

        return $this->render('ham/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }
}

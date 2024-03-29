<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ChangeInfosType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class InfosController extends AbstractController
{
    #[Route('/mes-informations', name: 'app_infos')]
    public function index(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        if ($this->IsGranted("ROLE_USER")) {
            $user = $this->getUser();

            $form = $this->createForm(ChangeInfosType::class, $user);

                $form->handleRequest($request);

                if ($form->isSubmitted() && $form->isValid()) {

                    $entityManager->persist($user);
                    $entityManager->flush(); 

                    $this->addFlash('success', 'Vos informations ont bien été modifiées.');
                    return $this->redirect($request->getUri());
                }

            return $this->render('infos/infos.html.twig', [
                'controller_name' => 'InfosController',
                'formInfos' => $form
            ]);
        } else {
            $this->addFlash('danger', 'Vous n\'avez pas accès à cette page. Veuillez vous authentifier ou vous inscrire.');
            return $this->redirectToRoute('main_home');
        }
    }
    
}

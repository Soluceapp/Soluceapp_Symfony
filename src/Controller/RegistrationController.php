<?php

namespace App\Controller;

use App\Entity\Dutil;
use App\Form\RegistrationFormType;
use App\Repository\DutilRepository;
use App\Security\DutilAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use App\Services\JWTService;
use App\Services\MailerService;


class RegistrationController extends AbstractController
{
  
    #[Route('/registration', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, UserAuthenticatorInterface $userAuthenticator, DutilAuthenticator $authenticator, EntityManagerInterface $entityManager, 
    MailerService $mailer,DutilRepository $dutilRepository, JWTService $jwt): Response
    {
        $user = new Dutil();
        $user->setCreatedAt(new \DateTimeImmutable());
        $user->setRoles(['ROLE_USER']);
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )       
            );
           
            $entityManager->persist($user);
            $entityManager->flush();
  
          // génère le JWT (token du mail envoyé)
            $header =['typ'=> 'JWT','alg'=>'HS256'];
            $payload =['user_id'=> $user->getId()];
            $token=$jwt->generate($header, $payload, $this->getParameter('app.jwtsecret'));
          // generate a signed url and email it to the user
            $mailer->sendEmail(
                $user->getEmail(),
                '/registration/confirmation_email.html.twig',
                compact('user','token'),
            );     
            
        /*    return $userAuthenticator->authenticateUser(
                $user,
                $authenticator,
                $request
            );*/
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
    #[Route('/verif/{token}',name:'verify_user')]
    public function verifyUser($token, JWTService $jwt, DutilRepository $dutilRepository,EntityManagerInterface $entityManager):Response
    {
        if($jwt->isValid($token)&&!$jwt->isExpired($token)&&$jwt->check($token,$this->getParameter('app.jwtsecret')))
        {
            $payload = $jwt ->getPayload($token);
            $user = $dutilRepository->find($payload['user_id']);
            if($user&&!$user->isVerified()){
                $user->setIsVerified(true);
                 $user->setPoints(0);
                $entityManager->flush();
                $this->addFlash('success','Utilisateur activé');
                return $this->redirectToRoute('app_activities');
            }

        };
        $this->addflash('Danger', 'Le token est invalide ou a expiré');
        return $this->redirectToRoute('app_login');
    }

}

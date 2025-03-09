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

//
// Méthodes utilisées lors de l'inscription de l'élève
//
class RegistrationController extends AbstractController
{
  
    #[Route('/registration', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, UserAuthenticatorInterface $userAuthenticator, DutilAuthenticator $authenticator, EntityManagerInterface $entityManager, 
    MailerService $mailer,DutilRepository $dutilRepository, JWTService $jwt): Response
    {
        $user = new Dutil();
        $user->setCreatedAt(new \DateTimeImmutable());
        $user->setRoles(['ROLE_USER']);
        $user->setPoints(0);
        $user->setScenarioFait([0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0]);
        $user->setLimparticipation([0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0]);
        $user->setMotCroiseFait([0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0]);
        $user->setCoursFait([0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0]);
        $user->setNote(0);
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode le mot de passe
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
          // génère l'url et l'email
            $mailer->sendEmail(
                $user->getEmail(),
                '/registration/confirmation_email.html.twig',
                compact('user','token'),
            );   
            $this->addFlash('success','Vous avez reçu un mail à valider.');  
            return $this->redirectToRoute('home');
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
                 $entityManager->flush();
                $this->addFlash('success','Votre compte est activé');
                return $this->redirectToRoute('app_activities');
            }

        };
        $this->addflash('Danger', 'Le token est invalide ou a expiré');
        return $this->redirectToRoute('home');
    }

}

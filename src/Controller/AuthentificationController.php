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
use App\Services\AuthentificationService;
use App\Form\ResetPasswordRequestFormType;
use App\Form\ResetPasswordFormType;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

//
// Méthodes utilisées lors de l'inscription de l'élève
//
class AuthentificationController extends AbstractController
{
  
    #[Route('/registration', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, UserAuthenticatorInterface $userAuthenticator, DutilAuthenticator $authenticator, EntityManagerInterface $entityManager, 
    AuthentificationService $mailer,DutilRepository $dutilRepository, AuthentificationService $jwt): Response
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
    public function verifyUser($token, AuthentificationService $jwt, DutilRepository $dutilRepository,EntityManagerInterface $entityManager):Response
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

    #[Route(path:'/connexion', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {

        // récupère l'erreur si il y en a une
        $error = $authenticationUtils->getLastAuthenticationError();
        // récupère le username
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    #[Route(path:'/oubli-pass', name:'forgottent_password')]
    public function forgottenPassword(Request $request, DutilRepository $repository, TokenGeneratorInterface $tokenGeneratorInterface, EntityManagerInterface $entityManager, AuthentificationService $mailer): Response
    {
       $form= $this->createForm(ResetPasswordRequestFormType::class); 
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) { 
            $user = $repository->findOneByEmail($form->get('email')->getData());
            if($user) {
                $token = $tokenGeneratorInterface->generateToken();
                $user->setResetToken($token);
                $entityManager->persist($user);     
                $entityManager->flush();
                $url=$this->generateUrl('reset_pass', ['token'=>$token],UrlGeneratorInterface::ABSOLUTE_URL);
                $context= compact('url','user','token');
                $mailer->sendEmail(
                    $user->getEmail(),
                    'registration/confirmation_mot_de_passe.html.twig',
                    $context
                );
                $this->addFlash('success','Confirmez votre mot de passe par mail');
                return $this->redirectToRoute('home');
            }$this->addFlash('danger','Un problème est survenu');
            return $this->redirectToRoute('app_login');
        }
        
        return $this->render('security/reset_password_request.html.twig',['requestPassForm'=>$form->createView()]);
    }

    #[Route(path:'/oubli-pass/{token}', name:'reset_pass')]
    public function resetPass(string $token, Request $request, DutilRepository $repository, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = $repository->findOneByResetToken($token);
  
        if($user){
            $form=$this->createForm(ResetPasswordFormType::class);
            
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $user->setResetToken('');
                $user->setPassword(
                    $passwordHasher->hashPassword($user, $form->get('password')->getData()) 
                );
                $entityManager->persist($user);
                $entityManager->flush();
                $this->addFlash('success','Mot de passe changé avec succès');
                return $this->redirectToRoute('app_activities');
                }
            return $this->render('security/reset_password.html.twig', [
                'PassForm'=>$form->createView()
            ]);

        }
        $this->addFlash('danger','Jeton invalide');
        return $this->redirectToRoute('app_login');
    }


}

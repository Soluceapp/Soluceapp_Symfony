<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;


class FactureController extends AbstractController 
{
    #[Route('/activities', name: 'app_activities')]
    public function index(): Response
    { 
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED');
        return $this->render('activities/index.html.twig');
    }

    #[Route('/activities/facturemystere', name: 'app_facture')]
  
    public function facturemystere(SessionInterface $session): Response
    { 

       // Génère des données aléatoires de matrice
       $x1=rand(10,100);$x2=rand(10,100);$x3=rand(10,100);$x4=rand(10,100);$x5=rand(10,100);
       $y1=rand(10,100);$y2=rand(10,100);$y3=rand(10,100);$y4=rand(10,100);$y5=rand(10,100);
       $z1=rand(50000,60000);$z2=rand(50000,60000);$z3=rand(50000,60000);$z4=rand(50000,60000);$z5=rand(50000,60000);
       
       // Crée une matrice identitaire à modifier selon un hasard
       $p1=1;$p2=1;$p3=1;$p4=1;$p5=1;
       $q1=1;$q2=1;$q3=1;$q4=1;$q5=1;
       $r1=1;$r2=1;$r3=1;$r4=1;$r5=1;
       $s1=1;$s2=1;$s3=1;$s4=1;$s5=1;
       
       // Permet de tirer au hasard un chiffre des documents commerciaux dans A
       $ligne=rand(0,4);
       $colonne=rand(0,3);
       
       $A= array();
       $A[0] = array($p1,$q1,$r1,$s1);
       $A[1] = array($p2,$q2,$r2,$s2);
       $A[2] = array($p3,$q3,$r3,$s3);
       $A[3] = array($p4,$q4,$r4,$s4);
       $A[4] = array($p5,$q5,$r5,$s5);
       $A[$ligne][$colonne]=2;
       
       
       // Génère le bon de commande
       $B= array();
       $B[0] = array($z2,"L'économie générale",$x2,$y2,$x2*$y2);
       $B[1] = array($z1,"La responsabilité sociale de l'entreprise",$x1,$y1,$x1*$y1);
       $B[2] = array($z3,"La logistique",$x5,$y5,$x5*$y5);
       $B[3] = array($z4,"Précis de statistiques financières",$x4,$y4,$x4*$y4);
       $B[4] = array($z5,"Sociologie et psychologie du comportement",$x3,$y3,$x3*$y3);
       
       
       // Génère le bon de livraison
       $C= array();
       $C[0] = array($z1,"La responsabilité sociale de l'entreprise",$x1*$A[0][0],$y1*$A[0][1],($x1*$A[0][0])*($y1*$A[0][1]));
       $C[1] = array($z2,"L'économie générale",$x2*$A[3][0],$y2*$A[3][1],($x2*$A[3][0])*($y2*$A[3][1]));
       $C[2] = array($z4,"Précis de statistiques financières",$x4*$A[1][0],$y4*$A[1][1],($x4*$A[1][0])*($y4*$A[1][1]));
       $C[3] = array($z3,"La logistique",$x5*$A[2][0],$y5*$A[2][1],($x5*$A[2][0])*($y5*$A[2][1]));
       $C[4] = array($z5,"Sociologie et psychologie du comportement",$x3*$A[4][0],$y3*$A[4][1],($x3*$A[4][0])*($y3*$A[4][1]));
       
       
       // Génère la facture
       $D= array();
       $D[0] = array($z2,"L'économie générale",$x2*$A[1][2],$y2*$A[1][3],($x2*$A[1][2])*($y2*$A[1][3]));
       $D[1] = array($z3,"La logistique",$x5*$A[0][2],$y5*$A[0][3],($x5*$A[0][2])*($y5*$A[0][3]));
       $D[2] = array($z1,"La responsabilité sociale de l'entreprise",$x1*$A[4][2],$y1*$A[4][3],($x1*$A[4][2])*($y1*$A[4][3]));
       $D[3] = array($z5,"Sociologie et psychologie du comportement",$x3*$A[3][2],$y3*$A[3][3],($x3*$A[3][2])*($y3*$A[3][3]));
       $D[4] = array($z4,"Précis de statistiques financières",$x4*$A[2][2],$y4*$A[2][3],($x4*$A[2][2])*($y4*$A[2][3]));
       
       // Génère les totaux
       $E= array();
       $E[0] = array($B[0][4]+$B[1][4]+$B[2][4]+$B[3][4]+$B[4][4],1,1);
       $E[1] = array($C[0][4]+$C[1][4]+$C[2][4]+$C[3][4]+$C[4][4],1,1);
       $E[2] = array($D[0][4]+$D[1][4]+$D[2][4]+$D[3][4]+$D[4][4],1,1);
       
       $E[0][1] = (0.2*$E[0][0]);
       $E[1][1] = (0.2*$E[1][0]);
       $E[2][1] = (0.2*$E[2][0]);
       $E[0][2] = ($E[0][0]+$E[0][1]);
       $E[1][2] = ($E[1][0]+$E[1][1]);
       $E[2][2] = ($E[2][0]+$E[2][1]);
       
       // Détermine le montant qui devrait se trouver à la place de l'erreur $solution
       
       $M = array();
       $M[0] = array($x1,$y1,$x5,$y5);
       $M[1] = array($x4,$y4,$x2,$y2);
       $M[2] = array($x5,$y5,$x4,$y4);
       $M[3] = array($x2,$y2,$x3,$y3);
       $M[4] = array($x3,$y3,$x1,$y1);
       
       $sol=$M[$ligne][$colonne]; 
       $session->set("solution",$sol);

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED');
        return $this->render('activities/facturemystere.html.twig',
        ['B00'=> $B[0],
        'B01'=> $B[1],
        'B02'=> $B[2],
        'B03'=> $B[3],
        'B04'=> $B[4],
        'E00'=> $E[0],
        'C00'=> $C[0],
        'C01'=> $C[1],
        'C02'=> $C[2],
        'C03'=> $C[3],
        'C04'=> $C[4],
        'E01'=> $E[1],
        'D00'=> $D[0],
        'D01'=> $D[1],
        'D02'=> $D[2],
        'D03'=> $D[3],
        'D04'=> $D[4],
        'E02'=> $E[2],
        'SOL'=> $sol
        ]
    
    );
    }

}

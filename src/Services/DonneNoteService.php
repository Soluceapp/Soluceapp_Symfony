<?php
namespace App\Services;


use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Dutil;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DonneNoteService extends AbstractController 
{

    public function donneNote(EntityManagerInterface $entityManager):void
    {
        $dutil=$entityManager->getRepository(Dutil::class)->find($this->getUser());
        $note=$dutil->getNote();
        $points=$dutil->getPoints();
        if($points<=4)
        {
            $note=$points;
        }
        else
        {
            $note=4+(($points-4)*0.25);
        }

        $dutil->setNote($note);
        $entityManager->persist($dutil);
        $entityManager->flush();    
    }

   
}
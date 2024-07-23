<?php

namespace App\Controller;

use App\Entity\Ticket;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TicketController extends AbstractController
{
    private $doctrine;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    #[Route('/tikets', name: 'tikets',)]
    public function index(): Response
    {
        // Récupérer tous les tickets
        $tickets = $this->doctrine->getRepository(Ticket::class)->findAll();

        // Créer un tableau pour compter les lots
        $lotCounts = [];
        $lotTickets = [];

        // Parcourir les tickets et compter les lots
        foreach ($tickets as $ticket) {
            $lotName = $ticket->getLot()->getName();
            if (!isset($lotCounts[$lotName])) {
                $lotCounts[$lotName] = 0;
                $lotTickets[$lotName] = [];
            }
            $lotCounts[$lotName]++;
            $lotTickets[$lotName][] = $ticket;
        }

        return $this->render('ticket/index.html.twig', [
            'lotCounts' => $lotCounts,
            'lotTickets' => $lotTickets,
        ]);
    }
}

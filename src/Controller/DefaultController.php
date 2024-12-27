<?php
declare(strict_types=1);
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    #[Route('/{reactRouting}', name: 'app_react', requirements: ['reactRouting' => '^(?!api).+'], priority: -1)]
    public function index(): Response
    {
        return $this->render('base.html.twig', []);
    }
}
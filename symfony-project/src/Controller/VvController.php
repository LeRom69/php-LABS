<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class VvController extends AbstractController
{
    #[Route('/test', name: 'app_test')]
    public function test(): Response
    {
        return new Response('Test method response');
    }
}
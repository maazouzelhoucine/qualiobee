<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContentController extends AbstractController
{
    /**
     * @Route("/content/{section}", name="content_section")
     */
    
     public function loadContent(string $contentType): Response
     {
         // Load content based on the content type (e.g., "sessions", "dashboard")
         switch ($contentType) {
             case 'sessions':
                 // Render the content for Sessions
                 return $this->render('content/sessions.html.twig');
             case 'dashboard':
                 // Render the content for Dashboard
                 return $this->render('content/dashboard.html.twig');
             // Add more cases for other menu items...
             default:
                 return new Response('Content not found.', 404);
         }
     }
    
    }

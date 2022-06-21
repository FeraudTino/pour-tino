<?php

namespace App\Controller;

use App\Repository\AdresseRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ApiAdresseController extends AbstractController
{
    #[Route('/api/adresse', name: 'app_api_adresse')]
    public function index(AdresseRepository $rep, SerializerInterface $serializer)
    {
        $adresses = $rep->findAll();

        $json = $serializer->serialize($adresses, 'json', [
            'groups' => 'adresse:read'
        ]);
        $reponse = new Response($json, 200, ['content-type' => 'application/json']);
        return $reponse;
    }
}

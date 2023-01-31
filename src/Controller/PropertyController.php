<?php

namespace App\Controller;

use App\Entity\Property;
use App\Repository\PropertyRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class PropertyController extends AbstractController
{
    public function __construct(private PropertyRepository $propertyRepository)
    {
    }

    #[Route('/biens', 'property.index')]
    public function index(): Response
    {
        $properties = $this->propertyRepository->findAllNotSold();
        return $this->render('property/index.html.twig', [
            'current_menu' => 'properties'
        ]);
    }

    #[Route('/biens/{slug}-{id}', 'property.show', ['slug' => '[a-z0-9\-]*'])]
    public function show(Property $property, string $slug): Response
    {
        if ($property->getSlug() != $slug) {
            return $this->redirectToRoute('property.show', [
                'id' => $property->getId(),
                'slug' => $property->getSlug()
            ], 301);
        }
        return $this->render('property/show.html.twig', [
            'current_menu' => 'properties',
            'property' => $property
        ]);
    }
}
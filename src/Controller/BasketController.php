<?php

namespace App\Controller;

use App\Entity\Basket;
use App\Entity\Products;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BasketController extends AbstractController
{
    private $entityManager;
    public function __construct(ManagerRegistry $doctrine)
    {
        $this->entityManager = $doctrine->getManager();
    }

    #[Route('/basket/{id}', name: 'app_basket')]
    public function index(Products $products): Response
    {
        $basket = new Basket();
        $user = $this->getUser();

        $basket->setUser($user);
        $basket->setQty(1);
        $basket->setPrice($products->getPrice());
        $basket->addProduct($products);

        $this->entityManager->persist($basket);
        $this->entityManager->flush();

        $this->addFlash('success-basket', 'Ürün sepete eklendi');
        // go back
        return $this->redirectToRoute('app_sales');
    }
}

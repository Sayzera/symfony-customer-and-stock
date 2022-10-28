<?php

namespace App\Controller;

use App\Entity\Images;
use App\Entity\Products;
use App\Form\ImageFormType;
use App\Form\ProductFormType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductsController extends AbstractController
{
    public $entityManager;
    public function __construct(ManagerRegistry $registry)
    {
        $this->entityManager = $registry->getManager();
    }


    #[Route('/products', name: 'app_products')]
    public function index(): Response
    {
        $products = $this->entityManager->getRepository(Products::class)->findBy([], ['id' => 'DESC']);

        return $this->render('products/index.html.twig', [
            'controller_name' => 'ProductsController',
            'products' => $products
        ]);
    }

    #[Route('/products/new', name: 'app_products_new')]
    public function new(Request $request): Response
    {
        $products = new Products();

        $form = $this->createForm(ProductFormType::class, $products);
        $form->handleRequest($request);



        if ($form->isSubmitted() && $form->isValid()) {
            // get file
            $files = $form->get('images')->getData();

            foreach ($files as $file) {
                $fileName = md5(uniqid()) . '.' . $file->guessExtension();
                $file->move(
                    $this->getParameter('images_directory'),
                    $fileName
                );
                $images = new Images();
                // Resmi yüklüyoruz 
                $images->setImageName($fileName);
                $products->addImage($images);
                $this->entityManager->persist($images);
            }

            $this->entityManager->persist($products);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_products');
        }


        return $this->render('products/create_product.html.twig', [
            'controller_name' => 'ProductsController',
            'form' => $form->createView(),
            'errors' => $form->getErrors(true, true)
        ]);
    }

    #[Route('/products/edit/{id}', name: 'app_products_edit')]
    public function edit(Request $request, Products $products)
    {
        $form = $this->createForm(ProductFormType::class, $products);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            $this->addFlash('success', 'Ürün başarıyla güncellendi');

            return $this->redirectToRoute('app_products');
        }

        return $this->render('products/edit_product.html.twig', [
            'controller_name' => 'ProductsController',
            'form' => $form->createView(),
            'errors' => $form->getErrors(true, true)
        ]);
    }

    #[Route('/product/images/{id}', name: 'app_product_images')]
    public function images(Products $products)
    {
        return $this->render('products/images.html.twig', [
            'images' => $products->getImages()
        ]);
    }

    #[Route('/product/images/delete/{id}', name: 'app_product_images_delete')]
    public function deleteImage(Images $images)
    {
        $product_id = $images->getProducts()->getId();
        // resmi sil
        if (file_exists($this->getParameter('images_directory') . '/' . $images->getImageName())) {
            unlink($this->getParameter('images_directory') . '/' . $images->getImageName());
        }

        $this->entityManager->remove($images);
        $this->entityManager->flush();


        // go back referer
        return $this->redirectToRoute('app_product_images', ['id' => $product_id]);
    }

    #[Route('/product/add-image/{id}', name: 'app_product_add_image')]
    public function addImage(Request $request): Response
    {
        $images = new Images();
        $products = $this->entityManager->getRepository(Products::class)->find($request->get('id'));

        $form = $this->createForm(ImageFormType::class, $images);

        // requestleri yakala
        $form->handleRequest($request);

        $fileImages =  $form->get('image_name')->getData();

        if ($form->isSubmitted() && $form->isValid()) {

            foreach ($fileImages as $fileImage) {
                $fileName = md5(uniqid()) . '.' . $fileImage->guessExtension();
                $fileImage->move(
                    $this->getParameter('images_directory'),
                    $fileName
                );

                $images = new Images();

                $images->setProducts($products);
                $images->setImageName($fileName);
                $products->addImage($images);
                $this->entityManager->persist($images);
            }


            $this->entityManager->flush();



            return $this->redirectToRoute('app_product_images', ['id' => $products->getId()]);
        }


        return $this->render('products/add_image.html.twig', [
            'form' => $form->createView()
        ]);
    }
}

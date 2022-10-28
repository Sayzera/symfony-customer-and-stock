<?php

namespace App\Controller;

use App\Entity\Categories;
use App\Form\CategoryFormType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    public $entityManager;
    public function __construct(ManagerRegistry $registry)
    {
        $this->entityManager = $registry->getManager();
    }

    #[Route('/category', name: 'app_category')]
    public function index(): Response
    {
        $categories = $this->entityManager->getRepository(Categories::class)->findBy([], ['id' => 'DESC']);

        return $this->render('category/index.html.twig', [
            'controller_name' => 'CategoryController',
            'categories' => $categories,
        ]);
    }

    #[Route('/category/create', name: 'create_category')]
    public function create_category(Request $request)
    {
        $category = new Categories();

        $form = $this->createForm(CategoryFormType::class, $category);
        $form->handleRequest($request);

        // form error 

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($category);
            $this->entityManager->flush();

            // return $this->redirectToRoute('app_category');


            $this->addFlash('success', 'Kategori başarıyla oluşturuldu');
            return $this->redirectToRoute('app_category');
        }


        return $this->render('category/create_category.html.twig', [
            'form' => $form->createView(),
            'errors' => $form->getErrors(true, true)
        ]);
    }

    #[Route('/category/edit/{id}', name: 'edit_category')]
    public function edit_category(Request $request, $id)
    {
        $category = $this->entityManager->getRepository(Categories::class)->find($id);

        $form = $this->createForm(CategoryFormType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($category);
            $this->entityManager->flush();

            $this->addFlash('success', 'Kategori başarıyla güncellendi');
            return $this->redirectToRoute('app_category');
        }

        return $this->render('category/category_update.html.twig', [
            'form' => $form->createView(),
            'errors' => $form->getErrors(true, true)
        ]);
    }

    #[Route('/category/delete/{id}', name: 'delete_category')]
    public function delete_category(Categories $categories)
    {
        $this->entityManager->remove($categories);
        $this->entityManager->flush();

        $this->addFlash('success', 'Kategori başarıyla silindi');
        return $this->redirectToRoute('app_category');
    }
}

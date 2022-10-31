<?php

namespace App\Controller;

use App\Entity\Categories;
use App\Entity\Products;
use App\Entity\User;
use App\Repository\ProductsRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SalesController extends AbstractController
{
    public $entityManager;
    public function __construct(ManagerRegistry $doctrine)
    {
        $this->entityManager = $doctrine->getManager();
    }

    #[Route('/tum-urunler/{category_id?}', name: 'app_sales')]
    public function index(Request $request, ProductsRepository $repo): Response
    {
        $category_id = $request->get('category_id') ?? null;

        $page = $request->query->get('page', 1);
        $limit = 10;
        $pagesCount = ceil(count($repo->findAll()) / $limit);

        $params = [];

        if ($category_id != null) {
            $params['categories_id'] = $category_id;
        }

        $products = $repo->findBy($params, ['id' => 'DESC'], $limit, ($page - 1) * $limit);

        $user = $this->getUser();
        // get user basket count
        $count =  count($user->getBaskets());


        return $this->render('sales/index.html.twig', [
            'controller_name' => 'SalesController',
            'products' => $products,
            'basketCount' => $count,
        ]);
    }


    #[Route('/search', name: 'app_search', methods: ['POST'])]
    public function searchData(Request $request, ProductsRepository $repo)
    {
        // Axios ile gelen veriyi alıyoruz
        $data = $request->getContent();
        $data = json_decode($data, true);


        $products = $repo->searchProduct($data['search']);
        $categories = $repo->searchCategory($data['search']);

        return $this->json([
            'products' => $products,
            'categories' => $categories
        ]);
    }
}

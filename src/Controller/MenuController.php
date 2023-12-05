<?php

namespace App\Controller;

use App\Repository\DishRepository;
use App\Service\DishService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MenuController extends AbstractController
{
    #[Route('/menu', name: 'menu')]
    public function index(DishService $dishService): Response
    {
        $dish = $dishService->getAllDishes();

        return $this->render('menu/index.html.twig', [
            'dish' => $dish
        ]);
    }
}

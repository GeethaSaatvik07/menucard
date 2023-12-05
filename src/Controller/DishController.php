<?php

namespace App\Controller;

use App\Entity\Dish;
use App\Form\DishType;
use App\Repository\DishRepository;
use App\Service\DishService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;

#[Route('/dish', name: 'dish.')]
class DishController extends AbstractController
{
    #[Route('/', name: 'edit')]
    public function index(DishService $dishService): Response
    {   
        $dishes = $dishService->getAllDishes();

        return $this->render('dish/index.html.twig', [
            'dish' => $dishes,
        ]);
    }

    #[Route('/create', name: 'create')]
    public function create(Request $request, ManagerRegistry $doctrine){
        $dish = new Dish();
        $form = $this->createForm(DishType::class,$dish);
        $form->handleRequest($request);

        if ($form->isSubmitted()){
        $em = $doctrine->getManager();
        $image = $request->files->get('dish')['image'];

        if($image){
            $filename = md5(uniqid()).'.'.$image->guessClientExtension();
        };

        $image->move(
            $this->getParameter('images_folder'),
            $filename
        );
        $dish->setImage($filename);

        $em->persist($dish);

        $em->flush();

        return $this->redirect($this->generateUrl('dish.edit'));
        }

        return $this->render('dish/create.html.twig', [
            'createForm' => $form->createView(),
        ]);
    }

    #[Route('/remove/{id}', name: 'remove')]
    public function delete($id, DishService $dishService)
    {
        // $em = $doctrine->getManager();
        // $dish = $ds->find($id);
        // $em->remove($dish);
        // $em->flush();

        $dishService->removeDish($id);

        // message
        $this->addFlash('success', "Dish was deleted successfully");

        return $this->redirect($this->generateUrl('dish.edit'));
    }

    // #[Route('/show/{id}', name: 'show')]
    // public function show(DishService $dishService, Dish $dish){
    //     return $this->render('dish/show.html.twig', [
    //         'dish' => $dishService->showDishDetails($dish),
    //     ]);
    // }

    #[Route('/show/{id}', name: 'show')]
    public function show(Dish $dish){
        return $this->render('dish/show.html.twig', [
            'dish' => $dish,
        ]);
    }
}

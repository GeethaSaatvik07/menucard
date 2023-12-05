<?php 

namespace App\Service;

use App\Entity\Dish;
use App\Form\DishType;
use App\Repository\DishRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;

class DishService
{
    private $dishRepository;
    private $doctrine;

    public function __construct(DishRepository $dishRepository, ManagerRegistry $doctrine){
        $this->dishRepository = $dishRepository;
        $this->doctrine = $doctrine;
    }

    public function getAllDishes(){
        return $this->dishRepository->findAll();
    }

    public function removeDish(int $id){
        $em = $this->doctrine->getManager();
        $dish = $this->dishRepository->find($id);
        $em->remove($dish);
        $em->flush();
    }

    public function showDishDetails(Dish $dish){
        return $dish;
    }
}
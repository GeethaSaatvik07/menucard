<?php

namespace App\Service;

use App\Entity\Merchant;
use App\Form\MerchantType;
use App\Repository\MerchantRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class MerchantService{

    private $merchantRepository;
    private $doctrine;
    private $formFactory;

    public function __construct(MerchantRepository $merchantRepository, ManagerRegistry $doctrine,FormFactoryInterface $formFactory){
        $this->merchantRepository = $merchantRepository;
        $this->doctrine = $doctrine;
        $this->formFactory = $formFactory;
    }

    public function getAllMerchants()
    {
        return $this->merchantRepository->getAllNonDeletedMerchants();

        // return $this->merchantRepository->findAll();
    }

    public function getMerchantById(int $id)
    {
        // $merchant = $this->merchantRepository->find($id);
        $merchant = $this->merchantRepository->getAllNonDeletedMerchantsById($id);

        if (!$merchant){
            return "Merchant not found";
        }else{
            return $merchant;
        }
    }

    // public function deleteMerchant(int $id)
    // {
    //     $em = $this->doctrine->getManager();
    //     $merchant = $this->merchantRepository->find($id);
    //     $message = '';
    //     if(!$merchant){
    //         $message = "Merchant not found";
    //     }
    //     else{
    //         $message = "Merchant deleted"; 
    //         $em->remove($merchant);
    //         $em->flush();
    //     }

    //     return $message;
    // }

    public function softDeleteMerchant(int $id){
        $em = $this->doctrine->getManager();
        $merchant = $this->merchantRepository->find($id);

        if (!$merchant){
            return "Merchant not found";
        }
        else{
            $merchant->setIsDeleted(true);
            $em->flush();   

            return "Merchant deleted";
        }
    }
}
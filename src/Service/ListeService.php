<?php

namespace App\Service;

use App\Repository\ListeRepository;
use Doctrine\Persistence\ManagerRegistry;

class ListeService{

    private $doctrine;
    private $listeRepository;

    public function __construct(ManagerRegistry $doctrine, ListeRepository $listeRepository){
        $this->doctrine = $doctrine;
        $this->listeRepository = $listeRepository;
    }

    public function getAllDetails(){
        return $this->listeRepository->findAll();
    }
}
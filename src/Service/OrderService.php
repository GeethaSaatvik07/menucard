<?php

namespace App\Service;

use App\Entity\Dish;
use App\Entity\Order;
use App\Repository\OrderRepository;
use Doctrine\Persistence\ManagerRegistry;

class OrderService{

    private $orderRepository;
    private $doctrine;

    public function __construct(OrderRepository $orderRepository, ManagerRegistry $doctrine){
        $this->orderRepository = $orderRepository;
        $this->doctrine = $doctrine;
    }

    public function getTableOrder(string $table){
        return $this->orderRepository->findBy(
            ['tables' => $table],
        );
    }

    public function orderDish(Dish $dish){
        $order = new Order();
        $order->setTables('table1');
        $order->setName($dish->getName());
        $order->setOrdernumber($dish->getId());
        $order->setPrice($dish->getPrice());
        $order->setStatus("open");

        $em = $this->doctrine->getManager();
        $em->persist($order);
        $em->flush();

        return $order;
    }

    public function checkAndSetStatus(int $id, string $status){
        $em = $this->doctrine->getManager();
        $order = $em->getRepository(Order::class)->find($id);

        $order->setStatus($status);
        $em->flush();
    }

    public function deleteOrder(int $id){
        $em = $this->doctrine->getManager();
        $order = $this->orderRepository->find($id);
        $em->remove($order);
        $em->flush();
    }
}
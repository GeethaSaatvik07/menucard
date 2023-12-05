<?php

namespace App\Controller;

use App\Entity\Dish;
use App\Entity\Order;
use App\Repository\OrderRepository;
use App\Service\OrderService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractController
{
    #[Route('/order', name: 'app_order')]
    public function index(OrderService $orderService)
    {
        $order = $orderService->getTableOrder('table1');

        return $this->render('order/index.html.twig', [
            'order' => $order
        ]);
    }

    #[Route('/orders/{id}', name: 'orders')]
    public function order(Dish $dish, OrderService $orderService){
        // $order = new Order();
        // $order->setTables('table1');
        // $order->setName($dish->getName());
        // $order->setOrdernumber($dish->getId());
        // $order->setPrice($dish->getPrice());
        // $order->setStatus("open");

        // $em = $doctrine->getManager();
        // $em->persist($order);
        // $em->flush();

        $order = $orderService->orderDish($dish);

        $this->addFlash('Order', $order->getName().' has added to the order');

        return $this->redirect($this->generateUrl('menu'));
    }

    #[Route('/status/{id},{status}', name: 'status')]
    public function status($id, $status, OrderService $orderService){
        // $em = $doctrine->getManager();
        // $order = $em->getRepository(Order::class)->find($id);

        // $order->setStatus($status);
        // $em->flush();

        $orderService->checkAndSetStatus($id, $status);

        return $this->redirect($this->generateURl('app_order'));
    }

    #[Route('/delete/{id}', name: 'delete')]
    public function delete($id, OrderService $orderService){
        // $em = $doctrine->getManager();
        // $order = $or->find($id);
        // $em->remove($order);
        // $em->flush();

        $orderService->deleteOrder($id);

        return $this->redirect($this->generateUrl('app_order'));
    }
}

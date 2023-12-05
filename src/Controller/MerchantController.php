<?php

namespace App\Controller;

use App\Entity\Merchant;
use App\Form\MerchantType;
use App\Repository\MerchantRepository;
use App\Service\MerchantService;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Test\FormInterface;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;
use Symfony\Component\Validator\Constraints as Assert;

#[Route('/merchant')]
class MerchantController extends AbstractController
{
    #[Route('/', name: 'app_merchant_index', methods: ['GET'])]
    public function index(MerchantService $merchantService)
    {
        $merchants = $merchantService->getAllMerchants();
        return $this->json($merchants);
    }

    #[Route('/new', name: 'app_merchant_new', methods: ['GET','POST'])]
    public function createMerchant(Request $request, ManagerRegistry $doctrine)
    {         
        $data = json_decode($request->getContent(), true);
        
        if (!is_array($data)){
            return $this->json("Invalid json data. Expected array", 400);
        }
        
        $em = $doctrine->getManager();
        $merchants = [];
        
        foreach ($data as $merchantData) {
            $merchant = new Merchant();
        
            $form = $this->createFormBuilder($merchant,['csrf_protection' => false])
                ->add('name', TextType::class, [
                    'constraints' => [
                        new Assert\NotBlank(),
                        new Assert\Type('string'),
                        new Assert\Regex([
                            'pattern' => '/^[a-zA-Z]+$/',
                            'message' => 'Name should contain only alphabets.'
                        ]),
                    ],
                ])
                ->add('address', TextareaType::class, [
                    'constraints' => [
                        new Assert\NotBlank(),
                        new Assert\Type('string'),
                        new Assert\Regex([
                            'pattern' => '/^[a-zA-Z0-9\s\.,#\-]+$/',
                            'message' => 'Address should be alphanumeric with some special characters.',
                        ]),
                    ],
                ])
                ->add('phonenumber', TelType::class, [
                    'constraints' => [
                        new Assert\NotBlank(),
                        new Assert\Type([
                            'type' => 'numeric', 
                            'message' => 'Phone number should contain only numbers.'
                        ]),
                        new Assert\Length(10),
                    ],
                ])
                ->add('email', EmailType::class, [
                    'constraints' => [
                        new Assert\NotBlank(),
                        new Assert\Email(['message' => 'Invalid email format.']),
                    ],
                ])
                ->getForm();
        
            $form->submit($merchantData);
        
            if ($form->isValid() && $form->isSubmitted()) {
                $em->persist($merchant);
                $merchants[] = $merchantData;
            } else {
                $errors = [];
                foreach ($form->getErrors(true) as $error) {
                    $errors[] = $error->getMessage();
                }
        
                return $this->json(['message' => 'Invalid Data', 'errors' => $errors], 400);
            }
        }
        
         $em->flush();
        
        return $this->json($merchants, 201);
    }

    #[Route('/{id}', name: 'app_merchant_show', methods: ['GET'])]
    public function show(MerchantService $merchantService, int $id)
    {
        $merchant = $merchantService->getMerchantById($id);
        return $this->json($merchant);
    }

    #[Route('/{id}/edit', name: 'app_merchant_edit', methods: ['PUT'])]
    public function edit(Request $request, int $id, ManagerRegistry $doctrine)
    {
        $em = $doctrine->getManager();
        $merchant = $em->getRepository(Merchant::class)->find($id);
        
        if (!$merchant) {
            return $this->json("Merchant not found", 404);
        }
        
        $data = json_decode($request->getContent(), true);
        
        if (!isset($data['name']) || !ctype_alpha($data['name'])) {
            return $this->json("Name should contain only alphabets.", 400);
        }
        
        if (!isset($data['address']) || !preg_match('/^[a-zA-Z0-9\s\-,.#]+$/', $data['address'])) {
            return $this->json("Address should be alphanumeric with some special characters.", 400);
        }
          
        if (!isset($data['phonenumber']) || !ctype_digit($data['phonenumber'])) {
            return $this->json("Phone number should contain only numbers.", 400);
        }

        if (!isset($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            return $this->json("Invalid email format.", 400);
        }
        
        $merchant->setName($data['name']);
        $merchant->setAddress($data['address']);
        $merchant->setEmail($data['email']);
        $merchant->setPhoneNumber($data['phonenumber']);
        
        $em->flush();
        
        return $this->json($merchant, 200);

    }

    #[Route('/{id}', name: 'app_merchant_delete', methods: ['DELETE'])]
    public function delete(int $id, MerchantService $merchantService)
    {
        $merchantMessage = $merchantService->softDeleteMerchant($id);
        return $this->json($merchantMessage);
    }
}

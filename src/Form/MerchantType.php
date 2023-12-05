<?php

namespace App\Form;

use App\Entity\Merchant;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class MerchantType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('address')
            ->add('phonenumber')
            ->add('email');

        // $builder
            // ->add('name', TextType::class, [
            //     'constraints' => [
            //         new Assert\NotBlank(),
            //         new Assert\Type([
            //             'type' => 'alpha', 
            //             'message' => 'Name should contain only alphabets.'
            //         ]),
            //     ],
            // ])
            // ->add('address', TextareaType::class, [
            //     'constraints' => [
            //         new Assert\NotBlank(),
            //         new Assert\Regex([
            //             'pattern' => '/^[a-zA-Z0-9\s\.,#\-]+$/',
            //             'message' => 'Address should be alphanumeric with some special characters.',
            //         ]),
            //     ],
            // ])
            // ->add('phonenumber', TelType::class, [
            //     'constraints' => [
            //         new Assert\NotBlank(),
            //         new Assert\Type([
            //             'type' => 'numeric', 
            //             'message' => 'Phone number should contain only numbers.'
            //         ]),
            //     ],
            // ])
            // ->add('email', EmailType::class, [
            //     'constraints' => [
            //         new Assert\NotBlank(),
            //         new Assert\Email(['message' => 'Invalid email format.']),
            //     ],
            // ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Merchant::class,
        ]);
    }
}

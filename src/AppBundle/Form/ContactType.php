<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'attr' => [
                    'class' => 'input',
                    'placeholder' => 'form.name'
                ]
            ])
            ->add('surname', TextType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'input',
                    'placeholder' => 'form.surname'
                ]
            ])
            ->add('address', TextType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'input',
                    'placeholder' => 'form.address'
                ]
            ])
            ->add('city', TextType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'input',
                    'placeholder' => 'form.city'
                ]
            ])
            ->add('zipcode', NumberType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'input',
                    'placeholder' => 'form.zipcode'
                ]
            ])
            ->add('email', EmailType::class, [
                'attr' => [
                    'class' => 'input',
                    'placeholder' => 'form.email'
                ]
            ])
            ->add('body', TextareaType::class, [
                'attr' => [
                    'class' => 'textarea',
                    'placeholder' => 'form.body'
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'form.send',
                'attr' => [
                    'class' => 'input button submit'
                ]
            ]);
    }

    public function getName()
    {
        return 'Contact';
    }
}

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
        $builder->add('name', TextType::class, ['attr' => ['class'=>'input','placeholder'=>'Nom']])
                ->add('surname', TextType::class, ['attr' => ['class'=>'input','placeholder'=>'Prénom']])
                ->add('adress', TextType::class, ['attr' => ['class'=>'input','placeholder'=>'Adresse']])
                ->add('city', TextType::class, ['attr' => ['class'=>'input','placeholder'=>'Ville']])
                ->add('zipcode', NumberType::class, ['attr' => ['class'=>'input','placeholder'=>'Code Postal']])
                ->add('mail', EmailType::class, ['attr' => ['class'=>'input','placeholder'=>'Email']])
                ->add('body', TextareaType::class, ['attr' => ['class'=>'input','placeholder'=>'Message']])
                ->add('save', SubmitType::class, ['attr' => ['class'=>'input button','label'=>'contact.us']]);
    }

    public function getName()
    {
        return 'Contact';
    }
}

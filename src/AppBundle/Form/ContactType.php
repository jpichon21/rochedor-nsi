<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', TextType::class)
                ->add('surname', TextType::class)
                ->add('adress', TextType::class)
                ->add('city', TextType::class)
                ->add('zipcode', TextType::class)
                ->add('mail', EmailType::class)
                ->add('body', TextareaType::class)
                ->add('save', SubmitType::class, array('label' => 'contact.us'));
    }

    public function getName()
    {
        return 'Contact';
    }
}

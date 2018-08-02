<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class ShippingType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('name')
        ->add('weight')
        ->add('country')
        ->add('price')
        ->add(
            'relatedcountries',
            CollectionType::class,
            [
            'entry_type' => TextType::class,
            'allow_add' => true,
            'allow_delete' => true,
            'prototype' => true,
            'attr' => array(
                'class' => 'collection',
            ),
            'required' => true,
            'by_reference' => false
            ]
        )
        ->add(
            'maximalWeight',
            CheckboxType::class,
            array(
            'label'    => 'Limit max',
            'required' => false,
            )
        );
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Shipping'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_shipping';
    }
}

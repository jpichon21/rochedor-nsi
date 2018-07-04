<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use AppBundle\Entity\Tax;

class ProduitType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('refprd')
        ->add('produitcourt')
        ->add('produitlong')
        ->add('codrub')
        ->add('codb')
        ->add('isbn')
        ->add('ean')
        ->add('serie')
        ->add('auteur')
        ->add('editeur')
        ->add('typprd')
        ->add('dateparution')
        ->add('prix')
        ->add('prixht')
        ->add('promo')
        ->add('poids')
        ->add('etatprd')
        ->add('largeur')
        ->add('hauteur')
        ->add('epaisseur')
        ->add('nbpage')
        ->add('stock')
        ->add('hide')
        ->add('adimg')
        ->add('adimg2')
        ->add('adimg3')
        ->add('urlbook')
        ->add('pageprd')
        ->add('memoprd')
        ->add('presentation')
        ->add('enreg')
        ->add('rang')
        ->add('maj')
        ->add('nouveaute')
        ->add('themes')
        ->add(
            'taxes',
            EntityType::class,
            [
                'class' => Tax::class,
                'choice_label' => function ($tax) {
                    return $tax->getName().' - '.$tax->getRate();
                },
                'multiple' => true,
                'expanded' => true

            ]
        )
        ;
    }
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Produit'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_produit';
    }
}

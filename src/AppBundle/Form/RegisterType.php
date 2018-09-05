<?php
namespace AppBundle\Form;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use AppBundle\Entity\Contact;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class RegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('nom', TextType::class, ['attr' => ['class' => 'input' , 'placeholder' => 'form.name']])
        ->add('prenom', TextType::class, ['attr' => ['class' => 'input' , 'placeholder' => 'form.surname']])
        ->add('civil', ChoiceType::class, [
            'choices' => [
                'form.label.male' => 'M.',
                'form.label.female' => 'Mme',
                'form.label.miss' => 'Mlle',
                'form.label.father' => 'Père',
                'form.label.sister' =>'Soeur',
                'form.label.brother' =>'Frère'],
                'attr' => ['class' => 'select']])
        ->add('adresse', TextType::class, [ 'required' => false, 'attr' => [
            'class' => 'input',
            'placeholder' => 'form.address']
        ])
        ->add('cp', NumberType::class, [ 'required' => false, 'attr' => [
            'class' => 'input',
            'placeholder' => 'form.zipcode']
        ])
        ->add('ville', TextType::class, [ 'required' => false, 'attr' => [
            'class' => 'input',
            'placeholder' => 'form.city']
        ])
        ->add('pays', TextType::class, [ 'required' => false, 'attr' => [
            'class' => 'input',
            'placeholder' => 'form.country']
        ])
        ->add('tel', NumberType::class, [ 'required' => false, 'attr' => [
            'class' => 'input',
            'placeholder' => 'form.phone']
        ])
        ->add('mobil', NumberType::class, [ 'required' => false, 'attr' => [
            'class' => 'input',
            'placeholder' => 'form.mobile']
        ])
        ->add('email', EmailType::class, [
            'attr' => ['class' => 'input' , 'placeholder' => 'form.email', 'maxlength' => 40]
            ])
        ->add('datnaiss', DateType::class, [
            'format' => 'dd/MM/yyyy',
            'widget' => 'single_text',
            'attr' => ['class' => 'input' , 'placeholder' => 'form.birthday']])
        ->add('password', RepeatedType::class, [
            'type' => PasswordType::class,
            'invalid_message' => 'form.message.passwords_mismatch',
            'options' => array('attr' => array('class' => 'password-field')),
            'required' => true,
            'first_options'  => array(
                'attr' => [
                'class' => 'input',
                'placeholder' => 'form.password']
            ),
            'second_options' => array(
                'attr'=> [
                'class' => 'input',
                'placeholder' => 'form.password.repeat']
            )
            ])
        ->add('profession', TextType::class, ['required' => false, 'attr' => [
            'class' => 'input',
            'placeholder' => 'form.profession']
        ]);
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Contact::class,
        ));
    }
}

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
        ->add('nom', TextType::class, ['attr' => ['class'=>'input','placeholder'=>'Nom']])
        ->add('prenom', TextType::class, ['attr' => ['class'=>'input','placeholder'=>'Prénom']])
        ->add('civil', ChoiceType::class, [
            'label' => 'form.label.gender',
            'choices' => [
                'form.label.male' => 'M.',
                'form.label.female' => 'Mme',
                'form.label.miss' => 'Mlle',
                'form.label.father' => 'Père',
                'form.label.sister' =>'Soeur',
                'form.label.brother' =>'Frère'],
                'attr' => ['class'=>'select']])
        ->add('adresse', TextType::class, [ 'required' => false ,'attr' => ['class'=>'input','placeholder'=>'Adresse']])
        ->add('cp', NumberType::class, [ 'required' => false,'attr' => ['class'=>'input','placeholder'=>'Code Postal']])
        ->add('ville', TextType::class, [ 'required' => false,'attr' => ['class'=>'input','placeholder'=>'Ville']])
        ->add('pays', TextType::class, [ 'required' => false,'attr' => ['class'=>'input','placeholder'=>'Pays']])
        ->add('tel', NumberType::class, [ 'required' => false,'attr' => ['class'=>'input','placeholder'=>'Téléphone']])
        ->add('mobil', NumberType::class, [ 'required' => false,'attr' => ['class'=>'input','placeholder'=>'Mobile']])
        ->add('email', EmailType::class, ['attr' => ['class'=>'input','placeholder'=>'Email']])
        ->add('datnaiss', DateType::class, [
            'format' => 'dd/MM/yyyy',
            'widget' => 'single_text',
            'attr' => ['class'=>'input','placeholder'=>'Date de naissance']])
        ->add('password', RepeatedType::class, [
            'type' => PasswordType::class,
            'invalid_message' => 'form.message.passwords_mismatch',
            'options' => array('attr' => array('class' => 'password-field')),
            'required' => true,
            'first_options'  => array('label'=>'Mot de passe','attr' => [
                'class'=>'input password',
                'placeholder'=>'Mot de passe']
                ),
            'second_options' => array('label'=>'Répéter le mot de passe', 'attr'=> [
                'class'=>'input password',
                'placeholder'=>'Répéter le mot de passe']
                )
            ])
        ->add('profession', TextType::class, ['required' => false, 'attr' => [
            'class'=>'input',
            'placeholder'=>'Profession']
        ])
        ;
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Contact::class,
        ));
    }
}

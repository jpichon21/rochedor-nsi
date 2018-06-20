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
        ->add('nom', TextType::class, ['label' => 'form.label.lastname'])
        ->add('prenom', TextType::class, ['label' => 'form.label.firstname'])
        ->add('civil', ChoiceType::class, [
            'label' => 'form.label.gender',
            'choices' => [
                'form.label.male' => 'M.',
                'form.label.female' => 'Mme',
                'form.label.miss' => 'Mlle',
                'form.label.father' => 'Père',
                'form.label.sister' =>'Soeur',
                'form.label.brother' =>'Frère']
            ])
        ->add('adresse', TextType::class, ['label' => 'form.label.address', 'required' => false])
        ->add('cp', NumberType::class, ['label' => 'form.label.zipcode', 'required' => false])
        ->add('ville', TextType::class, ['label' => 'form.label.city', 'required' => false])
        ->add('pays', TextType::class, ['label' => 'form.label.country', 'required' => false])
        ->add('tel', NumberType::class, ['label' => 'form.label.phone', 'required' => false])
        ->add('mobil', NumberType::class, ['label' => 'form.label.mobile', 'required' => false])
        ->add('email', EmailType::class, ['label' => 'form.label.email'])
        ->add('datnaiss', DateType::class, [
            'label' => 'form.label.birthday',
            'format' => 'dd/MM/yyyy',
            'widget' => 'single_text'])
        ->add('password', RepeatedType::class, [
            'type' => PasswordType::class,
            'invalid_message' => 'form.message.passwords_mismatch',
            'options' => array('attr' => array('class' => 'password-field')),
            'required' => true,
            'first_options'  => array('label' => 'form.label.password'),
            'second_options' => array('label' => 'form.label.password_repeat')
        ])
        ->add('profession', TextType::class, ['label' => 'form.label.job', 'required' => false])
        ;
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Contact::class,
        ));
    }
}

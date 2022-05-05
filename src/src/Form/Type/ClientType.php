<?php

namespace App\Form\Type;

use App\Entity\Client;
use App\Enum\EducationType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ClientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname', TextType::class)
            ->add('lastname', TextType::class)
            ->add('phone', TelType::class, [
                'attr' => [
                    'placeholder' => 'form.client.phone.placeholder'
                ]
            ])
            ->add('email', EmailType::class)
            ->add('education', EnumType::class, [
                'class' => EducationType::class,
                'choice_label' => function($choice) {
                    return $choice->label();
                },
            ])
            ->add('consent', CheckboxType::class,[
                'required' => false,
            ])
            ->add('save', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Client::class,
            'consent' => false,
        ]);

        $resolver->setAllowedTypes('consent', 'bool');
    }
}
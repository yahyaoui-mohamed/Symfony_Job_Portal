<?php

namespace App\Form;

use App\Entity\Experience;
use App\Entity\user;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ExperienceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('company', TextType::class, [
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('jobTitle', TextType::class, [
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('yearFrom', DateType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
            ])
            ->add('toYear', DateType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Experience::class,
        ]);
    }
}

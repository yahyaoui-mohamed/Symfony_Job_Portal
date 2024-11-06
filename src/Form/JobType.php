<?php

namespace App\Form;

use App\Entity\Job;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class JobType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class)
            ->add('description', TextareaType::class)
            ->add('requirements', CollectionType::class, [
                'entry_type' => TextType::class,  // Each entry will be a text input
                'allow_add' => true,
                'allow_delete' => true,           // Allow removing entries dynamically
                'by_reference' => false,
                'empty_data' => [],
                'attr' => ['class' => 'requirements-collection'],
                'entry_options' => [
                    'label' => false,
                    'attr' => [
                        'class' => 'form-control mb-3'
                    ]
                ]
            ])
            ->add('experiences', CollectionType::class, [
                'entry_type' => TextType::class,  // Each entry will be a text input
                'allow_add' => true,
                'allow_delete' => true,           // Allow removing entries dynamically
                'by_reference' => false,
                'empty_data' => [],
                'attr' => ['class' => 'experiences-collection'],
                'entry_options' => [
                    'label' => false,
                    'attr' => [
                        'class' => 'form-control mb-3'
                    ]
                ]
            ])
            ->add('missions', CollectionType::class, [
                'entry_type' => TextType::class,  // Each entry will be a text input
                'allow_add' => true,              // Allow adding new entries dynamically
                'allow_delete' => true,           // Allow removing entries dynamically
                'by_reference' => false,
                'empty_data' => [],
                'attr' => ['class' => 'missions-collection'],
                'entry_options' => [
                    'label' => false,
                    'attr' => [
                        'class' => 'form-control mb-3'
                    ]
                ]
            ])
            ->add('minsalary', NumberType::class)
            ->add('maxsalary', NumberType::class)
            ->add('country', TextType::class)
            ->add('city', TextType::class)
            ->add('type')
            ->add("post", SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Job::class,
        ]);
    }
}

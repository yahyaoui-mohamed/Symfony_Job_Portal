<?php

namespace App\Form;

use App\Service\YearGeneratorService;
use App\Entity\Education;
use App\Entity\user;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EducationType extends AbstractType
{
    private YearGeneratorService $yearGenerator;

    public function __construct(YearGeneratorService $yearGenerator)
    {
        $this->yearGenerator = $yearGenerator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('year', ChoiceType::class, [
                'attr' => [
                    'class' => 'form-select mb-3'
                ],
                'required' => false,
                'choices' => $this->yearGenerator->getYearsList(),
                'placeholder' => 'Select a year',
            ])
            ->add('university', TextType::class, [
                'attr' => [
                    'class' => 'form-control mb-3',
                    'placeholder' => 'University',
                ],
                'required' => false,
            ])
            ->add('degree', ChoiceType::class, [
                'attr' => [
                    'class' => 'form-select mb-3',

                ],
                'placeholder' => 'Degree',
                'choices' => [
                    'High School' => 'high school',
                    'Bachelor Degree' => 'bachelor degree',
                    'Master Degree' => 'master degree',
                    'PhD' => 'phd'
                ],
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Education::class,
        ]);
    }
}

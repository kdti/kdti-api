<?php

declare(strict_types=1);

namespace App\Form;

use App\Request\PostJobOffer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class PostJobOfferType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('description')
            ->add('seniorityLevel')
            ->add('minimumSalary', IntegerType::class)
            ->add('maximumSalary', IntegerType::class)
            ->add('allowRemote');
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => PostJobOffer::class,
            'csrf_protection' => false,
        ]);
    }
}

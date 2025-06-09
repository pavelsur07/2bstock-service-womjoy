<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\CashflowCategory;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\CallbackTransformer;

class CashflowCategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Название категории'
            ])
            ->add('parent', EntityType::class, [
                'class' => CashflowCategory::class,
                'required' => false,
                'choice_label' => 'name',
                'label' => 'Родительская категория'
            ])
            ->add('sortOrder', IntegerType::class, [
                'label' => 'Приоритет отображения',
                'required' => false,
                'empty_data' => '0',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CashflowCategory::class,
        ]);
    }
}

<?php

namespace App\Form;

use App\Entity\CashAccount;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
class CashAccountType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class)
            ->add('type', ChoiceType::class, [
                'choices' => [
                    'Касса' => CashAccount::CASH,
                    'Банковский счёт' => CashAccount::BANK,
                    'Виртуальный фонд' => CashAccount::VIRTUAL,
                ],
            ])
            ->add('openingBalance', NumberType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CashAccount::class,
        ]);
    }
}

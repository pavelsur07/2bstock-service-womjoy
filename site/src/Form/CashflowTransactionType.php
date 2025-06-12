<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\CashAccount;
use App\Entity\CashflowCategory;
use App\Entity\CashflowTransaction;
use App\Entity\Project;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class CashflowTransactionType extends AbstractType
{


    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('date', Type\DateType::class, [
                'widget' => 'single_text',
                'label' => 'Дата',
                'input' => 'datetime_immutable',
            ])
            ->add('project', EntityType::class, [
                'class' => Project::class,
                'label' => 'Проект',
                'required' => false,
                'placeholder' => 'Без проекта',
                'choice_label' => 'name',
                'query_builder' => function (EntityRepository $er) use ($options) {
                    return $er->createQueryBuilder('p')
                        ->where('p.company = :company')
                        ->setParameter('company', $options['company'] ?? null)
                        ->orderBy('p.name', 'ASC');
                },
            ])
            ->add('amount', Type\MoneyType::class, [ 'currency' => 'RUB', 'label' => 'Сумма' ])
            ->add('direction', Type\ChoiceType::class, [
                'choices' => [ 'Доход' => CashflowTransaction::INCOME, 'Расход' => CashflowTransaction::EXPENSE ],
                'label' => 'Тип операции'
            ])
            ->add('account', EntityType::class, [
                'class' => CashAccount::class,
                'choice_label' => 'name',
            ])
            ->add('category', EntityType::class, [
                'class' => CashflowCategory::class,
                'choice_label' => fn (CashflowCategory $category) => $category->getIndentedName(),
                'label' => 'Категория',
                'query_builder' => function (EntityRepository $er) use ($options) {
                    return $er->createQueryBuilder('c')
                        ->leftJoin('c.children', 'child')
                        ->where('c.company = :company')
                        ->andWhere('child.id IS NULL')
                        ->setParameter('company', $options['company'] ?? null)
                        ->orderBy('c.sortOrder', 'ASC')
                        ->addOrderBy('c.name', 'ASC');
                },
            ])
            ->add('comment', Type\TextareaType::class, [ 'required' => false, 'label' => 'Комментарий' ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CashflowTransaction::class,
            'company' => null,
            ]);
    }
}

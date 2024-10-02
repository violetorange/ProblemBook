<?php

namespace App\Form;

use App\Entity\Tasks;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use function Doctrine\ORM\add;

class EditTaskType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('user_owner', EntityType::class, [
                'attr' => [
                    'class' => 'form-control mb-3'
                ],
                'class' => User::class,
                'choice_label' => 'email',
                'label' => 'Назначить'
            ])
            ->add('cost_estimation', NumberType::class, [
                'attr' => [
                    'class' => 'form-control mb-3 formSmallInput'
                ],
                'scale' => 1,
                'label' => 'Оценка (ч)'
            ])
            ->add('timeCostsDescription', TextareaType::class, [
                'attr' => [
                    'class' => 'form-control mb-3 nonResize',
                    'maxlength' => 255,
                    'rows' => 1
                ],
                'required' => false,
                'mapped' => false,
                'label' => 'Проделанная работа',
            ])
            ->add('timeCostsAmount', NumberType::class, [
                'attr' => [
                    'class' => 'form-control mb-3 formSmallInput',
                ],
                'required' => false,
                'mapped' => false,
                'data' => 0,
                'scale' => 1,
                'label' => 'Трудозатраты (ч)',
            ])
            ->add('text', TextareaType::class, [
                'attr' => [
                    'class' => 'form-control mb-3',
                    'rows' => 5
                ],
                'required' => false,
                'mapped' => false,
                'label' => 'Комментарий:',
            ])
            ->add('Save', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-primary mt-3'
                ],
                'label' => 'Опубликовать',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Tasks::class,
        ]);
    }
}

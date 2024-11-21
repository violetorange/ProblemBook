<?php

namespace App\Form\admin;

use App\Entity\Projects;
use App\Entity\Tasks;
use App\Entity\User;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TaskType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Название задачи'
                ],
                'label' => 'Название'
            ])
            ->add('description', TextareaType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Описание задачи'
                ],
                'label' => 'Описание'
            ])
            ->add('type', ChoiceType::class, [
                'attr' => [
                    'class' => 'form-control',
                ],
                'choices' => [
                    'Ошибка' => 'Ошибка',
                    'Аналитика' => 'Аналитика',
                    'Улучшение' => 'Улучшение',
                    'Менеджмент' => 'Менеджмент',
                    'Поддержка' => 'Поддержка',
                    'Разработка' => 'Разработка',
                    'Дизайн' => 'Дизайн',
                    'Верстка' => 'Верстка',
                    'Тестирование' => 'Тестирование',
                    'Тех. администрирование' => 'Тех. администрирование'
                ],
                'label' => 'Тип'
            ])
            ->add('created_at', null, [
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'Дата начала'
            ])
            ->add('cost_estimation', NumberType::class, [
                'scale' => 1,
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'Оценка времени (ч)'
            ])
            ->add('user_owner', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'email',
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'Назначен'
            ])
            ->add('project', EntityType::class, [
                'class' => Projects::class,
                'choice_label' => 'title',
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'Проект'
            ])
            ->add('Save', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-primary mt-2'
                ],
                'label' => 'Опубликовать'
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

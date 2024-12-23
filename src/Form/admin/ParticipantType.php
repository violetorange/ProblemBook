<?php

namespace App\Form\admin;

use App\Entity\Participants;
use App\Entity\Projects;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ParticipantType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('project', EntityType::class, [
                'attr' => [
                    'class' => 'form-control',
                ],
                'label' => 'Название проекта',
                'class' => Projects::class,
                'choice_label' => 'title',
            ])
            ->add('participant', EntityType::class, [
                'attr' => [
                    'class' => 'form-control',
                ],
                'label' => 'Пользователь',
                'class' => User::class,
                'choice_label' => 'email',
            ])
            ->add('Save', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-primary mt-2'
                ],
                'label' => 'Добавить'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Participants::class,
        ]);
    }
}

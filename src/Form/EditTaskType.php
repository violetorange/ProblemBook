<?php

namespace App\Form;

use App\Entity\Tasks;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EditTaskType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('user_owner', EntityType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
                'class' => User::class,
                'choice_label' => 'email',
                'label' => 'Назначить'
            ])
            ->add('text', TextareaType::class, [
                'attr' => [
                    'class' => 'form-control',
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

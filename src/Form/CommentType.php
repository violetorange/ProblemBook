<?php

namespace App\Form;

use App\Entity\Comments;
use App\Entity\Tasks;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('number', IntegerType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Номер комментария'
                ]
            ])
            ->add('text', TextareaType::class, [
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('created_at', null, [
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('user_owner', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'email',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('task', EntityType::class, [
                'class' => Tasks::class,
                'choice_label' => 'id',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('Save', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-primary'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Comments::class,
        ]);
    }
}

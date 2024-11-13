<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Post;
use App\Entity\PostType as Type;
use App\Entity\User;
use App\Enum\ApprovalStatusEnum;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('content')
            ->add('publishedAt', null, [
                'widget' => 'single_text',
            ])
            ->add('postType', EntityType::class, [
                'class' => Type::class,
                'choice_label' => 'title',
            ])
            ->add('categories', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'title',
                'multiple' => true,
            ])
            ->add('status', EnumType::class, [
                'class' => ApprovalStatusEnum::class,
            ])
            ->add('imageFile', FileType::class, [
                'mapped' => false,
                'required' => false,
            ])
            ->add('save', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
        ]);
    }
}

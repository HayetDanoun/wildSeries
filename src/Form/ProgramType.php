<?php

namespace App\Form;

use App\Entity\Program;
use App\Entity\Actor;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use App\Entity\Category;

class ProgramType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title',TextType::class)
            ->add('synopsis',TextType::class)
            ->add('poster',FileType::class ,['required' => false,])
            ->add('country',CountryType::class)
            ->add('year',IntegerType::class)
            ->add('category', null, ['choice_label' => 'name'])
        ;
        $builder->add('actors', EntityType::class, [
            'class' => Actor::class,
            'choice_label' => function (Actor $actor) {
              return $actor->getFirstname() . ' ' . $actor->getLastname() . ' ' ;
            },
            'multiple' => true,
            'expanded' => true,
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Program::class,
        ]);
    }
}

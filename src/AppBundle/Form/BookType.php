<?php

namespace AppBundle\Form;

use AppBundle\Entity\Book;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BookType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add("title", TextType::class)
            ->add("author", TextType::class)
            ->add("publishDate", DateType::class)
            ->add("pageCount", IntegerType::class)
            ->add('format', EntityType::class, array(
                // query choices from this entity
                'placeholder' => 'Choose Format',
                'class' => 'AppBundle:Format',

                // use the User.username property as the visible option string
                'choice_label' => 'format',

                // used to render a select box, check boxes or radios
                // 'multiple' => true,
                // 'expanded' => true,
            ))
            ->add("isbn", TextType::class)
            ->add("resume", TextareaType::class)
            ->add("image", FileType::class, [
                'data_class' => null,
                'required' => false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                "data_class" => Book::class
            ]
        );
    }

    public function getBlockPrefix()
    {
        return 'app_bundle_book_type';
    }
}

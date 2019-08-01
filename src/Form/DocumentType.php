<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Document;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DocumentType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("documentName", TextType::class, array(
                "label" => "Dokumento pavadinimas",
                'attr' => array()
            ))
            ->add("documentDate", DateType::class, array(
                "html5" => true,
                "label" => "Pradžios data",
                'widget' => 'single_text',
                'attr' => array(),
                'required' => false
            ))
            ->add("documentExpires", DateType::class, array(
                "label" => "Pabaigos data",
                'widget' => 'single_text',
                'attr' => array(),
                'required' => false
            ))
            ->add("documentReminder", DateType::class, array(
                "label" => "Priminimas",
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
                'required' => false
            ))
            ->add('tag', CollectionType::class, array(
                'label' => false,
                'entry_type' => TagType::class,
                'entry_options' => array('label' => false),
                'allow_add' => true,
                'by_reference' => false,
                'allow_delete' => true,
                'mapped' => false
            ))
            ->add("documentNotes", TextareaType::class, array(
                "label" => "Pastabos",
                'attr' => array(
                    "style" => "width: 100%",
                    "rows" => "3"
                ),
                'required' => false
            ))
            ->add('files', FileType::class, array(
                'required' => false,
                'mapped' => false,
                'multiple' => true,
                'attr'     => [
                    'accept' => 'application/zip, text/plain, application/pdf, image/*',
                    'multiple' => 'multiple',
                    'capture'=> 'camera'
                ]
            ))
            ->add('category', EntityType::class, array(
                "label" => "Kategorijos",
                'class' => Category::class,
                'choice_label' => 'categoryName',
            ))
            ->add("save", SubmitType::class, array(
                "label" => "Išsaugoti",
                'attr' => array('style' => 'float: left')
            ))
            ->add("cancel", ButtonType::class, array(
                "label" => "Atšaukti",
                'attr' => array()
            ))
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Document::class,
        ));
    }
}

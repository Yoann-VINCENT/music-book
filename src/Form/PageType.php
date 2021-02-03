<?php

namespace App\Form;

use App\Entity\Book;
use App\Entity\Page;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichFileType;

class PageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('imageFile', VichFileType::class, [
                'required' => true,
                'attr' => [
                    'placeholder' => 'Choose ...',
                ],
                'download_label' => false,
                'asset_helper' => true,
            ])
            ->add('songFile', VichFileType::class, [
                'required' => true,
                'attr' => [
                    'placeholder' => 'Choose ...',
                ],
                'download_label' => false,
                'asset_helper' => true,
            ])
            ->add('lyrics')
            ->add('page_number')
            ->add('book', null, ['choice_label' => 'title'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Page::class,
        ]);
    }
}

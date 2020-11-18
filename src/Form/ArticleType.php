<?php

namespace App\Form;

use App\Entity\Article;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('category', ChoiceType::class, [
                'choices' => [
                    'Vehicules' => 'vehicules',
                    'Multimedia' => 'Multimedia',
                    'Meuble' => 'Meuble',
                    'Immobilier' => 'Immmoblier',
                    'Mode' => 'Mode',
                    'Service' => 'Service',
                    'Loisirs' => 'Loisirs',
                    'Divers' => 'Divers',

                ]
            ])
            ->add('marque', TextType::class)
            ->add('carburant', ChoiceType::class, [
                'choices' => [
                    'Essence' => 'Essence',
                    'Diesel' => 'Diesel',
                    'Ethanol' => 'Ethanol',
                    'Electric' => 'EElectric'
                ]

            ])
            ->add('titre', TextType::class, [
                'label' => 'titre de l\'annonce'
            ])
            ->add('description')
            ->add('imageFile', VichImageType::class, [
                'required' => false,
                'allow_delete' => true,
                'delete_label' => '...',
                'image_uri' => true,
                #'imagine_pattern' => 'my_thumbnail',
                'asset_helper' => true,
            ])
            ->add('prix')
            ->add('etat', ChoiceType::class, [
                'choices' => [
                    'Etat neuf' => 'Etat neuf',
                    'Occasion' => 'Occasion',
                    'A réparer' => 'reparer'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}

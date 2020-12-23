<?php

namespace App\Form;

use App\Entity\Article;
use App\Entity\Category;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
                "placeholder" => "Séléctionnez une catégorie !"
            ]);
        $builder->get('category')->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) {

            $form = $event->getForm();
            $category = $form->getData();
            // dump($form->getParent());
            if ($category == "Vehicule") {

                //dump($category);
                $form->getParent()->add('marque', TextType::class)
                    ->add('carburant', ChoiceType::class, [
                        'choices' => [
                            'Essence' => 'Essence',
                            'Diesel' => 'Diesel',
                            'Ethanol' => 'Ethanol',
                            'Electric' => 'EElectric'
                        ]

                    ]);
            }
        });


        $builder->get('category')->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) {


            $form = $event->getForm();
            $category = $form->getData();

            if ($category !== "Vehicule") {

                $form->getParent()->add('titre', TextType::class, [
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
                    ])
                    ->add('ville', TextType::class);
            };
        });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}

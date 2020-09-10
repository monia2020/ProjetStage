<?php

namespace App\Form;

use App\Entity\Produits;
use App\Entity\Categories;
use App\Entity\SousCategories;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

class ProduitsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom')
            ->add('reference')
            ->add('categorie', EntityType::class, [
                'class' => Categories::class,
                'choice_label' => 'nom',
                'placeholder' => 'Selectionner la categorie',
                'mapped'=>false,
                'required' =>false,
            ]);

        $builder->get('categorie')->addEventListener(
                FormEvents::POST_SUBMIT,
                function (FormEvent $event)
                {
                    $form = $event->getForm();
                    $form->getparent()->add('sousCategories', EntityType::class, [
                        'class' => SousCategories::class,
                        'placeholder' => 'Selectionner la sous categorie',
                        'choices' => $form->getData()->getSousCategory()
    
                    ]);
                });

        $builder->addEventListener(
                FormEvents::POST_SET_DATA,
                function(FormEvent $event)
                {
                    $form = $event->getForm();
                    $data = $event->getData();
                    $sous_categorie = $data->getSousCategories();
    
                    if($sous_categorie)
                    {
                        $form->get('categorie')->setData($sous_categorie->getCategorie());
    
                        $form->add('sousCategories', EntityType::class, [
                            'class' => SousCategories::class,
                            'placeholder' => 'Selectionner la sous categorie',
                            'choices' => $sous_categorie->getCategorie()->getSousCategory()
                        ]);
                    }
                    // else
                    // {
                    //     $form->get('categorie')->setData($sous_categorie->getCategorie());
    
                    //     $form->add('sousCategories', EntityType::class, [
                    //         'class' => SousCategories::class,
                    //         'placeholder' => 'Selectionner la sous categorie',
                    //         'choices' => []
                    //     ]);
                    // }    
                }
            );
            
        $builder   
            ->add('prix')
            ->add('stock')
            ->add('tva')
            ->add('caracteristiques')
            ->add('risques')
            ->add('description')
            ->add('promotion')
            ->add('imageFile', VichImageType::class, [
                'label' => 'Image produit',
                'allow_delete' => false,
                'download_uri' => false,
                'required'     => false
        ]);    
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Produits::class,
        ]);
    }
}

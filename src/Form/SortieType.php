<?php

namespace App\Form;

use App\Entity\Sorties;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SortieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom'
            ])
            ->add('dateHeureDebut', DateTimeType::class, [
                'html5' => true,
                'widget' => 'single_text',
            ])
            ->add('duree')
            ->add('dateLimiteInscription', DateType::class, [
                'html5' => true,
                'widget' => 'single_text',
            ])
            ->add('nbInscriptionMax')
            ->add('infoSortie')
            ->add('etat', ChoiceType::class, [
                'choices' => [
                    'Creee' => 'Creee',
                    'Ouverte' => 'Ouverte',
                    'Annulee' => 'Annulee'
                ],
                'multiple' => false
            ])
            ->add('campus', ChoiceType::class, [
        'choices' => [
            'Nantes' => 'Nantes',
            'Rennes' => 'Rennes',
            'La Roche-Sur-Yon' => 'La Roche-Sur-Yon'
        ],
        'multiple' => false
    ])
            ->add('lieu', TextType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Sorties::class,
        ]);
    }
}

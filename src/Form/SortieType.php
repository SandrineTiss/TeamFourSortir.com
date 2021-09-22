<?php

namespace App\Form;

use App\Entity\Lieu;
use App\Entity\Sorties;
use App\Entity\Etat;
use App\Entity\Campus;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\User\User;

class SortieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom de la sortie :'
            ])
            ->add('dateHeureDebut', DateTimeType::class, [
                'label' => 'Date et heure de la sortie :',
                'html5' => true,
                'widget' => 'single_text',
            ])
            ->add('duree', TimeType::class, [
                'label' => 'Durée (en minutes) :',
                'html5' => true,
                'with_minutes' => false

            ])
            ->add('dateLimiteInscription', DateType::class, [
                'label' => 'Date limite d\'inscription :',
                'html5' => true,
                'widget' => 'single_text',
            ])
            ->add('nbInscriptionMax', IntegerType::class, [
                'label' => 'Nombre de places :'
            ])
            ->add('infoSortie', null, [
                'label' => 'Description et infos :'
            ]);
            $builder->add('etat', EntityType::class, [
                'label' => 'Etat :',
                'class' => Etat::class,
                'choice_label' => 'libelle',
                'multiple' => false
            ]);

            $builder->add('campus', EntityType::class, [
                'label' => 'Campus :',
                'class' => Campus::class,
                'choice_label' => 'nom',
                'multiple' => false
            ]);

             $builder->add('lieu', EntityType::class, [
                'class' => Lieu::class,
                 'choice_label'=>'nom',
                 'multiple' => false
                     ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Sorties::class,
        ]);
    }
}

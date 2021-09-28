<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\Lieu;
use App\Entity\Sorties;
use App\Entity\Ville;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SortieParticipationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom de la sortie'
            ])
            ->add('dateHeureDebut', DateTimeType::class, [
                'label' => 'Date et heure de la sortie',
                'html5' => true,
                'widget' => 'single_text',
            ])
            ->add('duree', IntegerType::class, [
                'label' => 'Durée (en minutes)'
            ])
            ->add('dateLimiteInscription', DateType::class, [
                'label' => 'Date limite d\'inscription',
                'html5' => true,
                'widget' => 'single_text',
            ])
            ->add('nbInscriptionMax', IntegerType::class, [
                'label' => 'Nombre de places'
            ])
            ->add('infoSortie', null, [
                'label' => 'Description et infos'
            ])
            ->add('campus', EntityType::class, [
                'mapped' => true,
                'class' => Campus::class,
                'choice_label' => 'nom',
                'choice_value' => 'nom',
                'placeholder' => 'Campus',
                'label' => 'Campus',
                'required' => false])

             ->add('lieu', EntityType::class, [
                 'class' => Lieu::class,
                 'choice_label' => 'nom',
                 'choice_value' => 'nom',
                 'label' => 'Lieu: ',
                 'required' => false
             ])
            ->add('ville', EntityType::class, [
                'mapped' => true,
                'class' => Ville::class,
                'choice_label' => 'nom',
                'choice_value' => 'nom',
                'placeholder' => 'Ville',
                'label' => 'Ville',
                'required' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Sorties::class,
        ]);
    }
}

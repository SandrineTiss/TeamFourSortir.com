<?php

namespace App\Form;

use App\Entity\Lieu;
use App\Entity\Sorties;
use App\Entity\Etat;
use App\Entity\Campus;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Exception\CredentialsExpiredException;

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
            ->add('infoSortie');
            $builder->add('etat', EntityType::class, [
                'class' => Etat::class,
                'choice_label' => 'libelle',
                'multiple' => false
            ]);

            $builder->add('campus', EntityType::class, [
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

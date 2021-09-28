<?php

namespace App\Form;

use App\Entity\Lieu;
use App\Entity\Sorties;
use App\Entity\Ville;
use App\Repository\LieuRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\User\User;
use function Sodium\add;

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
            ->add('duree', IntegerType::class, [
                'label' => 'Durée (en minutes) :'
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
            ])
//            $builder->add('etat', EntityType::class, [
//                'label' => 'Etat :',
//                'class' => Etat::class,
//                'choice_label' => 'libelle',
//                'multiple' => false
//            ]);

            ->add('ville', EntityType::class, [
                'mapped' => false,
                'class' => Ville::class,
                'choice_label' => 'nom',
                'placeholder' => 'Ville',
                'label' => 'Ville',
                'required' => false
            ])

             ->add('lieu', EntityType::class, [
                 'class' => Lieu::class,
                 'choice_label' => 'nom',
                 'placeholder' => 'Lieu',
                 'required' => false
             ]);

//             $formModifier = function (FormInterface $form, Ville $ville) {
//                 $lieu = null === $ville ? [] : $ville->getNom();
//                 //$lieu = $lieuRepository->findBy(['ville' => $ville]);
//
//                 $form->add('lieu', EntityType::class, [
//                     'mapped' => false,
//                     'class' => Lieu::class,
//                     'choice_label' => 'nom',
//                     'choices' => $lieu,
//                     'placeholder' => 'Lieu (choisir une ville)',
//                     'label' => 'Lieu: ',
//                     'required' => false
//                 ]);
//             };
//
//             $builder->get('ville')->addEventListener(
//                 FormEvents::PRE_SUBMIT,
//                 function (FormEvent $event) use ($formModifier){
//                     $ville = $event->getForm()->getData();
//                     $formModifier($event->getForm()->getParent(), $ville);
//                 }
//             );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Sorties::class,
        ]);
    }
}

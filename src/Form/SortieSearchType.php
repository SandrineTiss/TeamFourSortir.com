<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\SortieSearch;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateIntervalType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SortieSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('campus', EntityType::class, [
                'class'=> Campus::class,
                'choice_label'=>'nom',
                'attr' => ['filtreSearch'],
                'required'=>false
            ])

            ->add('organisateur', CheckboxType::class, [
                'attr' => ['filtreSearch'],
                'label' => 'Je suis l\'organisateur',
                'required'=>false
            ])

            ->add('nom', TextType::class, [
                'attr' => ['filtreSearch'],
                'label'=>'Le nom de la sortie contient:',
                'required'=>false
            ])

            ->add('date', DateType::class, [
                'html5' => true,
                'widget' => 'single_text',
                'label' => 'Entre le:',
                'required'=>false
            ])
            ->add('date2', DateType::class, [
                'html5' => true,
                'widget' => 'single_text',
                'label' => 'et le:',
                'required'=>false
            ])

            ->add('inscrit', CheckboxType::class, [
                'attr' => ['filtreSearch'],
                'label'=>'Sorties auxquelles je suis inscrit/e',
                'required'=>false
            ])

            ->add('notInscrit', CheckboxType::class, [
                'attr' => ['filtreSearch'],
                'label'=>'Sorties auxquelles je ne suis pas inscrit/e',
                'required'=>false
            ])

            ->add('ended', CheckboxType::class, [
                'attr' => ['filtreSearch'],
                'label'=>'Sorties passées',
                'required'=>false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SortieSearch::class,
            'method'=>'get',
            'csrf_protection'=>false
        ]);
    }

    public function getBlockPrefix()
    {
        return '';
    }
}

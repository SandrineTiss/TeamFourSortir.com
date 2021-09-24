<?php

namespace App\Form;

use App\Entity\Sorties;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AnnulerSortieType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('infoSortie',TextType::class, [
                'label'=>'Motif d\'annulation :',
                'attr' => ['value' => ''],
                'required'=>true
            ])
            ->add('nom', TextType::class,[
                'required'=>true
            ])
            ->add('dateHeureDebut',DateTimeType::class,[
                'html5' => true,
                'widget' => 'single_text',
                'required'=>true
                ])
            ->add('duree', IntegerType::class, [
                'required'=>true
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

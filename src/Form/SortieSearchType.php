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
use Symfony\Component\Security\Core\Security;

class SortieSearchType extends AbstractType
{
    private $security;
    public function __construct(Security $security){
        $this->security = $security;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $user = $this->security->getUser();
        $builder
            ->add('campus', EntityType::class, [
                'class'=> Campus::class,
                'choice_label'=>'nom',
                'label' => 'Campus ',
                'placeholder' => 'Tous les campus',
                'required'=>false
            ])

            ->add('organisateur', CheckboxType::class, [
                'label' => 'Je suis l\'organisateur/trice',
                'required'=>false
            ])

            ->add('nom', TextType::class, [
                'attr' => ['placeholder' => 'Rechercher '],
                'label'=>'Le nom de la sortie contient:',
                'required'=>false
            ])

            ->add('dateMin', DateType::class, [
                'html5' => true,
                'widget' => 'single_text',
                'label' => 'Entre le: ',
                'required'=>false
            ])
            ->add('datemax', DateType::class, [
                'html5' => true,
                'widget' => 'single_text',
                'label' => ' et le: ',
                'required'=>false
            ])

            ->add('inscrit', CheckboxType::class, [
                'label'=>'Sorties auxquelles je suis inscrit/e',
                'required'=>false
            ])

            ->add('notInscrit', CheckboxType::class, [
                'label'=>'Sorties auxquelles je ne suis pas inscrit/e',
                'required'=>false
            ])

            ->add('ended', CheckboxType::class, [
                'label' => 'Sorties passées',
                'value' => 'Cloturée',
                'required' => false
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

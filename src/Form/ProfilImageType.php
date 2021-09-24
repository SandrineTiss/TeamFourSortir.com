<?php

namespace App\Form;

use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;

class ProfilImageType extends \Symfony\Component\Form\AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('image', FileType::class, [
                'label' => false,
                'multiple' => false,
                'mapped' => false,
                'required' => false
            ]);
    }

}
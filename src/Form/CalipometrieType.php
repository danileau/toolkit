<?php

namespace App\Form;

use App\Entity\Calipometrie;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CalipometrieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('bauch', NumberType::class, array('attr' => array('class' => 'form-control', 'placeholder' => 'Nur Wert eingeben z.B 81')))
            ->add('brust', NumberType::class, array('attr' => array('class' => 'form-control', 'placeholder' => 'Nur Wert eingeben z.B 81')))
            ->add('huefte', NumberType::class, array('attr' => array('class' => 'form-control', 'placeholder' => 'Nur Wert eingeben z.B 81')))
            ->add('trizeps', NumberType::class, array('attr' => array('class' => 'form-control', 'placeholder' => 'Nur Wert eingeben z.B 81')))
            ->add('bein', NumberType::class, array('attr' => array('class' => 'form-control', 'placeholder' => 'Nur Wert eingeben z.B 81')))
            ->add('achsel', NumberType::class, array('attr' => array('class' => 'form-control', 'placeholder' => 'Nur Wert eingeben z.B 81')))
            ->add('ruecken', NumberType::class, array('attr' => array('class' => 'form-control', 'placeholder' => 'Nur Wert eingeben z.B 81')))
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Calipometrie::class,
        ]);
    }
}

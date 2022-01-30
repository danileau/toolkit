<?php

namespace App\Form;

use App\Entity\Gewicht;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GewichtType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('gewicht', NumberType::class, array('attr' => array('class' => 'form-control', 'placeholder' => 'Nur Wert eingeben z.B 81')))
            ->add('bf', NumberType::class, array('attr' => array('class' => 'form-control', 'placeholder' => 'Nur Wert eingeben z.B 17.5')))
            ->add('timestamp', DateType::class, array('attr' => array('class' => 'form-date-control')))
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Gewicht::class,
        ]);
    }
}

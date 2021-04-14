<?php

namespace App\Form;

use App\Entity\Place;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PlaceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name',null, [
                'attr' => [
                    'placeholder' => 'Name',
                    'class' => 'form-control m-1',
                ]])
            ->add('address',null, [
                'attr' => [
                    'placeholder' => 'Address',
                'class' => 'form-control m-1',
                 ]])
            ->add('latitude',null, [
                'attr' => [
                    'placeholder' => 'Latitude',
                    'class' => 'form-control m-1',
                ]])
            ->add('longitude',null, [
                'attr' => [
                    'placeholder' => 'Longitude',
                    'class' => 'form-control m-1',
                ]])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Place::class,
        ]);
    }
}

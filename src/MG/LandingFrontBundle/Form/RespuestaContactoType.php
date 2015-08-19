<?php

namespace MG\LandingFrontBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class RespuestaContactoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('mensaje', 'textarea', array('attr' => array('placeholder' => 'Respuesta para el usuario')))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'MG\LandingFrontBundle\Entity\RespuestaContacto'
        ));
    }

    public function getName()
    {
        return 'mg_landingfrontbundle_respuestacontactotype';
    }
}

<?php

namespace MG\MensajeriaBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class MensajesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('mensaje', 'textarea', array('required'=>true, 'label'=>false, 'attr' => array('placeholder' => 'Mensaje')))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'MG\MensajeriaBundle\Entity\Mensajes'
        ));
    }

    public function getName()
    {
        return 'mg_mensajeriabundle_mensajestype';
    }
}

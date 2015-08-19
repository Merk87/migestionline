<?php

namespace MG\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class ClientUpdateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', 'email', array('attr' => array('placeholder' => 'Email'),'disabled' => true))
            ->add('name', 'text', array('attr' => array('placeholder' => 'Nombre')))
            ->add('apellidos', 'text', array('attr' => array('placeholder' => 'Apellidos')))
            ->add('nif', 'text', array('attr' => array('placeholder' => 'NIF', 'maxlength' => 9)))
            ->add('fechaNacimiento', 'date', array(
            'widget' => 'single_text'))
            ->add('telefono', 'text', array('attr' => array('placeholder' => 'Teléfono'), 'required' => false))
            ->add('telefonoMvl', 'text', array('attr' => array('placeholder' => 'Teléfono móvil'), 'required' => false))
            ->add('direccion', 'text', array('attr' => array('placeholder' => 'Dirección'), 'required' => false))
            ->add('ciudad', 'text', array('attr' => array('placeholder' => 'Ciudad'), 'required' => false))
            ->add('codigoPostal', 'text', array('attr' => array('placeholder' => 'Código Postal'), 'required' => false))
            ->add('pais', 'text', array('attr' => array('placeholder' => 'País'), 'required' => false))
        ;
    }



    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'MG\UserBundle\Entity\User'
        ));
    }

    public function getName()
    {
        return 'mg_clientbundle_userupdatetype';
    }
}

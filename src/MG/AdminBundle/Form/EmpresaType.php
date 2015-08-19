<?php

namespace MG\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class EmpresaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre', 'text', array('attr' => array('placeholder' => 'Nombre')))
            ->add('CIF', 'text', array('attr' => array('placeholder' => 'CIF', 'maxlength' => 9)))
            ->add('direccion', 'text', array('attr'=>array('placeholder' => 'Dirección')))
            ->add('codigoPostal', 'text', array('attr' => array('placeholder' => 'Código Postal'), 'required' => true))
            ->add('ciudad', 'text', array('attr' => array('placeholder' => 'Ciudad'), 'required' => true))
            ->add('pais', 'choice', array('choices' => array('España' => 'España')))
            ->add('telefono', 'text', array('attr' => array('placeholder' => 'Teléfono', 'maxlength' => 15)))
            ->add('logoFile', 'file', array( 'attr' => array('multiple' => true,) ,'required' => false))
            ->add('logoTitle', 'text', array('attr' => array('placeholder' => 'propiedad title del tag <img>')))
            ->add('logoAlt', 'text', array('attr' => array('placeholder' => 'propiedad alt del tag <img>')))
            ->add('enabled', 'choice', array('choices' => array('1' => 'Activo','0' => 'Desactivado')))
            ->add('public', 'choice', array('choices' => array('1' => 'Publico', '0' => 'Privado')))
            ->add('web', 'text', array('attr' => array('placeholder' => 'Web')))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'MG\AdminBundle\Entity\Empresa'
        ));
    }

    public function getName()
    {
        return 'mg_adminbundle_empresatype';
    }
}

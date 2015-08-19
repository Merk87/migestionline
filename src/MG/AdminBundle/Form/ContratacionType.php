<?php

namespace MG\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ContratacionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text', array('attr' => array('placeholder' => 'Nombre / Razón social')))
            ->add('NIF', 'text', array('attr' => array('placeholder' => 'NIF / CIF','maxlength' => 9)))
            ->add('domicilioFiscal', 'text', array('attr' => array('placeholder' => 'Domicilio fiscal')))
            ->add('domicilioFacturacion', 'text', array('attr' => array('placeholder' => 'Domicilio para notificaciones')))
            ->add('ciudad', 'text', array('attr' => array('placeholder' => 'Ciudad')))
            ->add('pais', 'choice', array('choices' => array('España' => 'España')))
            ->add('codigoPostal', 'text', array('attr' => array('placeholder' => 'Código Postal')))
            ->add('telefono', 'text', array('attr' => array('placeholder' => 'Teléfono'),'required' => true))
            ->add('paquete' , 'entity', array( 'class' => 'MGAdminBundle:Paquete','property' => 'displayForm', 'attr' => array('class' => 'formedSelectors')))
            ->add('periodo' , 'entity', array( 'class' => 'MGAdminBundle:PeriodosContratacion', 'property' => 'displayForm', 'attr' => array('class' => 'formedSelectors')))
            ->add('telefonoMvl', 'text', array('attr' => array('placeholder' => 'Teléfono móvil'),'required' => true))
            ->add('email', 'text', array('attr' => array('placeholder' => 'Dirección email')))
            ->add('web', 'text', array('attr' => array('placeholder' => 'Web'),'required' => false))
            ->add('aceptacionPol','checkbox', array(
                'attr' => array('class' => 'checkbx pull-left')))
            ->add('aceptacionContra', 'checkbox', array(
                'attr' => array('class' => 'checkbx pull-left')))
            ->add('cuentaDomiciliacion', 'text', array('attr' => array('placeholder' => 'Número de cuenta bancaria  ')))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'MG\AdminBundle\Entity\Contratacion'
        ));
    }

    public function getName()
    {
        return 'mg_adminbundle_contrataciontype';
    }
}

<?php

namespace MG\RepoBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class EditServiciosType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre', 'text', array('attr' => array('placeholder' => 'Nombre'), 'required' => true))
            ->add('descripcion', 'text', array('attr' => array('placeholder' => 'Descripción'), 'required' => true))
            ->add('precio', 'integer',  array('attr'=>array('min' => 0) ,'required'=> true))
            ->add('enabled', 'choice', array('choices' => array('1' => 'Activo','0' => 'Desactivado')))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'MG\RepoBundle\Entity\Servicios'
        ));
    }

    public function getName()
    {
        return 'mg_repobundle_giveuserstype';
    }
}

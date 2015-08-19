<?php

namespace MG\RepoBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;


class AdminRepoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('descripcion')
            ->add('limiteArchivos', 'integer')
            ->add('enabled', 'choice', array('choices' => array('1' => 'Activo','0' => 'Desactivado')))
            ->add('empresa','entity', array(
            'class' => 'MGAdminBundle:Empresa',
            'property' => 'nombre',
            'required' => true))
            ->add('users', 'entity', array(
            'class'=>'MGUserBundle:User',
            'property' => 'username',
            'multiple' => 'true',
            'required' => false))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'MG\RepoBundle\Entity\Repo'
        ));
    }

    public function getName()
    {
        return 'mg_bundle_repotype';
    }
}

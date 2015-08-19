<?php

namespace MG\RepoBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ClientContratacionAddServicesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('serviciosSeleccionadosEmpresa', 'entity', array(
                'class' => 'MGRepoBundle:Servicios',
                'choices' => $options['otherServ'],
                'property' => 'nombre',
                'expanded' => true,
                'multiple' => true
                )
            )
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'MG\RepoBundle\Entity\ClientContratacion',
            'servicios' => '',
            'otherServ' => ''

        ));
    }

    public function getName()
    {
        return 'mg_repobundle_clientcontratacionaddservstype';
    }
}

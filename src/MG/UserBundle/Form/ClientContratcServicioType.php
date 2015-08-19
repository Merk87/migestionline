<?php

namespace MG\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class ClientContratcServicioType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('serv_clientes', 'entity', array(
                    'class' => 'MGRepoBundle:Servicios',
                    'choices' => $options['selectServices'],
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
            'data_class' => 'MG\UserBundle\Entity\User',
            'selectServices' => ''

        ));
    }

    public function getName()
    {
        return 'mg_clientcontratc_servicetype';
    }
}

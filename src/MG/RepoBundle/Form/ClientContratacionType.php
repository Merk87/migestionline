<?php

namespace MG\RepoBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ClientContratacionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('serviciosSolicitados', 'entity', array(
                'class'=> 'MGRepoBundle:ServType',
                'property' => 'displayName',
                'multiple' => true,
                'expanded' => true,
                'required' => true,
                'attr' => array('class' => 'servList')
            ))
            ->add('serviciosPersonalizados', 'textarea', array('required' => false, 'attr' => array(
                'class' => 'custom_service',
                'placeholder' => 'Si quieres ampliar la información o el servicio que buscas no aparece listado, explicanos que necesitas, nosotros te lo conseguiremos.',
                )))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'MG\RepoBundle\Entity\ClientContratacion'
        ));
    }

    public function getName()
    {
        return 'mg_repobundle_clientcontrataciontype';
    }
}

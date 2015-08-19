<?php

namespace MG\RepoBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ComentariosType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('comentario', 'textarea', array('attr'=>array('placeholder' => 'Comentario', 'class' => 'fulltext'), 'required' => true))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'MG\RepoBundle\Entity\Comentarios'
        ));
    }

    public function getName()
    {
        return 'mg_repobundle_comentariostype';
    }
}

<?php

namespace MG\RepoBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ArchivoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('file', 'file', array('attr' => array(
            'multiple' => true,  'required' => false)))
            /*->add('categoria')
            ->add('repoId')
            ->add('idUsuario')
            ->add('categoriaID')
            ->add('filePath')
            ->add('segName')
            ->add('vistoGestor')
            ->add('comentariosCli')
            ->add('comentariosGes')
            ->add('repo')
            ->add('user')*/
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'MG\RepoBundle\Entity\Archivo'
        ));
    }

    public function getName()
    {
        return 'mg_repobundle_archivotype';
    }
}

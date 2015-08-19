<?php

namespace MG\RepoBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class EditRepoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('descripcion')
            ->add('limiteArchivos', 'integer', array('attr' => array('min' => 0)))
            ->add('enabled', 'choice', array('choices' => array('1' => 'Activo','0' => 'Desactivado')))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'MG\RepoBundle\Entity\Repo',
            'empId' => ''
        ));
    }

    public function getName()
    {
        return 'mg_editrepobundle_repotype';
    }
}

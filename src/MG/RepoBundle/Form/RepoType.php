<?php

namespace MG\RepoBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class RepoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('descripcion')
            ->add('limiteArchivos', 'integer', array('attr' => array('min' => 0)))
            ->add('enabled', 'choice', array('choices' => array('1' => 'Activo','0' => 'Desactivado')))
            ->add('empresa','entity', array(
            'class' => 'MGAdminBundle:Empresa',
            'query_builder' => function(EntityRepository $emp_users) use ($options)
            {
                if($options['userRol'] == 1)
                {
                    return $emp_users->createQueryBuilder('e');
                }else
                {
                    return $emp_users->createQueryBuilder('e')
                        ->join('e.users', 'u')
                        ->where('u.id =?1')
                        ->setParameter(1, $options['userId']);
                }
            },
            'property' => 'nombre',
            'required' => true))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'MG\RepoBundle\Entity\Repo',
            'userId' => '',
            'userRol' => ''
        ));
    }

    public function getName()
    {
        return 'mg_repobundle_repotype';
    }
}

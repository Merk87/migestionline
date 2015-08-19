<?php

namespace MG\RepoBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class ServiciosCustomType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre', 'text', array('attr' => array('placeholder' => 'Nombre'), 'required' => true))
            ->add('descripcion', 'text', array('attr' => array('placeholder' => 'Descripción'), 'required' => true))
            ->add('precio', 'integer', array('attr'=>array('min' => 0) ,'required'=> true))
            ->add('enabled', 'choice', array('choices' => array('1' => 'Activo','0' => 'Desactivado')))
            ->add('empresa', 'entity', array(
            'class' => 'MGAdminBundle:Empresa',
            'query_builder' => function(EntityRepository $usersEmp) use ($options)
            {
                if($options['roleName'] != 'ROLE_SUPER_ADMIN')
                {
                    return $usersEmp->createQueryBuilder('e')
                        ->join('e.users', 'u')
                        ->where('u.id =?1')
                        ->setParameter(1, $options['userId']);
                }else
                {
                    return $usersEmp->createQueryBuilder('e');
                }
            },
            'property' => 'nombre',
            'multiple' => false,
            'required' => true))

        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'MG\RepoBundle\Entity\Servicios',
            'userId' => '',
            'roleName' => ''
        ));
    }

    public function getName()
    {
        return 'mg_repobundle_servicios_customtype';
    }
}

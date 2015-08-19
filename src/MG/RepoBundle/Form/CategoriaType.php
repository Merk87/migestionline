<?php

namespace MG\RepoBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class CategoriaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre')
            ->add('descripcion')
            ->add('enabled', 'choice', array('choices' => array('1' => 'Activo','0' => 'Desactivado')))
            ->add('servicio', 'entity',array(
            'class'=>'MGRepoBundle:Servicios',
            'query_builder' => function(EntityRepository $servEmp) use ($options)
            {
                if($options['roleId'] != 1 && ($options['roleId'] != 2))
                {
                   return $servEmp->createQueryBuilder('s')
                        ->join('s.empresa', 'e')
                        ->join('e.users', 'u')
                        ->where('u.id =?1')
                        ->andWhere('e.id =?2')
                        ->orderBy('s.nombre', 'ASC')
                        ->setParameter(1, $options['userId'])
                        ->setParameter(2, $options['empId'] );
                }elseif($options['roleId'] == 2 && $options['empId'] == 0)
                {
                    return $servEmp->createQueryBuilder('s')
                        ->join('s.empresa', 'e')
                        ->join('e.users', 'u')
                        ->where('u.id =?1')
                        ->setParameter(1, $options['userId']);
                }elseif($options['roleId'] == 2 && $options['empId'] != 0)
                {
                    return $servEmp->createQueryBuilder('s')
                        ->join('s.empresa', 'e')
                        ->join('e.users', 'u')
                        ->where('u.id =?1')
                        ->andWhere('e.id =?2')
                        ->orderBy('s.nombre', 'ASC')
                        ->setParameter(1, $options['userId'])
                        ->setParameter(2, $options['empId'] );
                }
                else
                {
                    return $servEmp->createQueryBuilder('s');
                }
            },
            'property' => 'nombre'))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'MG\RepoBundle\Entity\Categoria',
            'userId' => '',
            'roleId' => '',
            'empId' => ''
        ));
    }

    public function getName()
    {
        return 'mg_repobundle_categoriatype';
    }
}

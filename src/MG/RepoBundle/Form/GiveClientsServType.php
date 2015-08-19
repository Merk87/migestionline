<?php

namespace MG\RepoBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class GiveClientsServType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
           ->add('clientes', 'entity', array(
            'class'=>'MGUserBundle:User',
            'query_builder' => function(EntityRepository $usersEmp) use($options)
            {
                return $usersEmp->createQueryBuilder('u')
                    ->join('u.empresas', 'e')
                    ->join('e.repo', 'r')
                    ->where('r.empresaId =?1')
                    ->andWhere('u.rolId =?2')
                    ->orderBy('u.username', 'ASC')
                    ->setParameter(1, $options['empId'])
                    ->setParameter(2, '4');
            },
            'property' => 'username',
            'multiple' => 'true',
            'expanded' => 'true',
            'required' => false))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
                'data_class' => 'MG\RepoBundle\Entity\Servicios',
                'empId' => ''
        ));
    }

    public function getName()
    {
        return 'mg_giveclientsserv_repotype';
    }
}

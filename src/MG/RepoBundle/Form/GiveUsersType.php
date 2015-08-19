<?php

namespace MG\RepoBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class GiveUsersType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
           ->add('users', 'entity', array(
            'class'=>'MGUserBundle:User',
            'query_builder' => function(EntityRepository $usersEmp) use($options)
            {
                return $usersEmp->createQueryBuilder('u')
                    ->join('u.empresas', 'e')
                    ->join('e.repo', 'r')
                    ->where('r.empresaId =?1')
                    ->andWhere('u.rolId >=?2')
                    ->andWhere('u.rolId !=?3')
                    ->orderBy('u.username', 'ASC')
                    ->setParameter(1, $options['empId'])
                    ->setParameter(2, $options['rolId'])
                    ->setParameter(3, '4');
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
            'data_class' => 'MG\RepoBundle\Entity\Repo',
            'empId' => '',
            'rolId' => ''
        ));
    }

    public function getName()
    {
        return 'mg_giveusers_repotype';
    }
}

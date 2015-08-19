<?php

namespace MG\RepoBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class GestionAdminType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('descripcion', 'textarea', array('attr' => array('placeholder' => 'Descripción')))
            ->add('categoria', 'entity', array('class' => 'MGRepoBundle:Categoria',
                   'query_builder' => function (EntityRepository $userCat) use ($options)
                   {
                       if($options['rolU'] == 'ROLE_SUPER_ADMIN' || $options['rolU'] == 'ROLE_ADMIN')
                       {
                           return $userCat->createQueryBuilder('c')
                               ->join('c.servicio', 's')
                               ->join('s.clientes', 'cli')
                               ->where('cli.id =?1')
                               ->andWhere('c.enabled =?2')
                               ->setParameter(1, $options['cliId'])
                               ->setParameter(2, '1')
                               ;
                       }else
                       {
                           return $userCat->createQueryBuilder('c')
                               ->join('c.servicio', 's')
                               ->join('s.clientes', 'cli')
                               ->join('s.users', 'us')
                               ->where('cli.id =?1')
                               ->andWhere('us.id =?2')
                               ->andWhere('c.enabled =?3')
                               ->setParameter(1, $options['cliId'])
                               ->setParameter(2, $options['usId'])
                               ->setParameter(3, '1')
                               ;
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
            'data_class' => 'MG\RepoBundle\Entity\Gestion',
            'cliId' => '',
            'rolU' => '',
            'usId' => ''
        ));
    }

    public function getName()
    {
        return 'mg_repobundle_gestionadmintype';
    }
}

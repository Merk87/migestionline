<?php

namespace MG\RepoBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class ArchivoMultiType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('file', 'file', array('attr' => array(
            'multiple' => true, 'required' => false)))
            ->add('repo', 'entity', array(
            'class' => 'MGRepoBundle:Repo',
            'query_builder'=> function(EntityRepository $userRepo) use ($options)
            {
                return $userRepo->createQueryBuilder('r')
                    ->join('r.clientes', 'c')
                    ->where('c.id =?1')
                    ->andWhere('r.empresaId =?2')
                    ->setParameter(1, $options['userId'])
                    ->setParameter(2, $options['empId'])
                    ;
            },
            'property' => 'descripcion'
            ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'MG\RepoBundle\Entity\Archivo',
            'userId' => '',
            'empId' => ''
        ));
    }

    public function getName()
    {
        return 'mg_repobundle_archivomultitype';
    }
}

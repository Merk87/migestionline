<?php

namespace MG\RepoBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class GestionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('descripcion', 'textarea', array('attr' => array('placeholder' => 'Descripción')))
            ->add('categoria', 'entity', array('class' => 'MGRepoBundle:Categoria',
                   'query_builder' => function (EntityRepository $userCat) use ($options)
                   {
                       return $userCat->createQueryBuilder('c')
                           ->join('c.servicio', 's')
                           ->join('s.clientes', 'cli')
                           ->where('cli.id =?1')
                           ->andWhere('c.enabled =?2')
                           ->andWhere('s.empresaId =?3')
                           ->setParameter(1, $options['cliId'])
                           ->setParameter(2, '1')
                           ->setParameter(3, $options['empAct'])
                           ;
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
            'empAct' => ''
        ));
    }

    public function getName()
    {
        return 'mg_repobundle_gestiontype';
    }
}

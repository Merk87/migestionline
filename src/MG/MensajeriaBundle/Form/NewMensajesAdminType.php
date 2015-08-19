<?php

namespace MG\MensajeriaBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class NewMensajesAdminType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('asunto', 'text', array('attr' => array('placeholder'=>'Asunto')))
            ->add('mensaje', 'textarea', array('required'=>true, 'label'=>false, 'attr' => array('placeholder' => 'Mensaje')))
            ->add('destinatario', 'entity', array('class' => 'MGUserBundle:User',
                'query_builder' => function(EntityRepository $us_dest) use ($options)
                {
                    if($options['rolCheck'] == 1)
                    {
                        return $us_dest->createQueryBuilder('u')
                            ->join('u.empresas', 'e')
                            ->where('e.id =?1')
                            ->andWhere('u.rolId >= 2')
                            ->andWhere('u.id !=?2')
                            ->orderBy('u.id', 'ASC')
                            ->setParameter(1, $options['empId'])
                            ->setParameter(2, $options['usId']);
                    }else
                    {
                        return $us_dest->createQueryBuilder('u')
                            ->join('u.empresas', 'e')
                            ->where('e.id =?1')
                            ->andWhere('u.rolId = 4')
                            ->andWhere('u.id !=?2')
                            ->orderBy('u.id', 'ASC')
                            ->setParameter(1, $options['empId'])
                            ->setParameter(2, $options['usId']);
                    }
                }, 'property' => 'username'))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'MG\MensajeriaBundle\Entity\Mensajes',
            'empId' => '',
            'rolCheck' => '',
            'usId' => ''
        ));
    }

    public function getName()
    {
        return 'mg_mensajeriabundle_newmensajesadmintype';
    }
}

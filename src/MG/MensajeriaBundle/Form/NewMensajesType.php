<?php

namespace MG\MensajeriaBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class NewMensajesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('asunto', 'text', array('attr' => array('placeholder'=>'Asunto')))
            ->add('mensaje', 'textarea', array('required'=>true, 'label'=>false, 'attr' => array('placeholder' => 'Mensaje')))
            ->add('destinatario', 'entity', array('class' => 'MGUserBundle:User',
                'query_builder' => function(EntityRepository $us_dest) use ($options)
                {
                    return $us_dest->createQueryBuilder('u')
                        ->join('u.empresas', 'e')
                        ->where('e.id =?1')
                        ->andwhere('u.rolId >= 2')
                        ->andwhere('u.rolId < 4')
                        ->orderBy('u.id', 'ASC')
                        ->setParameter(1, $options['empId']);

                }, 'property' => 'username'))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'MG\MensajeriaBundle\Entity\Mensajes',
            'empId' => ''
        ));
    }

    public function getName()
    {
        return 'mg_mensajeriabundle_newmensajestype';
    }
}

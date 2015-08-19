<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Merkury
 * Date: 28/08/13
 * Time: 9:32
 * To change this template use File | Settings | File Templates.
 */

namespace MG\LandingFrontBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Collection;


class ContactType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('Nombre', 'text', array(
                'attr' => array(
                    'placeholder' => '¿Como te llamas?',
                    'pattern'     => '.{2,}' //minlength
                )
            ))
            ->add('Email', 'email', array(
                'attr' => array(
                    'placeholder' => 'Para poder contestarte.'
                )
            ))
            ->add('Asunto', 'text', array(
                'attr' => array(
                    'placeholder' => 'El asunto de tu mensaje.',
                    'pattern'     => '.{3,}' //minlength
                )
            ))
            ->add('Mensaje', 'textarea', array(
                'attr' => array(
                    'cols' => 90,
                    'rows' => 10,
                    'placeholder' => 'El mensaje para nosotros...'
                )
            ))
            ->add('Politica', 'checkbox', array(
                'attr' => array('class' => 'checkbx pull-left')
            ))
            ->add('Publicidad', 'checkbox', array('required' => false,
                'attr' => array('class' => 'checkbx pull-left', )
            ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'MG\LandingFrontBundle\Entity\Contact'
        ));
    }


    public function getName()
    {
        return 'mg_frontlanding_contacttype';
    }

}
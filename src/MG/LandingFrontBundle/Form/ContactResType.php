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
use Symfony\Component\Validator\Constraints\Collection;


class ContactResType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('Mensaje', 'textarea', array(
                'attr' => array(
                    'cols' => 90,
                    'rows' => 10,
                    'placeholder' => 'El mensaje para nosotros...'
                )
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
        return 'mg_frontlanding_contactrestype';
    }

}
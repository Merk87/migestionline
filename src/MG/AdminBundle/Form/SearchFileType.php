<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Merkury
 * Date: 28/08/13
 * Time: 9:32
 * To change this template use File | Settings | File Templates.
 */

namespace MG\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Collection;


class SearchFileType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('fechaIni', 'date', array(
                'widget' => 'single_text'))

            ->add('fechaFin', 'date', array(
                'widget' => 'single_text'))
            ;
    }


    public function getName()
    {
        return 'mg_adminbundle_searchfiles';
    }


}
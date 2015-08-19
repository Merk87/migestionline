<?php

namespace MG\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class UserUpdateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', 'email', array('attr' => array('placeholder' => 'Email'),'disabled' => true))
            ->add('name', 'text', array('attr' => array('placeholder' => 'Nombre')))
            ->add('apellidos', 'text', array('attr' => array('placeholder' => 'Apellidos')))
            ->add('nif', 'text', array('attr' => array('placeholder' => 'NIF', 'maxlength' => 9)))
            ->add('fechaNacimiento', 'date', array(
            'widget' => 'single_text'))
            ->add('telefono', 'text', array('attr' => array('placeholder' => 'Teléfono'), 'required' => false))
            ->add('telefonoMvl', 'text', array('attr' => array('placeholder' => 'Teléfono móvil'), 'required' => false))
            ->add('direccion', 'text', array('attr' => array('placeholder' => 'Dirección'), 'required' => false))
            ->add('ciudad', 'text', array('attr' => array('placeholder' => 'Ciudad'), 'required' => false))
            ->add('codigoPostal', 'text', array('attr' => array('placeholder' => 'Código Postal'), 'required' => false))
            ->add('pais', 'choice', array('choices' => array('España' => 'España')))
            ->add('rol', 'entity', array(
            'class' => 'MGUserBundle:Rol',
            'query_builder' => function(EntityRepository $validRoles) use ($options)
            {
                if($options['userRol'] == 1)
                {
                    return $validRoles->createQueryBuilder('r');
                }else
                {
                    return $validRoles->createQueryBuilder('r')
                        ->where('r.id >=?1')
                        ->setParameter(1, $options['userRol']);
                }
            },
            'property' => 'display_rol'))
            ->add('empresas', 'entity', array( 'attr' => array('class' => 'empSelector'),
            'class' => 'MGAdminBundle:Empresa',
            'query_builder' => function(EntityRepository $ownEmpresas) use ($options)
            {
                if($options['userRol'] == 1)
                {
                    return $ownEmpresas->createQueryBuilder('e');
                }
                else
                {
                    return $ownEmpresas->createQueryBuilder('e')
                        ->join('e.users', 'u')
                        ->where('u.id =?1')
                        ->setParameter(1, $options['userId']);
                }
                //ESTO SACA LAS EMPRESAS DEL USUARIO :D
            },
            'property' => 'nombre',
            'multiple' => true,
            'expanded' => true,
            'required' => false))
        ;
    }



    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'MG\UserBundle\Entity\User',
            'userRol' => '',
            'userId' => ''
        ));
    }

    public function getName()
    {
        return 'mg_userbundle_userupdatetype';
    }
}

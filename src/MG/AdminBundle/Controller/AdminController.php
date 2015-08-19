<?php

namespace MG\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AdminController extends Controller
{
    public function indexAction()
    {
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();

        if($user->getRol()->getRolName() == 'ROLE_SUPER_ADMIN' )
        {
            $rolID = $em->getRepository('MGUserBundle:Rol')
                ->findOneBy(array('rolName' => 'ROLE_CLIENTE'))
                ->getId();
            $clientes = $em->getRepository('MGUserBundle:User')
                ->findBy(array('rolId' => $rolID ));

            if(!isset($clientes) || sizeof($clientes) < 1 || $rolID < 1)
            {
                $clientes = array();
                $rolId = 0;
            }

            return $this->render('MGAdminBundle:Default:index.html.twig', array(
                'clientes' => $clientes
            ));

        }
        else if($user->getRol()->getRolName() ==  'ROLE_ADMIN')
        {
            $empresas = $user->getEmpresas();

            if(sizeof($empresas) > 1)
            {
                foreach($empresas as $e)
                {
                    if(sizeof($e->getUsers()) > 1 )
                    {
                        foreach($e->getUsersByRol('ROLE_CLIENTE') as $c)
                        {
                            $pre_clientes[] = $c;
                        }
                    }
                }

                if(!isset($pre_clientes) || sizeof($pre_clientes) < 1)
                {
                    $clientes = array();
                }else
                {
                    $clientes = array_unique($pre_clientes, SORT_REGULAR);
                }

                return $this->render('MGAdminBundle:Default:index.html.twig', array(
                    'clientes' => $clientes
                ));
            }else
            {
                $clientes = $empresas[0]->getUsersByRol('ROLE_CLIENTE');
                return $this->render('MGAdminBundle:Default:index.html.twig', array(
                    'clientes' => $clientes
                ));
            }

        }
        else if($user->getRol()->getRolName() ==  'ROLE_SUBGES')
        {
            $empresas = $user->getEmpresas();
            $userServs = $this->getUser()->getServUsers();

            if(sizeof($empresas) > 1)
            {
                foreach($empresas as $e)
                {
                    foreach($e->getUsersByRol('ROLE_CLIENTE') as $c)
                    {
                        $pre_clientes[] = $c;
                    }
                }

                if(!isset($pre_clientes) || sizeof($pre_clientes) < 1)
                {
                    $clientes = array();
                }else
                {
                    $unique_users = array_unique($pre_clientes, SORT_REGULAR);
                    foreach($unique_users as $c)
                    {
                        foreach ($userServs as $u_serv)
                        {
                            foreach ($c->getServClientes() as $c_serv) {
                                if($u_serv == $c_serv)
                                {
                                    $pre_sort_clientes[] = $c;
                                }
                            }
                        }
                    }

                    if(!isset($pre_sort_clientes) || sizeof($pre_sort_clientes) < 1 || $pre_sort_clientes == false)
                    {
                        $clientes = array();
                    }else
                    {
                        $clientes = array_unique($pre_sort_clientes, SORT_REGULAR);
                    }
                }
                return $this->render('MGAdminBundle:Default:index.html.twig', array(
                    'clientes' => $clientes
                ));

            }else
            {
                $clientes = $empresas[0]->getUsersByRol('ROLE_CLIENTE');

                if(!isset($clientes) || sizeof($clientes) < 1 || $clientes == false)
                {
                    $clientes = array();
                }

                return $this->render('MGAdminBundle:Default:index.html.twig', array(
                    'clientes' => $clientes
                ));
            }

        }else
        {
            $mensaje = "No tienes permisos para visualizar esta página.";
            $msjTipo = "error";
            return $this->render('MGAdminBundle:Msg:messages.html.twig', array('mensaje' => $mensaje, 'tipo' => $msjTipo));
        }

    }
}

<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Merkury
 * Date: 1/07/13
 * Time: 9:12
 * To change this template use File | Settings | File Templates.
 */
namespace MG\AdminBundle\Controller;
use MG\RepoBundle\Form\ServiciosCustomType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use MG\RepoBundle\Entity\Servicios;
use MG\RepoBundle\Entity\Categoria;
use MG\RepoBundle\Form\ServiciosType;
use MG\RepoBundle\Form\EditServiciosType;
use MG\RepoBundle\Form\GiveUsersServType;
use MG\RepoBundle\Form\GiveClientsServType;



class AdminServiciosController extends Controller
{

    public function indexAction($page = NULL, $limit = 10)
    {

        if($this->getUser()->getRolId() == 1)
        {
            $first = true;
            $last = false;

            if(!isset($page) || $page < 2)
            {
                $page = 1;
                $first = false;
            }

            $servRepo = $this->getDoctrine()->getRepository('MGRepoBundle:Servicios');

            $allr = $servRepo->findAll();

            $servicios = $servRepo->findAllWithLimitAndOffset($limit, ($page-1)*$limit);

            if(!$servicios)
            {
                $this->get('session')->getFlashBag()->add('fail', 'No hay servicios actualmente registrados');
                return $this->redirect($this->generateUrl('mg_admin_homepage'));
            }

            foreach($servicios as $serv)
            {
                $empresas_pre[] = $serv->getEmpresa();
            }
            $empresas = array_unique($empresas_pre, SORT_REGULAR);

            if(!$empresas)
            {
                $this->get('session')->getFlashBag()->add('fail', 'No existen empresas disponibles');
                return $this->redirect($this->generateUrl('mg_servadmin_homepage', array('page'=> 1)));
            }

            $page_number = ceil(count($allr)/$limit);

            if($page_number == $page)
            {
                $last = true;
            }

            return $this->render('MGAdminBundle:Servicios:servgestion.html.twig', array(
                'servicios' => $servicios,
                'pages' => $page_number,
                'first' => $first,
                'last' => $last,
                'page' => $page,
                'empresas' => $empresas

            ));
        }elseif($this->getUser()->getRolId() >= 2 )
        {
            $empresas = $this->getUser()->getEmpresas();
            if(sizeof($empresas) == 1)
            {
                return $this->redirect($this->generateUrl('mg_serv_by_empresa', array('empId' => $empresas[0]->getId(),'page'=> 1)));
            }
            else
            {
                return $this->render('MGAdminBundle:Servicios:selectempserv.html.twig', array('empresas' => $empresas));
            }
        }
        else
        {
            $mensaje = "No tienes permisos para visualizar estos servicios.";
            $msjTipo = "error";
            return $this->render('MGAdminBundle:Msg:messages.html.twig', array('mensaje' => $mensaje, 'tipo' => $msjTipo));
        }
    }

    public function detailAction($servId)
    {

        if($this->getUser()->getRolId() <= 3)
        {
            $em = $this->getDoctrine()->getManager();

            $servicio = $em->getRepository('MGRepoBundle:Servicios')
                ->find($servId);

            if(!$servicio)
            {
                $this->get('session')->getFlashBag()->add('fail', 'No existen ese servicio');
                return $this->redirect($this->generateUrl('mg_servadmin_homepage', array('page'=> 1)));
            }

            return $this->render('MGAdminBundle:Servicios:detailserv.html.twig', array('servicio' => $servicio));
        }else
        {
            $mensaje = "No tienes permisos para crear servicios.";
            $msjTipo = "error";
            return $this->render('MGAdminBundle:Msg:messages.html.twig', array('mensaje' => $mensaje, 'tipo' => $msjTipo));
        }


    }

    public function createAction(Request $request, $empId = 0)
    {
        if($this->getUser()->getRolId() < 3)
        {
            $serv = new Servicios();

            $userId= $this->getUser()->getId();

            $pre_types = $this->getDoctrine()->getRepository('MGRepoBundle:ServType')
                ->findBy(array('enabled' => true));

            foreach($pre_types as $pt)
            {
                $types[$pt->getDisplayName()] = $pt->getDisplayName();
            }

            $form = $this->createForm(new ServiciosType(), $serv, array(
                'userId' => $userId,
                'roleName' => $this->getUser()->getRol()->getRolName(),
                'types' => $types
            ));

            if($request->isMethod('POST'))
            {
                $form->bind($request);
                if($form->isValid())
                {
                    $formData = $this->getRequest()->request->get($form->getName());
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($serv);
                    $em->flush();

                    if($this->getUser()->getRolId()== 1 )
                    {
                        $this->get('session')->getFlashBag()->add('notice', 'Servicio introducido correctamente');
                        return $this->redirect($this->generateUrl('mg_servadmin_homepage', array('page' => 1)));
                    }else if($this->getUser()->getRolId() == 2)
                    {

                        $this->get('session')->getFlashBag()->add('notice', 'Servicio introducido correctamente');
                        return $this->redirect($this->generateUrl('mg_serv_by_empresa', array('empId' => $formData['empresa'], 'page' => 1)));

                    }
                }
            }

            return $this->render('MGAdminBundle:Servicios:newserv.html.twig', array(
                'newServForm' => $form->createView(),
                'empId' => $empId
            ));
        }else
        {
            $mensaje = "No tienes permisos para ver l servicios.";
            $msjTipo = "error";
            return $this->render('MGAdminBundle:Msg:messages.html.twig', array('mensaje' => $mensaje, 'tipo' => $msjTipo));
        }
    }

    public function createCustomAction(Request $request, $empId = 0)
    {
        if($this->getUser()->getRolId() < 3)
        {
            $serv = new Servicios();

            $userId= $this->getUser()->getId();

            $form = $this->createForm(new ServiciosCustomType(), $serv, array(
                'userId' => $userId,
                'roleName' => $this->getUser()->getRol()->getRolName()
            ));

            if($request->isMethod('POST'))
            {
                $form->bind($request);
                if($form->isValid())
                {
                    $formData = $this->getRequest()->request->get($form->getName());
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($serv);
                    $em->flush();

                    if($this->getUser()->getRolId()== 1 )
                    {
                        $this->get('session')->getFlashBag()->add('notice', 'Servicio introducido correctamente');
                        return $this->redirect($this->generateUrl('mg_servadmin_homepage', array('page' => 1)));
                    }else if($this->getUser()->getRolId() == 2)
                    {

                        $this->get('session')->getFlashBag()->add('notice', 'Servicio introducido correctamente');
                        return $this->redirect($this->generateUrl('mg_serv_by_empresa', array('empId' => $formData['empresa'], 'page' => 1)));

                    }
                }
            }

            return $this->render('MGAdminBundle:Servicios:newservcustom.html.twig', array(
                'newCustomServForm' => $form->createView(),
                'empId' => $empId
            ));

        }else
        {
            $mensaje = "No tienes permisos para ver l servicios.";
            $msjTipo = "error";
            return $this->render('MGAdminBundle:Msg:messages.html.twig', array('mensaje' => $mensaje, 'tipo' => $msjTipo));
        }

    }

    public function updateAction(Request $request, $servId, $empId)
    {

        $em = $this->getDoctrine()->getManager();

        $serv = $em->getRepository('MGRepoBundle:Servicios')
            ->find($servId);

        $tieneEmpresa = 0;
        foreach ($this->getUser()->getEmpresas() as $empresa) {
            if($empresa->getId() == $serv->getEmpresaId())
            {
                $tieneEmpresa= 1;
            }
        }

       if(($this->getUser()->getRolId() < 3 && $tieneEmpresa != 0) || $this->getUser()->getRolId() == 1)
       {

           $form = $this->createForm(New EditServiciosType(), $serv);

           if($request->isMethod('POST'))
           {
               $form->bind($request);
               if($form->isValid())
               {
                   $em = $this->getDoctrine()->getManager();
                   $em->merge($serv);
                   $em->flush();

                   $this->get('session')->getFlashBag()->add('notice', 'Servicio modificado correctamente');

                      return $this->redirect($this->generateUrl('mg_serv_by_empresa', array('page' => 1, 'empId' => $empId)));

               }

           }
           return $this->render('MGAdminBundle:Servicios:updateserv.html.twig',
               array(
                   'editServForm' => $form->createView(),
                   'servId' => $servId,
                   'empId' => $empId
               ));
       }else
       {
           $mensaje = "No tienes permisos para modificar este servicio.";
           $msjTipo = "error";
           return $this->render('MGAdminBundle:Msg:messages.html.twig', array('mensaje' => $mensaje, 'tipo' => $msjTipo));
       }
    }

    public function blockAction($servId)
    {

        $em = $this->getDoctrine()->getManager();

        $serv = $em->getRepository('MGRepoBundle:Servicios')
            ->find($servId);

        if(!$serv)
        {
            $this->get('session')->getFlashBag()->add('fail', 'No existe el servicio seleccionado');
            return $this->redirect($this->generateUrl('mg_servadmin_homepage', array('page' => 1)));
        }

        $tieneEmpresa = 0;
        foreach ($this->getUser()->getEmpresas() as $empresa) {
            if($empresa->getId() == $serv->getEmpresaId())
            {
                $tieneEmpresa= 1;
            }
        }

        if(($this->getUser()->getRolId() <= 2 && $tieneEmpresa == 1) || $this->getUser()->getRolId()== 1)
        {
            $em = $this->getDoctrine()->getManager();

            $serv = $em->getRepository('MGRepoBundle:Servicios')
                ->find($servId);

            if(!$serv)
            {
                $this->get('session')->getFlashBag()->add('fail', 'Servicio no encontrado');
                if($this->getUser()->getRolId()== 1)
                {
                    return $this->redirect($this->generateUrl('mg_servadmin_homepage', array('page'=> 1)));
                }else
                {
                    return $this->redirect($this->generateUrl('mg_serv_by_empresa', array('page'=> 1, 'empId' => $serv->getEmpresaId())));
                }
            }

            if($serv->isEnabled() == true)
            {
                $serv->setEnabled(false);
                $em->persist($serv);
                $em->flush();
                $this->get('session')->getFlashBag()->add('fail', 'Servicio bloqueado');
                if($this->getUser()->getRolId()== 1)
                {
                    return $this->redirect($this->generateUrl('mg_servadmin_homepage', array('page'=> 1)));
                }else
                {
                    return $this->redirect($this->generateUrl('mg_serv_by_empresa', array('page'=> 1, 'empId' => $serv->getEmpresaId())));
                }
            }else
            {
                $serv->setEnabled(true);
                $em->persist($serv);
                $em->flush();
                $this->get('session')->getFlashBag()->add('notice', 'Servicio desbloqueado');
                if($this->getUser()->getRolId()== 1)
                {
                    return $this->redirect($this->generateUrl('mg_servadmin_homepage', array('page'=> 1)));
                }else
                {
                    return $this->redirect($this->generateUrl('mg_serv_by_empresa', array('page'=> 1, 'empId' => $serv->getEmpresaId())));
                }
            }
        }else
        {
            $mensaje = "No tienes permisos para modificar este servicio.";
            $msjTipo = "error";
            return $this->render('MGAdminBundle:Msg:messages.html.twig', array('mensaje' => $mensaje, 'tipo' => $msjTipo));
        }


    }

    public function listServEmpresaAction($empId, $page = 1, $limit = 10)
    {
        $tieneEmpresa = 0;
        foreach ($this->getUser()->getEmpresas() as $empresa) {
            if($empId == $empresa->getId())
            {
                $tieneEmpresa = 1;
            }
        }

        if(($tieneEmpresa > 0 && $this->getUser()->getRolId() <= 3) || $this->getUser()->getRolId() == 1 )
        {
            $first = true;
            $last = false;
            $offset = 10 * ($page - 1);

            if(!isset($page) || $page < 2)
            {
                $page = 1;
                $first = false;
            }

            $em = $this->getDoctrine()->getManager();

            $empresa = $em->getRepository('MGAdminBundle:Empresa')
                ->find($empId);

            if(!$empresa)
            {
                $this->get('session')->getFlashBag()->add('fail', 'No existe el servicio seleccionado');
                return $this->redirect($this->generateUrl('mg_admin_homepage', array('page' => 1)));
            }

            $alls = $empresa->getServicios();

            if(!$alls)
            {
                $this->get('session')->getFlashBag()->add('fail', 'No existen servicios para esta empresa');
                return $this->redirect($this->generateUrl('mg_admin_homepage', array('page' => 1)));
            }

            $page_number = ceil(count($alls)/$limit);

            if($page_number == $page)
            {
                $last = true;
            }

            for($i = $offset; $i < $limit+$offset; $i++)
            {
                if(isset($alls[$i]))
                {
                    $servicios[] = $alls[$i];
                }
            }

            if(!isset($servicios) || !$servicios)
            {
                $this->get('session')->getFlashBag()->add('fail', 'No existen servicios para esta empresa');
                return $this->redirect($this->generateUrl('mg_admin_homepage', array('page' => 1)));
            }

            if($this->getUser()->getRolId() == 1)
            {
                $empresas = $em->getRepository('MGAdminBundle:Empresa')
                 ->findAll();
            }else
            {
                $empresas = $this->getUser()->getEmpresas();
            }

            return $this->render('MGAdminBundle:Servicios:servgestionfiltered.html.twig', array(
                 'servicios' => $servicios,
                 'pages' => $page_number,
                 'first' => $first,
                 'last' => $last,
                 'page' => $page,
                 'empresas' => $empresas,
                 'empId' => $empId
            ));
        }else
        {
            $mensaje = "No tienes permisos para visualizar estos servicios.";
            $msjTipo = "error";
            return $this->render('MGAdminBundle:Msg:messages.html.twig', array('mensaje' => $mensaje, 'tipo' => $msjTipo));
        }
    }

    public function giveUsersAction($empId = 0, $servId, Request $request)
    {
        if($this->getUser()->getRolId() <=2)
        {
            $em = $this->getDoctrine()->getManager();

            $serv = $em->getRepository('MGRepoBundle:Servicios')
                ->find($servId);

            if(!$serv)
            {
                $this->get('session')->getFlashBag()->add('fail', 'No existe el servicio');
                if($this->getUser()->getRolId() == 1)
                {
                    return $this->redirect($this->generateUrl('mg_servadmin_homepage', array('page'=> 1)));
                }else
                {
                    return $this->redirect($this->generateUrl('mg_serv_by_empresa', array('page'=> 1, 'empId' => $empId)));
                }
            }

            $users_serv = $serv->getUsers();
            $empresa_serv = $serv->getEmpresa();
            $tieneEmpresa = 0;
            $pertenece = 0;
            foreach($this->getUser()->getEmpresas() as $empresa)
            {
                if($empresa == $empresa_serv)
                {
                    $tieneEmpresa = 1;
                }
            }

            foreach($users_serv as $user)
            {
                if($user == $this->getUser())
                {
                    $pertenece = 1;
                }
            }

            if($serv->isEnabled() == true )
            {
                if(($tieneEmpresa == 1 && $this->getUser()->getRolId() == 2 ) || $this->getUser()->getRolId() == 1  )
                {
                    $rolId = $this->getUser()->getRolId();
                    $empreId = $serv->getEmpresaId();
                    $form = $this->createForm(new GiveUsersServType(), $serv, array('empId' => $empreId, 'rolId' => $rolId));

                    if($request->isMethod('POST'))
                    {
                        $form->bind($request);
                        if($form->isValid())
                        {
                            $em->merge($serv);
                            $em->flush();

                            $this->get('session')->getFlashBag()->add('notice', 'Usuarios asignados correctamente');

                            if($this->getUser()->getRolId() == 1)
                            {
                                return $this->redirect($this->generateUrl('mg_servadmin_homepage', array('page' => 1)));
                            }else
                            {
                                return $this->redirect($this->generateUrl('mg_serv_by_empresa', array('page' => 1, 'empId' => $empId)));
                            }

                        }
                    }

                    return $this->render('MGAdminBundle:Servicios:giveuserserv.html.twig', array('assignUserForm' => $form->createView(), 'servId' => $servId, 'empId' => $empId));
                }elseif($tieneEmpresa == 1 && $pertenece == 1 && $this->getUser()->getRolId() == 3)
                {
                    $rolId = $this->getUser()->getRolId();
                    $empreId = $serv->getEmpresaId();
                    $form = $this->createForm(new GiveUsersServType(), $serv, array('empId' => $empreId, 'rolId' => $rolId));

                    if($request->isMethod('POST'))
                    {
                        $form->bind($request);
                        if($form->isValid())
                        {
                            $em->merge($serv);
                            $em->flush();

                            $this->get('session')->getFlashBag()->add('notice', 'Repositorio modificado correctamente');

                            return $this->redirect($this->generateUrl('mg_repo_by_empresa', array('page' => 1, 'empId' => $empId)));
                        }
                    }

                    return $this->render('MGAdminBundle:Servicios:giveuserserv.html.twig', array('assignUserForm' => $form->createView(), 'repId' => $servId, 'empId' => $empId));
                }
                else
                {
                    $mensaje = "No tienes permisos para asignar usuarios a este servicio.";
                    $msjTipo = "error";
                    return $this->render('MGAdminBundle:Msg:messages.html.twig', array('mensaje' => $mensaje, 'tipo' => $msjTipo));
                }

            } else
            {
                $mensaje = "No se pueden asignar usuarios aun servicio inactivo";
                $msjTipo = "error";
                return $this->render('MGAdminBundle:Msg:messages.html.twig', array('mensaje' => $mensaje, 'tipo' => $msjTipo));
            }
        }else
        {
            $mensaje = "No tienes permisos para acceder a esta zona.";
            $msjTipo = "error";
            return $this->render('MGAdminBundle:Msg:messages.html.twig', array('mensaje' => $mensaje, 'tipo' => $msjTipo));
        }

    }

    public function giveClientsAction($empId = 0, $servId, Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $serv = $em->getRepository('MGRepoBundle:Servicios')
            ->find($servId);

        if(!$serv)
        {
            $this->get('session')->getFlashBag()->add('fail', 'No existe el repositorio');
            return $this->redirect($this->generateUrl('mg_repoadmin_homepage', array('page'=> 1)));
        }

        $users_serv = $serv->getUsers();
        $empresa_serv = $serv->getEmpresa();
        $tieneEmpresa = 0;
        $pertenece = 0;
        foreach($this->getUser()->getEmpresas() as $empresa)
        {
            if($empresa == $empresa_serv)
            {
                $tieneEmpresa = 1;
            }
        }

        foreach($users_serv as $user)
        {
            if($user == $this->getUser())
            {
                $pertenece = 1;
            }
        }

        if($serv->isEnabled() == true )
        {
            if(($tieneEmpresa == 1 && $this->getUser()->getRolId() == 2) || $this->getUser()->getRolId() == 1)
            {
                $empreId = $serv->getEmpresaId();
                $form = $this->createForm(new GiveClientsServType(), $serv, array('empId' => $empreId));

                if($request->isMethod('POST'))
                {
                    $form->bind($request);
                    if($form->isValid())
                    {
                        $em->merge($serv);
                        $em->flush();

                        $this->get('session')->getFlashBag()->add('notice', 'Clientes agregados correctamente');

                        if($this->getUser()->getRolId() == 1)
                        {
                            return $this->redirect($this->generateUrl('mg_servadmin_homepage', array('page' => 1)));
                        }else
                        {
                            return $this->redirect($this->generateUrl('mg_serv_by_empresa', array('page' => 1, 'empId' => $empId)));
                        }

                    }
                }

                return $this->render('MGAdminBundle:Servicios:giveclientsserv.html.twig', array('assignUserForm' => $form->createView(), 'servId' => $servId, 'empId' => $empId));
            }elseif($tieneEmpresa == 1 && $pertenece == 1 && $this->getUser()->getRolId() == 3)
            {
                $empreId = $serv->getEmpresaId();
                $form = $this->createForm(new GiveClientsServType(), $serv, array('empId' => $empreId));

                if($request->isMethod('POST'))
                {
                    $form->bind($request);
                    if($form->isValid())
                    {
                        $em->merge($serv);
                        $em->flush();

                        $this->get('session')->getFlashBag()->add('notice', 'Clientes agregados correctamente');
                        return $this->redirect($this->generateUrl('mg_serv_by_empresa', array('page' => 1, 'empId' => $empId)));
                    }
                }

                return $this->render('MGAdminBundle:Servicios:giveclientsserv.html.twig', array('assignUserForm' => $form->createView(), 'servId' => $servId, 'empId' => $empId));
            }
            else
            {
                $mensaje = "No tienes permisos para asignar clientes a este servicio.";
                $msjTipo = "error";
                return $this->render('MGAdminBundle:Msg:messages.html.twig', array('mensaje' => $mensaje, 'tipo' => $msjTipo));
            }

        } else
        {
            $mensaje = "No se pueden asignar clientes aun servicio inactivo";
            $msjTipo = "error";
            return $this->render('MGAdminBundle:Msg:messages.html.twig', array('mensaje' => $mensaje, 'tipo' => $msjTipo));
        }
    }
}

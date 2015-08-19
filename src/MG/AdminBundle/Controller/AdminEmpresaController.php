<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Merkury
 * Date: 24/06/13
 * Time: 10:48
 * To change this template use File | Settings | File Templates.
 */

namespace MG\AdminBundle\Controller;

use MG\RepoBundle\Entity\Repo;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use MG\AdminBundle\Entity\Empresa;
use MG\AdminBundle\Form\EmpresaType;

class AdminEmpresaController extends Controller
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
            $empRepo = $this->getDoctrine()->getRepository('MGAdminBundle:Empresa');

            $alle = $empRepo->findAll();

            $empresas = $empRepo->findAllWithLimitAndOffset($limit, ($page-1)*$limit);

            if(!$empresas)
            {
                $this->get('session')->getFlashBag()->add('fail', 'No hay empresas actualmente registradas');
                return $this->redirect($this->generateUrl('mg_admin_homepage'));
            }

            $page_number = ceil(count($alle)/$limit);

            if($page_number == $page)
            {
                $last = true;
            }

            return $this->render('MGAdminBundle:Empresas:empgestion.html.twig', array(
                'empresas' => $empresas,
                'pages' => $page_number,
                'first' => $first,
                'last' => $last,
                'page' => $page

            ));
        }else
        {
            $mensaje = "No tienes permisos para visualizar esta página.";
            $msjTipo = "error";
            return $this->render('MGAdminBundle:Msg:messages.html.twig', array('mensaje' => $mensaje, 'tipo' => $msjTipo));
        }

    }

    public function createAction(Request $request)
    {

        if($this->getUser()->getRolId() == 1)
        {
            $empresa = new Empresa();

            $form = $this->createForm(new EmpresaType(), $empresa);

            if($request->isMethod('POST'))
            {
                $form->bind($request);
                if($form->isValid())
                {

                    $empresa->upload();

                    $repo = new Repo();
                    $repo->setEmpresa($empresa);
                    $repo->setDescripcion('Repositorio empresa '.$empresa->getNombre());
                    $repo->setEnabled(true);
                    $repo->setLimiteArchivos(1000);

                    $em = $this->getDoctrine()->getManager();
                    $em->persist($empresa);
                    $em->persist($repo);
                    $em->flush();

                    $this->get('session')->getFlashBag()->add('notice', 'Empresa introducida correctamente');
                    return $this->redirect($this->generateUrl('mg_empadmin_homepage', array('page' => 1)));
                }
            }

            return $this->render('MGAdminBundle:Empresas:createemp.html.twig', array('newEmpresaForm' => $form->createView()));
        }else
        {
            $mensaje = "No tienes permisos para visualizar esta página.";
            $msjTipo = "error";
            return $this->render('MGAdminBundle:Msg:messages.html.twig', array('mensaje' => $mensaje, 'tipo' => $msjTipo));
        }

    }

    public function detailAction($empId)
    {
        if($this->getUser()->getRolId() == 1)
        {
            $em = $this->getDoctrine()->getManager();

            $empresa = $em->getRepository('MGAdminBundle:Empresa')
                ->findOneById($empId);

            if(!$empresa)
            {
                $this->get('session')->getFlashBag()->add('fail', 'No se encuentra la empresa');
                return $this->redirect($this->generateUrl('mg_empadmin_homepage', array('page' => 1)));
            }

            $empleados = 0;
            $clientes = 0;
            foreach($empresa->getUsers() as $user)
            {
                if($user->getRolId() == 4)
                {
                    $clientes++;
                }else if($user->getRolId() == 3)
                {
                    $empleados++;
                }
            }
        }else
        {
            $mensaje = "No tienes permisos para visualizar esta página.";
            $msjTipo = "error";
            return $this->render('MGAdminBundle:Msg:messages.html.twig', array('mensaje' => $mensaje, 'tipo' => $msjTipo));
        }

        return $this->render('MGAdminBundle:Empresas:detailemp.html.twig', array('empresa' => $empresa, 'clientes' => $clientes, 'empleados' => $empleados));
    }

    public function updateAction($empId, Request $request)
    {
        if($this->getUser()->getRolId() == 1)
        {
            $em = $this->getDoctrine()->getManager();

            $empresa = $em->getRepository('MGAdminBundle:Empresa')
                ->find($empId);

            if(!$empresa)
            {
                $this->get('session')->getFlashBag()->add('fail', 'No se encuentra la empresa');
                return $this->redirect($this->generateUrl('mg_empadmin_homepage', array('page' => 1)));
            }

            $logo = $empresa->getLogoPath();
            $form = $this->createForm(new EmpresaType(), $empresa);

            if($request->isMethod('POST'))
            {
                $form->bind($request);
                if($form->isValid())
                {
                    $empresa->upload();
                    $em->persist($empresa);
                    $em->flush();

                    if($logo != $empresa->getLogoPath())
                    {
                        if(file_exists($logo))
                        {
                            unlink($logo);
                        }
                    }

                    $this->get('session')->getFlashBag()->add('notice', 'Empresa modificada correctamente');
                    return $this->redirect($this->generateUrl('mg_empadmin_homepage', array('page' => 1)));
                }
            }

            return $this->render('MGAdminBundle:Empresas:updateemp.html.twig', array('empEditForm' => $form->createView(), 'empId' => $empId));
        }else
        {
            $mensaje = "No tienes permisos para visualizar esta página.";
            $msjTipo = "error";
            return $this->render('MGAdminBundle:Msg:messages.html.twig', array('mensaje' => $mensaje, 'tipo' => $msjTipo));
        }
    }

    public function blockAction($empId)
    {
        if($this->getUser()->getRolId() == 1)
        {
            $em = $this->getDoctrine()->getManager();

            $empresa = $em->getRepository('MGAdminBundle:Empresa')
                ->find($empId);

            if(!$empresa)
            {
                $this->get('session')->getFlashBag()->add('fail', 'Empresa bloqueada satisfactoriamente');
                return $this->redirect($this->generateUrl('mg_empadmin_homepage', array('page' => 1)));
            }

            if($empresa->isEnabled() == true)
            {
                $empresa->setEnabled(false);
                $em->merge($empresa);
                $em->flush();

                $users = $empresa->getUsers();

                foreach($users as $user)
                {
                    if($user->getRolId() > 1 && $user->getRolId() < 4)
                    {
                        $user->setLocked(true);
                        $em->persist($user);
                        $em->flush();
                    }

                }
                $this->get('session')->getFlashBag()->add('fail', 'Empresa bloqueada');
                return $this->redirect($this->generateUrl('mg_empadmin_homepage', array('page'=> 1)));
            }
            elseif($empresa->isEnabled() == false)
            {
                $empresa->setEnabled(true);
                $em->merge($empresa);
                $em->flush();

                $users = $empresa->getUsers();

                foreach($users as $user)
                {
                    if($user->getRolId() > 1 && $user->getRolId() < 4  )
                    {
                        $user->setLocked(false);
                        $em->persist($user);
                        $em->flush();
                    }

                }
                $this->get('session')->getFlashBag()->add('notice', 'Empresa desbloqueada');
                return $this->redirect($this->generateUrl('mg_empadmin_homepage', array('page'=> 1)));
            }
        }else
        {
            $mensaje = "No tienes permisos para visualizar esta página.";
            $msjTipo = "error";
            return $this->render('MGAdminBundle:Msg:messages.html.twig', array('mensaje' => $mensaje, 'tipo' => $msjTipo));
        }

    }
}

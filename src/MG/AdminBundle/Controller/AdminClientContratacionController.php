<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Merkury
 * Date: 11/10/13
 * Time: 10:57
 * To change this template use File | Settings | File Templates.
 */

namespace MG\AdminBundle\Controller;

use MG\RepoBundle\Form\ClientContratacionAddServicesType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class AdminClientContratacionController extends Controller {

    public function indexAction($page = NULL, $limit = 10)
    {
        if($this->getUser()->getRol()->getRolName() == 'ROLE_SUPER_ADMIN')
        {
            $first = true;
            $last = false;

            if(!isset($page) || $page < 2)
            {
                $page = 1;
                $first = false;
            }

            $sol_repo = $this->getDoctrine()->getRepository('MGRepoBundle:ClientContratacion');

            $allc = $sol_repo->findAll();

            foreach($allc as $c)
            {
                if($c->getVista() == false)
                {
                    $c->setVista(true);
                    $em = $this->getDoctrine()->getManager();
                    $em->merge($c);
                    $em->flush();
                }

            }

            $contrataciones = $sol_repo->findAllWithLimitAndOffset($limit, ($page-1)*$limit);

            if(!isset($contrataciones) || !$contrataciones )
            {
                $this->get('session')->getFlashBag()->add('fail', 'No hay ninguna solicitud de contratación registradas');
                return $this->redirect($this->generateUrl('mg_admin_homepage'));
            }

            $page_number = ceil(count($allc)/$limit);

            if($page_number == $page)
            {
                $last = true;
            }

            return $this->render('MGAdminBundle:Contratacion:indexclientcontratacion.html.twig',
                array(
                    'contrataciones' => $contrataciones,
                    'pages' => $page_number,
                    'first' => $first,
                    'last' => $last,
                    'page' => $page
                ));

        }
        else
        {
            $mensaje = "No estas autorizado a acceder a esta zona.";
            $msjTipo = "error";
            return $this->render('MGAdminBundle:Msg:messages.html.twig', array('mensaje' => $mensaje, 'tipo' => $msjTipo));
        }
    }

    public function detailAction($contId, Request $request)
    {
        if($this->getUser()->getRol()->getRolName() == 'ROLE_SUPER_ADMIN')
        {
            $em = $this->getDoctrine()->getManager();

            $sol_cont = $em->getRepository('MGRepoBundle:ClientContratacion')
                ->find($contId);

            if(!$sol_cont)
            {
                $this->get('session')->getFlashBag()->add('fail', 'Solicitud no encontrada.');
                return $this->redirect($this->generateUrl('mg_client_cont_homepage', array('page' => 1)));
            }

            $servicios = array();
            foreach($sol_cont->getServiciosSolicitados() as $serv_sol)
            {
                $pre_serv = $em->getRepository('MGRepoBundle:Servicios')->findAllServicesActiveUsingName($serv_sol->getDisplayName());

                if($pre_serv || sizeof($pre_serv) > 0)
                {
                    foreach($pre_serv as $ps )
                    {
                        $servicios[] = $ps;
                    }
                }

                if($serv_sol->getDisplayName() == 'Otros')
                {
                    $otherserv = $em->getRepository('MGRepoBundle:Servicios')
                        ->findAllServicesActive();
                }

            }

            if(sizeof($servicios) < 1)
            {
                $this->get('session')->getFlashBag()->add('fail', 'No existen servicios solicitados');
                return $this->redirect($this->generateUrl('mg_client_cont_homepage', array('page' => 1)));
            }

            //COMPROBAMOS SI EXISTE LA VARIABLE OTHERSERVICES, SI NO PASAMOS LA FORM LA VARIABLE SERVICIOS,
            //PARA PODER PERSISTIR LOS SERVICIOS QUE MÁS SE AJUSTAN A LA SOLICITUD.
            if(!isset($otherserv) || sizeof($otherserv) < 1)
            {
                $form = $this->createForm(new ClientContratacionAddServicesType(),$sol_cont, array(
                    'otherServ' => $servicios
                ));
            }else
            {
                $form = $this->createForm(new ClientContratacionAddServicesType(),$sol_cont, array(
                    'otherServ' => $otherserv
                ));

                //ELIMINAMOS LOS SERVICIOS QUE YA HEMOS OBTENIDO EN LA VARIABLE SERVICIOS
                foreach($otherserv as $elementKey => $os)
                {
                    foreach($servicios as $s)
                    {
                        if($os == $s)
                        {
                            unset($otherserv[$elementKey]);
                        }
                    }
                }
            }

            if($request->isMethod('POST'))
            {
                $form->bind($request);
                if($form->isValid())
                {
                    $em->persist($sol_cont);
                    $em->flush();

                    $this->get('session')->getFlashBag()->add('notice', 'Servicios recomendados agregados correctamente.');
                    return $this->redirect($this->generateUrl('mg_client_cont_detail', array('contId' => $contId)));
                }
            }

            if(!isset($otherserv))
            {
                return $this->render('MGAdminBundle:Contratacion:detailcont.html.twig', array(
                    'solicitud' => $sol_cont,
                    'servicios' => $servicios,
                    'addServForm' => $form->createView()
                ));
            }else
            {
                return $this->render('MGAdminBundle:Contratacion:detailcont.html.twig', array(
                    'solicitud' => $sol_cont,
                    'servicios' => $servicios,
                    'otherServs' => $otherserv,
                    'addServForm' => $form->createView()
                ));
            }
        }
        else
        {
            $mensaje = "No estas autorizado a acceder a esta zona.";
            $msjTipo = "error";
            return $this->render('MGAdminBundle:Msg:messages.html.twig', array('mensaje' => $mensaje, 'tipo' => $msjTipo));
        }
    }

}
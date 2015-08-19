<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Merkury
 * Date: 7/10/13
 * Time: 11:49
 * To change this template use File | Settings | File Templates.
 */

namespace MG\RepoBundle\Controller;


use MG\RepoBundle\Entity\ClientContratacion;
use MG\RepoBundle\Form\ClientContratacionType;
use MG\UserBundle\Form\ClientContratcServicioType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ContratacionServicioController extends Controller  {

    public function indexContratacionClienteAction(Request $request)
    {
        if($this->getUser()->getRol()->getRolName() == 'ROLE_CLIENTE')
        {
            $solicitudContratacion = new ClientContratacion();

            $form = $this->createForm(new ClientContratacionType(), $solicitudContratacion);

            $servTypes = $this->getDoctrine()->getRepository('MGRepoBundle:ServType')
                ->findby(array('enabled' => true));

            if(!$servTypes)
            {
                $mensaje = "Funcionalidad temporalmente desactivada.";
                $msjTipo = "error";
                return $this->render('MGRepoBundle:Msg:messagenosession.html.twig', array('mensaje' => $mensaje, 'tipo' => $msjTipo));
            }

            if($request->isMethod('POST'))
            {
                $form->bind($request);
                if($form->isValid())
                {
                    $solicitudContratacion->setCliente($this->getUser());
                    $solicitudContratacion->setFechaSolicitud(new \DateTime());
                    $solicitudContratacion->setGestionada(false);
                    $solicitudContratacion->setVista(false);

                    $em = $this->getDoctrine()->getManager();

                    if(sizeof($solicitudContratacion->getServiciosSolicitados()) < 1)
                    {
                        $this->get('session')->getFlashBag()->add('fail', 'Error. Debes seleccionar al menos un servicio.');
                        return $this->redirect($this->generateUrl('mg_home_contratacion'));

                    }

                    $em->persist($solicitudContratacion);
                    $em->flush();

                    $this->get('session')->getFlashBag()->add('notice', 'Solicitud registrada, en breves nos pondremos en contacto.');
                    return $this->redirect($this->generateUrl('mg_home_contratacion'));
                }
            }


            return $this->render('MGRepoBundle:Contratacion:index.html.twig', array(
                'selectServForm' => $form->createView(),
                'servTypes' => $servTypes
            ));
        }else
        {
            $mensaje = "No tienes permisos para acceder a este area.";
            $msjTipo = "error";
            return $this->render('MGRepoBundle:Msg:messagenosession.html.twig', array('mensaje' => $mensaje, 'tipo' => $msjTipo));
        }

    }

    public function detailContratacionAction(Request $request, $contId)
    {
        if($this->getUser()->getRol()->getRolName() == 'ROLE_CLIENTE')
        {
            $em = $this->getDoctrine()->getManager();

            $sol_cont = $em->getRepository('MGRepoBundle:ClientContratacion')
                ->find($contId);

            if(!$sol_cont)
            {
                $this->get('session')->getFlashBag()->add('fail', 'Solicitud no encontrada.');
                return $this->redirect($this->generateUrl('mg_repo_homepage'));
            }

            if($sol_cont->getCliente() == $this->getUser())
            {
                if($sol_cont->getGestionada() == false)
                {
                    echo($sol_cont->getGestionada());
                    if(sizeof($sol_cont->getServiciosSeleccionadosEmpresa()) > 0)
                    {
                        foreach($sol_cont->getServiciosSeleccionadosEmpresa() as $sse)
                        {
                            $servicios_seleccionados[] = $sse;
                        }

                        if(!isset($servicios_seleccionados))
                        {
                            $this->get('session')->getFlashBag()->add('fail', 'No existen servicios seleccionados.');
                            return $this->redirect($this->generateUrl('mg_repo_homepage'));
                        }

                        $form = $this->createForm(new ClientContratcServicioType(), $this->getUser(), array(
                            'selectServices' => $servicios_seleccionados
                        ));

                        if($request->isMethod('POST'))
                        {
                            $form->bind($request);
                            if($form->isValid())
                            {
                                $formData = $this->getRequest()->request->get($form->getName());
                                $em = $this->getDoctrine()->getManager();

                                if(isset($formData['serv_clientes']) && sizeof($formData['serv_clientes']) > 0 )
                                {
                                    foreach ($formData['serv_clientes'] as $sc)
                                    {
                                        $pre_empresa_serv = $em->getRepository('MGRepoBundle:Servicios')
                                            ->find($sc);

                                        $pre_noclean_empresa_serv[] = $pre_empresa_serv->getEmpresa();
                                        $servicios[] = $pre_empresa_serv;

                                    }
                                }else
                                {
                                    $mensaje = "Erro desconocido.";
                                    $msjTipo = "error";
                                    return $this->render('MGRepoBundle:Msg:messages.html.twig', array('mensaje' => $mensaje, 'tipo' => $msjTipo));
                                }

                                $empresa_serv = array_unique($pre_noclean_empresa_serv, SORT_REGULAR);

                                foreach ($empresa_serv as $es)
                                {
                                    if(!in_array($this->getUser(), $es->getClients()))
                                    {
                                        $this->getUser()->addEmpresa($es);

                                        $gestores = $es->getUsersByRol('ROLE_ADMIN');

                                        foreach($gestores as $g)
                                        {
                                            $message = \Swift_Message::newInstance()
                                            ->setContentType('text/html')
                                            ->setSubject('Solicitud ampliación de servicios')
                                            ->setFrom(array('noreply@migestionline.com' => 'Robot Mi Gestión Online'))
                                            ->setTo($g->getEmail())
                                            ->setBody(
                                                $this->renderView(
                                                    'MGRepoBundle:MailTemplates:nuevasolicitudcontratacion.html.twig',
                                                    array(
                                                        'name' => $sol_cont->getCliente()->getUserName(),
                                                        'mail' => $sol_cont->getCliente()->getEmail(),
                                                        'servicios' => $servicios
                                                    )
                                                )
                                            );
                                            $this->get('mailer')->send($message);
                                        }
                                    }
                                }

                                foreach($servicios as $s)
                                {
                                    $s->addCliente($this->getUser());
                                    $em->merge($s);
                                    $em->flush();
                                }

                                $sol_cont->setGestionada(true);

                                $em->persist($this->getUser());
                                $em->persist($sol_cont);
                                $em->flush();

                                $this->get('session')->getFlashBag()->add('notice', 'Servicios contratados correctamente.');
                                return $this->redirect($this->generateUrl('mg_repo_homepage'));
                            }
                        }

                        return $this->render('MGRepoBundle:Contratacion:detailcontratacion.html.twig', array(
                            'solicitudContratacion' => $sol_cont,
                            'addServsForm' => $form->createView()
                        ));

                    }else
                    {
                        return $this->render('MGRepoBundle:Contratacion:detailcontratacion.html.twig', array(
                            'solicitudContratacion' => $sol_cont
                        ));
                    }
                }else
                {
                    $mensaje = "Esta solicitud ya ha sido gestionada. Si necesitas ayuda ponte en contacto con tu gestor.";
                    $msjTipo = "error";
                    return $this->render('MGRepoBundle:Msg:messages.html.twig', array('mensaje' => $mensaje, 'tipo' => $msjTipo));
                }
            }else
            {
                $mensaje = "No estas autorizado a acceder a esta zona.";
                $msjTipo = "error";
                return $this->render('MGRepoBundle:Msg:messages.html.twig', array('mensaje' => $mensaje, 'tipo' => $msjTipo));
            }
        }else
        {
            $mensaje = "No estas autorizado a acceder a esta zona.";
            $msjTipo = "error";
            return $this->render('MGRepoBundle:Msg:messages.html.twig', array('mensaje' => $mensaje, 'tipo' => $msjTipo));
        }
    }

    public function discardContratacionAction($contId)
    {
        $em = $this->getDoctrine()->getManager();

        $sol_cont = $em->getRepository('MGRepoBundle:ClientContratacion')
            ->find($contId);

        if($this->getUser()->getRol()->getRolName() == 'ROLE_SUPER_ADMIN')
        {
            $em->remove($sol_cont);
            $em->flush();

            $this->get('session')->getFlashBag()->add('notice', 'Solicitud eliminada.');
            return $this->redirect($this->generateUrl('mg_client_cont_homepage', array('page'=>1)));

        }elseif($this->getUser() == $sol_cont->getCliente())
        {
            $em->remove($sol_cont);
            $em->flush();

            $this->get('session')->getFlashBag()->add('notice', 'Solicitud eliminada.');
            return $this->redirect($this->generateUrl('mg_repo_homepage'));

        }else
        {
            $mensaje = "No tienes permisos para acceder a este area.";
            $msjTipo = "error";
            return $this->render('MGRepoBundle:Msg:messagenosession.html.twig', array('mensaje' => $mensaje, 'tipo' => $msjTipo));
        }
    }
}
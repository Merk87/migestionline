<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Merkury
 * Date: 16/09/13
 * Time: 10:26
 * To change this template use File | Settings | File Templates.
 */

namespace MG\AdminBundle\Controller;

use MG\LandingFrontBundle\Entity\RespuestaContacto;
use MG\LandingFrontBundle\Form\RespuestaContactoType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;


class AdminContactController extends Controller {

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

            $public_conver = $this->getDoctrine()->getRepository('MGLandingFrontBundle:ConversacionPublica');

            $allc = $public_conver->findAll();

            $conversaciones_publicas = $public_conver->findAllWithLimitAndOffset($limit, ($page-1)*$limit);

            if(!isset($conversaciones_publicas) || !$conversaciones_publicas )
            {
                $this->get('session')->getFlashBag()->add('fail', 'No hay ninguna solicitud de información registradas');
                return $this->redirect($this->generateUrl('mg_admin_homepage'));
            }

            $page_number = ceil(count($allc)/$limit);

            if($page_number == $page)
            {
                $last = true;
            }

            return $this->render('MGAdminBundle:Contacto:indexcontacto.html.twig',
                array(
                    'conversaciones' => $conversaciones_publicas,
                    'pages' => $page_number,
                    'first' => $first,
                    'last' => $last,
                    'page' => $page
                ));

        }
        else
        {
            $mensaje = "No estas autorizado a acceder a esta área.";
            $msjTipo = "error";
            return $this->render('MGAdminBundle:Msg:messages.html.twig', array('mensaje' => $mensaje, 'tipo' => $msjTipo));
        }



    }

    public function showAction($idConv, Request $request)
    {
        if($this->getUser()->getRol()->getRolName() == 'ROLE_SUPER_ADMIN')
        {

            $em = $this->getDoctrine()->getManager();
            $publicConv  = $em->getRepository('MGLandingFrontBundle:ConversacionPublica')
                ->find($idConv);

            $respuestaContact = new RespuestaContacto();
            $form = $this->createForm(new RespuestaContactoType(), $respuestaContact);

            if (!$publicConv) {
                $this->get('session')->getFlashBag()->add('fail', 'No se encuentra la solicitud de información');
                return $this->redirect($this->generateUrl('mg_contactos_homepage', array('page' => 1)));
            }

            foreach($publicConv->getMensajesCli() as $solContact)
            {
                if($solContact->getLeido() == false)
                {
                    $solContact->setLeido(true);
                    $em = $this->getDoctrine()->getManager();
                    $em->merge($solContact);
                    $em->flush();
                }
            }

            if($request->isMethod('POST'))
            {
                $form->bind($request);
                if($form->isValid())
                {

                    $respuestaContact->setConversacionPublica($publicConv);
                    $respuestaContact->setFechaRespuesta(new \DateTime());
                    $publicConv->setFechaUltimo(new \DateTime());
                    $respuestaContact->setAutor($this->getUser());

                    $CliIndeMsg = sizeof($publicConv->getMensajesCli());
                    $allCliMsg = $publicConv->getMensajesCli();
                    $emailCli = $allCliMsg[$CliIndeMsg - 1]->getEmail();

                    $em->persist($respuestaContact);
                    $em->flush();

                    $message = \Swift_Message::newInstance()
                        ->setContentType('text/html')
                        ->setSubject('Respuesta a tu consulta Mi Gestión Online')
                        ->setFrom(array('noreply@migestiononline.com' => 'Robot Mi Gestión Online'))
                        ->setTo($emailCli)
                        ->setBody(
                            $this->renderView(
                                'MGLandingFrontBundle:MailTemplates:respuesta.html.twig',
                                array(
                                    'message' => $form->get('mensaje')->getData(),
                                    'hash' => $publicConv->getHashConv()
                                )
                            )
                        );

                    $this->get('mailer')->send($message);
                    $this->get('session')->getFlashBag()->add('notice', '¡Respuesta enviada!');
                    return $this->redirect($this->generateUrl('mg_public_conver_detail',
                        array(
                            'idConv' => $idConv
                        )));
                   }
            }

            return $this->render('MGAdminBundle:Contacto:detailcontact.html.twig',
                array(
                   'conversacion' => $publicConv,
                   'respuesta' => $form->createView()
                ));
        }else
        {
            $mensaje = "No estas autorizado a acceder a esta área.";
            $msjTipo = "error";
            return $this->render('MGAdminBundle:Msg:messages.html.twig', array('mensaje' => $mensaje, 'tipo' => $msjTipo));
        }

    }

    public function discardConvContactAction($convId)
    {
        if($this->getUser()->getRol()->getRolName() == 'ROLE_SUPER_ADMIN')
        {
            $em = $this->getDoctrine()->getManager();

            $conversacion = $em->getRepository('MGLandingFrontBundle:ConversacionPublica')
                ->find($convId);

            if($conversacion->getActiva() == true)
            {
                $conversacion->setActiva(false);
                $mensajesCli = $conversacion->getMensajesCli();
                foreach($mensajesCli as $mc)
                {
                    $mc->setLeido(true);
                    $em->persist($mc);
                    $em->flush();
                }
                $em->persist($conversacion);
                $em->flush();

                $this->get('session')->getFlashBag()->add('notice', '¡Conversación archivada!');
                return $this->redirect($this->generateUrl('mg_contactos_homepage',
                    array(
                        'page' => 1
                    )));
            }else
            {
                $conversacion->setActiva(true);
               $em->persist($conversacion);
                $em->flush();

                $this->get('session')->getFlashBag()->add('notice', '¡Conversación reabierta!');
                return $this->redirect($this->generateUrl('mg_contactos_homepage',
                    array(
                        'page' => 1
                    )));
            }

        }
        else
        {
            $mensaje = "No estas autorizado a acceder a esta área.";
            $msjTipo = "error";
            return $this->render('MGAdminBundle:Msg:messages.html.twig', array('mensaje' => $mensaje, 'tipo' => $msjTipo));
        }

    }

}
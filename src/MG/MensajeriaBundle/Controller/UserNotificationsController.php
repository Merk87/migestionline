<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Merkury
 * Date: 16/08/13
 * Time: 9:29
 * To change this template use File | Settings | File Templates.
 */

namespace MG\MensajeriaBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;


class UserNotificationsController extends Controller {

    public function newMessagesNotificationAction()
    {
        if($this->getUser()->getRolId()<= 4)
        {

            $conversaciones = $this->getUser()->getConversaciones();
            $noLeidos = array();

            foreach($conversaciones as $c)
            {
                if($c->hasUnread($this->getUser()) == true)
                {
                    $noLeidos[$c->getId()] = true;
                }
            }

            $respuesta = array(
                'respuesta' => 'OK',
                'responseCode' => 200,
                'cantidadDeConversaciones' => sizeof($noLeidos),
            );

            return new JsonResponse($respuesta, 200, array('ContentType', 'application/json'));

        }else
        {
            if($this->getUser()->getRolId() > 4 )
            {
                $mensaje = "No puedes acceder a esta zona.";
                $msjTipo = "error";
                return $this->render('MGRepoBundle:Msg:messages.html.twig', array('mensaje' => $mensaje, 'tipo' => $msjTipo));
            }else
            {
                $mensaje = "No tienes permiso para acceder a este área.";
                $msjTipo = "error";
                return $this->render('MGAdminBundle:Msg:messages.html.twig', array('mensaje' => $mensaje, 'tipo' => $msjTipo));
            }
        }
    }

    public function newGestionNotificationAction()
    {
        if($this->getUser()->getRolId() <= 2)
        {

            $noGestionadas = array();
            foreach($this->getUser()->getEmpresas() as $emp)
            {
                foreach($emp->getGestiones() as $g)
                {
                    if($g->getEstadoId() == 1)
                    {
                        $noGestionadas[$g->getId()] = true;
                    }
                }
            }

            $respuesta = array(
                'respuesta' => 'OK',
                'responseCode' => 200,
                'cantidadDeGestiones' => sizeof($noGestionadas),
            );

            return new JsonResponse($respuesta, 200, array('ContentType', 'application/json'));

        }elseif($this->getUser()->getRolId() == 3)
        {
            $userServs = $this->getUser()->getServUsers();

            foreach($userServs as $serv)
            {
                $userCats[] = $serv->getCategoria();
            }


            $noGestionadas = array();
            foreach($this->getUser()->getEmpresas() as $emp)
            {
                foreach($emp->getGestiones() as $g)
                {
                    if(isset($userCats))
                    {
                        foreach($userCats as $categorias)
                        {
                            foreach ($categorias as $cat) {
                                if($g->getIdCategoria() == $cat->getId() && $g->getEstadoId() == 1)
                                {
                                        $noGestionadas[$g->getId()] = true;
                                }
                            }
                        }

                    }

                }
            }

            $respuesta = array(
                'respuesta' => 'OK',
                'responseCode' => 200,
                'cantidadDeGestiones' => sizeof($noGestionadas),
            );

            return new JsonResponse($respuesta, 200, array('ContentType', 'application/json'));

        }
        else
        {
            if($this->getUser()->getRolId() > 4 )
            {
                $mensaje = "No puedes acceder a esta zona.";
                $msjTipo = "error";
                return $this->render('MGRepoBundle:Msg:messages.html.twig', array('mensaje' => $mensaje, 'tipo' => $msjTipo));
            }else
            {
                $mensaje = "No tienes permiso para acceder a este área.";
                $msjTipo = "error";
                return $this->render('MGAdminBundle:Msg:messages.html.twig', array('mensaje' => $mensaje, 'tipo' => $msjTipo));
            }
        }
    }

    public function newContratacionNotificacionAction()
    {
        if($this->getUser()->getRol()->getRolName() == 'ROLE_SUPER_ADMIN')
        {
            $em = $this->getDoctrine()->getManager();

            $new_contrataciones = $em->getRepository('MGAdminBundle:Contratacion')
                ->findBy(array('readed' => false));

            $respuesta = array(
                'respuesta' => 'OK',
                'responseCode' => 200,
                'nuevasContrataciones' => sizeof($new_contrataciones),
            );

            return new JsonResponse($respuesta, 200, array('ContentType', 'application/json'));

        }else
        {
            if($this->getUser()->getRolId() > 4 )
            {
                $mensaje = "No puedes acceder a esta zona.";
                $msjTipo = "error";
                return $this->render('MGRepoBundle:Msg:messages.html.twig', array('mensaje' => $mensaje, 'tipo' => $msjTipo));
            }else
            {
                $mensaje = "No tienes permiso para acceder a este área.";
                $msjTipo = "error";
                return $this->render('MGAdminBundle:Msg:messages.html.twig', array('mensaje' => $mensaje, 'tipo' => $msjTipo));
            }
        }
    }

    public function newClientContratacionNotificacionAction()
    {
        if($this->getUser()->getRol()->getRolName() == 'ROLE_SUPER_ADMIN')
        {
            $em = $this->getDoctrine()->getManager();

            $new_contrataciones_client = $em->getRepository('MGRepoBundle:ClientContratacion')
                ->findBy(array('vista' => false));

            $respuesta = array(
                'respuesta' => 'OK',
                'responseCode' => 200,
                'nuevasContratacionesCliente' => sizeof($new_contrataciones_client),
            );

            return new JsonResponse($respuesta, 200, array('ContentType', 'application/json'));

        }else
        {
            if($this->getUser()->getRolId() > 4 )
            {
                $mensaje = "No puedes acceder a esta zona.";
                $msjTipo = "error";
                return $this->render('MGRepoBundle:Msg:messages.html.twig', array('mensaje' => $mensaje, 'tipo' => $msjTipo));
            }else
            {
                $mensaje = "No tienes permiso para acceder a este área.";
                $msjTipo = "error";
                return $this->render('MGAdminBundle:Msg:messages.html.twig', array('mensaje' => $mensaje, 'tipo' => $msjTipo));
            }
        }
    }

    public function newAllContratacionNotificacionAction()
    {
        if($this->getUser()->getRol()->getRolName() == 'ROLE_SUPER_ADMIN')
        {
            $em = $this->getDoctrine()->getManager();

            $new_contrataciones_no_client = $em->getRepository('MGAdminBundle:Contratacion')
                ->findBy(array('readed' => false));

            $new_contrataciones_client = $em->getRepository('MGRepoBundle:ClientContratacion')
                ->findBy(array('vista' => false));

            $new_contrataciones = sizeof($new_contrataciones_client) + sizeof($new_contrataciones_no_client);

            $respuesta = array(
                'respuesta' => 'OK',
                'responseCode' => 200,
                'totalNuevasContrataciones' => $new_contrataciones,
            );

            return new JsonResponse($respuesta, 200, array('ContentType', 'application/json'));

        }else
        {
            if($this->getUser()->getRolId() > 4 )
            {
                $mensaje = "No puedes acceder a esta zona.";
                $msjTipo = "error";
                return $this->render('MGRepoBundle:Msg:messages.html.twig', array('mensaje' => $mensaje, 'tipo' => $msjTipo));
            }else
            {
                $mensaje = "No tienes permiso para acceder a este área.";
                $msjTipo = "error";
                return $this->render('MGAdminBundle:Msg:messages.html.twig', array('mensaje' => $mensaje, 'tipo' => $msjTipo));
            }
        }
    }

    public function changeStatusNotificationAction()
    {
        if($this->getUser()->getRolId() == 2)
        {

            $newNotificaciones = array();

            foreach($this->getUser()->getEmpresas() as $emp)
            {
                foreach($emp->getGestiones() as $g)
                {
                    if($g->getNotificacion()->getActiveForEmp() == true && $g->getNotificacion()->getEstado()->getEstadoCode() != 'GESTION_NUEVA')
                    {
                        $newNotificaciones[] = $g->getNotificacion();
                    }
                }
            }

            $respuesta = array(
                'respuesta' => 'OK',
                'responseCode' => '200',
                'numeroNotificaciones' => sizeof($newNotificaciones)
            );

            return new JsonResponse($respuesta, 200, array('ContentType', 'application/json'));
        }
        elseif($this->getUser()->getRolId() == 3)
        {

            $userServs = $this->getUser()->getServUsers();

            foreach($userServs as $serv)
            {
                $userCats[] = $serv->getCategoria();
            }

            $newNotificaciones = array();

            foreach($this->getUser()->getEmpresas() as $emp)
            {
                foreach($emp->getGestiones() as $g)
                {
                    if(isset($userCats))
                    {
                        foreach($userCats as $categorias)
                        {
                            foreach ($categorias as $cat)
                            {
                                if($g->getIdCategoria() == $cat->getId())
                                {
                                    if($g->getNotificacion()->getActiveForEmp() == true && $g->getNotificacion()->getEstado()->getEstadoCode() != 'GESTION_NUEVA')
                                    {
                                        $newNotificaciones[] = $g->getNotificacion();
                                    }
                                }
                            }
                        }
                    }
                }
            }

            $respuesta = array(
                'respuesta' => 'OK',
                'responseCode' => 200,
                'numeroNotificaciones' => sizeof($newNotificaciones),
            );

            return new JsonResponse($respuesta, 200, array('ContentType', 'application/json'));
        }
        else
        {
            if($this->getUser()->getRolId() == 4 )
            {
                $mensaje = "No puedes acceder a esta zona.";
                $msjTipo = "error";
                return $this->render('MGRepoBundle:Msg:messages.html.twig', array('mensaje' => $mensaje, 'tipo' => $msjTipo));
            }else
            {
                $mensaje = "No tienes permiso para acceder a este área.";
                $msjTipo = "error";
                return $this->render('MGAdminBundle:Msg:messages.html.twig', array('mensaje' => $mensaje, 'tipo' => $msjTipo));
            }
        }
    }

    public function changeStatusNotificationUserAction($empId)
    {
        if($this->getUser()->getRolId() == 4)
        {
            $allg = $this->getUser()->getGestiones();

            if(!$allg || !isset($allg) || sizeof($allg) < 1)
            {
                $respuesta = array(
                    'respuesta' => 'FAIL',
                    'responseCode' => 204,
                    'mensaje' => 'No hay gestiones para el usuario'
                );

                return new JsonResponse($respuesta, 200, array('ContentType', 'application/json'));
            }

            foreach($allg as $g)
            {
                if($g->getIdEmpresa() == $empId)
                {
                    $userGest[] = $g;
                }
            }

            if(!$userGest || !isset($userGest) || sizeof($userGest) < 1)
            {
                $respuesta = array(
                    'respuesta' => 'FAIL',
                    'responseCode' => 204,
                    'mensaje' => 'No hay gestiones de esa empresa'
                );

                return new JsonResponse($respuesta, 200, array('ContentType', 'application/json'));
            }

            $newNotificaciones = array();

            foreach($userGest as $gest)
            {
                if($gest->getNotificacion()->getActiveForUser() == true)
                {
                    $newNotificaciones[] = $gest->getNotificacion();
                }
            }

            $respuesta = array(
                'respuesta' => 'OK',
                'responseCode' => 200,
                'numeroNotificaciones' => sizeof($newNotificaciones)
            );

            return new JsonResponse($respuesta, 200, array('ContentType', 'application/json'));
        }else
        {
            $respuesta = array(
                'respuesta' => 'OK',
                'responseCode' => 204,
                'msg' => 'No tienes acceso'
            );

            return new JsonResponse($respuesta, 200, array('ContentType', 'application/json'));
        }
    }

    public function showChangedGestAdminAction($limit = 10, $page = 1)
    {
        if($this->getUser()->getRolId() <= 2)
        {
            $first = true;
            $last = false;
            $offset = 10 * ($page - 1);

            if(!isset($page) || $page < 2)
            {
                $page = 1;
                $first = false;
            }

           $allg = array();

            foreach($this->getUser()->getEmpresas() as $emp)
            {
                foreach($emp->getGestiones() as $g)
                {
                    if($g->getNotificacion()->getActiveForEmp() == true && $g->getNotificacion()->getEstado()->getEstadoCode() != 'GESTION_NUEVA')
                    {
                        $allg[] = $g;
                    }
                }
            }

            if(!isset($allg) || sizeof($allg) < 0)
            {
                $mensaje = "No existen notificaciones.";
                $msjTipo = "error";
                return $this->render('MGAdminBundle:Msg:messages.html.twig', array('mensaje' => $mensaje, 'tipo' => $msjTipo));
            }

            for($i = $offset; $i < $limit + $offset; $i++)
            {
                if(isset($allg[$i]))
                {
                    $newNotificaciones[] = $allg[$i];
                }
            }

            if(!isset($newNotificaciones))
            {
                $mensaje = "No existen notificaciones.";
                $msjTipo = "error";
                return $this->render('MGAdminBundle:Msg:messages.html.twig', array('mensaje' => $mensaje, 'tipo' => $msjTipo));
            }

            $page_number = ceil(count($allg)/$limit);

            if($page_number == $page)
            {
                $last = true;
            }

           return $this->render('MGMensajeriaBundle:MensajesAdmin:indexnotificaciones.html.twig',
               array(
                   'newNotificaciones' => $newNotificaciones,
                   'pages' => $page_number,
                   'first' => $first,
                   'last' => $last,
                   'page' => $page
               ));
        }
        elseif($this->getUser()->getRolId() == 3)
        {

            $first = true;
            $last = false;
            $offset = 10 * ($page - 1);

            if(!isset($page) || $page < 2)
            {
                $page = 1;
                $first = false;
            }

            $userServs = $this->getUser()->getServUsers();

            foreach($userServs as $serv)
            {
                $userCats[] = $serv->getCategoria();
            }

            $allg = array();

            foreach($this->getUser()->getEmpresas() as $emp)
            {
                foreach($emp->getGestiones() as $g)
                {
                    if(isset($userCats))
                    {
                        foreach($userCats as $categorias)
                        {
                            foreach ($categorias as $cat)
                            {
                                if($g->getIdCategoria() == $cat->getId())
                                {
                                    if($g->getNotificacion()->getActiveForEmp() == true && $g->getNotificacion()->getEstado()->getEstadoCode() != 'GESTION_NUEVA')
                                    {
                                        $allg[] = $g;
                                    }
                                }
                            }
                        }
                    }
                }
            }

            if(!isset($allg) || sizeof($allg) < 0)
            {
                $mensaje = "No existen notificaciones.";
                $msjTipo = "error";
                return $this->render('MGAdminBundle:Msg:messages.html.twig', array('mensaje' => $mensaje, 'tipo' => $msjTipo));
            }

            for($i = $offset; $i < $limit + $offset; $i++)
            {
                if(isset($allg[$i]))
                {
                    $newNotificaciones[] = $allg[$i];
                }
            }

            $page_number = ceil(count($allg)/$limit);

            if($page_number == $page)
            {
                $last = true;
            }

            if(!isset($newNotificaciones) || sizeof($newNotificaciones) < 1)
            {
                $mensaje = "No existen notificaciones.";
                $msjTipo = "error";
                return $this->render('MGAdminBundle:Msg:messages.html.twig', array('mensaje' => $mensaje, 'tipo' => $msjTipo));
            }

            return $this->render('MGMensajeriaBundle:MensajesAdmin:indexnotificaciones.html.twig',
                array(
                    'newNotificaciones' => $newNotificaciones,
                    'pages' => $page_number,
                    'first' => $first,
                    'last' => $last,
                    'page' => $page
                ));
        }else
        {
            $mensaje = "No tienes permiso para acceder a este área.";
            $msjTipo = "error";
            return $this->render('MGAdminBundle:Msg:messages.html.twig', array('mensaje' => $mensaje, 'tipo' => $msjTipo));
        }
    }

    public function showChangedGestUserAction(/*$limit = 10, $page = 1,*/ $empId)
    {
        if($this->getUser()->getRolId() == 4)
        {
            $allg = $this->getUser()->getGestiones();

            if(!$allg || !isset($allg) || sizeof($allg) < 1)
            {
                $mensaje = "No existen notificaciones.";
                $msjTipo = "error";
                return $this->render('MGRepoBundle:Msg:messages.html.twig', array('mensaje' => $mensaje, 'tipo' => $msjTipo));
            }

            foreach($allg as $g)
            {
                if($g->getIdEmpresa() == $empId)
                {
                    $userGest[] = $g;
                }
            }

            if(!isset($userGest) || !$userGest || sizeof($userGest) < 1)
            {
                $mensaje = "No existen notificaciones.";
                $msjTipo = "error";
                return $this->render('MGRepoBundle:Msg:messages.html.twig', array('mensaje' => $mensaje, 'tipo' => $msjTipo));

            }

            $newNotificaciones = array();

            foreach($userGest as $gest)
            {
                if($gest->getNotificacion()->getActiveForUser() == true)
                {
                    $newNotificaciones[] = $gest;
                }
            }

            return $this->render('MGMensajeriaBundle:MensajesCliente:indexnotificacionescli.html.twig',
                array(
                    'newNotificaciones' => $newNotificaciones,
                   /* 'pages' => $page_number,
                    'first' => $first,
                    'last' => $last,
                    'page' => $page*/
                ));

        }else
        {
            $mensaje = "No puedes acceder a esta zona.";
            $msjTipo = "error";
            return $this->render('MGRepoBundle:Msg:messages.html.twig', array('mensaje' => $mensaje, 'tipo' => $msjTipo));
        }
    }

    public function disableNotificationAction($notId)
    {
        $em = $this->getDoctrine()->getManager();

        $notificacion = $em->getRepository('MGMensajeriaBundle:Notificaciones')
            ->find($notId);

        if(!$notificacion || !isset($notificacion))
        {
            $respuesta = array(
                'respuesta' => 'FAIL',
                'responseCode' => 204,
                'reason' => 'No se encuentra la notificacion'
            );

            return new JsonResponse($respuesta, 200, array('ContentType', 'application/json'));
        }

        if($this->getUser()->getRolId() == 2)
        {

            $newNotificaciones = array();

            foreach($this->getUser()->getEmpresas() as $emp)
            {
                foreach($emp->getGestiones() as $g)
                {
                    if($g->getNotificacion()->getActiveForEmp() == true && $g->getNotificacion()->getEstado()->getEstadoCode() != 'GESTION_NUEVA')
                    {
                        $newNotificaciones[] = $g->getNotificacion();
                    }
                }
            }

            if(!isset($newNotificaciones) || sizeof($newNotificaciones) < 1 )
            {
                $respuesta = array(
                    'respuesta' => 'FAIL',
                    'responseCode' => 204,
                    'reason' => 'No existen notificaciones que el user pueda ver'
                );
                return new JsonResponse($respuesta, 200, array('ContentType', 'application/json'));
            }

            foreach($newNotificaciones as $n)
            {
                if($n == $notificacion)
                {
                    $notificacion->setActiveForEmp(false);
                    $em->merge($notificacion);
                    $em->flush();
                }
            }

            $respuesta = array(
                'respuesta' => 'OK',
                'responseCode' => 200
            );
            return new JsonResponse($respuesta, 200, array('ContentType', 'application/json'));

        }
        elseif($this->getUser()->getRolId() == 3)
        {

            $userServs = $this->getUser()->getServUsers();

            foreach($userServs as $serv)
            {
                $userCats[] = $serv->getCategoria();
            }

            if(!isset($userCats) || sizeof($userCats) < 1 )
            {
                $respuesta = array(
                    'respuesta' => 'FAIL',
                    'responseCode' => 204,
                    'reason' => 'No existen notificaciones que el user pueda ver'
                );
                return new JsonResponse($respuesta, 200, array('ContentType', 'application/json'));
            }

            foreach($this->getUser()->getEmpresas() as $emp)
            {
                foreach($emp->getGestiones() as $g)
                {
                    if(isset($userCats))
                    {
                        foreach($userCats as $categorias)
                        {
                            foreach ($categorias as $cat)
                            {
                                if($g->getIdCategoria() == $cat->getId())
                                {
                                    if($g->getNotificacion()->getActiveForEmp() == true && $g->getNotificacion()->getEstado()->getEstadoCode() != 'GESTION_NUEVA')
                                    {
                                        $alln[] = $g->getNotificacion();
                                    }
                                }
                            }
                        }
                    }
                }
            }

            if(!isset($alln) || sizeof($alln) < 1 )
            {
                $respuesta = array(
                    'respuesta' => 'FAIL',
                    'responseCode' => 204,
                    'reason' => 'No existen notificaciones que el user pueda ver'
                );
                return new JsonResponse($respuesta, 200, array('ContentType', 'application/json'));
            }

            foreach($alln as $n)
            {
                if($n == $notificacion)
                {
                    $notificacion->setActiveForEmp(false);
                    $em->merge($notificacion);
                    $em->flush();
                }
            }

            $respuesta = array(
                'respuesta' => 'OK',
                'responseCode' => 200
            );
            return new JsonResponse($respuesta, 200, array('ContentType', 'application/json'));

        }
        elseif($this->getUser()->getRolId() == 4)
        {
            $gestiones = $this->getUser()->getGestiones();

            if(!isset($gestiones) || sizeof($gestiones) < 1 )
            {
                $respuesta = array(
                    'respuesta' => 'FAIL',
                    'responseCode' => 204,
                    'reason' => 'No existen notificaciones que el user pueda ver'
                );
                return new JsonResponse($respuesta, 200, array('ContentType', 'application/json'));
            }

            foreach($gestiones as $g)
            {
                if($g->getNotificacion() == $notificacion)
                {
                    $notificacion->setActiveForUser(false);
                    $em->merge($notificacion);
                    $em->flush();
                }
            }


            $respuesta = array(
                'respuesta' => 'OK',
                'responseCode' => 200
            );
            return new JsonResponse($respuesta, 200, array('ContentType', 'application/json'));
        }else
        {

            $respuesta = array(
                'respuesta' => 'FAIL',
                'responseCode' => 204
            );
            return new JsonResponse($respuesta, 200, array('ContentType', 'application/json'));
        }
    }

    public function newContactMsgNotificationAction()
    {
        if($this->getUser()->getRol()->getRolName() == 'ROLE_SUPER_ADMIN')
        {

            $em = $this->getDoctrine()->getManager();
            $conversacion_publica = $em->getRepository('MGLandingFrontBundle:ConversacionPublica')
                ->findAll();

            $noLeidos = array();

            foreach($conversacion_publica as $c)
            {
                if($c->hasUnread($this->getUser()) == true)
                {
                    $noLeidos[$c->getId()] = true;
                }
            }

            $respuesta = array(
                'respuesta' => 'OK',
                'responseCode' => 200,
                'cantidadDeContactos' => sizeof($noLeidos),
            );

            return new JsonResponse($respuesta, 200, array('ContentType', 'application/json'));

        }else
        {
            if($this->getUser()->getRol()->getRolName() != 'ROLE_SUPER_ADMIN' )
            {
                $mensaje = "No puedes acceder a esta zona.";
                $msjTipo = "error";
                return $this->render('MGRepoBundle:Msg:messages.html.twig', array('mensaje' => $mensaje, 'tipo' => $msjTipo));
            }else
            {
                $mensaje = "No tienes permiso para acceder a este área.";
                $msjTipo = "error";
                return $this->render('MGAdminBundle:Msg:messages.html.twig', array('mensaje' => $mensaje, 'tipo' => $msjTipo));
            }
        }
    }

    public function checkContratacionNotificationAction()
    {
        if($this->getUser()->getRol()->getRolName() == 'ROLE_CLIENTE')
        {
            $em = $this->getDoctrine()->getManager();

            $pre_new_contrataciones = $em->getRepository('MGRepoBundle:ClientContratacion')
                ->findBy(array('vista' => true));

            foreach ($pre_new_contrataciones as $nc)
            {
                if(sizeof($nc->getServiciosSeleccionadosEmpresa()) > 0 && $nc->getGestionada() == false)
                {
                    $new_contrataciones[] = $nc;
                }
            }

            if(!isset($new_contrataciones))
            {
                $mensaje = "No existen contrataciones nuevas.";
                $msjTipo = "error";
                return $this->render('MGRepoBundle:Msg:messagenosession.html.twig', array('mensaje' => $mensaje, 'tipo' => $msjTipo));
            }

            $respuesta = array(
                'respuesta' => 'OK',
                'responseCode' => 200,
                'ContratacionesARevisar' => sizeof($new_contrataciones),
            );

            return new JsonResponse($respuesta, 200, array('ContentType', 'application/json'));

        }else
        {
            if($this->getUser()->getRolId() > 4 )
            {
                $mensaje = "No puedes acceder a esta zona.";
                $msjTipo = "error";
                return $this->render('MGRepoBundle:Msg:messages.html.twig', array('mensaje' => $mensaje, 'tipo' => $msjTipo));
            }else
            {
                $mensaje = "No tienes permiso para acceder a este área.";
                $msjTipo = "error";
                return $this->render('MGAdminBundle:Msg:messages.html.twig', array('mensaje' => $mensaje, 'tipo' => $msjTipo));
            }
        }
    }

    public function showContractNotificationAction()
    {
        $user = $this->getUser();
        if($user->getRol()->getRolName() == 'ROLE_CLIENTE')
        {
            $em = $this->getDoctrine()->getManager();

            $solicitudes_contratacion = $user->getContratacionCliente();

            foreach($solicitudes_contratacion as $sc)
            {
                if(sizeof($sc->getServiciosSeleccionadosEmpresa()) > 0 && $sc->getGestionada() == false)
                {
                   $solicitudes_contratacion_con_opciones[] = $sc;
                }
            }

            return $this->render('MGMensajeriaBundle:MensajesCliente:indexnotificacionescontratacion.html.twig',array(
                'solicitudesARevisar' => $solicitudes_contratacion_con_opciones
            ));

        }else
        {
            $mensaje = "No puedes acceder a esta zona.";
            $msjTipo = "error";
            return $this->render('MGRepoBundle:Msg:messages.html.twig', array('mensaje' => $mensaje, 'tipo' => $msjTipo));
        }
    }

    public function showNewUsersAction()
    {
        $user = $this->getUser();
        if($user->getRol()->getRolName() == 'ROLE_ADMIN')
        {
            $em = $this->getDoctrine()->getManager();

            $newClients = array();

            foreach($user->getEmpresas() as $empresa)
            {
                foreach($empresa->getClients() as $client)
                {
                    if($client->getNuevo() == true)
                    {
                        $newClients[] = $client;
                    }
                }
            }

            return $this->render('MGMensajeriaBundle:MensajesAdmin:newclientsindex.html.twig',array(
                'newClients' => $newClients
            ));

        }else
        {
            if($this->getUser()->getRolId() > 4 )
            {
                $mensaje = "No puedes acceder a esta zona.";
                $msjTipo = "error";
                return $this->render('MGRepoBundle:Msg:messages.html.twig', array('mensaje' => $mensaje, 'tipo' => $msjTipo));
            }else
            {
                $mensaje = "No tienes permiso para acceder a este área.";
                $msjTipo = "error";
                return $this->render('MGAdminBundle:Msg:messages.html.twig', array('mensaje' => $mensaje, 'tipo' => $msjTipo));
            }
        }
    }

    public function newClientsNotificationAction()
    {
        $user = $this->getUser();
        if($this->getUser()->getRol()->getRolName() == 'ROLE_ADMIN')
        {
            $em = $this->getDoctrine()->getManager();

            $newClients = array();

            foreach($user->getEmpresas() as $empresa)
            {
                foreach($empresa->getClients() as $client)
                {
                    if($client->getNuevo() == true)
                    {
                        $newClients[] = $client;
                    }
                }
            }

            $respuesta = array(
                'respuesta' => 'OK',
                'responseCode' => 200,
                'nuevosClientes' => sizeof($newClients),
            );

            return new JsonResponse($respuesta, 200, array('ContentType', 'application/json'));

        }else
        {
            if($this->getUser()->getRolId() > 4 )
            {
                $mensaje = "No puedes acceder a esta zona.";
                $msjTipo = "error";
                return $this->render('MGRepoBundle:Msg:messages.html.twig', array('mensaje' => $mensaje, 'tipo' => $msjTipo));
            }else
            {
                $mensaje = "No tienes permiso para acceder a este área.";
                $msjTipo = "error";
                return $this->render('MGAdminBundle:Msg:messages.html.twig', array('mensaje' => $mensaje, 'tipo' => $msjTipo));
            }
        }
    }
}
<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Merkury
 * Date: 8/08/13
 * Time: 9:14
 * To change this template use File | Settings | File Templates.
 */
namespace MG\MensajeriaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use MG\MensajeriaBundle\Entity\Mensajes;
use MG\MensajeriaBundle\Entity\Conversaciones;


use MG\MensajeriaBundle\Form\MensajesType;
use MG\MensajeriaBundle\Form\NewMensajesType;
use MG\MensajeriaBundle\Form\NewMensajesAdminType;

class UserMessageController extends Controller
{

    public function indexAction($page = NULL, $limit = 10)
    {
        $infoCli = $this->get('session')->get('infoClient');

        if($this->getUser()->getRolId() == 4)
        {
            if($infoCli != null)
            {
                $first = true;
                $last = false;

                $em = $this->getDoctrine()->getManager();

                $status = $em->getRepository('MGMensajeriaBundle:Status')
                    ->findAll();

                $session = $this->get('session');
                $session->set('infoMsg',array(
                    'msg_status' => $status
                ));

                if(!isset($page) || $page < 2)
                {
                    $page = 1;
                    $first = false;
                }

                $conversRepo = $em->getRepository('MGMensajeriaBundle:Conversaciones');

                $conversaciones = $conversRepo->findAllWithLimitAndOffset($limit, ($page-1)*$limit, $this->getUser()->getId());
                $allc = $conversRepo->findAllForPagination($this->getUser()->getId());

                if(!$conversaciones)
                {
                    $mensaje = "No tienes ninguna conversación abierta.";
                    $msjTipo = "error";

                    return $this->render('MGMensajeriaBundle:MensajesCliente:nomsg.html.twig', array('mensaje' => $mensaje, 'tipo' => $msjTipo));

                }

                $page_number = ceil(count($allc)/$limit);

                if($page_number == $page)
                {
                    $last = true;
                }
                return $this->render('MGMensajeriaBundle:MensajesCliente:messagesclientindex.html.twig', array(
                    'conversaciones' => $conversaciones,
                    'pages' => $page_number,
                    'first' => $first,
                    'last' => $last,
                    'page' => $page,
                ));

            }else
            {
                return $this->redirect($this->generateUrl('mg_repo_homepage'));
            }
        }
        elseif($this->getUser()->getRolId() < 4)
        {
            $first = true;
            $last = false;

            $em = $this->getDoctrine()->getManager();

            $status = $em->getRepository('MGMensajeriaBundle:Status')
                ->findAll();

            $session = $this->get('session');
            $session->set('infoMsg',array(
                'msg_status' => $status
            ));

            if(!isset($page) || $page < 2)
            {
                $page = 1;
                $first = false;
            }

            $conversRepo = $em->getRepository('MGMensajeriaBundle:Conversaciones');

            $conversaciones = $conversRepo->findAllWithLimitAndOffset($limit, ($page-1)*$limit, $this->getUser()->getId());
            $allc = $conversRepo->findAllForPagination($this->getUser()->getId());


            if(!$conversaciones)
            {
                $mensaje = "No tienes ninguna conversación abierta.";
                $msjTipo = "error";

                $empresas = $this->getUser()->getEmpresas();
                if($this->getUser()->getRolId() == 1)
                {
                    $empresas = $em->getRepository('MGAdminBundle:Empresa')
                        ->findAll();
                }
                return $this->render('MGMensajeriaBundle:MensajesAdmin:nomsg.html.twig', array('mensaje' => $mensaje, 'tipo' => $msjTipo, 'empresas' => $empresas));
            }

            $page_number = ceil(count($allc)/$limit);

            if($page_number == $page)
            {
                $last = true;
            }

            if($this->getUser()->getRolId() == 1)
            {

                $usuarios = $em->getRepository('MGUserBundle:User')
                    ->findAll();
                $empresas = $em->getRepository('MGAdminBundle:Empresa')
                    ->findAll();

            }else
            {
                $empresas = $this->getUser()->getEmpresas();

                if(sizeof($empresas)> 1)
                {
                    foreach($empresas as $emp)
                    {
                        $usuarios[] = $emp->getEmployees();

                    }
                }else
                {
                    $empu = $empresas[0];
                    $usuarios[] = $empu->getEmployees();
                }

            }

            if(!isset($usuarios) || !isset($empresas))
            {
                $mensaje = "Error desconocido.";
                $msjTipo = "error";

                return $this->render('MGMensajeriaBundle:MensajesAdmin:nomsg.html.twig', array('mensaje' => $mensaje, 'tipo' => $msjTipo));
            }

            return $this->render('MGMensajeriaBundle:MensajesAdmin:messagesadminindex.html.twig', array(
                'conversaciones' => $conversaciones,
                'empresas' => $empresas,
                'usuarios' => $usuarios,
                'pages' => $page_number,
                'first' => $first,
                'last' => $last,
                'page' => $page,
            ));

        }else
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

    public function showChatAction($chatId)
    {
        if($this->getUser()->getRolId() == 4)
        {
            $infoCli = $this->get('session')->get('infoClient');
            if($infoCli != null)
            {
                $canReaz = false;
                $em = $this->getDoctrine()->getManager();

                $chat = $em->getRepository('MGMensajeriaBundle:Conversaciones')
                    ->find($chatId);

                if(!$chat)
                {
                    $mensaje = "No tienes ninguna conversación abierta.";
                    $msjTipo = "error";

                    return $this->render('MGRepoBundle:Msg:messages.html.twig', array('mensaje' => $mensaje, 'tipo' => $msjTipo));

                }

                $userChats = $this->getUser()->getConversaciones();

                foreach($userChats as $uc)
                {
                    if($uc->getId() == $chatId)
                    {
                        $canReaz = true;
                    }
                }

                if($canReaz == true)
                {
                    $message = new Mensajes();

                    $form = $this->createForm(new MensajesType(), $message);

                    $noLeidos = $chat->getUnread($this->getUser());

                    if($noLeidos != 0)
                    {
                        foreach($noLeidos as $nol)
                        {
                            $status = $em->getRepository('MGMensajeriaBundle:Status')
                                ->findOneBy(array('statusCode' => 'READED_MESSAGE'));

                            $nol->setLeido(true);
                            $nol->setStatus($status);
                            $nol->setFechaLectura(new \DateTime);
                            $em->merge($nol);
                            $em->flush();

                        }
                    }

                    return $this->render('MGMensajeriaBundle:MensajesCliente:readpanel.html.twig', array(
                        'conversacion' => $chat,
                        'sendMsgForm' => $form->createView()

                    ));

                }else
                {
                    $mensaje = "No tienes permisos para leer esta conversación.";
                    $msjTipo = "error";
                    return $this->render('MGRepoBundle:Msg:messages.html.twig', array('mensaje' => $mensaje, 'tipo' => $msjTipo));

                }
            }else
            {
                return $this->redirect($this->generateUrl('mg_repo_homepage'));
            }
        }
        elseif($this->getUser()->getRolId() < 4)
        {
            $canReaz = false;
            $em = $this->getDoctrine()->getManager();

            $chat = $em->getRepository('MGMensajeriaBundle:Conversaciones')
                ->find($chatId);

            if(!$chat)
            {
                $mensaje = "No tienes ninguna conversación abierta.";
                $msjTipo = "error";

                $empresas = $this->getUser()->getEmpresas();
                if($this->getUser()->getRolId() == 1)
                {
                    $empresas = $em->getRepository('MGAdminBundle:Empresa')
                        ->findAll();
                }
                return $this->render('MGMensajeriaBundle:MensajesAdmin:nomsg.html.twig', array('mensaje' => $mensaje, 'tipo' => $msjTipo, 'empresas' => $empresas));

            }

            $userChats = $this->getUser()->getConversaciones();

            foreach($userChats as $uc)
            {
                if($uc->getId() == $chatId)
                {
                    $canReaz = true;
                }
            }

            $members = $chat->getMembers();

            foreach($members as $m)
            {
                if($m->getId() != $this->getUser()->getId())
                {
                    $cliente = $m;
                }
            }

            if($canReaz == true)
            {
                $message = new Mensajes();

                $form = $this->createForm(new MensajesType(), $message);

                $noLeidos = $chat->getUnread($this->getUser());

                if($noLeidos != 0)
                {
                    foreach($noLeidos as $nol)
                    {
                        $status = $em->getRepository('MGMensajeriaBundle:Status')
                            ->findOneBy(array('statusCode' => 'READED_MESSAGE'));

                        $nol->setLeido(true);
                        $nol->setStatus($status);
                        $nol->setFechaLectura(new \DateTime);
                        $em->merge($nol);
                        $em->flush();

                    }
                }

                return $this->render('MGMensajeriaBundle:MensajesAdmin:readpaneladmin.html.twig', array(
                    'conversacion' => $chat,
                    'cliente' => $cliente,
                    'sendMsgForm' => $form->createView()
                ));

            }else
            {
                $mensaje = "No tienes permisos para leer esta conversación.";
                $msjTipo = "error";
                return $this->render('MGAdminBundle:Msg:messages.html.twig', array('mensaje' => $mensaje, 'tipo' => $msjTipo));

            }

        }

    }

    public function newChatAction(Request $request)
    {
        if($this->getUser()->getRolId() == 4)
        {
            $infoCli = $this->get('session')->get('infoClient');

            if($infoCli != null)
            {
                    $mensaje = new Mensajes();

                    $form = $this->createForm(new NewMensajesType(), $mensaje, array(
                        'empId' => $infoCli['empresa']->getId()
                    ));


                    if($request->isMethod('POST'))
                    {
                        $em = $this->getDoctrine()->getManager();
                        $form->bind($request);
                        if($form->isValid())
                        {
                            $chat = new Conversaciones();

                            /*$servTarget = $mensaje->getServicio();
                            $usersServ = $servTarget->getUsers();

                            $top = 0;
                            foreach ($usersServ as $us)
                            {
                                $numConv = sizeof($us->getConversaciones());
                                if($numConv >= $top)
                                {
                                    $targetUser = $us;
                                }
                            }*/

                            $status = $em->getRepository('MGMensajeriaBundle:Status')
                                ->find(1);

                            $mensaje->setFechaCreacion(new \DateTime());
                            $mensaje->setRemitente($this->getUser());
                            //$mensaje->setDestinatario($targetUser);
                            $mensaje->setLeido(false);
                            $mensaje->setStatus($status);

                            $targetUser = $mensaje->getDestinatario();

                            $chat->setAsunto($mensaje->getAsunto());
                            $chat->addMember($this->getUser());
                            $chat->addMember($targetUser);
                            $chat->setFechaCreacion(new \DateTime());

                            $chat->addMensaje($mensaje);
                            $chat->setFechaLastMessage($mensaje->getFechaCreacion());
                            $mensaje->setConversacion($chat);
                            $this->getUser()->addMensajesAsSender($mensaje);
                            $this->getUser()->addConversacione($chat);
                            $targetUser->addMensajesAsReceiver($mensaje);
                            $targetUser->addConversacione($chat);
                            $em->persist($chat);
                            $em->persist($mensaje);
                            $em->persist($this->getUser());
                            $em->persist($targetUser);
                            $em->flush();

                            ///AGREGADA PERSISTENCIA DE LOS USUARIOS Y COSAS A LAS COLECCIONES.

                            $this->get('session')->getFlashBag()->add('notice', 'Mensaje enviado correctamente');
                            return $this->redirect($this->generateUrl('mg_mensajeria_homepage', array('page' => 1)));
                        }
                    }

                    return $this->render('MGMensajeriaBundle:MensajesCliente:createmsgclient.html.twig', array('createMsg' => $form->createView()));

            }else
            {
                return $this->redirect($this->generateUrl('mg_repo_homepage'));
            }
        }else
        {
            $mensaje = "No tienes permisos para llevar a cabo esta acción.";
            $msjTipo = "error";

            return $this->render('MGRepoBundle:Msg:messages.html.twig', array('mensaje' => $mensaje, 'tipo' => $msjTipo));

        }

    }

    public function newChatAdminAction(Request $request, $empId)
    {
       if($this->getUser()->getRolId() < 4)
       {
           $mensaje = new Mensajes();

           foreach ($this->getUser()->getEmpresas() as $emp ) {
               $us_emp[] = $emp->getId();
           }

           if($this->getUser()->getRolId() <= 2)
           {
               $form = $this->createForm(new NewMensajesAdminType(), $mensaje, array(
                   'empId' =>$empId, 'rolCheck' => 1, 'usId' => $this->getUser()->getId()
               ));
           }else
           {
               $form = $this->createForm(new NewMensajesAdminType(), $mensaje, array(
                   'empId' =>$empId, 'rolCheck' => 0, 'usId' => $this->getUser()->getId()
               ));
           }


           if($request->isMethod('POST'))
           {
               $em = $this->getDoctrine()->getManager();
               $form->bind($request);
               if($form->isValid())
               {
                   $chat = new Conversaciones();

                   $status = $em->getRepository('MGMensajeriaBundle:Status')
                       ->find(1);

                   $mensaje->setFechaCreacion(new \DateTime());
                   $mensaje->setRemitente($this->getUser());
                   $mensaje->setLeido(false);
                   $mensaje->setStatus($status);

                   $targetUser = $mensaje->getDestinatario();

                   $chat->setAsunto($mensaje->getAsunto());
                   $chat->addMember($this->getUser());
                   $chat->addMember($targetUser);
                   $chat->setFechaCreacion(new \DateTime());

                   $chat->addMensaje($mensaje);
                   $chat->setFechaLastMessage($mensaje->getFechaCreacion());
                   $mensaje->setConversacion($chat);
                   $this->getUser()->addMensajesAsSender($mensaje);
                   $this->getUser()->addConversacione($chat);
                   $targetUser->addMensajesAsReceiver($mensaje);
                   $targetUser->addConversacione($chat);
                   $em->persist($chat);
                   $em->persist($mensaje);
                   $em->persist($this->getUser());
                   $em->persist($targetUser);
                   $em->flush();

                   ///AGREGADA PERSISTENCIA DE LOS USUARIOS Y COSAS A LAS COLECCIONES.

                   $this->get('session')->getFlashBag()->add('notice', 'Mensaje enviado correctamente');
                   return $this->redirect($this->generateUrl('mg_mensajeria_homepage', array('page' => 1)));
               }
           }

           return $this->render('MGMensajeriaBundle:MensajesAdmin:createmsgadmin.html.twig', array('createMsg' => $form->createView(), 'empId'=>$empId));
       }else
       {
           $mensaje = "No tienes permisos para leer esta conversación.";
           $msjTipo = "error";

           return $this->render('MGAdminBundle:Msg:messages.html.twig', array('mensaje' => $mensaje, 'tipo' => $msjTipo));

       }
    }

    public function sendMessageAction($chatId, Request $request)
    {

        if($this->getUser()->getRolId() == 4)
        {
            $infoCli = $this->get('session')->get('infoClient');

            if($infoCli != null)
            {
                $canReaz = false;
                $em = $this->getDoctrine()->getManager();

                $chat = $em->getRepository('MGMensajeriaBundle:Conversaciones')
                    ->find($chatId);

                if(!$chat)
                {
                    $mensaje = "No tienes ninguna conversación abierta.";
                    $msjTipo = "error";
                    return $this->render('MGRepoBundle:Msg:messages.html.twig', array('mensaje' => $mensaje, 'tipo' => $msjTipo));

                }

                $userChats = $this->getUser()->getConversaciones();

                foreach($userChats as $uc)
                {
                    if($uc->getId() == $chatId)
                    {
                        $canReaz = true;
                    }
                }

                $members = $chat->getMembers();

                foreach($members as $m)
                {
                    if($m != $this->getUser())
                    {
                        $destinatario = $m;
                    }
                }

                if(!isset($destinatario))
                {
                    $mensaje = "Error de destinatario";
                    $msjTipo = "error";
                    return $this->render('MGRepoBundle:Msg:messages.html.twig', array('mensaje' => $mensaje, 'tipo' => $msjTipo));
                }

                if($canReaz == true)
                {
                    $message = new Mensajes();
                    $form = $this->createForm(new MensajesType(), $message);

                    if($request->isMethod('POST'))
                    {
                        $form->bind($request);

                        if($form->isValid())
                        {

                            $status = $em->getRepository('MGMensajeriaBundle:Status')
                                ->find(1);

                            $message->setConversacion($chat);
                            $message->setDestinatario($destinatario);
                            $message->setFechaCreacion(new \DateTime());
                            $message->setRemitente($this->getUser());
                            $message->setLeido(false);
                            $message->setStatus($status);

                            $em->persist($message);
                            $em->flush();

                            $this->get('session')->getFlashBag()->add('notice', 'Mensaje enviado correctamente');
                            return $this->redirect($this->generateUrl('mg_show_conv', array('chatId'=>$chatId)));
                        }
                    }

                }else
                {
                    $mensaje = "No tienes permisos para leer esta conversación.";
                    $msjTipo = "error";
                    return $this->render('MGRepoBundle:Msg:messages.html.twig', array('mensaje' => $mensaje, 'tipo' => $msjTipo));

                }
                return false;
            }
            else
            {
                return $this->redirect($this->generateUrl('mg_repo_homepage'));
            }

        }elseif($this->getUser()->getRolId() < 4)
        {
            $canReaz = false;
            $em = $this->getDoctrine()->getManager();

            $chat = $em->getRepository('MGMensajeriaBundle:Conversaciones')
                ->find($chatId);

            if(!$chat)
            {
                $mensaje = "No tienes ninguna conversación abierta.";
                $msjTipo = "error";
                $empresas = $this->getUser()->getEmpresas();
                if($this->getUser()->getRolId() == 1)
                {
                    $empresas = $em->getRepository('MGAdminBundle:Empresa')
                        ->findAll();
                }
                return $this->render('MGMensajeriaBundle:MensajesAdmin:nomsg.html.twig', array('mensaje' => $mensaje, 'tipo' => $msjTipo, 'empresas' => $empresas));


            }

            $userChats = $this->getUser()->getConversaciones();

            foreach($userChats as $uc)
            {
                if($uc->getId() == $chatId)
                {
                    $canReaz = true;
                }
            }

            $members = $chat->getMembers();

            foreach($members as $m)
            {
                if($m != $this->getUser())
                {
                    $destinatario = $m;
                }
            }

            if(!isset($destinatario))
            {
                $mensaje = "Error de destinatario";
                $msjTipo = "error";
                return $this->render('MGAdminBundle:Msg:messages.html.twig', array('mensaje' => $mensaje, 'tipo' => $msjTipo));

            }

            if($canReaz == true)
            {
                $message = new Mensajes();
                $form = $this->createForm(new MensajesType(), $message);

                if($request->isMethod('POST'))
                {
                    $form->bind($request);

                    if($form->isValid())
                    {

                        $status = $em->getRepository('MGMensajeriaBundle:Status')
                            ->find(1);

                        $message->setConversacion($chat);
                        $message->setDestinatario($destinatario);
                        $message->setFechaCreacion(new \DateTime());
                        $message->setRemitente($this->getUser());
                        $message->setLeido(false);
                        $message->setStatus($status);

                        $em->persist($message);
                        $em->flush();

                        $this->get('session')->getFlashBag()->add('notice', 'Mensaje enviado correctamente');
                        return $this->redirect($this->generateUrl('mg_show_conv', array('chatId'=>$chatId)));
                    }
                }

            }else
            {
                $mensaje = "No tienes permisos para leer esta conversación.";
                $msjTipo = "error";
                return $this->render('MGAdminBundle:Msg:messages.html.twig', array('mensaje' => $mensaje, 'tipo' => $msjTipo));

            }
            return false;
        }else
        {
            $mensaje = "No tienes permisos para completar la acción.";
            $msjTipo = "error";

            return $this->render('MGAdminBundle:Msg:messages.html.twig', array('mensaje' => $mensaje, 'tipo' => $msjTipo));
        }

    }

    public function changeReceiverAction($chatId, $newRecieverId)
    {
        if($this->getUser()->getRolId() < 4 )
        {
            $em = $this->getDoctrine()->getManager();

            $chat = $em->getRepository('MGMensajeriaBundle:Conversaciones')
                ->find($chatId);

            if(!$chat)
            {
                $mensaje = "No exsite la conversación.";
                $msjTipo = "error";
                return $this->render('MGAdminBundle:Msg:messages.html.twig', array('mensaje' => $mensaje, 'tipo' => $msjTipo));
            }

            $newReciever = $em->getRepository('MGUserBundle:User')
                ->find($newRecieverId);

            if(!$newReciever)
            {
                $mensaje = "No existe el usuario.";
                $msjTipo = "error";
                return $this->render('MGAdminBundle:Msg:messages.html.twig', array('mensaje' => $mensaje, 'tipo' => $msjTipo));
            }

            $canReaz = false;

            $userChats = $this->getUser()->getConversaciones();

            $members = $chat->getMembers();
            $isMember = false;

            foreach($members as $m)
            {
                if($m == $newReciever)
                {
                    $isMember = true;
                }
            }

            foreach($userChats as $uc)
            {
                if($uc->getId() == $chatId)
                {
                    $canReaz = true;
                }
            }

            if($canReaz == true)
            {
                if($isMember == false)
                {
                    $this->getUser()->removeConversacione($chat);
                    $newReciever->addConversacione($chat);

                    $mensajes = $chat->getMensajes();
                    foreach($mensajes as $m)
                    {
                        if($m->getDestinatario() == $this->getUser())
                        {
                            $this->getUser()->removeMensajesAsReceiver($m);
                            $newReciever->addMensajesAsReceiver($m);
                            $m->setDestinatario($newReciever);
                            $em->persist($m);
                            $em->flush();
                        }else if($m->getRemitente() == $this->getUser())
                        {
                            $this->getUser()->removeMensajesAsSender($m);
                            $newReciever->addMensajesAsSender($m);
                            $m->setRemitente($newReciever);
                            $em->persist($m);
                            $em->flush();
                        }
                    }

                    $chat->removeMember($this->getUser());
                    $chat->addMember($newReciever);

                    $em->persist($chat);
                    $em->persist($this->getUser());
                    $em->persist($newReciever);
                    $em->flush();

                    $this->get('session')->getFlashBag()->add('notice', 'Destinatario modificado correctamente');
                    return $this->redirect($this->generateUrl('mg_mensajeria_homepage', array('page' => 1)));
                }else
                {
                    $this->get('session')->getFlashBag()->add('fail', 'El destinatario es el mismo.');
                    return $this->redirect($this->generateUrl('mg_mensajeria_homepage', array('page' => 1)));
                }


            }else
            {
                $mensaje = "No tienes permisos para relizar la acción.";
                $msjTipo = "error";
                return $this->render('MGAdminBundle:Msg:messages.html.twig', array('mensaje' => $mensaje, 'tipo' => $msjTipo));
            }
        }else
        {
            $mensaje = "Acceso denegado";
            $msjTipo = "error";
            return $this->render('MGAdminBundle:Msg:messages.html.twig', array('mensaje' => $mensaje, 'tipo' => $msjTipo));
        }
    }

    public function indexMessageByStatusAction($page = NULL, $statusId, $limit=10)
    {
        $infoCli = $this->get('session')->get('infoClient');

        if($this->getUser()->getRolId() == 4)
        {
            if($infoCli != null)
            {
                $first = true;
                $last = false;

                $em = $this->getDoctrine()->getManager();

                $status = $em->getRepository('MGMensajeriaBundle:Status')
                    ->findAll();

                $session = $this->get('session');
                $session->set('infoMsg',array(
                    'msg_status' => $status
                ));

                if(!isset($page) || $page < 2)
                {
                    $page = 1;
                    $first = false;
                }

                $conversRepo = $em->getRepository('MGMensajeriaBundle:Conversaciones');

                $conversaciones = $conversRepo->findAllWithLimitAndOffsetAndStatus($limit, ($page-1)*$limit, $this->getUser()->getId(), $statusId);
                $allc = $conversRepo->findAllForPaginationStatus($this->getUser()->getId(), $statusId);

                if(!$conversaciones)
                {
                    $mensaje = "No tienes ninguna conversación abierta.";
                    $msjTipo = "error";

                    return $this->render('MGMensajeriaBundle:MensajesCliente:nomsg.html.twig', array('mensaje' => $mensaje, 'tipo' => $msjTipo));

                }

                $page_number = ceil(count($allc)/$limit);

                if($page_number == $page)
                {
                    $last = true;
                }
                return $this->render('MGMensajeriaBundle:MensajesCliente:messagesclientindex.html.twig', array(
                    'conversaciones' => $conversaciones,
                    'pages' => $page_number,
                    'first' => $first,
                    'last' => $last,
                    'page' => $page,
                ));

            }else
            {
                return $this->redirect($this->generateUrl('mg_repo_homepage'));
            }
        }

    }
}

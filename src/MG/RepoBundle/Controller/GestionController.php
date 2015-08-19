<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Merkury
 * Date: 16/07/13
 * Time: 17:12
 * To change this template use File | Settings | File Templates.
 */

namespace MG\RepoBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use MG\RepoBundle\Entity\Gestion;
use MG\RepoBundle\Entity\Archivo;
use MG\RepoBundle\Entity\Comentarios;
use MG\MensajeriaBundle\Entity\Notificaciones;
use MG\RepoBundle\Form\GestionType;
use MG\RepoBundle\Form\ArchivoType;
use MG\RepoBundle\Form\ArchivoMultiType;
use MG\RepoBundle\Form\ComentariosType;

use Symfony\Component\HttpFoundation\Response;

class GestionController extends Controller
{

    public function indexAction()
    {

        if($this->getUser()->getRolId() == 4)
        {
            $cliente = $this->getUser();

            $empresas = $cliente->getEmpresas();

            if(sizeof($empresas) == 1)
            {
                return $this->redirect($this->generateUrl('mg_user_panel', array('empName' => $empresas[0]->getNombre(), 'page' => 1)));
            }else if(!isset($empresas) || !$empresas || sizeof($empresas) < 1)
            {
                $mensaje = "No eres cliente de ningun gestor.";
                $msjTipo = "error";
                return $this->render('MGRepoBundle:Msg:messagenosession.html.twig', array('mensaje' => $mensaje, 'tipo' => $msjTipo));
            }

            foreach($empresas as $emp)
            {
                $serv_pre = $emp->getServicios();
                foreach($serv_pre as $serv)
                {
                    $serv_clients = $serv->getClientes();
                    foreach($serv_clients as $cli)
                    {
                        if($cli == $cliente)
                        {
                            $servicios[] = $serv;

                        }
                    }
                }
            }

            if(!isset($servicios) || !$servicios)
            {
                $servicios = array();
            }

            return $this->render('MGRepoBundle:Default:selectEmp.html.twig', array(
                'empresas' => $empresas,
                'servicios' => $servicios));
        }else
        {
            $mensaje = "No tienes permiso para acceder a este área.";
            $msjTipo = "error";
            return $this->render('MGAdminBundle:Msg:messages.html.twig', array('mensaje' => $mensaje, 'tipo' => $msjTipo));
        }

    }

    public function userPanelAction($empName, $limit = 10, $page = 1)
    {

        $first = true;
        $last = false;

        if(!isset($page) || $page < 2)
        {
            $page = 1;
            $first = false;
        }

        $cliente = $this->getUser();
        $em = $this->getDoctrine()->getManager();

        $pertenece = 0;

        $empresa = $em->getRepository('MGAdminBundle:Empresa')
            ->findOneBy(array('nombre' => $empName));

        $estados = $em->getRepository('MGRepoBundle:Estado')
            ->findAll();

        if(!$empresa)
        {
            $mensaje = "No tienes permisos para acceder a este area.";
            $msjTipo = "error";
            return $this->render('MGRepoBundle:Msg:messagenosession.html.twig', array('mensaje' => $mensaje, 'tipo' => $msjTipo));
        }

        foreach($empresa->getUsers() as $user)
        {
            if($user == $this->getUser())
            {
                $pertenece = 1;
            }
        }

        if(isset($empresa) && $pertenece == 1)
        {
            if($cliente->getRolId() == 4)
            {
                $serv_pre = $empresa->getServicios();

                foreach($serv_pre as $serv)
                {
                    $serv_clients = $serv->getClientes();
                    foreach($serv_clients as $cli)
                    {
                        if($cli == $cliente)
                        {
                            $servicios[] = $serv;
                        }
                    }
                }

                if(!isset($servicios) || !$servicios)
                {
                    $servicios = array();
                }

                $session = $this->get('session');
                $session->set('infoClient',array(
                    'empresa' => $empresa,
                    'servicios' => $servicios,
                    'estados' => $estados
                ));

                $gestRepo = $em->getRepository('MGRepoBundle:Gestion');

                $desc = 'DESC';
                $gestiones = $gestRepo->findAllWithLimitAndOffsetCli($limit, ($page-1)*$limit, $desc, $empresa->getId(), $cliente->getId());
                $allg = $gestRepo->findAllForPaginationCli($empresa->getId(), $cliente->getId());

                if(!$allg || sizeof($allg) < 1 || !isset($gestiones) || !$gestiones)
                {
                    $mensaje = "No tienes ninguna gestión abierta.";
                    $msjTipo = "error";
                    return $this->render('MGRepoBundle:Msg:nogest.html.twig', array('mensaje' => $mensaje, 'tipo' => $msjTipo));
                }

                $page_number = ceil(count($allg)/$limit);

                if($page_number == $page)
                {
                    $last = true;
                }

                return $this->render('MGRepoBundle:Gestiones:managegest.html.twig', array(
                    'gestiones' => $gestiones,
                    'pages' => $page_number,
                    'first' => $first,
                    'last' => $last,
                    'page' => $page,
                ));

            }else
            {

                $mensaje = "Tu nivel de permisos no permite acceder a esta seccion.";
                $msjTipo = "error";
                return $this->render('MGRepoBundle:Msg:messagenosession.html.twig', array('mensaje' => $mensaje, 'tipo' => $msjTipo));
            }

        }else
        {
            $mensaje = "No tienes permisos para acceder a este area.";
            $msjTipo = "error";
            return $this->render('MGRepoBundle:Msg:messagenosession.html.twig', array('mensaje' => $mensaje, 'tipo' => $msjTipo));
        }

    }

    public function userPanelFilteredAction($empName, $limit=10, $page =1, $catId = null)
    {

        $infoCli = $this->get('session')->get('infoClient');

        if($infoCli != null)
        {
            $first = true;
            $last = false;

            if(!isset($page) || $page < 2)
            {
                $page = 1;
                $first = false;
            }

            $cliente = $this->getUser();
            $em = $this->getDoctrine()->getManager();

            $pertenece = 0;

            $empresa = $em->getRepository('MGAdminBundle:Empresa')
                ->findOneBy(array('nombre' => $empName));

            if(!$empresa)
            {
                $mensaje = "No tienes permisos para acceder a este area.";
                $msjTipo = "error";
                return $this->render('MGRepoBundle:Msg:messagenosession.html.twig', array('mensaje' => $mensaje, 'tipo' => $msjTipo));
            }

            foreach($empresa->getUsers() as $user)
            {
                if($user == $this->getUser())
                {
                    $pertenece = 1;
                }
            }

            if(isset($empresa) && $pertenece == 1)
            {
                if($cliente->getRolId() == 4)
                {

                    $gestRepo = $em->getRepository('MGRepoBundle:Gestion');

                    $desc = 'DESC';
                    $gestiones = $gestRepo->findAllWithLimitAndOffsetCatCli($limit, ($page-1)*$limit, $desc, $empresa->getId(), $cliente->getId(), $catId);
                    $allg = $gestRepo->findAllCatForPaginationCli($empresa->getId(), $cliente->getId(), $catId);

                    if(!$allg || sizeof($allg) < 1 || !isset($gestiones) || !$gestiones)
                    {
                        $mensaje = "No tienes ninguna gestión abierta en esta categoría.";
                        $msjTipo = "error";
                        return $this->render('MGRepoBundle:Msg:nogest.html.twig', array('mensaje' => $mensaje, 'tipo' => $msjTipo));
                    }

                    $page_number = ceil(count($allg)/$limit);

                    if($page_number == $page)
                    {
                        $last = true;
                    }

                    return $this->render('MGRepoBundle:Gestiones:managegest.html.twig', array(
                        'gestiones' => $gestiones,
                        'pages' => $page_number,
                        'first' => $first,
                        'last' => $last,
                        'page' => $page,
                    ));

                }else
                {

                    $mensaje = "Tu nivel de permisos no permite acceder a esta seccion.";
                    $msjTipo = "error";
                    return $this->render('MGRepoBundle:Msg:messagenosession.html.twig', array('mensaje' => $mensaje, 'tipo' => $msjTipo));
                }

            }else
            {
                $mensaje = "No tienes permisos para acceder a este area.";
                $msjTipo = "error";
                return $this->render('MGRepoBundle:Msg:messagenosession.html.twig', array('mensaje' => $mensaje, 'tipo' => $msjTipo));
            }
        }
        else
        {
            return $this->redirect($this->generateUrl('mg_repo_homepage'));
        }

    }

    public function userPanelFilteredEstAction($empName, $limit=10, $page =1, $estId = null)
    {

        $infoCli = $this->get('session')->get('infoClient');

        if($infoCli != null)
        {
            $first = true;
            $last = false;

            if(!isset($page) || $page < 2)
            {
                $page = 1;
                $first = false;
            }

            $cliente = $this->getUser();
            $em = $this->getDoctrine()->getManager();

            $pertenece = 0;

            $empresa = $em->getRepository('MGAdminBundle:Empresa')
                ->findOneBy(array('nombre' => $empName));

            if(!$empresa)
            {
                $mensaje = "No tienes permisos para acceder a este area.";
                $msjTipo = "error";
                return $this->render('MGRepoBundle:Msg:messagenosession.html.twig', array('mensaje' => $mensaje, 'tipo' => $msjTipo));
            }

            foreach($empresa->getUsers() as $user)
            {
                if($user == $this->getUser())
                {
                    $pertenece = 1;
                }
            }

            if(isset($empresa) && $pertenece == 1)
            {
                if($cliente->getRolId() == 4)
                {

                    $gestRepo = $em->getRepository('MGRepoBundle:Gestion');

                    $desc = 'DESC';
                    $gestiones = $gestRepo->findAllWithLimitAndOffsetEstCli($limit, ($page-1)*$limit, $desc, $empresa->getId(), $cliente->getId(), $estId);
                    $allg = $gestRepo->findAllEstForPaginationCli($empresa->getId(), $cliente->getId(), $estId);

                    if(!$allg || sizeof($allg) < 1 || !isset($gestiones) || !$gestiones)
                    {
                        $mensaje = "No tienes ninguna gestión con ese estado.";
                        $msjTipo = "error";
                        return $this->render('MGRepoBundle:Msg:nogest.html.twig', array('mensaje' => $mensaje, 'tipo' => $msjTipo));
                    }

                    $page_number = ceil(count($allg)/$limit);

                    if($page_number == $page)
                    {
                        $last = true;
                    }

                    return $this->render('MGRepoBundle:Gestiones:managegest.html.twig', array(
                        'gestiones' => $gestiones,
                        'pages' => $page_number,
                        'first' => $first,
                        'last' => $last,
                        'page' => $page,
                    ));

                }else
                {

                    $mensaje = "Tu nivel de permisos no permite acceder a esta seccion.";
                    $msjTipo = "error";
                    return $this->render('MGRepoBundle:Msg:messagenosession.html.twig', array('mensaje' => $mensaje, 'tipo' => $msjTipo));
                }

            }else
            {
                $mensaje = "No tienes permisos para acceder a este area.";
                $msjTipo = "error";
                return $this->render('MGRepoBundle:Msg:messagenosession.html.twig', array('mensaje' => $mensaje, 'tipo' => $msjTipo));
            }

        }
        else
        {
            return $this->redirect($this->generateUrl('mg_repo_homepage'));
        }
    }

    function newGestionClienteAction(Request $request)
    {
        if($this->getUser()->getRolId() == 4 && sizeof($this->getUser()->getServClientes()) > 0)
        {
            $infoCli = $this->get('session')->get('infoClient');

            if($infoCli == null)
            {
                $mensaje = "Error desconocido.";
                $msjTipo = "error";
                return $this->render('MGRepoBundle:Msg:messagenosession.html.twig', array('mensaje' => $mensaje, 'tipo' => $msjTipo));
            }

            $gestion = new Gestion();

            $form = $this->createForm(new GestionType(), $gestion, array('cliId' => $this->getUser()->getId(), 'empAct' => $infoCli['empresa']->getId()));

            if($request->isMethod('POST'))
            {

                $form->bind($request);
                if($form->isValid())
                {

                    $em = $this->getDoctrine()->getManager();
                    $empresa = $em->getRepository('MGAdminBundle:Empresa')
                        ->find($infoCli['empresa']->getId());

                    if(!$empresa)
                    {
                        $mensaje = "Error desconocido.";
                        $msjTipo = "error";
                        return $this->render('MGRepoBundle:Msg:messages.html.twig', array('mensaje' => $mensaje, 'tipo' => $msjTipo));
                    }

                    $estado = $em->getRepository('MGRepoBundle:Estado')
                        ->find(1);

                    $GestType = $em->getRepository('MGMensajeriaBundle:TipoNotificacion')
                        ->find(2);

                    $gestion->setEmpresa($empresa);
                    $gestion->setCliente($this->getUser());
                    $gestion->setFechaCreacion(new \DateTime());
                    $gestion->setEstado($estado);

                    $em->persist($gestion);

                    $notificacion = new Notificaciones();
                    $notificacion->setGestion($gestion);
                    $notificacion->setTipo($GestType);
                    $notificacion->setEstado($estado);
                    $notificacion->setDestinatarioCliente($gestion->getCliente());
                    $notificacion->setActiveForEmp(true);
                    $notificacion->setActiveForUser(false);

                    $em->persist($notificacion);
                    $em->flush();

                    if(sizeof($this->getUser()->getRepoClient()) == 0 )
                    {
                        $this->get('session')->getFlashBag()->add('notice', 'Gestión introducida correctamente');
                        return $this->redirect($this->generateUrl('mg_user_panel', array('empName' => $infoCli['empresa']->getNombre(), 'page' => 1)));
                    }else
                    {
                        $hazRepos = 0;
                        foreach($this->getUser()->getRepoClient() as $repo)
                        {
                            if($repo->getEmpresaId() == $infoCli['empresa']->getId())
                            {
                                $hazRepos++;
                            }

                        }
                        if($hazRepos > 0)
                        {
                            $this->get('session')->getFlashBag()->add('notice', 'Gestión introducida correctamente');
                            return $this->redirect($this->generateUrl('mg_add_file_gestion', array('gestId' => $gestion->getId())));


                        }else
                        {
                            $this->get('session')->getFlashBag()->add('notice', 'Gestión introducida correctamente');
                            return $this->redirect($this->generateUrl('mg_user_panel', array('empName' =>$infoCli['empresa']->getNombre(), 'page' => 1 )));

                        }
                    }

                }else
                {
                    $this->get('session')->getFlashBag()->add('fail', 'Gestión no introducida');
                    return $this->redirect($this->generateUrl('mg_user_panel', array('empName' =>$infoCli['empresa']->getNombre(), 'page' => 1)));
                }
            }
            return $this->render('MGRepoBundle:Gestiones:newgestion.html.twig',
                array(
                    'newGestionForm' => $form->createView()
                ));

        }else
        {
            $mensaje = "No tienes permiso para acceder a este área.";
            $msjTipo = "error";
            return $this->render('MGRepoBundle:Msg:messages.html.twig', array('mensaje' => $mensaje, 'tipo' => $msjTipo));
        }
    }

    public function addFileAction(Request $request, $gestId)
    {
        $infoCli = $this->get('session')->get('infoClient');

        if($infoCli != null)
        {
            if(isset($gestId) && $gestId != 0)
            {
                $isowner = false;
                $user_gest = $this->getUser()->getGestiones();

                foreach($user_gest as $gest)
                {
                    if($gest->getIdCliente() == $this->getUser()->getId())
                    {
                        $isowner = true;
                    }
                }

                if($isowner == true)
                {
                    $em = $this->getDoctrine()->getManager();

                    $notificacion_activa = $em->getRepository('MGMensajeriaBundle:Notificaciones')
                        ->findOneBy(array('idGestion' => $gestId));

                    if($notificacion_activa->getActiveForUser() == true)
                    {
                        if($notificacion_activa->getEstado()->getEstadoCode() != 'GESTION_ACCION_REQUERIDA' || $notificacion_activa->getEstado()->getEstadoCode() != 'GESTION_NUEVA')
                        {
                            $gestion_dis = $em->getRepository('MGRepoBundle:Gestion')
                                ->find($gestId);

                            $est_readed = $em->getRepository('MGRepoBundle:Estado')
                                ->findOneBy(array('estadoCode' => 'GESTION_RECIBIDA'));

                            $gestion_dis->setEstado($est_readed);

                            $notificacion_activa->setActiveForUser(false);
                            $em->persist($notificacion_activa);
                            $em->flush();
                        }

                    }

                    $repo_temp = $this->getUser()->getRepoClient();

                    foreach($repo_temp as $r)
                    {
                        if($r->getEmpresaId() == $infoCli['empresa']->getId() )
                        {
                            $repos[] = $r;
                        }
                    }

                    if(isset($repos))
                    {
                        if(sizeof($repos) == 1)
                        {

                            $newFile = new Archivo();
                            $newComment = new Comentarios();

                            $form = $this->createForm(new ArchivoType(), $newFile);
                            $form_comm = $this->createForm(new ComentariosType(), $newComment);

                            $userlogged = $this->getUser();
                            $gestion = $em->getRepository('MGRepoBundle:Gestion')
                                ->find($gestId);

                            if($request->isMethod('POST'))
                            {
                                $form->bind($request);
                                $files = $form['file']->getData();

                                if($form->isValid())
                                {
                                    foreach ($files as $f)
                                    {

                                        $tempFile = new Archivo();
                                        $tempFile->setRepo($repos[0]);
                                        $tempFile->setUser($userlogged);
                                        $tempFile->setDelUser(true);
                                        $tempFile->setDelEmpresa(false);
                                        $tempFile->setFile($f);

                                        $infoCli = $this->get('session')->get('infoClient');
                                        $ofFT = $tempFile->upload($infoCli['empresa']->getNombre());

                                        if($ofFT != false)
                                        {
                                            $tempFile->setGestion($gestion);
                                            $em->persist($tempFile);
                                            $em->flush();
                                        }else
                                        {
                                            $this->get('session')->getFlashBag()->add('fail', 'El tipo de archivo que intentas subir no esta permitido');
                                            return $this->redirect($this->generateUrl('mg_manage_cli_gest', array('gestId' =>  $gestId)));
                                        }
                                    }

                                    $addByClient = $em->getRepository('MGRepoBundle:Estado')
                                        ->findOneBy(array('estadoCode' => 'GESTION_FILES_ADDED_CLIENTE'));

                                    $gestion->setEstado($addByClient);

                                    $notificacion = $gestion->getNotificacion();
                                    $notificacion->setEstado($addByClient);
                                    $notificacion->setActiveForUser(false);
                                    $notificacion->setActiveForEmp(true);

                                    $em->persist($gestion);
                                    $em->persist($notificacion);
                                    $em->flush();

                                    $this->get('session')->getFlashBag()->add('notice', 'Fichero/s subidos correctamente');
                                    return $this->redirect($this->generateUrl('mg_manage_cli_gest', array('gestId' =>  $gestId)));
                                }
                            }


                            return $this->render('MGRepoBundle:Gestiones:clientGestmanage.html.twig',
                                array(
                                    'addFilesForm' => $form->createView(),
                                    'newCommForm' => $form_comm->createView(),
                                    'gestId' => $gestId,
                                    'gestion' => $gestion
                                ));

                        }elseif(sizeof($repos) > 1)
                        {
                            $newFile = new Archivo();
                            $newComment = new Comentarios();
                            $em = $this->getDoctrine()->getManager();

                            $gestion = $em->getRepository('MGRepoBundle:Gestion')
                                ->find($gestId);

                            $form = $this->createForm(new ArchivoMultiType(), $newFile, array('userId' => $this->getUser()->getId(), 'empId' => $gestion->getIdEmpresa() ));
                            $form_comm = $this->createForm(new ComentariosType(), $newComment);

                            $userlogged = $this->getUser();
                            if($request->isMethod('POST'))
                            {
                                $form->bind($request);
                                $files = $form['file']->getData();
                                if($form->isValid())
                                {

                                    foreach ($files as $f) {
                                        $tempFile = new Archivo();
                                        $tempFile->setRepo($newFile->getRepo());
                                        $tempFile->setUser($userlogged);
                                        $tempFile->setDelUser(true);
                                        $tempFile->setDelEmpresa(false);
                                        $tempFile->setFile($f);
                                        $infoCli = $this->get('session')->get('infoClient');
                                        $ofFT = $tempFile->upload($infoCli['empresa']->getNombre());

                                        if($ofFT != false)
                                        {
                                            $tempFile->setGestion($gestion);
                                            $em->persist($tempFile);
                                            $em->flush();
                                        }else
                                        {
                                            $this->get('session')->getFlashBag()->add('fail', 'El tipo de archivo que intentas subir no esta permitido');
                                            return $this->redirect($this->generateUrl('mg_manage_cli_gest', array('gestId' =>  $gestId)));
                                        }
                                    }

                                    $addByClient = $em->getRepository('MGRepoBundle:Estado')
                                        ->findOneBy(array('estadoCode' => 'GESTION_FILES_ADDED_CLIENTE'));

                                    $gestion->setEstado($addByClient);

                                    $notificacion = $gestion->getNotificacion();
                                    $notificacion->setEstado($addByClient);
                                    $notificacion->setActiveForUser(false);
                                    $notificacion->setActiveForEmp(true);

                                    $em->persist($gestion);
                                    $em->persist($notificacion);
                                    $em->flush();

                                    $this->get('session')->getFlashBag()->add('notice', 'Fichero/s subidos correctamente');
                                    return $this->redirect($this->generateUrl('mg_manage_cli_gest', array('gestId' =>  $gestId)));

                                }

                            }

                            return $this->render('MGRepoBundle:Gestiones:clientGestmanage.html.twig',
                                array(
                                    'addFilesForm' => $form->createView(),
                                    'newCommForm' => $form_comm->createView(),
                                    'gestId' => $gestId,
                                    'gestion' => $gestion
                                ));
                        }
                        else
                        {
                            $mensaje = "No tienes acceso a ningún repositorio.";
                            $msjTipo = "error";
                            return $this->render('MGRepoBundle:Msg:messages.html.twig', array('mensaje' => $mensaje, 'tipo' => $msjTipo));
                        }
                    }else
                    {

                        $newComment = new Comentarios();

                        $form_comm = $this->createForm(new ComentariosType(), $newComment);


                        $em = $this->getDoctrine()->getManager();
                        $gestion = $em->getRepository('MGRepoBundle:Gestion')
                            ->find($gestId);

                        return $this->render('MGRepoBundle:Gestiones:clientGestmanage.html.twig',
                            array(
                                'newCommForm' => $form_comm->createView(),
                                'gestId' => $gestId,
                                'gestion' => $gestion
                            ));
                    }

                }else
                {
                    $mensaje = "No tienes permiso para acceder a esta gestión.";
                    $msjTipo = "error";
                    return $this->render('MGRepoBundle:Msg:messages.html.twig', array('mensaje' => $mensaje, 'tipo' => $msjTipo));
                }

            }else
            {
                $mensaje = "No tienes permiso para acceder a este área.";
                $msjTipo = "error";
                return $this->render('MGRepoBundle:Msg:messages.html.twig', array('mensaje' => $mensaje, 'tipo' => $msjTipo));
            }
        }else
        {
            return $this->redirect($this->generateUrl('mg_repo_homepage'));
        }

    }

    public function addCommentAction($gestId, Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $gestion = $em->getRepository('MGRepoBundle:Gestion')
            ->find($gestId);

        if(!$gestion)
        {
            $this->get('session')->getFlashBag()->add('fail', 'No hay gestiones actualmente registradas');
            return $this->redirect($this->generateUrl('mg_admin_homepage'));
        }

        if($this->getUser()->getRolId() == 4 && $gestion->getIdCliente() == $this->getUser()->getId())
        {

            $comentario = new Comentarios();

            $form = $this->createForm(new ComentariosType(), $comentario);

            if($request->isMethod('POST'))
            {
                $form->bind($request);
                if($form->isValid())
                {
                    $comentario->setGestion($gestion);
                    $comentario->setAutor($this->getUser());
                    $comentario->setFecha(new \DateTime());
                    $statusNew = $em->getRepository('MGMensajeriaBundle:Status')
                        ->find(1);

                    $comentario->setStatus($statusNew);

                    if($this->getUser()->getRolId() <=3)
                    {
                        $comentario->setTipoAutor('1');
                    }else
                    {
                        $comentario->setTipoAutor('2');
                    }

                    $em->persist($comentario);
                    $em->flush();
                    $this->get('session')->getFlashBag()->add('notice', 'Comentario agregado correctamente');
                    return $this->redirect($this->generateUrl('mg_manage_cli_gest', array('gestId' => $gestion->getId())));

                }
            }

            //return $this->redirect($this->generateUrl('mg_manage_gest', array('gestId' => $gestId)));

            /*return $this->render('MGAdminBundle:Gestiones:addnewgestcomm.html.twig',
                array('newGestCommForm' => $form->createView(),
                      'gestion' => $gestion,
                      'comentarios' => $comentarios
                     ));*/


        }else
        {
            $mensaje = "No tienes permiso para completar esta acción.";
            $msjTipo = "error";
            return $this->render('MGRepoBundle:Msg:messages.html.twig', array('mensaje' => $mensaje, 'tipo' => $msjTipo));
        }

    }

    public function downloadServAction($fileId)
    {
        $userlogged = $this->getUser();
        $em = $this->getDoctrine()->getManager();

        $file = $em->getRepository('MGRepoBundle:Archivo')
            ->find($fileId);

        if(!$file)
        {
           if($this->getUser()->getRolId() <=3)
           {
               $mensaje = "No existe el archivo que intentas descargar.";
               $msjTipo = "error";
               return $this->render('MGAdminBundle:Msg:messages.html.twig', array('mensaje' => $mensaje, 'tipo' => $msjTipo));
           }else
           {
               $mensaje = "No existe el archivo que intentas descargar.";
               $msjTipo = "error";
               return $this->render('MGRepoBundle:Msg:messages.html.twig', array('mensaje' => $mensaje, 'tipo' => $msjTipo));
           }

        }else
        {
            if(($userlogged->getId() == $file->getIdUsuario()) || $userlogged->getRolId() == 1)
            {
                $publicFile = $file->servDownload();

                $response = new Response();
                $response->headers->set('Content-type', 'application/octect-stream');
                $response->headers->set('Content-Disposition', sprintf('attachment; filename="%s"', $file->getOriginalName() ));
                $response->headers->set('Content-Length', filesize($publicFile));
                $response->headers->set('Content-Transfer-Encoding', 'binary');
                //$response->setContent(file_get_contents($publicFile))
                if($response->setContent(file_get_contents($publicFile)))
                {
                    unlink($publicFile);
                }
                return $response;



            }elseif(($userlogged->getRolId() == 3) || $userlogged->getRolId() == 2)
            {
                $pertenece = false;
                $empresas_gest = $userlogged->getEmpresas();
                $empresa_file = $file->getRepo()->getEmpresa()->getId();


                if(sizeof($empresas_gest) > 1)
                {
                    foreach($empresas_gest as $emp)
                    {
                        if($emp->getId() == $empresa_file)
                        {
                            $pertenece = true;
                        }
                    }
                }else
                {
                    if($empresas_gest[0]->getId() == $empresa_file)
                    {
                        $pertenece = true;
                    }
                }

                /***************FIN COMPROBACION EMPRESA********************/
                $hasService = false;
                if($pertenece == true)
                {
                    if($userlogged->getRolId() == 3)
                    {
                        $users_servicio = $file->getGestion()->getCategoria()->getServicio()->getUsers();

                        foreach($users_servicio as $us_serv)
                        {
                            if($us_serv->getId() == $userlogged->getId())
                            {
                                $hasService = true;

                            }else
                            {

                                $mensaje = "No tienes permiso para descargar este fichero.";
                                $msjTipo = "error";
                                return $this->render('MGAdminBundle:Msg:messages.html.twig', array('mensaje' => $mensaje, 'tipo' => $msjTipo));
                            }
                        }

                        $hasRepo = false;
                        if($hasService == true)
                        {
                            $users_repo= $file->getRepo()->getUsers();

                            foreach($users_repo as $us_repo)
                            {
                                if($us_repo->getId() == $userlogged->getId())
                                {
                                    $hasRepo = true;
                                }else
                                {
                                    $mensaje = "No tienes permiso para descargar este fichero.";
                                    $msjTipo = "error";
                                    return $this->render('MGAdminBundle:Msg:messages.html.twig', array('mensaje' => $mensaje, 'tipo' => $msjTipo));
                                }
                            }
                        }else
                        {
                            $mensaje = "No tienes permiso para descargar este fichero.";
                            $msjTipo = "error";
                            return $this->render('MGAdminBundle:Msg:messages.html.twig', array('mensaje' => $mensaje, 'tipo' => $msjTipo));
                        }

                        if($hasService == true && $hasRepo == true)
                        {
                            $publicFile = $file->servDownload();

                            /************COMPROBAR que cojones pasa!!!!**********************/

                            $response = new Response();
                            $response->headers->set('Content-type', 'application/octect-stream');
                            $response->headers->set('Content-Disposition', sprintf('attachment; filename="%s"', $file->getOriginalName() ));
                            $response->headers->set('Content-Length', filesize($publicFile));
                            $response->headers->set('Content-Transfer-Encoding', 'binary');
                            //$response->setContent(file_get_contents($publicFile));
                            if($response->setContent(file_get_contents($publicFile)))
                            {
                                unlink($publicFile);
                            }
                            return $response;
                        }else
                        {
                            $mensaje = "No tienes permiso para descargar este fichero.";
                            $msjTipo = "error";
                            return $this->render('MGAdminBundle:Msg:messages.html.twig', array('mensaje' => $mensaje, 'tipo' => $msjTipo));
                        }

                    }elseif ($userlogged->getRolId() == 2)
                    {
                        $publicFile = $file->servDownload();

                        $response = new Response();
                        $response->headers->set('Content-type', 'application/octect-stream');
                        $response->headers->set('Content-Disposition', sprintf('attachment; filename="%s"', $file->getOriginalName() ));
                        $response->headers->set('Content-Length', filesize($publicFile));
                        $response->headers->set('Content-Transfer-Encoding', 'binary');
                        //$response->setContent(file_get_contents($publicFile));
                        if($response->setContent(file_get_contents($publicFile)))
                        {
                            unlink($publicFile);
                        }
                        return $response;
                    }else
                    {
                        $mensaje = "No tienes permiso para descargar este fichero.";
                        $msjTipo = "error";
                        return $this->render('MGAdminBundle:Msg:messages.html.twig', array('mensaje' => $mensaje, 'tipo' => $msjTipo));
                    }
                }else
                {
                    $mensaje = "No tienes permiso para descargar este fichero.";
                    $msjTipo = "error";
                    return $this->render('MGAdminBundle:Msg:messages.html.twig', array('mensaje' => $mensaje, 'tipo' => $msjTipo));
                }

            }else
            {
                $mensaje = "No tienes permiso para descargar este fichero.";
                $msjTipo = "error";
                return $this->render('MGRepoBundle:Msg:messages.html.twig', array('mensaje' => $mensaje, 'tipo' => $msjTipo));
            }
        }

    }

    public function closeGestCliAction($gestId)
    {

        $em = $this->getDoctrine()->getManager();
        $gestion = $em->getRepository('MGRepoBundle:Gestion')
            ->find($gestId);

        if(isset($gestion))
        {
            if($gestion->isUnsolved() == true)
            {
                if($gestion->getIdCliente() == $this->getUser()->getId())
                {

                    $niceSolved = $em->getRepository('MGRepoBundle:Estado')
                        ->find(8);

                    $gestion->setEstado($niceSolved);

                    $notificacion = $gestion->getNotificacion();
                    $notificacion->setEstado($niceSolved);
                    $notificacion->setActiveForUser(false);
                    $notificacion->setActiveForEmp(true);

                    $em->merge($gestion);
                    $em->merge($notificacion);
                    $em->flush();
                    $this->get('session')->getFlashBag()->add('notice', 'Gestion cerrada como resuelta');
                    return $this->redirect($this->generateUrl('mg_user_panel', array('empName' => $gestion->getEmpresa()->getNombre(), 'page' => 1)));
                }else
                {
                    $this->get('session')->getFlashBag()->add('fail', 'No tienes permisos para realizar la acción');
                    return $this->redirect($this->generateUrl('mg_user_panel', array('empName' => $gestion->getEmpresa()->getNombre(), 'page' => 1)));
                }
            }else
            {
                $this->get('session')->getFlahsBag()->add('fail', 'La gestión ya se encuentra resuelta');
                return $this->redirect($this->generateUrl('mg_user_panel', array('empName' => $gestion->getEmpresa()->getNombre(), 'page' => 1)));
            }
        }else
        {
            $this->get('session')->getFlashBag()->add('fail', 'La gestión no existe');
            return $this->redirect($this->generateUrl('mg_user_panel', array('empName' => $gestion->getEmpresa()->getNombre(), 'page' => 1)));
        }
    }

    public function deleteFileAction($fileId)
    {
        $userlogged = $this->getUser();
        $em = $this->getDoctrine()->getManager();

        $file = $em->getRepository('MGRepoBundle:Archivo')
            ->find($fileId);

        if(!$file)
        {
            if($this->getUser()->getRolId() <=3)
            {
                $mensaje = "No existe el archivo que intentas eliminar.";
                $msjTipo = "error";
                return $this->render('MGAdminBundle:Msg:messages.html.twig', array('mensaje' => $mensaje, 'tipo' => $msjTipo));
            }else
            {
                $mensaje = "No existe el archivo que intentas eliminar.";
                $msjTipo = "error";
                return $this->render('MGRepoBundle:Msg:messages.html.twig', array('mensaje' => $mensaje, 'tipo' => $msjTipo));
            }

        }else
        {
            if(($userlogged->getId() == $file->getIdUsuario()) || $userlogged->getRolId() == 1)
            {
                if($userlogged->getRol()->getRolName() == 'ROLE_SUPER_ADMIN')
                {
                    $gestId = $file->getIdGestion();

                    unlink($file->getFilePath());
                    $em->remove($file);
                    $em->flush();

                    $this->get('session')->getFlashBag()->add('notice', 'Archivo Eliminado');
                    return $this->redirect($this->generateUrl('mg_manage_gest', array('gestId' => $gestId)));
                }
                else
                {
                    if($file->getDelUser() == true)
                    {
                        $gestId = $file->getIdGestion();

                        unlink($file->getFilePath());
                        $em->remove($file);
                        $em->flush();

                        $this->get('session')->getFlashBag()->add('notice', 'Archivo Eliminado');
                        return $this->redirect($this->generateUrl('mg_manage_cli_gest', array('gestId' => $gestId)));
                    }else
                    {
                        $mensaje = "No tienes permiso para eliminar este fichero.";
                        $msjTipo = "error";
                        return $this->render('MGRepoBundle:Msg:messages.html.twig', array('mensaje' => $mensaje, 'tipo' => $msjTipo));
                    }
                }

            }elseif(($userlogged->getRolId() == 3) || $userlogged->getRolId() == 2)
            {
                $pertenece = false;
                $empresas_gest = $userlogged->getEmpresas();
                $empresa_file = $file->getRepo()->getEmpresa()->getId();


                if(sizeof($empresas_gest) > 1)
                {
                    foreach($empresas_gest as $emp)
                    {
                        if($emp->getId() == $empresa_file)
                        {
                            $pertenece = true;
                        }
                    }
                }else
                {
                    if($empresas_gest[0]->getId() == $empresa_file)
                    {
                        $pertenece = true;
                    }
                }

                /***************FIN COMPROBACION EMPRESA********************/
                $hasService = false;
                if($pertenece == true)
                {
                    if($userlogged->getRolId() == 3)
                    {
                        $users_servicio = $file->getGestion()->getCategoria()->getServicio()->getUsers();

                        foreach($users_servicio as $us_serv)
                        {
                            if($us_serv->getId() == $userlogged->getId())
                            {
                                $hasService = true;

                            }else
                            {
                                $mensaje = "No tienes permiso para eliminar este fichero.";
                                $msjTipo = "error";
                                return $this->render('MGAdminBundle:Msg:messages.html.twig', array('mensaje' => $mensaje, 'tipo' => $msjTipo));
                            }
                        }

                        $hasRepo = false;
                        if($hasService == true)
                        {
                            $users_repo= $file->getRepo()->getUsers();

                            foreach($users_repo as $us_repo)
                            {
                                if($us_repo->getId() == $userlogged->getId())
                                {
                                    $hasRepo = true;
                                }else
                                {
                                    $mensaje = "No tienes permiso para eliminar este fichero.";
                                    $msjTipo = "error";
                                    return $this->render('MGAdminBundle:Msg:messages.html.twig', array('mensaje' => $mensaje, 'tipo' => $msjTipo));
                                }
                            }
                        }else
                        {
                            $mensaje = "No tienes permiso para eliminar este fichero.";
                            $msjTipo = "error";
                            return $this->render('MGAdminBundle:Msg:messages.html.twig', array('mensaje' => $mensaje, 'tipo' => $msjTipo));
                        }

                        if($hasService == true && $hasRepo == true)
                        {
                            if($file->getDelEmpresa() == true)
                            {
                                $gestId = $file->getIdGestion();

                                unlink($file->getFilePath());
                                $em->remove($file);
                                $em->flush();

                                $this->get('session')->getFlashBag()->add('notice', 'Archivo Eliminado');
                                return $this->redirect($this->generateUrl('mg_manage_gest', array('gestId' => $gestId)));
                            }else
                            {
                                $mensaje = "No tienes permiso para eliminar este fichero.";
                                $msjTipo = "error";
                                return $this->render('MGAdminBundle:Msg:messages.html.twig', array('mensaje' => $mensaje, 'tipo' => $msjTipo));
                            }

                        }else
                        {
                            $mensaje = "No tienes permiso para eliminar este fichero.";
                            $msjTipo = "error";
                            return $this->render('MGAdminBundle:Msg:messages.html.twig', array('mensaje' => $mensaje, 'tipo' => $msjTipo));
                        }

                    }elseif ($userlogged->getRolId() == 2)
                    {
                        if($file->getDelEmpresa() == true)
                        {
                            $gestId = $file->getIdGestion();

                            unlink($file->getFilePath());
                            $em->remove($file);
                            $em->flush();

                            $this->get('session')->getFlashBag()->add('notice', 'Archivo Eliminado');
                            return $this->redirect($this->generateUrl('mg_manage_gest', array('gestId' => $gestId)));
                        }else
                        {
                            $mensaje = "No tienes permiso para eliminar este fichero.";
                            $msjTipo = "error";
                            return $this->render('MGAdminBundle:Msg:messages.html.twig', array('mensaje' => $mensaje, 'tipo' => $msjTipo));
                        }

                    }else
                    {
                        $mensaje = "No tienes permiso para eliminar este fichero.";
                        $msjTipo = "error";
                        return $this->render('MGAdminBundle:Msg:messages.html.twig', array('mensaje' => $mensaje, 'tipo' => $msjTipo));
                    }
                }else
                {
                    $mensaje = "No tienes permiso para eliminar este fichero.";
                    $msjTipo = "error";
                    return $this->render('MGAdminBundle:Msg:messages.html.twig', array('mensaje' => $mensaje, 'tipo' => $msjTipo));
                }

            }else
            {
                $mensaje = "No tienes permiso para eliminar este fichero.";
                $msjTipo = "error";
                return $this->render('MGRepoBundle:Msg:messages.html.twig', array('mensaje' => $mensaje, 'tipo' => $msjTipo));
            }
        }

    }

    public function deleteRepoFileAction($fileId)
    {
        $userlogged = $this->getUser();
        $em = $this->getDoctrine()->getManager();

        $file = $em->getRepository('MGRepoBundle:Archivo')
            ->find($fileId);

        if(!$file)
        {
                $mensaje = "No existe el archivo que intentas eliminar.";
                $msjTipo = "error";
                return $this->render('MGRepoBundle:Msg:messages.html.twig', array('mensaje' => $mensaje, 'tipo' => $msjTipo));

        }else
        {
            if($userlogged->getId() == $file->getIdUsuario())
            {
                    if($file->getDelUser() == true)
                    {
                        unlink($file->getFilePath());
                        $em->remove($file);
                        $em->flush();

                        $this->get('session')->getFlashBag()->add('notice', 'Archivo Eliminado');
                        return $this->redirect($this->generateUrl('mg_cli_show_files', array('page' => 1)));
                    }else
                    {
                        $mensaje = "No tienes permiso para eliminar este fichero.";
                        $msjTipo = "error";
                        return $this->render('MGRepoBundle:Msg:messages.html.twig', array('mensaje' => $mensaje, 'tipo' => $msjTipo));
                    }
            }elseif($userlogged->getRol()->getRolName('ROLE_SUPER_ADMIN'))
            {
                $repoId = $file->getRepoId();
                unlink($file->getFilePath());
                $em->remove($file);
                $em->flush();

                $this->get('session')->getFlashBag()->add('notice', 'Archivo Eliminado');
                return $this->redirect($this->generateUrl('mg_repo_show_all_files', array('repoId' => $repoId, 'page' => 1)));
            }
            else
            {
                $mensaje = "No tienes permiso para eliminar este fichero.";
                $msjTipo = "error";
                return $this->render('MGRepoBundle:Msg:messages.html.twig', array('mensaje' => $mensaje, 'tipo' => $msjTipo));
            }
        }

    }
 /*  public function testAction()
    {

        $infoCli = $this->get('session')->get('infoClient');
        var_dump($infoCli);

        return $this->render('MGRepoBundle:Default:test.html.twig');

    }*/

}

<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Merkury
 * Date: 24/07/13
 * Time: 12:31
 * To change this template use File | Settings | File Templates.
 */
namespace MG\AdminBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use MG\RepoBundle\Entity\Gestion;
use MG\RepoBundle\Entity\Comentarios;
use MG\RepoBundle\Entity\Archivo;
use MG\MensajeriaBundle\Entity\Notificaciones;

use MG\RepoBundle\Form\GestionAdminType;
use MG\RepoBundle\Form\ComentariosType;
use MG\RepoBundle\Form\ArchivoType;



class AdminGestionController extends Controller
{

    public function indexAction($page = NULL, $limit = 10, $ord = 0)
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

            $gestRepos = $this->getDoctrine()->getRepository('MGRepoBundle:Gestion');

            $allg = $gestRepos->findAll();

            if($ord == 0)
            {
                $desc = 'DESC';
                $gestiones = $gestRepos->findAllWithLimitAndOffset($limit, ($page-1)*$limit, $desc);
            }else
            {
                $asc = 'ASC';
                $gestiones = $gestRepos->findAllWithLimitAndOffset($limit, ($page-1)*$limit, $asc);
            }

            if(!$gestiones )
            {
                $this->get('session')->getFlashBag()->add('fail', 'No hay gestiones actualmente registradas');
                return $this->redirect($this->generateUrl('mg_admin_homepage'));
            }

            $em = $this->getDoctrine()->getManager();

            $empresas = $em->getRepository('MGAdminBundle:Empresa')
                ->findAll();

            $estados = $em->getRepository('MGRepoBundle:Estado')
                ->findAll();

            $session = $this->get('session');
            $session->set('generalInfo',array(
                'estado' => $estados
            ));

            if(!$empresas)
            {
                $this->get('session')->getFlashBag()->add('fail', 'No existen empresas disponibles');
                return $this->redirect($this->generateUrl('mg_repoadmin_homepage', array('page'=> 1)));
            }

            $page_number = ceil(count($allg)/$limit);

            if($page_number == $page)
            {
                $last = true;
            }

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

            return $this->render('MGAdminBundle:Gestiones:gestionindex.html.twig', array(
                'gestiones' => $gestiones,
                'pages' => $page_number,
                'first' => $first,
                'last' => $last,
                'page' => $page,
                'empresas' => $empresas,
                'ord' => $ord,
                'clientes' => $clientes

            ));
        }else
        {
            $empresas = $this->getUser()->getEmpresas();
            if(sizeof($empresas) == 1)
            {
                return $this->redirect($this->generateUrl('mg_gest_by_empresa', array('empId' => $empresas[0]->getId(),'page'=> 1)));
            }
            else
            {
                return $this->render('MGAdminBundle:Gestiones:selectEmpGest.html.twig', array('empresas' => $empresas));
            }
        }
    }

    public function listGestEmpresaAction($empId, $limit = 10, $page = 1)
    {
        $tieneEmpresa = 0;
        foreach ($this->getUser()->getEmpresas() as $empresa) {
            if($empId == $empresa->getId())
            {
                $tieneEmpresa = 1;
            }
        }

        if(($tieneEmpresa == 1 && $this->getUser()->getRolId() == 2) || $this->getUser()->getRolId() == 1)
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

            $estados = $em->getRepository('MGRepoBundle:Estado')
                ->findAll();

            $session = $this->get('session');
            $session->set('generalInfo',array(
                'estado' => $estados
            ));

            if(!$empresa)
            {
                $this->get('session')->getFlashBag()->add('fail', 'No existe la empresa seleccionada');
                return $this->redirect($this->generateUrl('mg_gest_homepage', array('page' => 1, 'ord'=>0)));
            }

            if($this->getUser()->getRolId() == 1)
            {
                $alle = $em->getRepository('MGAdminBundle:Empresa')
                    ->findAll();
            }else
            {
                $alle = $this->getUser()->getEmpresas();
            }

            if(!$alle)
            {
                $this->get('session')->getFlashBag()->add('fail', 'No existen empresas');
                return $this->redirect($this->generateUrl('mg_gest_homepage', array('page' => 1, 'ord'=>0)));
            }

            $allg = $em->getRepository('MGRepoBundle:Gestion')
                ->findBy(array('idEmpresa' => $empId), array('fechaCreacion' => 'DESC'));

            if(!isset($allg) || sizeof($allg) < 1)
            {
                $this->get('session')->getFlashBag()->add('fail', 'No existen gestiones para esa empresa');
                return $this->redirect($this->generateUrl('mg_admin_homepage', array('page' => 1, 'ord'=>0)));
            }

            for($i = $offset; $i < $limit + $offset; $i++)
            {
                if(isset($allg[$i]))
                {
                    $gestiones[] = $allg[$i];
                }
            }

            if(!isset($gestiones) || sizeof($allg) < 1)
            {
                $this->get('session')->getFlashBag()->add('fail', 'No existen gestiones para esa empresa');
                return $this->redirect($this->generateUrl('mg_admin_homepage', array('page' => 1, 'ord'=>0)));
            }

            $page_number = ceil(count($allg)/$limit);

            if($page_number == $page)
            {
                $last = true;
            }

            $clientes = $empresa->getUsersByRol('ROLE_CLIENTE');

            if(!isset($clientes) || sizeof($clientes) < 1 || $clientes == false)
            {
                $clientes = array();
            }

            $nombre_empresa = $empresa->getNombre();

            return $this->render('MGAdminBundle:Gestiones:gestionindexfiltered.html.twig', array(
                'gestiones' => $gestiones,
                'nombre_emp' => $nombre_empresa,
                'pages' => $page_number,
                'first' => $first,
                'last' => $last,
                'page' => $page,
                'empId' => $empId,
                'empresas' => $alle,
                'clientes' => $clientes
            ));
        }
        elseif($tieneEmpresa == 1 && $this->getUser()->getRolId() == 3)
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

            $estados = $em->getRepository('MGRepoBundle:Estado')
                ->findAll();

            $session = $this->get('session');
            $session->set('generalInfo',array(
                'estado' => $estados
            ));

            if(!$empresa)
            {
                $mensaje = "No existen la empresa seleccionada.";
                $msjTipo = "error";
                return $this->render('MGAdminBundle:Msg:messages.html.twig', array('mensaje' => $mensaje, 'tipo' => $msjTipo));
            }

            $alle = $this->getUser()->getEmpresas();

            if(!$alle)
            {
                $mensaje = "No existen empresas.";
                $msjTipo = "error";
                return $this->render('MGAdminBundle:Msg:messages.html.twig', array('mensaje' => $mensaje, 'tipo' => $msjTipo));
            }


            $allg = $empresa->getGestiones();

            $userServs = $this->getUser()->getServUsers();

            foreach($userServs as $serv)
            {
                $userCats[] = $serv->getCategoria();
            }


            if(sizeof($allg) < 1)
            {
                $mensaje = "No existen gestiones para la empresa seleccionada.";
                $msjTipo = "error";
                return $this->render('MGAdminBundle:Msg:messages.html.twig', array('mensaje' => $mensaje, 'tipo' => $msjTipo));
            }

            for($i = $offset; $i < $limit + $offset; $i++)
            {
                if(isset($allg[$i]))
                {
                   if(isset($userCats))
                   {
                       foreach($userCats as $categorias)
                       {
                           foreach ($categorias as $cat) {
                               if($allg[$i]->getIdCategoria() == $cat->getId())
                               {
                                   $gestiones[] = $allg[$i];
                               }
                           }

                       }
                   }
                }
            }

            if(!isset($gestiones))
            {
                $mensaje = "No existen gestiones para las que estes autorizado.";
                $msjTipo = "error";
                return $this->render('MGAdminBundle:Msg:messages.html.twig', array('mensaje' => $mensaje, 'tipo' => $msjTipo));
            }

            $page_number = ceil(count($allg)/$limit);

            if($page_number == $page)
            {
                $last = true;
            }

            $pre_clientes = $empresa->getUsersByRol('ROLE_CLIENTE');

            if(!isset($pre_clientes) || sizeof($pre_clientes) < 1 || $pre_clientes == false)
            {
                $clientes = array();
            }else
            {
                foreach($pre_clientes as $c)
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

            $nombre_empresa = $empresa->getNombre();

            return $this->render('MGAdminBundle:Gestiones:gestionindexfiltered.html.twig', array(
                'gestiones' => $gestiones,
                'nombre_emp' => $nombre_empresa,
                'pages' => $page_number,
                'first' => $first,
                'last' => $last,
                'page' => $page,
                'empId' => $empId,
                'empresas' => $alle,
                'clientes' => $clientes
            ));
        }
        else
        {
            $mensaje = "Acceso no permitido";
            $msjTipo = "error";
            return $this->render('MGAdminBundle:Msg:messages.html.twig', array('mensaje' => $mensaje, 'tipo' => $msjTipo));
        }

    }

    public function listGestEstadoEmpresaAction($empId, $limit = 10, $page = 1, $estId = 0, $ord = 0)
    {
        $tieneEmpresa = 0;
        foreach ($this->getUser()->getEmpresas() as $empresa) {
            if($empId == $empresa->getId())
            {
                $tieneEmpresa = 1;
            }
        }

        if(($tieneEmpresa == 1 && $this->getUser()->getRolId() == 2) || $this->getUser()->getRolId() == 1)
        {
            $first = true;
            $last = false;

            if(!isset($page) || $page < 2)
            {
                $page = 1;
                $first = false;
            }

            $repoGest = $this->getDoctrine()->getRepository('MGRepoBundle:Gestion');


            if($ord == 0)
            {
                $desc = 'DESC';
                $gestiones = $repoGest->findAllWithLimitEstAndOffset($limit, ($page-1)*$limit, $desc, $estId, $empId);
                $allg = $repoGest->findAllForPagination($desc, $estId, $empId);
            }else
            {
                $asc = 'ASC';
                $gestiones = $repoGest->findAllWithLimitEstAndOffset($limit, ($page-1)*$limit, $asc, $estId, $empId);
                $allg = $repoGest->findAllForPagination($asc, $estId, $empId);
            }


            if(!$gestiones )
            {
                $this->get('session')->getFlashBag()->add('fail', 'No hay gestiones actualmente registradas con ese estado');
                return $this->redirect($this->generateUrl('mg_gest_homepage', array('page' => 1, 'ord'=>0)));
            }

            $em = $this->getDoctrine()->getManager();

            $empresas = $em->getRepository('MGAdminBundle:Empresa')
                ->findAll();

            if(!$empresas)
            {
                $this->get('session')->getFlashBag()->add('fail', 'No existen la empresa seleccionada');
                return $this->redirect($this->generateUrl('mg_gest_homepage', array('page' => 1, 'ord'=>0)));
            }

            $page_number = ceil(count($allg)/$limit);

            if($page_number == $page)
            {
                $last = true;
            }


            $empresa = $em->getRepository('MGAdminBundle:Empresa')
                ->find($empId);

            $clientes = $empresa->getUsersByRol('ROLE_CLIENTE');

            if(!isset($clientes) || sizeof($clientes) < 1 || $clientes == false)
            {
                $clientes = array();
            }


            $nombre_empresa = $empresa->getNombre();
            return $this->render('MGAdminBundle:Gestiones:gestionindexfilteredest.html.twig', array(
                'gestiones' => $gestiones,
                'nombre_emp' => $nombre_empresa,
                'pages' => $page_number,
                'first' => $first,
                'last' => $last,
                'page' => $page,
                'empresas' => $empresas,
                'ord' => $ord,
                'estado' => $estId,
                'empId' => $empId,
                'clientes' => $clientes

            ));

        }
        elseif($tieneEmpresa == 1 && $this->getUser()->getRolId() == 3)
        {
            $first = true;
            $last = false;
            $offset = 10 * ($page - 1);

            if(!isset($page) || $page < 2)
            {
                $page = 1;
                $first = false;
            }

            $repoGest = $this->getDoctrine()->getRepository('MGRepoBundle:Gestion');


            if($ord == 0)
            {
                $desc = 'DESC';
                $allg = $repoGest->findAllForPagination($desc, $estId, $empId);
            }else
            {
                $asc = 'ASC';
                $allg = $repoGest->findAllForPagination($asc, $estId, $empId);
            }

            $em = $this->getDoctrine()->getManager();

            $empresas = $em->getRepository('MGAdminBundle:Empresa')
                ->findAll();

            if(!$empresas)
            {
                $this->get('session')->getFlashBag()->add('fail', 'No existen la empresa seleccionada');
                return $this->redirect($this->generateUrl('mg_gest_homepage', array('page' => 1, 'ord'=>0)));
            }

            $userServs = $this->getUser()->getServUsers();

            foreach($userServs as $serv)
            {
                $userCats[] = $serv->getCategoria();
            }

            if(!$userServs || !isset($userServs) || sizeof($userServs) < 0 || !$userCats || !isset($userCats) || sizeof($userCats) < 0)
            {
                $this->get('session')->getFlashBag()->add('fail', 'No tienes permisos para acceder.');
                return $this->redirect($this->generateUrl('mg_gest_homepage', array('page' => 1, 'ord'=>0)));
            }

            for($i = $offset; $i < $limit + $offset; $i++)
            {
                if(isset($allg[$i]))
                {
                    if(isset($userCats))
                    {
                        foreach($userCats as $categorias)
                        {
                            foreach ($categorias as $cat) {
                                if($allg[$i]->getIdCategoria() == $cat->getId())
                                {
                                    $gestiones[] = $allg[$i];
                                }
                            }

                        }
                    }
                }
            }

            if(!isset($gestiones) )
            {
                $this->get('session')->getFlashBag()->add('fail', 'No hay gestiones actualmente registradas con ese estado');
                return $this->redirect($this->generateUrl('mg_gest_homepage', array('page' => 1, 'ord'=>0)));
            }

            $page_number = ceil(count($allg)/$limit);

            if($page_number == $page)
            {
                $last = true;
            }


            $empresa = $em->getRepository('MGAdminBundle:Empresa')
                ->find($empId);

            $pre_clientes = $empresa->getUsersByRol('ROLE_CLIENTE');

            if(!isset($pre_clientes) || sizeof($pre_clientes) < 1 || $pre_clientes == false)
            {
                $clientes = array();
            }else
            {
                foreach($pre_clientes as $c)
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
                    $clientes = array_unique($pre_sort_clientes);
                }
            }

            $nombre_empresa = $empresa->getNombre();
            return $this->render('MGAdminBundle:Gestiones:gestionindexfilteredest.html.twig', array(
                'gestiones' => $gestiones,
                'nombre_emp' => $nombre_empresa,
                'pages' => $page_number,
                'first' => $first,
                'last' => $last,
                'page' => $page,
                'empresas' => $empresas,
                'ord' => $ord,
                'estado' => $estId,
                'empId' => $empId,
                'clientes' => $clientes

            ));
        }
        else
        {
            $mensaje = "Acceso no permitido";
            $msjTipo = "error";
            return $this->render('MGAdminBundle:Msg:messages.html.twig', array('mensaje' => $mensaje, 'tipo' => $msjTipo));
        }
    }

    public function addCommentGestAction($gestId, Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $gestion = $em->getRepository('MGRepoBundle:Gestion')
            ->find($gestId);

        if(!$gestion)
        {
            $this->get('session')->getFlashBag()->add('fail', 'No hay gestiones actualmente registradas');
            return $this->redirect($this->generateUrl('mg_admin_homepage'));
        }

        $tieneEmpresa = 0;
        foreach ($this->getUser()->getEmpresas() as $empresa) {
            if($gestion->getIdEmpresa() == $empresa->getId())
            {
                $tieneEmpresa = 1;
            }
        }

        if(($this->getUser()->getRolId() == 2 && $tieneEmpresa == 1) || $this->getUser()->getRolId() == 1 )

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

                }
            }

            return $this->redirect($this->generateUrl('mg_manage_gest', array('gestId' => $gestId)));

            /*return $this->render('MGAdminBundle:Gestiones:addnewgestcomm.html.twig',
                array('newGestCommForm' => $form->createView(),
                      'gestion' => $gestion,
                      'comentarios' => $comentarios
                     ));*/

        }
        elseif($this->getUser()->getRolId() == 3 && $tieneEmpresa == 1)
        {
            $tienePermiso = 0;
            $servicio = $gestion->getCategoria()->getServicio();

            $user_serv = $servicio->getUsers();
            foreach($user_serv as $us)
            {
                if($us == $this->getUser())
                {
                    $tienePermiso = 1;
                }
            }

            if($tienePermiso == 1)
            {
                $comentario = new Comentarios();

                $comentarios = $gestion->getComentarios();

                $form = $this->createForm(new ComentariosType(), $comentario);

                if($request->isMethod('POST'))
                {
                    $form->bind($request);
                    if($form->isValid())
                    {
                        $comentario->setGestion($gestion);
                        $comentario->setAutor($this->getUser());
                        $comentario->setFecha(new \DateTime());

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

                    }
                }
                return $this->redirect($this->generateUrl('mg_manage_gest', array('gestId' => $gestId)));
                /*return $this->render('MGAdminBundle:Gestiones:addnewgestcomm.html.twig',
                    array('newGestCommForm' => $form->createView(),
                        'gestion' => $gestion,
                        'comentarios' => $comentarios
                    ));*/
            }else
            {
                $mensaje = "No estas autorizado para ver esta gestión.";
                $msjTipo = "error";
                return $this->render('MGAdminBundle:Msg:messages.html.twig', array('mensaje' => $mensaje, 'tipo' => $msjTipo));
            }
        }
        else
        {
            $mensaje = "No estas autorizado para ver agregar comentarios a esta gestion.";
            $msjTipo = "error";
            return $this->render('MGAdminBundle:Msg:messages.html.twig', array('mensaje' => $mensaje, 'tipo' => $msjTipo));
        }

    }

    public function manageGestAction(Request $request, $gestId)
    {
        if(isset($gestId) && $gestId != 0)
        {
            $hazAccess = false;

            $canAdd = false;
            $user_empresas = $this->getUser()->getEmpresas();

            $em = $this->getDoctrine()->getManager();

            $gestion = $em->getRepository('MGRepoBundle:Gestion')
                ->find($gestId);

            if(!isset($gestion))
            {
                $mensaje = "Gestión no encontrada";
                $msjTipo = "error";
                return $this->render('MGAdminBundle:Msg:messages.html.twig', array('mensaje' => $mensaje, 'tipo' => $msjTipo));
            }

            $emp_gestion = $gestion->getEmpresa()->getNombre();

            $comentarios = $gestion->getComentarios();

            foreach($user_empresas as $emp)
            {
                if($gestion->getIdEmpresa() == $emp->getId())
                {
                    $hazAccess = true;
                }
            }

            if($hazAccess == true || $this->getUser()->getRolId() == 1)
            {
                if($this->getUser()->getRolId() != 1)
                {
                    if($gestion->getNotificacion()->getActiveForEmp() == true)
                    {
                        $leido = $em->getRepository('MGRepoBundle:Estado')
                            ->findOneBy(array('estadoCode' => 'GESTION_RECIBIDA'));
                        $gestion->setEstado($leido);
                        $em->merge($gestion);
                        $em->flush();
                    }
                }
                if($this->getUser()->getRolId() <= 2)
                {
                    $clientOwner = $gestion->getCliente();
                    $clientRepos = $clientOwner->getRepoClient();

                    foreach($clientRepos as $repo)
                    {
                        if($repo->getEmpresaId() == $gestion->getIdEmpresa())
                        {
                            $targetRepo = $repo;
                        }
                    }

                    if(isset($targetRepo))
                    {
                        $newFile = new Archivo();
                        $newComment = new Comentarios();
                        $form = $this->createForm(new ArchivoType(), $newFile);
                        $form_com = $this->createForm(new ComentariosType(), $newComment);

                        if($request->isMethod('POST'))
                        {
                            $form->bind($request);
                            $files = $form['file']->getData();

                            if($form->isValid())
                            {
                                foreach ($files as $f)
                                {
                                    $tempFile = new Archivo();
                                    $tempFile->setRepo($targetRepo);
                                    $tempFile->setUser($clientOwner);
                                    $tempFile->setDelEmpresa(true);
                                    $tempFile->setDelUser(false);
                                    $tempFile->setFile($f);

                                    $ofFT = $tempFile->upload($emp_gestion);

                                    if($ofFT != false)
                                    {
                                        $tempFile->setGestion($gestion);
                                        $em->persist($tempFile);
                                        $em->flush();
                                    }else
                                    {
                                        $this->get('session')->getFlashBag()->add('fail', 'El tipo de archivo que intentas subir no esta permitido');
                                        return $this->redirect($this->generateUrl('mg_manage_gest', array('gestId' => $gestId)));
                                    }
                                }

                                $addByEmp = $em->getRepository('MGRepoBundle:Estado')
                                    ->findOneBy(array('estadoCode' => 'GESTION_FILES_ADDED_EMP'));

                                $notificacion = $gestion->getNotificacion();
                                $notificacion->setEstado($addByEmp);
                                $notificacion->setActiveForUser(false);
                                $notificacion->setActiveForEmp(true);
                                $gestion->setEstado($addByEmp);

                                $em->persist($notificacion);
                                $em->persist($gestion);
                                $em->flush();

                                $this->get('session')->getFlashBag()->add('notice', 'Fichero/s subidos correctamente');
                                //return $this->redirect($this->generateUrl('mg_gest_by_empresa', array('empId' => $gestion->getEmpresa()->getId(), 'page' => 1)));
                            }
                        }
                        return $this->render('MGAdminBundle:Gestiones:addfiles.html.twig',
                            array(
                                'addFilesForm' => $form->createView(),
                                'newGestCommForm' => $form_com->createView(),
                                'gestId' => $gestId,
                                'empId' => $gestion->getIdEmpresa(),
                                'gestion' => $gestion,
                                'comentarios' => $comentarios
                            ));
                    }else
                    {

                        $newComment = new Comentarios();

                        $form_com = $this->createForm(new ComentariosType(), $newComment);

                        return $this->render('MGAdminBundle:Gestiones:addfiles.html.twig',
                            array(
                                'newGestCommForm' => $form_com->createView(),
                                'gestId' => $gestId,
                                'empId' => $gestion->getIdEmpresa(),
                                'gestion' => $gestion,
                                'comentarios' => $comentarios
                            ));
                    }

                }
                else if($this->getUser()->getRolId() == 3)
                {
                    $userServs = $this->getUser()->getServUsers();

                    foreach($userServs as $serv)
                    {
                        $userCats[] = $serv->getCategoria();
                    }

                    $cat_gestion = $gestion->getCategoria();


                    foreach($userCats as $categorias)
                    {
                        foreach($categorias as $cat)
                        {
                            if($cat->getId() == $cat_gestion->getId())
                            {
                                $canAdd = true;
                            }
                        }
                    }

                    if($canAdd == true)
                    {

                        $clientOwner = $gestion->getCliente();
                        $clientRepos = $clientOwner->getRepoClient();

                        foreach($clientRepos as $repo)
                        {
                           if($repo->getEmpresaId() == $gestion->getIdEmpresa())
                           {
                               $targetRepo = $repo;
                           }
                        }

                        if(isset($targetRepo))
                        {
                            ////////////YA TENEMOS EL REPOSITORIO ACCESIBLE POR EL CLIENTE LOCALIZADO.

                            $newFile = new Archivo();
                            $newComment = new Comentarios();

                            $form = $this->createForm(new ArchivoType(), $newFile);
                            $form_com = $this->createForm(new ComentariosType(), $newComment);

                            if($request->isMethod('POST'))
                            {
                                $form->bind($request);
                                $files = $form['file']->getData();

                            if($form->isValid())
                            {
                                foreach ($files as $f)
                                {
                                    $tempFile = new Archivo();
                                    $tempFile->setRepo($targetRepo);
                                    $tempFile->setUser($clientOwner);
                                    $tempFile->setDelEmpresa(true);
                                    $tempFile->setDelUser(false);
                                    $tempFile->setFile($f);

                                    $ofFT = $tempFile->upload($emp_gestion);

                                    if($ofFT != false)
                                    {
                                        $tempFile->setGestion($gestion);
                                        $em->persist($tempFile);
                                        $em->flush();
                                    }else
                                    {
                                        $this->get('session')->getFlashBag()->add('fail', 'El tipo de archivo que intentas subir no esta permitido');
                                        return $this->redirect($this->generateUrl('mg_manage_gest', array('gestId' => $gestId)));
                                    }

                                }

                                $addByEmp = $em->getRepository('MGRepoBundle:Estado')
                                    ->findOneBy(array('estadoCode' => 'GESTION_FILES_ADDED_EMP'));

                                $notificacion = $gestion->getNotificacion();
                                $notificacion->setEstado($addByEmp);
                                $notificacion->setActiveForUser(false);
                                $notificacion->setActiveForEmp(true);

                                $gestion->setEstado($addByEmp);

                                $em->persist($notificacion);
                                $em->persist($gestion);
                                $em->flush();

                                $this->get('session')->getFlashBag()->add('notice', 'Fichero/s subidos correctamente');
                                    //return $this->redirect($this->generateUrl('mg_gest_by_empresa', array('empId' => $gestion->getEmpresa()->getId(), 'page' => 1)));
                                }
                            }

                            return $this->render('MGAdminBundle:Gestiones:addfiles.html.twig',
                                array(
                                    'addFilesForm' => $form->createView(),
                                    'newGestCommForm' => $form_com->createView(),
                                    'gestId' => $gestId,
                                    'empId' => $gestion->getIdEmpresa(),
                                    'comentarios' => $comentarios,
                                    'gestion' => $gestion
                                ));
                        }else
                        {
                            $newComment = new Comentarios();

                            $form_com = $this->createForm(new ComentariosType(), $newComment);

                            return $this->render('MGAdminBundle:Gestiones:addfiles.html.twig',
                                array(
                                    'newGestCommForm' => $form_com->createView(),
                                    'gestId' => $gestId,
                                    'empId' => $gestion->getIdEmpresa(),
                                    'gestion' => $gestion,
                                    'comentarios' => $comentarios
                                ));
                        }

                    }else
                    {
                       $mensaje = "No tienes permisos suficientes para realizar esta acción.";
                       $msjTipo = "error";
                       return $this->render('MGAdminBundle:Msg:messages.html.twig', array('mensaje' => $mensaje, 'tipo' => $msjTipo));
                    }
                }

            }else
            {
                $mensaje = "No tienes permisos suficientes para realizar esta acción.";
                $msjTipo = "error";
                return $this->render('MGAdminBundle:Msg:messages.html.twig', array('mensaje' => $mensaje, 'tipo' => $msjTipo));
            }
        }else
        {
            $mensaje = "No existe la gestión";
            $msjTipo = "error";
            return $this->render('MGAdminBundle:Msg:messages.html.twig', array('mensaje' => $mensaje, 'tipo' => $msjTipo));
        }

    }

    public function closeGestAction($gestId, $status)
    {
        $tieneEmpresa = 0;
        $em = $this->getDoctrine()->getManager();
        $gestion = $em->getRepository('MGRepoBundle:Gestion')
            ->find($gestId);

       if(isset($gestion))
       {
           if($gestion->isUnsolved() == true)
           {
               $empId = $gestion->getIdEmpresa();

               foreach ($this->getUser()->getEmpresas() as $empresa) {
                   if($empId == $empresa->getId())
                   {
                       $tieneEmpresa = 1;
                   }
               }

               if(($tieneEmpresa == 1 && $this->getUser()->getRolId() == 2) || $this->getUser()->getRolId() == 1)
               {
                   if($status == 10)
                   {
                       $niceSolved = $em->getRepository('MGRepoBundle:Estado')
                           ->find(8);


                       $gestion->setEstado($niceSolved);

                       $notificacion = $gestion->getNotificacion();

                       $notificacion->setEstado($niceSolved);
                       $notificacion->setActiveForUser(true);
                       $notificacion->setActiveForEmp(false);

                       $em->merge($gestion);
                       $em->merge($notificacion);
                       $em->flush();
                       $this->get('session')->getFlashBag()->add('notice', 'Gestion cerrada como resuelta');
                       return $this->redirect($this->generateUrl('mg_gest_by_empresa', array('empId' => $gestion->getIdEmpresa(), 'page' => 1)));
                   }else
                   {
                       $notSolved = $em->getRepository('MGRepoBundle:Estado')
                           ->find(9);


                       $gestion->setEstado($notSolved);
                       $notificacion = $gestion->getNotificacion();
                       $notificacion->setEstado($notSolved);
                       $notificacion->setActiveForUser(true);
                       $notificacion->setActiveForEmp(false);

                       $em->merge($gestion);
                       $em->merge($notificacion);
                       $em->flush();
                       $this->get('session')->getFlashBag()->add('fail', 'Gestión cerrada sin resolver');
                       return $this->redirect($this->generateUrl('mg_gest_by_empresa', array('empId' => $gestion->getIdEmpresa(), 'page' => 1)));
                   }
               }
               elseif($tieneEmpresa == 1 && $this->getUser()->getRolId() == 3)
               {
                   $userServs = $this->getUser()->getServUsers();

                   foreach($userServs as $serv)
                   {
                       $userCats[] = $serv->getCategoria();

                   }

                   $cat_gestion = $gestion->getCategoria();

                   $canGest = false;
                   foreach($userCats as $categorias)
                   {
                       foreach($categorias as $cat)
                       {
                           if($cat->getId() == $cat_gestion->getId())
                           {
                               $canGest = true;
                           }
                       }
                   }

                   if($canGest == true)
                   {
                       if($status == 10)
                       {
                           $niceSolved = $em->getRepository('MGRepoBundle:Estado')
                               ->find(8);


                           $gestion->setEstado($niceSolved);

                           $notificacion = $gestion->getNotificacion();
                           $notificacion->setEstado($niceSolved);
                           $notificacion->setActiveForUser(true);
                           $notificacion->setActiveForEmp(false);

                           $em->merge($gestion);
                           $em->merge($notificacion);
                           $em->flush();
                           $this->get('session')->getFlashBag()->add('notice', 'Gestion cerrada como resuelta');
                           return $this->redirect($this->generateUrl('mg_gest_by_empresa', array('empId' => $gestion->getIdEmpresa(), 'page' => 1)));
                       }else
                       {
                           $notSolved = $em->getRepository('MGRepoBundle:Estado')
                               ->find(9);

                           $gestion->setEstado($notSolved);

                           $notificacion = $gestion->getNotificacion();
                           $notificacion->setEstado($notSolved);
                           $notificacion->setActiveForUser(true);
                           $notificacion->setActiveForEmp(false);

                           $em->merge($gestion);
                           $em->merge($notificacion);
                           $em->flush();
                           $this->get('session')->getFlashBag()->add('fail', 'Gestión cerrada sin resolver');
                           return $this->redirect($this->generateUrl('mg_gest_by_empresa', array('empId' => $gestion->getIdEmpresa(), 'page' => 1)));
                       }
                   }else
                   {
                       $mensaje = "No tienes persmisos suficientes para llevar a cabo esta acción";
                       $msjTipo = "error";
                       return $this->render('MGAdminBundle:Msg:messages.html.twig', array('mensaje' => $mensaje, 'tipo' => $msjTipo));
                   }
               }
               elseif($gestion->getIdCliente() == $this->getUser()->getId())
               {
                   if($status == 10)
                   {
                       $niceSolved = $em->getRepository('MGRepoBundle:Estado')
                           ->find(8);


                       $gestion->setEstado($niceSolved);

                       $notificacion = $gestion->getNotificacion();
                       $notificacion->setEstado($niceSolved);
                       $notificacion->setActiveForUser(true);
                       $notificacion->setActiveForEmp(false);


                       $em->merge($gestion);
                       $em->merge($notificacion);
                       $em->flush();
                       $this->get('session')->getFlashBag()->add('notice', 'Gestion cerrada como resuelta');
                       //return $this->redirect($this->generateUrl('mg_gest_by_empresa', array('empId' => $gestion->getIdEmpresa(), 'page' => 1)));
                   }else
                   {
                       $notSolved = $em->getRepository('MGRepoBundle:Estado')
                           ->find(9);



                       $gestion->setEstado($notSolved);

                       $notificacion = $gestion->getNotificacion();
                       $notificacion->setEstado($notSolved);
                       $notificacion->setActiveForUser(true);
                       $notificacion->setActiveForEmp(false);

                       $em->merge($gestion);
                       $em->merge($notificacion);
                       $em->flush();
                       $this->get('session')->getFlashBag()->add('fail', 'Gestión cerrada sin resolver');
                       //return $this->redirect($this->generateUrl('mg_gest_by_empresa', array('empId' => $gestion->getIdEmpresa(), 'page' => 1)));
                   }
               }
               else
               {
                   $mensaje = "No tienes persmisos suficientes para llevar a cabo esta acción";
                   $msjTipo = "error";
                   return $this->render('MGAdminBundle:Msg:messages.html.twig', array('mensaje' => $mensaje, 'tipo' => $msjTipo));
               }
           }else
           {
               $mensaje = "La gestión ya se encuentra cerrada";
               $msjTipo = "error";
               return $this->render('MGAdminBundle:Msg:messages.html.twig', array('mensaje' => $mensaje, 'tipo' => $msjTipo));
           }
       }else
       {
           $mensaje = "La gestión no existe";
           $msjTipo = "error";
           return $this->render('MGAdminBundle:Msg:messages.html.twig', array('mensaje' => $mensaje, 'tipo' => $msjTipo));
       }

    }

    public function reopenGestAction($gestId)
    {
        $tieneEmpresa = 0;
        $em = $this->getDoctrine()->getManager();
        $gestion = $em->getRepository('MGRepoBundle:Gestion')
            ->find($gestId);

        if(isset($gestion))
        {
            if($gestion->isSolved() == true)
            {
                $empId = $gestion->getIdEmpresa();

                foreach ($this->getUser()->getEmpresas() as $empresa) {
                    if($empId == $empresa->getId())
                    {
                        $tieneEmpresa = 1;
                    }
                }

                if(($tieneEmpresa == 1 && $this->getUser()->getRolId() == 2) || $this->getUser()->getRolId() == 1)
                {
                    $reOpen = $em->getRepository('MGRepoBundle:Estado')
                        ->find(3);

                    $gestion->setEstado($reOpen);

                    $notificacion = $gestion->getNotificacion();

                    $notificacion->setEstado($reOpen);
                    $notificacion->setActiveForUser(true);
                    $notificacion->setActiveForEmp(false);

                    $em->merge($gestion);
                    $em->merge($notificacion);
                    $em->flush();
                    $this->get('session')->getFlashBag()->add('notice', 'Gestion reabierta');
                    return $this->redirect($this->generateUrl('mg_gest_by_empresa', array('empId' => $gestion->getIdEmpresa(), 'page' => 1)));

                }
                elseif($tieneEmpresa == 1 && $this->getUser()->getRolId() == 3)
                {
                    $userServs = $this->getUser()->getServUsers();

                    foreach($userServs as $serv)
                    {
                        $userCats[] = $serv->getCategoria();

                    }

                    $cat_gestion = $gestion->getCategoria();

                    $canGest = false;
                    foreach($userCats as $categorias)
                    {
                        foreach($categorias as $cat)
                        {
                            if($cat->getId() == $cat_gestion->getId())
                            {
                                $canGest = true;
                            }
                        }
                    }

                    if($canGest == true)
                    {
                        $reOpen = $em->getRepository('MGRepoBundle:Estado')
                            ->find(3);

                        $gestion->setEstado($reOpen);

                        $notificacion = $gestion->getNotificacion();

                        $notificacion->setEstado($reOpen);
                        $notificacion->setActiveForUser(true);
                        $notificacion->setActiveForEmp(false);

                        $em->merge($gestion);
                        $em->merge($notificacion);
                        $em->flush();
                        $this->get('session')->getFlashBag()->add('notice', 'Gestion reabierta');
                        return $this->redirect($this->generateUrl('mg_gest_by_empresa', array('empId' => $gestion->getIdEmpresa(), 'page' => 1)));
                    }else
                    {
                        $mensaje = "No tienes persmisos suficientes para llevar a cabo esta acción";
                        $msjTipo = "error";
                        return $this->render('MGAdminBundle:Msg:messages.html.twig', array('mensaje' => $mensaje, 'tipo' => $msjTipo));
                    }
                }
                else
                {
                    $mensaje = "No tienes persmisos suficientes para llevar a cabo esta acción";
                    $msjTipo = "error";
                    return $this->render('MGAdminBundle:Msg:messages.html.twig', array('mensaje' => $mensaje, 'tipo' => $msjTipo));
                }
            }else
            {
                $mensaje = "La gestión ya se encuentra abierta";
                $msjTipo = "error";
                return $this->render('MGAdminBundle:Msg:messages.html.twig', array('mensaje' => $mensaje, 'tipo' => $msjTipo));
            }
        }else
        {
            $mensaje = "La gestión no existe";
            $msjTipo = "error";
            return $this->render('MGAdminBundle:Msg:messages.html.twig', array('mensaje' => $mensaje, 'tipo' => $msjTipo));
        }
    }

    public function changeEstadoAction($gestId, $estadoId)
    {
        if($gestId != null && $gestId > 0)
        {
            $em = $this->getDoctrine()->getManager();

            $gestion = $em->getRepository('MGRepoBundle:Gestion')
                ->find($gestId);

            if(!$gestion)
            {
                $this->get('session')->getFlashBag()->add('fail', 'No hay gestiones actualmente registradas');
                return $this->redirect($this->generateUrl('mg_admin_homepage'));
            }

            $tieneEmpresa = 0;
            foreach ($this->getUser()->getEmpresas() as $empresa) {
                if($gestion->getIdEmpresa() == $empresa->getId())
                {
                    $tieneEmpresa = 1;
                }
            }

            if(($this->getUser()->getRolId() == 2 && $tieneEmpresa == 1) || $this->getUser()->getRolId() == 1 )
            {
                $newEstado = $em->getRepository('MGRepoBundle:Estado')
                    ->find($estadoId);

                if(!$newEstado)
                {
                    $this->get('session')->getFlashBag()->add('fail', 'No existe el estado');
                    return $this->redirect($this->generateUrl('mg_admin_homepage'));
                }

                $gestion->setEstado($newEstado);

                $notificacion = $gestion->getNotificacion();

                if($newEstado->getEstadoCode() != 'GESTION_NUEVA')
                {
                    $notificacion->setEstado($newEstado);
                    $notificacion->setActiveForUser(true);
                    $notificacion->setActiveForEmp(false);
                }

                $em->merge($gestion);
                $em->flush();
                $em->persist($notificacion);
                $em->flush();
                $this->get('session')->getFlashBag()->add('notice', 'Estado actualizado con exito');
                return $this->redirect($this->generateUrl('mg_manage_gest', array('gestId' => $gestId)));

            }
            elseif($this->getUser()->getRolId() == 3 && $tieneEmpresa == 1)
            {
                $tienePermiso = 0;
                $servicio = $gestion->getCategoria()->getServicio();

                $user_serv = $servicio->getUsers();
                foreach($user_serv as $us)
                {
                    if($us == $this->getUser())
                    {
                        $tienePermiso = 1;
                    }
                }

                if($tienePermiso == 1)
                {
                    $newEstado = $em->getRepository('MGRepoBundle:Estado')
                        ->find($estadoId);

                    if(!$newEstado)
                    {
                        $this->get('session')->getFlashBag()->add('fail', 'No existe el estado');
                        return $this->redirect($this->generateUrl('mg_admin_homepage'));
                    }

                    $gestion->setEstado($newEstado);

                    $notificacion = $gestion->getNotificacion();

                    if($newEstado->getEstadoCode() != 'GESTION_NUEVA')
                    {
                        $notificacion->setEstado($newEstado);
                        $notificacion->setActiveForUser(true);
                        $notificacion->setActiveForEmp(false);
                    }

                    $em->persist($gestion);
                    $em->persist($notificacion);
                    $em->flush();
                    $this->get('session')->getFlashBag()->add('notice', 'Estado actualizado con exito');
                    return $this->redirect($this->generateUrl('mg_manage_gest', array('gestId' => $gestId)));
                }else
                {
                    $mensaje = "No estas autorizado para realizar esta acción.";
                    $msjTipo = "error";
                    return $this->render('MGAdminBundle:Msg:messages.html.twig', array('mensaje' => $mensaje, 'tipo' => $msjTipo));
                }
            }
            else
            {
                $mensaje = "No estas autorizado para realizar esta acción.";
                $msjTipo = "error";
                return $this->render('MGAdminBundle:Msg:messages.html.twig', array('mensaje' => $mensaje, 'tipo' => $msjTipo));
            }
        }else
        {
            $mensaje = "La gestión no existe.";
            $msjTipo = "error";
            return $this->render('MGAdminBundle:Msg:messages.html.twig', array('mensaje' => $mensaje, 'tipo' => $msjTipo));
        }
    }

    public function createGestAdminAction(Request $request, $clientId)
    {

        $user = $this->getUser();

        $gestion = new Gestion();
        $form = $this->createForm(new GestionAdminType(), $gestion, array(
                'cliId' => $clientId,
                'rolU' => $user->getRol()->getRolName(),
                'usId' => $user->getId())
        );

        if($request->isMethod('POST'))
        {
            $form->bind($request);
            if($form->isValid())
            {
                $em = $this->getDoctrine()->getManager();
                $formData = $this->getRequest()->request->get($form->getName());
                $categoria = $em->getRepository('MGRepoBundle:Categoria')
                    ->find($formData['categoria']);
                $empId = $categoria->getServicio()->getEmpresa()->getId();
                $empresa = $em->getRepository('MGAdminBundle:Empresa')
                    ->findOneBy(array('id' => $empId ));

                $cliente = $em->getRepository('MGUserBundle:User')
                    ->find($clientId);

                if(!$empresa)
                {
                    $mensaje = "Error desconocido.";
                    $msjTipo = "error";
                    return $this->render('MGRepoBundle:Msg:messages.html.twig', array('mensaje' => $mensaje, 'tipo' => $msjTipo));
                }

                $estado = $em->getRepository('MGRepoBundle:Estado')
                    ->findOneBy(array('estadoCode' => 'GESTION_NUEVA'));

                $GestType = $em->getRepository('MGMensajeriaBundle:TipoNotificacion')
                    ->findOneBy(array('tipoCode' => 'GEST_TYPE'));

                $gestion->setEmpresa($empresa);
                $gestion->setCliente($cliente);
                $gestion->setFechaCreacion(new \DateTime());
                $gestion->setEstado($estado);

                $em->persist($gestion);

                $notificacion = new Notificaciones();
                $notificacion->setGestion($gestion);
                $notificacion->setTipo($GestType);
                $notificacion->setEstado($estado);
                $notificacion->setDestinatarioCliente($gestion->getCliente());
                $notificacion->setActiveForEmp(false);
                $notificacion->setActiveForUser(true);

                $em->persist($notificacion);
                $em->flush();

                $estados = $em->getRepository('MGRepoBundle:Estado')
                    ->findAll();

                $session = $this->get('session');
                $session->set('generalInfo',array(
                    'estado' => $estados
                ));

                $this->get('session')->getFlashBag()->add('notice', 'Gestión creada correctamente');
                return $this->redirect($this->generateUrl('mg_manage_gest', array('gestId' => $gestion->getId())));

            }
        }


        return $this->render('MGAdminBundle:Gestiones:newgest.html.twig',
            array('newGestForm' => $form->createView(),
                'cliId' => $clientId));

    }

    public function deleteFileGestAction()
    {

    }
}

<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Merkury
 * Date: 26/06/13
 * Time: 8:31
 * To change this template use File | Settings | File Templates.
 */

namespace MG\AdminBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use MG\RepoBundle\Entity\Repo;
use MG\RepoBundle\Form\RepoType;
use MG\RepoBundle\Form\EditRepoType;
use MG\RepoBundle\Form\GiveUsersType;
use MG\RepoBundle\Form\GiveClientsType;
use MG\AdminBundle\Form\SearchFileType;




class AdminRepoController extends Controller
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

            $reposRepo = $this->getDoctrine()->getRepository('MGRepoBundle:Repo');

            $allr = $reposRepo->findAll();

            $repositorios = $reposRepo->findAllWithLimitAndOffset($limit, ($page-1)*$limit);

            if(!$repositorios)
            {
                $this->get('session')->getFlashBag()->add('fail', 'No hay repositorios actualmente registrados');
                return $this->redirect($this->generateUrl('mg_admin_homepage'));
            }

            $em = $this->getDoctrine()->getManager();

            $empresas = $em->getRepository('MGAdminBundle:Empresa')
                ->findAll();

            if(!$empresas)
            {
                $this->get('session')->getFlashBag()->add('fail', 'No existen empresas disponibles');
                return $this->redirect($this->generateUrl('mg_repoadmin_homepage', array('page'=> 1)));
            }

            $page_number = ceil(count($allr)/$limit);

            if($page_number == $page)
            {
                $last = true;
            }

            return $this->render('MGAdminBundle:Repos:repogestion.html.twig', array(
                'repositorios' => $repositorios,
                'pages' => $page_number,
                'first' => $first,
                'last' => $last,
                'page' => $page,
                'empresas' => $empresas
            ));
        }else
        {
            $empresas = $this->getUser()->getEmpresas();
            if(sizeof($empresas) == 1)
            {
                return $this->redirect($this->generateUrl('mg_repo_by_empresa', array('empId' => $empresas[0]->getId(),'page'=> 1)));
            }
            else
            {
                return $this->render('MGAdminBundle:Repos:selectemprepo.html.twig', array('empresas' => $empresas));
            }
        }
    }

    public function createAction(Request $request, $empId = 0)
    {
        if($this->getUser()->getRolId() == 1)
        {
            $repo = new Repo();

            $userId= $this->getUser()->getId();

            $form = $this->createForm(new RepoType(), $repo, array('userId' => $userId, 'userRol' => $this->getUser()->getRolId()));

            if($request->isMethod('POST'))
            {
                $form->bind($request);
                if($form->isValid())
                {
                    $formData = $this->getRequest()->request->get($form->getName());
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($repo);
                    $em->flush();

                    $this->get('session')->getFlashBag()->add('notice', 'Repositorio creado correctamente');

                    if($this->getUser()->getRolId() == 1 && $empId == 0)
                    {
                        return $this->redirect($this->generateUrl('mg_repoadmin_homepage', array('page' => 1)));
                    }else
                    {
                        return $this->redirect($this->generateUrl('mg_repo_by_empresa', array('page' => 1, 'empId'=>$empId)));
                    }
                }
            }

            return $this->render('MGAdminBundle:Repos:newrepo.html.twig', array('newRepoForm' => $form->createView(), 'empId'=>$empId));
        }else
        {
            $mensaje = "No tienes permisos para crear repositorios.";
            $msjTipo = "error";
            return $this->render('MGAdminBundle:Msg:messages.html.twig', array('mensaje' => $mensaje, 'tipo' => $msjTipo));
        }

    }

    public function updateAction($repoId, Request $request)
    {

        if($this->getUser()->getRolId() == 1 )
        {
            $em = $this->getDoctrine()->getManager();

            $repo = $em->getRepository('MGRepoBundle:Repo')
                ->find($repoId);

            if(!$repo)
            {
                $this->get('session')->getFlashBag()->add('fail', 'No existe el repositorio');
                return $this->redirect($this->generateUrl('mg_repoadmin_homepage', array('page'=> 1)));
            }

            $empreId = $repo->getEmpresaId();
            $form = $this->createForm(new EditRepoType(), $repo, array('empId' => $empreId));

            if($request->isMethod('POST'))
            {
                $form->bind($request);
                if($form->isValid())
                {
                    $em->persist($repo);
                    $em->flush();

                    $this->get('session')->getFlashBag()->add('notice', 'Repositorio modificado correctamente');

                    return $this->redirect($this->generateUrl('mg_repoadmin_homepage', array('page' => 1)));
                }
            }
            return $this->render('MGAdminBundle:Repos:updaterepo.html.twig', array('editRepoForm' => $form->createView(), 'repId' => $repoId));
        }else
        {
            $mensaje = "No tienes permisos para modificar este repositorio.";
            $msjTipo = "error";
            return $this->render('MGAdminBundle:Msg:messages.html.twig', array('mensaje' => $mensaje, 'tipo' => $msjTipo));
        }

    }

    public function detailAction($repoId, $empId = 0)
    {
        $em = $this->getDoctrine()->getManager();

        $repo = $em->getRepository('MGRepoBundle:Repo')
            ->find($repoId);

        if(!$repo)
        {
            $this->get('session')->getFlashBag()->add('fail', 'No existe el repositorio');
            return $this->redirect($this->generateUrl('mg_repoadmin_homepage', array('page'=> 1)));
        }

        $ficheros_repo = 0;
        foreach($repo->getArchivos() as $archivo)
        {
            $ficheros_repo++;
        }

        $empresa_repo = $repo->getEmpresa();
        $users_repo = $repo->getUsers();
        $clients_repo = $repo->getClientes();
        $tieneEmpresa = 0;
        $pertenece = 0;
        foreach($this->getUser()->getEmpresas() as $empresa)
        {
            if($empresa == $empresa_repo)
            {
                $tieneEmpresa = 1;
            }
        }

        foreach($users_repo as $user)
        {
            if($user == $this->getUser())
            {
                $pertenece = 1;
            }
        }

        if(($tieneEmpresa == 1 && $pertenece == 1 && $this->getUser()->getRolId() <= 3 ) || ($tieneEmpresa = 1 && $this->getUser()->getRolId() == 2) || $this->getUser()->getRolId() == 1 )
        {
            return $this->render('MGAdminBundle:Repos:detailrepo.html.twig', array(
                'repo' => $repo, 'empresa' => $empresa_repo,
                'total_ficheros' => $ficheros_repo,
                'usuarios_repo' => $users_repo,
                'clientes_repo' => $clients_repo,
                'empId' => $empId));
        }
        else
        {
            $mensaje = "No tienes permisos para visualizar este repositorio.";
            $msjTipo = "error";
            return $this->render('MGAdminBundle:Msg:messages.html.twig', array('mensaje' => $mensaje, 'tipo' => $msjTipo));
        }
    }

    public function blockAction($repoId)
    {

        if($this->getUser()->getRolId() == 1)
        {
            $em = $this->getDoctrine()->getManager();

            $repo = $em->getRepository('MGRepoBundle:Repo')
                ->find($repoId);

            if(!$repo)
            {
                $this->get('session')->getFlashBag()->add('fail', 'Repositorio no encontrado');
                return $this->redirect($this->generateUrl('mg_repoadmin_homepage', array('page'=> 1)));
            }

            if($repo->isEnabled() == true)
            {
                $repo->setEnabled(false);
                $em->persist($repo);
                $em->flush();
                $this->get('session')->getFlashBag()->add('fail', 'Repositorio bloqueado');
                return $this->redirect($this->generateUrl('mg_repoadmin_homepage', array('page'=> 1)));
            }else
            {
                $repo->setEnabled(true);
                $em->persist($repo);
                $em->flush();
                $this->get('session')->getFlashBag()->add('notice', 'Repositorio desbloqueado');
                return $this->redirect($this->generateUrl('mg_repoadmin_homepage', array('page'=> 1)));
            }
        }else
        {
            $mensaje = "No tienes permisos para bloquear repositorios.";
            $msjTipo = "error";
            return $this->render('MGAdminBundle:Msg:messages.html.twig', array('mensaje' => $mensaje, 'tipo' => $msjTipo));
        }
    }

    public function giveUsersAction($repoId, Request $request, $empId = 0)
    {
        $em = $this->getDoctrine()->getManager();

        $repo = $em->getRepository('MGRepoBundle:Repo')
            ->find($repoId);

        if(!$repo)
        {
            $this->get('session')->getFlashBag()->add('fail', 'No existe el repositorio');
            return $this->redirect($this->generateUrl('mg_repoadmin_homepage', array('page'=> 1)));
        }

        $users_repo = $repo->getUsers();
        $empresa_repo = $repo->getEmpresa();
        $tieneEmpresa = 0;
        $pertenece = 0;
        foreach($this->getUser()->getEmpresas() as $empresa)
        {
            if($empresa == $empresa_repo)
            {
                $tieneEmpresa = 1;
            }
        }

        foreach($users_repo as $user)
        {
            if($user == $this->getUser())
            {
                $pertenece = 1;
            }
        }

        if($repo->isEnabled() == true )
        {
            if(($tieneEmpresa == 1 && $this->getUser()->getRolId() == 2) || $this->getUser()->getRolId() == 1)
            {
                $rolId = $this->getUser()->getRolId();
                $empreId = $repo->getEmpresaId();
                $form = $this->createForm(new GiveUsersType(), $repo, array('empId' => $empreId, 'rolId' => $rolId));

                if($request->isMethod('POST'))
                {
                    $form->bind($request);
                    if($form->isValid())
                    {
                        $em->merge($repo);
                        $em->flush();

                        $this->get('session')->getFlashBag()->add('notice', 'Repositorio modificado correctamente');

                        if($this->getUser()->getRolId() == 1)
                        {
                            return $this->redirect($this->generateUrl('mg_repoadmin_homepage', array('page' => 1)));
                        }else
                        {
                            return $this->redirect($this->generateUrl('mg_repo_by_empresa', array('page' => 1, 'empId' => $empId)));
                        }

                    }
                }

                return $this->render('MGAdminBundle:Repos:giveuserrepo.html.twig', array('assignUserForm' => $form->createView(), 'repId' => $repoId, 'empId' => $empId));
            }elseif($tieneEmpresa == 1 && $pertenece == 1 && $this->getUser()->getRolId() == 3)
            {
                $rolId = $this->getUser()->getRolId();
                $empreId = $repo->getEmpresaId();
                $form = $this->createForm(new GiveUsersType(), $repo, array('empId' => $empreId, 'rolId' => $rolId));

                if($request->isMethod('POST'))
                {
                    $form->bind($request);
                    if($form->isValid())
                    {
                        $em->merge($repo);
                        $em->flush();

                        $this->get('session')->getFlashBag()->add('notice', 'Repositorio modificado correctamente');

                            return $this->redirect($this->generateUrl('mg_repo_by_empresa', array('page' => 1, 'empId' => $empId)));
                    }
                }

                return $this->render('MGAdminBundle:Repos:giveuserrepo.html.twig', array('assignUserForm' => $form->createView(), 'repId' => $repoId, 'empId' => $empId));
            }
            else
            {
                $mensaje = "No tienes permisos para asignar usuarios a este repositorio.";
                $msjTipo = "error";
                return $this->render('MGAdminBundle:Msg:messages.html.twig', array('mensaje' => $mensaje, 'tipo' => $msjTipo));
            }

        } else
        {
            $mensaje = "No se pueden asignar usuarios aun repositorio inactivo";
            $msjTipo = "error";
            return $this->render('MGAdminBundle:Msg:messages.html.twig', array('mensaje' => $mensaje, 'tipo' => $msjTipo));
        }

    }

    public function giveClientsAction($repoId, Request $request, $empId = 0)
    {
        $em = $this->getDoctrine()->getManager();

        $repo = $em->getRepository('MGRepoBundle:Repo')
            ->find($repoId);

        if(!$repo)
        {
            $this->get('session')->getFlashBag()->add('fail', 'No existe el repositorio');
            return $this->redirect($this->generateUrl('mg_repoadmin_homepage', array('page'=> 1)));
        }

        $users_repo = $repo->getUsers();
        $empresa_repo = $repo->getEmpresa();
        $tieneEmpresa = 0;
        $pertenece = 0;
        foreach($this->getUser()->getEmpresas() as $empresa)
        {
            if($empresa == $empresa_repo)
            {
                $tieneEmpresa = 1;
            }
        }

        foreach($users_repo as $user)
        {
            if($user == $this->getUser())
            {
                $pertenece = 1;
            }
        }

        if($repo->isEnabled() == true )
        {
            if(($tieneEmpresa == 1 && $this->getUser()->getRolId() == 2) || $this->getUser()->getRolId() == 1)
            {
                $empreId = $repo->getEmpresaId();
                $form = $this->createForm(new GiveClientsType(), $repo, array('empId' => $empreId));

                if($request->isMethod('POST'))
                {
                    $form->bind($request);
                    if($form->isValid())
                    {
                        $em->merge($repo);
                        $em->flush();

                        $this->get('session')->getFlashBag()->add('notice', 'Clientes agregados correctamente');

                        if($this->getUser()->getRolId() == 1)
                        {
                            return $this->redirect($this->generateUrl('mg_repoadmin_homepage', array('page' => 1)));
                        }else
                        {
                            return $this->redirect($this->generateUrl('mg_repo_by_empresa', array('page' => 1, 'empId' => $empId)));
                        }

                    }
                }

                return $this->render('MGAdminBundle:Repos:giveclientsrepo.html.twig', array('assignUserForm' => $form->createView(), 'repId' => $repoId, 'empId' => $empId));
            }elseif($tieneEmpresa == 1 && $pertenece == 1 && $this->getUser()->getRolId() == 3)
            {
                $empreId = $repo->getEmpresaId();
                $form = $this->createForm(new GiveClientsType(), $repo, array('empId' => $empreId));

                if($request->isMethod('POST'))
                {
                    $form->bind($request);
                    if($form->isValid())
                    {
                        $em->merge($repo);
                        $em->flush();

                        $this->get('session')->getFlashBag()->add('notice', 'Clientes agregados correctamente');
                        return $this->redirect($this->generateUrl('mg_repo_by_empresa', array('page' => 1, 'empId' => $empId)));
                    }
                }

                return $this->render('MGAdminBundle:Repos:giveclientsrepo.html.twig', array('assignUserForm' => $form->createView(), 'repId' => $repoId, 'empId' => $empId));
            }
            else
            {
                $mensaje = "No tienes permisos para asignar clientes a este repositorio.";
                $msjTipo = "error";
                return $this->render('MGAdminBundle:Msg:messages.html.twig', array('mensaje' => $mensaje, 'tipo' => $msjTipo));
            }

        } else
        {
            $mensaje = "No se pueden asignar clientes aun repositorio inactivo";
            $msjTipo = "error";
            return $this->render('MGAdminBundle:Msg:messages.html.twig', array('mensaje' => $mensaje, 'tipo' => $msjTipo));
        }
    }

    public function listRepoEmpresaAction($empId, $limit = 10, $page = 1)
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

            if(!$empresa)
            {
                $this->get('session')->getFlashBag()->add('fail', 'No existe la empresa seleccionada');
                return $this->redirect($this->generateUrl('mg_admin_homepage', array('page' => 1)));
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
                return $this->redirect($this->generateUrl('mg_admin_homepage', array('page' => 1)));
            }

            $nombre_empresa = $empresa->getNombre();

            $allr = $empresa->getRepo();

            if(sizeof($allr) < 1)
            {
                $this->get('session')->getFlashBag()->add('fail', 'No existen repositorios para esa empresa');
                return $this->redirect($this->generateUrl('mg_admin_homepage', array('page' => 1)));
            }

            for($i = $offset; $i < $limit + $offset; $i++)
            {
                if($allr[$i])
                {
                    $repositorios[] = $allr[$i];
                }
            }

            $page_number = ceil(count($allr)/$limit);

            if($page_number == $page)
            {
                $last = true;
            }

            return $this->render('MGAdminBundle:Repos:repogestionfiltered.html.twig', array(
                'repositorios' => $repositorios,
                'nombre_emp' => $nombre_empresa,
                'pages' => $page_number,
                'first' => $first,
                'last' => $last,
                'page' => $page,
                'empId' => $empId,
                'empresas' => $alle
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

            if(!$empresa)
            {
                $this->get('session')->getFlashBag()->add('fail', 'No existe la empresa seleccionada');
                return $this->redirect($this->generateUrl('mg_admin_homepage'));
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
                return $this->redirect($this->generateUrl('mg_admin_homepage'));
            }

            $nombre_empresa = $empresa->getNombre();

            $allr = $this->getUser()->getRepos();

            if(sizeof($allr) < 1)
            {
                $this->get('session')->getFlashBag()->add('fail', 'No existen repositorios para esa empresa');
                return $this->redirect($this->generateUrl('mg_admin_homepage'));
            }

           for($i = $offset; $i < $limit + $offset; $i++)
            {
                if(isset($allr[$i]))
                {
                  if($allr[$i]->getEmpresaId() == $empId)
                  {
                      $repositorios[] = $allr[$i];
                  }
                }
            }

            if(!isset($repositorios))
            {
                $this->get('session')->getFlashBag()->add('fail', 'No existen repositorios asignados al usuario');
                return $this->redirect($this->generateUrl('mg_admin_homepage'));
            }

            $page_number = ceil(count($allr)/$limit);

            if($page_number == $page)
            {
                $last = true;
            }

            return $this->render('MGAdminBundle:Repos:repogestionfiltered.html.twig', array(
                'repositorios' => $repositorios,
                'nombre_emp' => $nombre_empresa,
                'pages' => $page_number,
                'first' => $first,
                'last' => $last,
                'page' => $page,
                'empId' => $empId,
                'empresas' => $alle
            ));
        }
        else
        {
            $mensaje = "Acceso no permitido";
            $msjTipo = "error";
            return $this->render('MGAdminBundle:Msg:messages.html.twig', array('mensaje' => $mensaje, 'tipo' => $msjTipo));
        }

    }

    public function showFilesAction($repoId, $limit = 10, $page = 1 )
    {
        $first = true;
        $last = false;

        if(!isset($page) || $page < 2)
        {
            $page = 1;
            $first = false;
        }

        $offset = 10 * ($page - 1);

        if($this->getUser()->getRol()->getRolName() == 'ROLE_ADMIN' || $this->getUser()->getRol()->getRolName() == 'ROLE_SUPER_ADMIN')
        {
            $em = $this->getDoctrine()->getManager();

            $repo = $em->getRepository('MGRepoBundle:Repo')
                ->find($repoId);

            $tieneEmpresa = false;
            $empresa_repo = $repo->getEmpresa();

            foreach($this->getUser()->getEmpresas() as $empresa)
            {
                if($empresa == $empresa_repo)
                {
                    $tieneEmpresa = 1;
                }
            }

            if($tieneEmpresa == true || $this->getUser()->getRol()->getRolName() == 'ROLE_SUPER_ADMIN' )
            {
                $repo_files = $em->getRepository('MGRepoBundle:Archivo');

                if(!$repo_files || !isset($repo_files))
                {
                    $mensaje = "No se han encontrado ficheros almacenados en el servidor.";
                    $msjTipo = "error";
                    return $this->render('MGAdminBundle:Msg:messages.html.twig', array('mensaje' => $mensaje, 'tipo' => $msjTipo));
                }

                $categorized_files = $repo_files->findAllByCategoryRepo($repoId, $limit, $offset);


                if(!$categorized_files || !isset($categorized_files))
                {
                    $mensaje = "No se han encontrado ficheros almacenados en el servidor.";
                    $msjTipo = "error";
                    return $this->render('MGAdminBundle:Msg:messages.html.twig', array('mensaje' => $mensaje, 'tipo' => $msjTipo));
                }

                $alla = $repo->getArchivos();

                if(!isset($alla) || sizeof($alla) < 1 || !$alla)
                {
                    $this->get('session')->getFlashBag()->add('fail', 'No existen ficheros en el repositorio.');
                    return $this->redirect($this->generateUrl('mg_repoadmin_homepage', array('page'=> 1)));
                }

                $page_number = ceil(count($alla)/$limit);

                if($page_number == $page)
                {
                    $last = true;
                }

                $searchForm = $this->createForm(new SearchFileType());

                return $this->render('MGAdminBundle:Repos:indexfiles.html.twig',
                    array(
                        'files' => $categorized_files,
                        'pages' => $page_number,
                        'first' => $first,
                        'last' => $last,
                        'page' => $page,
                        'repoId' => $repoId,
                        'searchForm' => $searchForm->createView()
                    ));
            }else
            {
                $mensaje = "No estas autorizado a acceder a esta área.";
                $msjTipo = "error";
                return $this->render('MGAdminBundle:Msg:messages.html.twig', array('mensaje' => $mensaje, 'tipo' => $msjTipo));
            }
        }
        elseif($this->getUser()->getRol()->getRolName() == 'ROLE_SUBGES')
        {
            $tieneEmpresa = false;
            $pertenece = false;

            $em = $this->getDoctrine()->getManager();

            $repo = $em->getRepository('MGRepoBundle:Repo')
                ->find($repoId);

            if(!$repo)
            {
                $this->get('session')->getFlashBag()->add('fail', 'No existe el repositorio');
                return $this->redirect($this->generateUrl('mg_repoadmin_homepage', array('page'=> 1)));
            }

            $em = $this->getDoctrine()->getManager();

            $empresa_repo = $repo->getEmpresa();
            $users_repo = $repo->getUsers();

            foreach($users_repo as $user)
            {
                if($user == $this->getUser())
                {
                    $pertenece = 1;
                }
            }

            foreach($this->getUser()->getEmpresas() as $empresa)
            {
                if($empresa == $empresa_repo)
                {
                    $tieneEmpresa = 1;
                }
            }

            if($pertenece == true && $tieneEmpresa == true)
            {
                $repo_files = $em->getRepository('MGRepoBundle:Archivo');

                $categorized_files = $repo_files->findAllByCategoryUser($this->getUser()->getId(), $repoId, $limit, $offset);

                if(!$categorized_files || !isset($categorized_files))
                {
                    $mensaje = "No se han encontrado ficheros almacenados en el servidor.";
                    $msjTipo = "error";
                    return $this->render('MGAdminBundle:Msg:messages.html.twig', array('mensaje' => $mensaje, 'tipo' => $msjTipo));
                }

                $alla = $repo_files->findAllByCategoryUserNL($this->getUser()->getId(), $repoId);

                $page_number = ceil(count($alla)/$limit);

                if($page_number == $page)
                {
                    $last = true;
                }

                $form = $this->createForm(new SearchFileType());

                return $this->render('MGAdminBundle:Repos:indexfiles.html.twig',
                    array(
                        'files' => $categorized_files,
                        'pages' => $page_number,
                        'first' => $first,
                        'last' => $last,
                        'page' => $page,
                        'repoId' => $repoId,
                        'searchForm' => $form->createView()
                    ));
            }else
            {
                $mensaje = "No estas autorizado a acceder a esta área.";
                $msjTipo = "error";
                return $this->render('MGAdminBundle:Msg:messages.html.twig', array('mensaje' => $mensaje, 'tipo' => $msjTipo));
            }
        }
        else
        {
            $mensaje = "No estas autorizado a acceder a esta área.";
            $msjTipo = "error";
            return $this->render('MGAdminBundle:Msg:messages.html.twig', array('mensaje' => $mensaje, 'tipo' => $msjTipo));
        }
    }

    public function showUserFilesAction($userId, $limit = 10, $page = 1)
    {
        $first = true;
        $last = false;

        if(!isset($page) || $page < 2)
        {
            $page = 1;
            $first = false;
        }

        $offset = 10 * ($page - 1);

        if($this->getUser()->getRol()->getRolName() == 'ROLE_SUPER_ADMIN')
        {

            $em = $this->getDoctrine()->getManager();

            $cliente = $em->getRepository('MGUserBundle:User')
                ->find($userId);

            $userFilesP = $em->getRepository('MGRepoBundle:Archivo')
               ->findAllByOwner($userId, $limit, $offset);

            if(!$userFilesP)
            {
              $this->get('session')->getFlashBag()->add('fail', 'No existen ficheros en el repositorio.');
              return $this->redirect($this->generateUrl('mg_repoadmin_homepage', array('page'=> 1)));
            }

            $alla = $cliente->getArchivos();

            if(!isset($alla) || sizeof($alla) < 1 || !$alla)
            {
              $this->get('session')->getFlashBag()->add('fail', 'No existen ficheros en el repositorio.');
              return $this->redirect($this->generateUrl('mg_repoadmin_homepage', array('page'=> 1)));
            }

            $page_number = ceil(count($alla)/$limit);

            if($page_number == $page)
            {
              $last = true;
            }

            return $this->render('MGAdminBundle:Repos:userfiles.html.twig',
                array(
                  'files' => $userFilesP,
                  'pages' => $page_number,
                  'first' => $first,
                  'last' => $last,
                  'page' => $page,

                ));
        }
        elseif($this->getUser()->getRol()->getRolName() == 'ROLE_ADMIN')
        {
            $em = $this->getDoctrine()->getManager();

            $userFiles = $em->getRepository('MGRepoBundle:Archivo')->findAllByOwnerNL($userId);

            if(!$userFiles)
            {
                $this->get('session')->getFlashBag()->add('fail', 'No existen ficheros en el repositorio.');
                return $this->redirect($this->generateUrl('mg_repoadmin_homepage', array('page'=> 1)));
            }

            $alla = array();

            foreach($userFiles as $f)
            {
                foreach($this->getUser()->getEmpresas() as $e)
                {
                    if($f->getRepo()->getEmpresaId() == $e->getId())
                    {
                        $alla[] = $f;
                    }
                }

            }

            if(!$alla || sizeof($alla) < 1)
            {
                $this->get('session')->getFlashBag()->add('fail', 'No existen ficheros en el repositorio.');
                return $this->redirect($this->generateUrl('mg_repoadmin_homepage', array('page'=> 1)));
            }


            for($i = $offset; $i < $limit + $offset; $i++)
            {
                if(isset($alla[$i]))
                {
                    $userFilesP[] = $alla[$i];
                }
            }

            if(!isset($userFilesP))
            {
                $this->get('session')->getFlashBag()->add('fail', 'No existen ficheros en el repositorio.');
                return $this->redirect($this->generateUrl('mg_repoadmin_homepage', array('page'=> 1)));
            }

            $page_number = ceil(count($alla)/$limit);

            if($page_number == $page)
            {
                $last = true;
            }

            return $this->render('MGAdminBundle:Repos:userfiles.html.twig',
                array(
                    'files' => $userFilesP,
                    'pages' => $page_number,
                    'first' => $first,
                    'last' => $last,
                    'page' => $page
                ));
        }
        elseif($this->getUser()->getRol()->getRolName() == 'ROLE_SUBGES')
        {
            $em = $this->getDoctrine()->getManager();

            $userFiles = $em->getRepository('MGRepoBundle:Archivo')->findAllByOwnerNL($userId);

            if(!$userFiles)
            {
                $this->get('session')->getFlashBag()->add('fail', 'No existen ficheros en el repositorio.');
                return $this->redirect($this->generateUrl('mg_repoadmin_homepage', array('page'=> 1)));
            }

            $alla = array();

            foreach($userFiles as $f)
            {
                foreach($this->getUser()->getEmpresas() as $e)
                {
                    if($f->getRepo()->getEmpresaId() == $e->getId())
                    {
                        foreach ($this->getUser()->getRepoUsers() as $ru)
                        {
                            if($ru->getId() == $f->getRepo()->getId())
                            {
                                foreach($this->getUser()->getServUsers() as $s)
                                {
                                    if($f->getGestion()->getCategoria()->getServicio()->getId() == $s->getId())
                                    {
                                        $alla[] = $f;
                                    }
                                }
                            }
                        }

                    }
                }

            }

            if(!$alla || sizeof($alla) < 1)
            {
                $this->get('session')->getFlashBag()->add('fail', 'No existen ficheros en el repositorio.');
                return $this->redirect($this->generateUrl('mg_repoadmin_homepage', array('page'=> 1)));
            }


            for($i = $offset; $i < $limit + $offset; $i++)
            {
                if(isset($alla[$i]))
                {
                    $userFilesP[] = $alla[$i];
                }
            }

            if(!isset($userFilesP))
            {
                $this->get('session')->getFlashBag()->add('fail', 'No existen ficheros en el repositorio.');
                return $this->redirect($this->generateUrl('mg_repoadmin_homepage', array('page'=> 1)));
            }


            $page_number = ceil(count($alla)/$limit);

            if($page_number == $page)
            {
                $last = true;
            }

            return $this->render('MGAdminBundle:Repos:userfiles.html.twig',
                array(
                    'files' => $userFilesP,
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

    public function takeDateFromUserAction(Request $request, $repoId)
    {
        $form = $this->createForm(new SearchFileType());

        if($request->isMethod('POST'))
        {
            $form->bind($request);
            $formData = $this->getRequest()->request->get($form->getName());
            if($form->isValid())
            {
                $fechaIni = $formData['fechaIni'];
                $fechaFin = $formData['fechaFin'];


                return $this->redirect($this->generateUrl('mg_repo_filter_files_repo', array(
                    'fechaIni' => $fechaIni,
                    'fechaFin' => $fechaFin,
                    'repoId' => $repoId,
                    'page' => 1
                )));
                
            }else
            {
                $mensaje = "Información no valida.";
                $msjTipo = "error";
                return $this->render('MGAdminBundle:Msg:messages.html.twig', array('mensaje' => $mensaje, 'tipo' => $msjTipo));
            }
        }else
        {
            $mensaje = "Error envío.";
            $msjTipo = "error";
            return $this->render('MGAdminBundle:Msg:messages.html.twig', array('mensaje' => $mensaje, 'tipo' => $msjTipo));
        }

    }

    public function filterDateFilesAction($fechaIni, $fechaFin, $repoId, $limit = 10, $page = 1)
    {
        $first = true;
        $last = false;

        if(!isset($page) || $page < 2)
        {
            $page = 1;
            $first = false;
        }

        $offset = 10 * ($page - 1);

        if($this->getUser()->getRol()->getRolName() == 'ROLE_SUPER_ADMIN')
        {

            $em = $this->getDoctrine()->getManager();

            $files = $em->getRepository('MGRepoBundle:Archivo')
                ->findBetweenDates($fechaIni, $fechaFin, $repoId, $limit, $offset);


            if(!$files)
            {
                $this->get('session')->getFlashBag()->add('fail', 'No existen ficheros para mostrar entre esas fechas.');
                return $this->redirect($this->generateUrl('mg_repo_show_all_files', array('repoId'=>$repoId, 'page'=> 1)));
            }

            $alla = $em->getRepository('MGRepoBundle:Archivo')
                ->findBetweenDatesNL($fechaIni, $fechaFin, $repoId);

            $page_number = ceil(count($alla)/$limit);

            if($page_number == $page)
            {
                $last = true;
            }

            $form = $this->createForm(new SearchFileType());

            return $this->render('MGAdminBundle:Repos:indexdatefiles.html.twig',
                array(
                    'files' => $files,
                    'pages' => $page_number,
                    'first' => $first,
                    'last' => $last,
                    'page' => $page,
                    'repoId' => $repoId,
                    'fechaIni' => $fechaIni,
                    'fechaFin' => $fechaFin,
                    'searchForm' => $form->createView()
                ));

        }
        elseif($this->getUser()->getRol()->getRolName() == 'ROLE_ADMIN')
        {

            $pertenece = false;
            $em = $this->getDoctrine()->getManager();

            $repo = $em->getRepository('MGRepoBundle:Repo')
                ->find($repoId);

            if(!$repo)
            {
                $this->get('session')->getFlashBag()->add('fail', 'No existe el repositorio a buscar.');
                return $this->redirect($this->generateUrl('mg_repo_show_all_files', array('repoId'=>$repoId, 'page'=> 1)));
            }

            foreach($this->getUser()->getEmpresas() as $e)
            {
                if($e->getId() == $repo->getEmpresa()->getId())
                {
                    $pertenece = true;
                }
            }

            if($pertenece == true)
            {
                $files = $em->getRepository('MGRepoBundle:Archivo')
                    ->findBetweenDates($fechaIni, $fechaFin, $repoId, $limit, $offset);


                if(!$files)
                {
                    $this->get('session')->getFlashBag()->add('fail', 'No existen ficheros para mostrar entre esas fechas.');
                    return $this->redirect($this->generateUrl('mg_repo_show_all_files', array('repoId'=>$repoId, 'page'=> 1)));
                }

                $alla = $em->getRepository('MGRepoBundle:Archivo')
                    ->findBetweenDatesNL($fechaIni, $fechaFin, $repoId);

                $page_number = ceil(count($alla)/$limit);

                if($page_number == $page)
                {
                    $last = true;
                }

                $form = $this->createForm(new SearchFileType());

                return $this->render('MGAdminBundle:Repos:indexdatefiles.html.twig',
                    array(
                        'files' => $files,
                        'pages' => $page_number,
                        'first' => $first,
                        'last' => $last,
                        'page' => $page,
                        'repoId' => $repoId,
                        'fechaIni' => $fechaIni,
                        'fechaFin' => $fechaFin,
                        'searchForm' => $form->createView()
                    ));
            }else
            {
                $this->get('session')->getFlashBag()->add('fail', 'No tienes permisos para acceder a esta busqueda.');
                return $this->redirect($this->generateUrl('mg_repoadmin_homepage', array('repoId'=>$repoId, 'page'=> 1)));
            }
        }
        elseif($this->getUser()->getRol()->getRolName() == 'ROLE_SUBGES')
        {
            $tieneEmpresa = false;
            $pertenece = false;

            $em = $this->getDoctrine()->getManager();

            $repo = $em->getRepository('MGRepoBundle:Repo')
                ->find($repoId);

            if(!$repo)
            {
                $this->get('session')->getFlashBag()->add('fail', 'No existe el repositorio');
                return $this->redirect($this->generateUrl('mg_repoadmin_homepage', array('page'=> 1)));
            }

            $em = $this->getDoctrine()->getManager();

            $empresa_repo = $repo->getEmpresa();
            $users_repo = $repo->getUsers();

            foreach($users_repo as $user)
            {
                if($user == $this->getUser())
                {
                    $pertenece = 1;
                }
            }

            foreach($this->getUser()->getEmpresas() as $empresa)
            {
                if($empresa == $empresa_repo)
                {
                    $tieneEmpresa = 1;
                }
            }

            if($pertenece == true && $tieneEmpresa == true)
            {
                $repo_files = $em->getRepository('MGRepoBundle:Archivo');

                $categorized_files = $repo_files->findAllByCategoryUser($this->getUser()->getId(), $repoId, $limit, $offset);

                if(!$categorized_files || !isset($categorized_files))
                {
                    $mensaje = "No se han encontrado ficheros almacenados en el servidor.";
                    $msjTipo = "error";
                    return $this->render('MGAdminBundle:Msg:messages.html.twig', array('mensaje' => $mensaje, 'tipo' => $msjTipo));
                }

                $alla = $repo_files->findAllByCategoryUserNL($this->getUser()->getId(), $repoId);

                $page_number = ceil(count($alla)/$limit);

                if($page_number == $page)
                {
                    $last = true;
                }

                $form = $this->createForm(new SearchFileType());

                return $this->render('MGAdminBundle:Repos:indexfiles.html.twig',
                    array(
                        'files' => $categorized_files,
                        'pages' => $page_number,
                        'first' => $first,
                        'last' => $last,
                        'page' => $page,
                        'repoId' => $repoId,
                        'searchForm' => $form->createView()
                    ));
            }else
            {
                $mensaje = "No estas autorizado a acceder a esta área.";
                $msjTipo = "error";
                return $this->render('MGAdminBundle:Msg:messages.html.twig', array('mensaje' => $mensaje, 'tipo' => $msjTipo));
            }
        }
        else
        {
            $this->get('session')->getFlashBag()->add('fail', 'No existen ficheros entre esas fechas.');
            return $this->redirect($this->generateUrl('mg_repo_show_all_files', array('repoId'=>$repoId, 'page'=> 1)));
        }

  }


}

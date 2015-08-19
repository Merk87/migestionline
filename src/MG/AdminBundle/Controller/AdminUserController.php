<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Merkury
 * Date: 11/06/13
 * Time: 11:10
 * To change this template use File | Settings | File Templates.
 */

namespace MG\AdminBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use MG\UserBundle\Form\UserType;
use MG\UserBundle\Form\UserUpdateType;
use Symfony\Component\HttpFoundation\Request;

class AdminUserController extends Controller
{
    public function indexAction($page = NULL, $limit = 10)
    {
        $userRol = $this->getUser()->getRolId();
        if($userRol == 1)
        {
            $first = true;
            $last = false;

            if(!isset($page) || $page < 2)
            {
                $page = 1;
                $first = false;
            }
            $userRepo = $this->getDoctrine()->getRepository('MGUserBundle:User');

            $allu = $userRepo->findAll();

            $usuarios = $userRepo->findAllWithLimitAndOffset($limit, ($page-1)*$limit);

            if(!$usuarios)
            {
                $this->get('session')->getFlashBag()->add('fail', 'No hay usuarios actualmente registrados');
                return $this->redirect($this->generateUrl('mg_admin_homepage'));
            }

            $em = $this->getDoctrine()->getManager();

            $empresas = $em->getRepository('MGAdminBundle:Empresa')
                ->findAll();

            if(!$empresas)
            {
                $this->get('session')->getFlashBag()->add('fail', 'No existen empresas disponibles');
                return $this->redirect($this->generateUrl('mg_admin_homepage'));
            }

            $page_number = ceil(count($allu)/$limit);

            if($page_number == $page)
            {
                $last = true;
            }

            return $this->render('MGAdminBundle:Users:usergestion.html.twig', array(
                'usuarios' => $usuarios,
                'pages' => $page_number,
                'first' => $first,
                'last' => $last,
                'page' => $page,
                'empresas' => $empresas,
                'rolId' => $userRol

            ));
        }else
        {

            $empresas = $this->getUser()->getEmpresas();
            if(sizeof($empresas) == 1)
            {
                return $this->redirect($this->generateUrl('mg_user_by_empresa', array('empresaID' => $empresas[0]->getId(),'page'=> 1)));
            }
            else
            {
                return $this->render('MGAdminBundle:Users:selectemp.html.twig', array('empresas' => $empresas));
            }
        }
    }

    public function createAction(Request $request, $empId=0)
    {
        $userManager = $this->get('fos_user.user_manager');

        $user = $userManager->createUser();

        if($this->getUser()->getRolId() <= 3)
        {
            $form = $this->createForm(new UserType('mg_userbundle_usertype'),
                $user,
                array(
                    'userRol' => $this->getUser()->getRolId(),
                    'userId' => $this->getUser()->getId()
                ));

            if($request->isMethod('POST'))
            {
                $form->bind($request);
                if($form->isValid())
                {
                    $formData = $this->getRequest()->request->get($form->getName());
                    $rol = $formData['rol'];

                    $em = $this->getDoctrine()->getManager();

                    $role_name = $em->getRepository('MGUserBundle:Rol')
                        ->findOneById($rol)
                        ->getRolName();

                    $user->addRole($role_name);
                    $user->setEnabled(true);
                    $user->setNuevo(false);
                    $userManager->updateUser($user);
                    $this->get('session')->getFlashBag()->add('notice', '¡Creación realizada con exito!');
                    if($this->getUser()->getRolId() == 1)
                    {
                        return $this->redirect($this->generateUrl('mg_useradmin_homepage', array('page'=> 1)));
                    }else
                    {
                        $empresas = $this->getUser()->getEmpresas();
                        if(sizeof($empresas) == 1)
                        {
                            return $this->redirect($this->generateUrl('mg_user_by_empresa', array('empresaID' => $empresas[0]->getId(),'page'=> 1)));
                        }
                        else
                        {
                            return $this->redirect($this->generateUrl('mg_useradmin_homepage', array('page'=> 1)));
                        }
                    }

                }
            }

            return $this->render('MGAdminBundle:Users:newuser.html.twig', array('createForm'=>$form->createView()));
        }else
        {
            $mensaje = "No tienes permisos para visualizar esta página.";
            $msjTipo = "error";
            return $this->render('MGAdminBundle:Msg:messages.html.twig', array('mensaje' => $mensaje, 'tipo' => $msjTipo));
        }

    }

    public function blockAction($userName)
    {
        $userManager = $this->get('fos_user.user_manager');
        $user = $userManager->findUserByUsername($userName);

        if(!$user)
        {
            $this->get('session')->getFlashBag()->add('fail', 'No se encuentra el usuario');
            return $this->redirect($this->generateUrl('mg_useradmin_homepage', array('page'=> 1)));
        }

        $tieneEmpresa = 0;
        foreach ($user->getEmpresas() as $empresa)
        {
            foreach($this->getUser()->getEmpresas() as $empresasCurrent)
            {
                if($empresa == $empresasCurrent)
                {
                    $tieneEmpresa++;
                }
            }
        }

        if($this->getUser()->getRolId() <= $user->getRolId())
        {
            if($tieneEmpresa > 0 || $this->getUser()->getRolId() == 1)
            {
                if($user->isLocked() == false)
                {
                    $user->setLocked(true);
                    $userManager->updateUser($user);
                    $this->get('session')->getFlashBag()->add('fail', 'Usuario bloqueado');
                    return $this->redirect($this->generateUrl('mg_useradmin_homepage', array('page'=> 1)));
                }else
                {
                    $user->setLocked(false);
                    $userManager->updateUser($user);
                    $this->get('session')->getFlashBag()->add('success', 'Usuario desbloqueado');
                    return $this->redirect($this->generateUrl('mg_useradmin_homepage', array('page'=> 1)));
                }
            }else
            {
                $mensaje = "El usuario no pertenece a tu empresa.";
                $msjTipo = "error";
                return $this->render('MGAdminBundle:Msg:messages.html.twig', array('mensaje' => $mensaje, 'tipo' => $msjTipo));
            }
        }else
        {
            $mensaje = "No tienes permisos para bloquear  este usuario.";
            $msjTipo = "error";
            return $this->render('MGAdminBundle:Msg:messages.html.twig', array('mensaje' => $mensaje, 'tipo' => $msjTipo));
        }

    }

    public function detailAction($userName, $empId=0)
    {
        $userManager = $this -> get('fos_user.user_manager');

        $user = $userManager->findUserByUsername($userName);
        if(!$user)
        {
            $this->get('session')->getFlashBag()->add('fail', 'No se encuentra el usuario');
            return $this->redirect($this->generateUrl('mg_user_by_empresa', array('empresaID' => $empId, 'page' => 1)));
        }

        $emp_users = $user->getEmpresas();
        if(!$emp_users)
        {
            $this->get('session')->getFlashBag()->add('fail', 'No se encuentra el usuario');
            return $this->redirect($this->generateUrl('mg_user_by_empresa', array('empresaID' => $empId, 'page' => 1)));
        }

        $tieneEmpresa = 0;
        foreach ($user->getEmpresas() as $empresa)
        {
            foreach($this->getUser()->getEmpresas() as $empresasCurrent)
            {
                if($empresa == $empresasCurrent)
                {
                    $tieneEmpresa++;
                }
            }
        }

        if($this->getUser()->getRolId() <= $user->getRolId())
        {
            if($tieneEmpresa > 0 || $this->getUser()->getRolId() == 1)
            {
                if($user->getNuevo() == true)
                {
                    $em = $this->getDoctrine()->getManager();
                    $user->setNuevo(false);
                    $em->persist($user);
                    $em->flush();
                }

                return $this -> render('MGAdminBundle:Users:detailuser.html.twig', array(
                    'userInfo' => $user, 'empresas' => $emp_users, 'empSelected' => $empId));
            }else
            {
                $mensaje = "El usuario no pertenece a tu empresa.";
                $msjTipo = "error";
                return $this->render('MGAdminBundle:Msg:messages.html.twig', array('mensaje' => $mensaje, 'tipo' => $msjTipo));
            }
        }else
        {
            $mensaje = "No tienes permisos para visualizar  este usuario.";
            $msjTipo = "error";
            return $this->render('MGAdminBundle:Msg:messages.html.twig', array('mensaje' => $mensaje, 'tipo' => $msjTipo));
        }
    }

    public function updateAction(Request $request ,$userName, $empId=0)
    {

        $userManager = $this -> get('fos_user.user_manager');

        $user = $userManager->findUserByUsername($userName);

        if(!$user)
        {
            $this->get('session')->getFlashBag()->add('fail', 'No se encuentra el usuario');
            return $this->redirect($this->generateUrl('mg_user_by_empresa', array('empresaID' => $empId, 'page' => 1)));
        }

        $tieneEmpresa = 0;
        foreach ($user->getEmpresas() as $empresa)
        {
            foreach($this->getUser()->getEmpresas() as $empresasCurrent)
            {
                if($empresa == $empresasCurrent)
                {
                    $tieneEmpresa++;
                }
            }
        }

        if($this->getUser()->getRolId() <= $user->getRolId())
        {
            if($tieneEmpresa > 0 || $this->getUser()->getRolId() == 1)
            {
                $form = $this -> createForm(new UserUpdateType('mg_userbundle_userupdatetype'),
                    $user,
                    array(
                        'userRol' => $this->getUser()->getRolId(),
                        'userId' => $this->getUser()->getId()
                    ));

                $em = $this->getDoctrine()->getManager();

                if($request->isMethod('POST'))
                {
                    $form->bind($request);
                    if($form->isValid())
                    {
                        $formData = $this->getRequest()->request->get($form->getName());
                        $rol = $formData['rol'];

                        $role_name = $em->getRepository('MGUserBundle:Rol')
                            ->findOneById($rol)
                            ->getRolName();

                        foreach ($user->getRoles() as $role)
                        {
                            $user->removeRole($role);
                        }

                        $user->addRole($role_name);
                        $em->merge($user);
                        $em->flush();
                        $this->get('session')->getFlashBag()->add('notice', '¡Actualización realizada con exito!');

                        if($this->getUser()->getRolId() == 1)
                        {
                            return $this->render('MGAdminBundle:Users:updateuser.html.twig',
                                array('userToEdit' => $user, 'editUserForm' => $form->createView(), 'empId' => $empId));
                        }elseif($empId == 0)
                        {
                            return $this->redirect($this->generateUrl('mg_admin_homepage'));
                        }
                        else
                        {
                            return $this->redirect($this->generateUrl('mg_user_by_empresa', array('empresaID' => $empId, 'page' => 1)));
                        }

                    }else{

                        $formData = $this->getRequest()->request->get($form->getName());

                        $sended_nif = $formData['nif'];
                        $check_nif = $em->getRepository('MGUserBundle:User')
                            ->findOneBy(array('nif' => $sended_nif))
                            ->getNif();

                        if($sended_nif == $check_nif)
                        {
                            $this->get('session')->getFlashBag()->add('fail', 'El NIF ya existe en la BBDD');
                        }
                    }
                }


                if($user->getNuevo() == true)
                {
                    $user->setNuevo(false);
                    $em->persist($user);
                    $em->flush();
                }

                return $this->render('MGAdminBundle:Users:updateuser.html.twig',
                    array('userToEdit' => $user, 'editUserForm' => $form->createView(), 'empId' => $empId));
            }else
            {
                $mensaje = "El usuario no pertenece a tu empresa.";
                $msjTipo = "error";
                return $this->render('MGAdminBundle:Msg:messages.html.twig', array('mensaje' => $mensaje, 'tipo' => $msjTipo));
            }
        }else
        {
            $mensaje = "No tienes permisos para editar este usuario.";
            $msjTipo = "error";
            return $this->render('MGAdminBundle:Msg:messages.html.twig', array('mensaje' => $mensaje, 'tipo' => $msjTipo));
        }

    }

    public function listUserEmpresaAction($empresaID, $limit = 10, $page = 1)
    {
        $tieneEmpresa = 0;
        foreach ($this->getUser()->getEmpresas() as $empresa) {
            if($empresaID == $empresa->getId())
            {
                $tieneEmpresa = 1;
            }
        }

        if($tieneEmpresa == 1 || $this->getUser()->getRolId() == 1)
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
                ->find($empresaID);

            if(!$empresa)
            {
                $this->get('session')->getFlashBag()->add('fail', 'No existe la empresa seleccionada');
                return $this->redirect($this->generateUrl('mg_empadmin_homepage', array('page' => 1)));
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


            if($this->getUser()->getRolId() == 1)
            {
                $allu = $empresa->getUsers();
            }else
            {
                $allu_pre = $empresa->getUsers();
                if(sizeof($allu_pre) < 1)
                {
                    $this->get('session')->getFlashBag()->add('fail', 'No existen usuarios para esa empresa');
                    return $this->redirect($this->generateUrl('mg_admin_homepage', array('page' => 1)));
                }

                foreach($allu_pre as $up)
                {
                    if($up->getRolId() != 1)
                    {
                        $allu[] = $up;
                    }
                }
            }

            if(!isset($allu) || sizeof($allu) < 1)
            {
                $this->get('session')->getFlashBag()->add('fail', 'No existen usuarios para esa empresa');
                return $this->redirect($this->generateUrl('mg_admin_homepage', array('page' => 1)));
            }

            for($i = $offset; $i < $limit + $offset; $i++)
            {
                if(isset($allu[$i]))
                {
                    if($allu[$i] != $this->getUser() && $this->getUser())
                    {
                        $usuarios[] = $allu[$i];
                    }
                }
            }

            if(!isset($usuarios))
            {
                $this->get('session')->getFlashBag()->add('fail', 'No existen usuarios para esa empresa');
                return $this->redirect($this->generateUrl('mg_admin_homepage', array('page' => 1)));
            }

            $page_number = ceil(count($allu)/$limit);

            if($page_number == $page)
            {
                $last = true;
            }

            return $this->render('MGAdminBundle:Users:usergestionfiltered.html.twig', array(
                'usuarios' => $usuarios,
                'nombre_emp' => $nombre_empresa,
                'pages' => $page_number,
                'first' => $first,
                'last' => $last,
                'page' => $page,
                'empId' => $empresaID,
                'empresas' => $alle
            ));
        }else
        {
              $mensaje = "Acceso no permitido";
              $msjTipo = "error";
              return $this->render('MGAdminBundle:Msg:messages.html.twig', array('mensaje' => $mensaje, 'tipo' => $msjTipo));
        }

    }

    public function listUserEmpresaRolAction($empresaID, $limit = 10, $page = 1, $rolId = NULL)
    {
        $tieneEmpresa = 0;
        foreach ($this->getUser()->getEmpresas() as $empresa) {
            if($empresaID == $empresa->getId())
            {
                $tieneEmpresa = 1;
            }
        }

        if(($tieneEmpresa == 1 && $this->getUser()->getRolId() <= $rolId) || $this->getUser()->getRolId() == 1)
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
                ->find($empresaID);

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

            $allu_pre = $empresa->getUsers();

            foreach ($allu_pre as $us)
            {
                if($us->getRolId() == $rolId)
                {
                    $allu[] = $us;
                }
            }

            if(!isset($allu) || sizeof($allu) < 1)
            {
                $this->get('session')->getFlashBag()->add('fail', 'No existen usuarios para esa empresa');
                return $this->redirect($this->generateUrl('mg_admin_homepage', array('page' => 1)));
            }

            $page_number = ceil(count($allu)/$limit);

            if($page_number == $page)
            {
                $last = true;
            }

            for($i = $offset; $i < $limit + $offset; $i++)
            {

                if(isset($allu[$i]))
                {
                    $usuarios[] = $allu[$i];
                }

            }

            if(!isset($usuarios) || sizeof($usuarios) < 1)
            {
                $this->get('session')->getFlashBag()->add('fail', 'No existen usuarios para esa empresa');
                return $this->redirect($this->generateUrl('mg_admin_homepage', array('page' => 1)));
            }

            return $this->render('MGAdminBundle:Users:usergestionfilteredrol.html.twig', array(
                'usuarios' => $usuarios,
                'nombre_emp' => $nombre_empresa,
                'pages' => $page_number,
                'first' => $first,
                'last' => $last,
                'page' => $page,
                'empId' => $empresaID,
                'empresas' => $alle,
                'rolId' => $rolId
            ));
        }else
        {
            $mensaje = "Acceso no permitido";
            $msjTipo = "error";
            return $this->render('MGAdminBundle:Msg:messages.html.twig', array('mensaje' => $mensaje, 'tipo' => $msjTipo));
        }
    }

}
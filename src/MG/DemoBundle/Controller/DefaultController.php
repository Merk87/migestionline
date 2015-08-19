<?php

namespace MG\DemoBundle\Controller;

use MG\MensajeriaBundle\Entity\Mensajes;
use MG\MensajeriaBundle\Form\MensajesType;
use MG\MensajeriaBundle\Form\NewMensajesAdminType;
use MG\RepoBundle\Entity\Archivo;
use MG\RepoBundle\Entity\Categoria;
use MG\RepoBundle\Entity\Comentarios;
use MG\RepoBundle\Entity\Gestion;
use MG\RepoBundle\Entity\Servicios;
use MG\RepoBundle\Form\ArchivoType;
use MG\RepoBundle\Form\CategoriaType;
use MG\RepoBundle\Form\ComentariosType;
use MG\RepoBundle\Form\EditServiciosType;
use MG\RepoBundle\Form\GestionAdminType;
use MG\RepoBundle\Form\GestionType;
use MG\RepoBundle\Form\ServiciosType;
use MG\UserBundle\Entity\User;
use MG\UserBundle\Form\UserType;
use MG\UserBundle\Form\UserUpdateType;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;


class DefaultController extends Controller
{

    //FUNCIONES PARA DEMO ADMIN
    public function indexAction()
    {
        return $this->render('MGDemoBundle:Default:index.html.twig');
    }

    public function loginDemoAction($target)
    {
        return $this->render('MGDemoBundle:Login:demologin.html.twig', array('target' => $target));
    }

    public function adminDemoAction()
    {
        return $this->render('MGDemoBundle:Admin:indexad.html.twig');
    }

    ///USER
    public function indexUserDemoAction()
    {
        return $this->render('MGDemoBundle:Admin:usergestion.html.twig');
    }

    public function createUserDemoAction(Request $request)
    {
        $userManager = $this->get('fos_user.user_manager');
        $user = $userManager->createUser();

        $form = $this->createForm(new UserType('mg_userbundle_usertype'), $user, array(
            'userRol' => '2'));

        if($request->isMethod('POST'))
        {
            $form->bind($request);

            $this->get('session')->getFlashBag()->add('notice', '¡Usuario agregado correctamente!');
            return $this->render('MGDemoBundle:Admin:usergestion.html.twig');
        }

        return $this->render('MGDemoBundle:Admin:newuser.html.twig', array('createForm'=> $form->createView()));
    }

    public function detailUserDemoAction()
    {
        return $this->render('MGDemoBundle:Admin:detailuser.html.twig');
    }

    public function updateUserDemoAction(Request $request)
    {
        $user = new User();

        $user->setUsername('UsuarioDemo1');
        $user->setEmail('usuariodemo1@migestiononline.com');
        $user->setName('Usuario 1');
        $user->setApellidos('Demo');
        $user->setNif('11111111H');
        $user->setTelefono('900000000');
        $user->setTelefonoMvl('900000000');
        $user->setDireccion('Calle Mayor 13 2A');
        $user->setCiudad('Madrid');
        $user->setCodigoPostal('28755');
        $user->setPais('España');

        $form = $this -> createForm(new UserUpdateType('mg_userbundle_userupdatetype'),
            $user,
            array(
                'userRol' => '2'
            ));

        if($request->isMethod('POST'))
        {
            $form->bind($request);
            $this->get('session')->getFlashBag()->add('notice', '¡Usuario actualizado correctamente!');
            return $this->render('MGDemoBundle:Admin:usergestion.html.twig');
        }

        return $this->render('MGDemoBundle:Admin:updateuser.html.twig', array('editUserForm' => $form->createView()));
    }

    public function listUserEmpresaDemoAction()
    {
        return $this->render('MGDemoBundle:Admin:usergestionfiltered.html.twig');
    }

    public function listUserEmpresaRolDemoAction()
    {
        return $this->render('MGDemoBundle:Admin:usergestionfilteredrol.html.twig');
    }
    ///FIN USER

    ///REPO
    public function indexRepoDemoAction()
    {
        return $this->render('MGDemoBundle:Admin:repogestion.html.twig');
    }

    public function detailRepoDemoAction()
    {
        return $this->render('MGDemoBundle:Admin:detailrepo.html.twig');
    }

    public function giveUsersRepoDemoAction(Request $request)
    {

        if($request->isMethod('POST'))
        {
            $this->get('session')->getFlashBag()->add('notice', '¡Usuarios actualizados correctamente!');
            return $this->render('MGDemoBundle:Admin:repogestion.html.twig');
        }

        return $this->render('MGDemoBundle:Admin:giveuserrepo.html.twig');
    }

    public function giveClientsRepoDemoAction(Request $request)
    {
        if($request->isMethod('POST'))
        {
            $this->get('session')->getFlashBag()->add('notice', '¡Usuarios actualizado correctamente!');
            return $this->render('MGDemoBundle:Admin:repogestion.html.twig');
        }
        return $this->render('MGDemoBundle:Admin:giveclientsrepo.html.twig');
    }

    public function listRepoEmpresaDemoAction()
    {
        return $this->render('MGDemoBundle:Admin:repogestionfiltered.html.twig');
    }

    public function viewFilesRepoAction()
    {
        return $this->render('MGDemoBundle:Admin:reposhowfiles.html.twig');
    }

    public function viewFilesRepoDateAction()
    {
        return $this->render('MGDemoBundle:Admin:reposhowfilesdate.html.twig');
    }

    public function viewFilesRepoUserAction()
    {
        return $this->render('MGDemoBundle:Admin:reposhowfilesuser.html.twig');
    }
    ///FIN REPO

    ///SERVICIOS
    public function indexServDemoAction()
    {
        return $this->render('MGDemoBundle:Admin:servgestion.html.twig');
    }

    public function detailServDemoAction()
    {
        return $this->render('MGDemoBundle:Admin:detailserv.html.twig');
    }

    public function createServDemoAction(Request $request)
    {
        $serv = new Servicios();
        $form = $this->createForm(new ServiciosType(), $serv);

        if($request->isMethod('POST'))
        {
            $this->get('session')->getFlashBag()->add('notice', '¡Servicio creado correctamente!');
            return $this->render('MGDemoBundle:Admin:servgestion.html.twig');
        }

        return $this->render('MGDemoBundle:Admin:newserv.html.twig', array('newServForm' => $form->createView()));
    }

    public function updateServDemoAction(Request $request)
    {

        $serv = new Servicios();

        $serv->setNombre('FISCAL');
        $serv->setDescripcion('FISCAL ESTIMACIÓN DIRECTA');
        $serv->setPrecio('150');

        $form = $this->createForm(new EditServiciosType(), $serv);

        if($request->isMethod('POST'))
        {
            $this->get('session')->getFlashBag()->add('notice', '¡Servicio actualizado correctamente!');
            return $this->render('MGDemoBundle:Admin:servgestion.html.twig');
        }

        return $this->render('MGDemoBundle:Admin:updateserv.html.twig', array('editServForm' => $form->createView()));
    }

    public function listServEmpresaDemoAction()
    {
        return $this->render('MGDemoBundle:Admin:servgestionfiltered.html.twig');
    }

    public function giveUsersServDemoAction(Request $request)
    {
        if($request->isMethod('POST'))
        {
            $this->get('session')->getFlashBag()->add('notice', '¡Usuarios actualizados correctamente!');
            return $this->render('MGDemoBundle:Admin:repogestion.html.twig');
        }
        return $this->render('MGDemoBundle:Admin:giveuserserv.html.twig');
    }

    public function giveClientsServDemoAction(Request $request)
    {
        if($request->isMethod('POST'))
        {
            $this->get('session')->getFlashBag()->add('notice', '¡Usuarios actualizados correctamente!');
            return $this->render('MGDemoBundle:Admin:repogestion.html.twig');
        }
        return $this->render('MGDemoBundle:Admin:giveclientsserv.html.twig');
    }
    ///FIN SERVICIOS

    ///CATEGORIAS
    public function indexCatDemoAction()
    {
        return $this->render('MGDemoBundle:Admin:catgestion.html.twig');
    }

    public function createCatDemoAction(Request $request)
    {

        $cat = new Categoria();

        $form = $this->createForm(new CategoriaType(),$cat);

        if($request->isMethod('POST'))
        {
            $this->get('session')->getFlashBag()->add('notice', '¡Categoría creada correctamente!');
            return $this->render('MGDemoBundle:Admin:repogestion.html.twig');
        }

        return $this->render('MGDemoBundle:Admin:newcat.html.twig', array('newCategoriaForm' => $form->createView()));
    }

    public function updateCatDemoAction(Request $request)
    {
        $cat = new Categoria();
        $serv = new Servicios();

        $cat->setNombre('Asesoría Tavernes.');
        $cat->setDescripcion('Categoría Fiscal');

        $form = $this->createForm(new CategoriaType(), $cat);

        if($request->isMethod('POST'))
        {
            $this->get('session')->getFlashBag()->add('notice', '¡Categoría actualizada correctamente!');
            return $this->render('MGDemoBundle:Admin:repogestion.html.twig');
        }

        return $this->render('MGDemoBundle:Admin:updatecat.html.twig', array('updateCategoriaForm' => $form->createView()));
    }

    public function detailCatDemoAction()
    {
        return $this->render('MGDemoBundle:Admin:detailcat.html.twig');
    }

    public function listCatEmpresaDemoAction()
    {
        return $this->render('MGDemoBundle:Admin:catgestionfiltered.html.twig');
    }
    ///FIN CATEGORIAS

    ///GESTION
    public function indexGestDemoAction()
    {
        return $this->render('MGDemoBundle:Admin:gestionindex.html.twig');
    }

    public function listGestEmpresaDemoAction()
    {
        return $this->render('MGDemoBundle:Admin:gestionindexfiltered.html.twig');
    }

    public function listGestEstadoEmpresaDemoAction()
    {
        return $this->render('MGDemoBundle:Admin:gestionindexfilteredest.html.twig');
    }

    public function manageGestDemoAction(Request $request)
    {
        $newFile = new Archivo();
        $newComment = new Comentarios();
        $form = $this->createForm(new ArchivoType(), $newFile);
        $form_com = $this->createForm(new ComentariosType(), $newComment);
        if($request->isMethod('POST'))
        {
            $this->get('session')->getFlashBag()->add('notice', 'Acción realizada con exito.');
            return $this->render('MGDemoBundle:Admin:gestionindex.html.twig');
        }

        return $this->render('MGDemoBundle:Admin:addfiles.html.twig', array(
            'addFilesForm' => $form->createView(),
            'newGestCommForm' => $form_com->createView()));
    }

    public function createGestAdminDemoAction(Request $request)
    {
        $gestion = new Gestion();
        $form = $this->createForm(new GestionAdminType(), $gestion);
        if($request->isMethod('POST'))
        {
            $this->get('session')->getFlashBag()->add('notice', 'Gestión creada con exito.');
            return $this->render('MGDemoBundle:Admin:gestionindex.html.twig');
        }
        return $this->render('MGDemoBundle:Admin:newgest.html.twig', array('newGestForm' => $form->createView()));
    }
    ///FIN GESTION

    ///MENSAJES

    public function indexMsgDemoAction()
    {
        return $this->render('MGDemoBundle:Admin:messagesadminindex.html.twig');
    }

    public function showChatDemoAction(Request $request)
    {
        $message = new Mensajes();

        $form = $this->createForm(new MensajesType(), $message);

        if($request->isMethod('POST'))
        {
            $this->get('session')->getFlashBag()->add('notice', 'Mensaje enviado con exito.');
            return $this->render('MGDemoBundle:Admin:messagesadminindex.html.twig');
        }

        return $this->render('MGDemoBundle:Admin:readpaneladmin.html.twig', array('sendMsgForm' => $form->createView()));
    }

    public function newChatAdminDemoAction(Request $request)
    {
        $mensaje = new Mensajes();
        $form = $this->createForm(new NewMensajesAdminType(), $mensaje);

        if($request->isMethod('POST'))
        {
            $this->get('session')->getFlashBag()->add('notice', 'Mensaje enviado con exito.');
            return $this->render('MGDemoBundle:Admin:messagesadminindex.html.twig');
        }

        return $this->render('MGDemoBundle:Admin:createmsgadmin.html.twig', array('createMsg' => $form->createView()));
    }


    //FIN FUNCIONES PARA DEMO ADMIN

    //FUNCIONES PARA DEMO CLIENTE

    public function userPanelDemoAction()
    {
        return $this->render('MGDemoBundle:Client:managegest.html.twig');
    }

    public function newGestionClienteDemoAction(Request $request)
    {
        $gestion = new Gestion();

        $form = $this->createForm(new GestionType(), $gestion);

        if($request->isMethod('POST'))
        {
            $this->get('session')->getFlashBag()->add('notice', 'Gestión creada con exito.');
            return $this->render('MGDemoBundle:Client:managegest.html.twig');
        }

        return $this->render('MGDemoBundle:Client:newgestion.html.twig', array('newGestionForm' => $form->createView()));
    }

    public function manageGestClientDemoAction(Request $request)
    {
        if($request->isMethod('POST'))
        {
            $this->get('session')->getFlashBag()->add('notice', 'Acción realizada correctamente.');
            return $this->render('MGDemoBundle:Client:managegest.html.twig');
        }

        return $this->render('MGDemoBundle:Client:clientGestmanage.html.twig');
    }

    public function userPanelFilteredDemoAction()
    {
        return $this->render('MGDemoBundle:Client:managegestcat.html.twig');

    }

    public function userPanelFilteredEstDemoAction()
    {
        return $this->render('MGDemoBundle:Client:managegestest.html.twig');
    }

    public function userMessagesPanelDemoAction()
    {
        return $this->render('MGDemoBundle:Client:messageindex.html.twig');
    }

    public function showMessageClientDemoAction(Request $request)
    {

        $message = new Mensajes();

        $form = $this->createForm(new MensajesType(), $message);

        if($request->isMethod('POST'))
        {
            $this->get('session')->getFlashBag()->add('notice', 'Mensaje enviado con exito.');
            return $this->render('MGDemoBundle:Client:messageindex.html.twig');
        }
        return $this->render('MGDemoBundle:Client:showmessage.html.twig', array('sendMsgForm' => $form->createView()));

    }

    public function newMessageClientDemoAction(Request $request)
    {
        $mensaje = new Mensajes();
        $form = $this->createForm(new NewMensajesAdminType(), $mensaje);

        if($request->isMethod('POST'))
        {
            $this->get('session')->getFlashBag()->add('notice', 'Mensaje enviado con exito.');
            return $this->render('MGDemoBundle:Client:messageindex.html.twig');
        }

        return $this->render('MGDemoBundle:Client:createmsgadmin.html.twig', array('createMsg' => $form->createView()));
    }

    public function showClientFilesDemoAction()
    {
        return $this->render('MGDemoBundle:Client:showfilesclient.html.twig');
    }

    public function showClientFilesDateDemoAction()
    {
        return $this->render('MGDemoBundle:Client:showfilesclientdate.html.twig');
    }
}

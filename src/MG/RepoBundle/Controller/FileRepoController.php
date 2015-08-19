<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Merkury
 * Date: 11/07/13
 * Time: 13:15
 * To change this template use File | Settings | File Templates.
 */

namespace MG\RepoBundle\Controller;
use MG\AdminBundle\Form\SearchFileType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class FileRepoController extends Controller
{
    public function showRepoFilesAction($limit = 10, $page = 1)
    {
        if($this->getUser()->getRol()->getRolName('ROLE_CLIENTE'))
        {
            $first = true;
            $last = false;

            if(!isset($page) || $page < 2)
            {
                $page = 1;
                $first = false;
            }

            $offset = 10 * ($page - 1);

            $em = $this->getDoctrine()->getManager();
            $repo_files = $em->getRepository('MGRepoBundle:Archivo');

            if(!$repo_files || !isset($repo_files))
            {
                $mensaje = "No se han encontrado ficheros almacenados en el servidor.";
                $msjTipo = "error";
                return $this->render('MGRepoBundle:Msg:messages.html.twig', array('mensaje' => $mensaje, 'tipo' => $msjTipo));
            }

            $categorized_files = $repo_files->findAllByOwner($this->getUser()->getId(), $limit, $offset);

            if(!$categorized_files || !isset($categorized_files))
            {
                $mensaje = "No se han encontrado ficheros almacenados en el servidor.";
                $msjTipo = "error";
                return $this->render('MGRepoBundle:Msg:messages.html.twig', array('mensaje' => $mensaje, 'tipo' => $msjTipo));
            }

            $alla = $this->getUser()->getArchivos();

            if(!isset($alla) || sizeof($alla) < 1 || !$alla)
            {
                $this->get('session')->getFlashBag()->add('fail', 'No existen ficheros en el repositorio.');
                return $this->redirect($this->generateUrl('mg_repo_homepage', array('page'=> 1)));
            }

            $page_number = ceil(count($alla)/$limit);

            if($page_number == $page)
            {
                $last = true;
            }

            $form = $this->createForm(new SearchFileType());

            return $this->render('MGRepoBundle:Archivos:showrepo.html.twig',
                array(
                    'files' => $categorized_files,
                    'pages' => $page_number,
                    'first' => $first,
                    'last' => $last,
                    'page' => $page,
                    'searchForm' => $form->createView()
            ));

            }
            else
            {
                $mensaje = "No tienes permisos para acceder a este area.";
                $msjTipo = "error";
                return $this->render('MGRepoBundle:Msg:messages.html.twig', array('mensaje' => $mensaje, 'tipo' => $msjTipo));
            }

    }

    public function takeDateFromPanelAction(Request $request)
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


                return $this->redirect($this->generateUrl('mg_show_files_by_date', array(
                    'fechaIni' => $fechaIni,
                    'fechaFin' => $fechaFin,
                    'page' => 1
                )));

            }else
            {
                $mensaje = "Información no valida.";
                $msjTipo = "error";
                return $this->render('MGRepoBundle:Msg:messages.html.twig', array('mensaje' => $mensaje, 'tipo' => $msjTipo));
            }
        }else
        {
            $mensaje = "Error envío.";
            $msjTipo = "error";
            return $this->render('MGRepoBundle:Msg:messages.html.twig', array('mensaje' => $mensaje, 'tipo' => $msjTipo));        }

    }

    public function filterDateFilesAction($fechaIni, $fechaFin, $limit = 10, $page = 1)
    {
        $first = true;
        $last = false;

        if(!isset($page) || $page < 2)
        {
            $page = 1;
            $first = false;
        }

        $offset = 10 * ($page - 1);

        if($this->getUser()->getRol()->getRolName() == 'ROLE_CLIENTE')
        {

            $em = $this->getDoctrine()->getManager();

            $files = $em->getRepository('MGRepoBundle:Archivo')
                ->findAllByOwnerBetweenDate($this->getUser()->getId(), $fechaIni, $fechaFin, $limit, $offset);

            if(!$files)
            {
                $this->get('session')->getFlashBag()->add('fail', 'No existen ficheros para mostrar entre esas fechas.');
                return $this->redirect($this->generateUrl('mg_cli_show_files', array('page'=> 1)));
            }

            $alla = $em->getRepository('MGRepoBundle:Archivo')
                ->findAllByOwnerBetweenDateNL($this->getUser()->getId(), $fechaIni, $fechaFin);

            $page_number = ceil(count($alla)/$limit);

            if($page_number == $page)
            {
                $last = true;
            }

            $form = $this->createForm(new SearchFileType());

            return $this->render('MGRepoBundle:Archivos:showrepofiltered.html.twig',
                array(
                    'files' => $files,
                    'pages' => $page_number,
                    'first' => $first,
                    'last' => $last,
                    'page' => $page,
                    'fechaIni' => $fechaIni,
                    'fechaFin' => $fechaFin,
                    'searchForm' => $form->createView()
                ));

        }
        else
        {
            $mensaje = "No tienes permisos para acceder a este area.";
            $msjTipo = "error";
            return $this->render('MGRepoBundle:Msg:messages.html.twig', array('mensaje' => $mensaje, 'tipo' => $msjTipo));
        }

    }
}

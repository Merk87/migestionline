<?php
    /**
     * Created by JetBrains PhpStorm.
     * User: Merkury
     * Date: 2/07/13
     * Time: 11:33
     * To change this template use File | Settings | File Templates.
     */

namespace MG\AdminBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use MG\RepoBundle\Entity\FileTypes;
use MG\RepoBundle\Form\FileTypesType;

class AdminFileTypeController extends Controller
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
            $fileTypeRepo = $this->getDoctrine()->getRepository('MGRepoBundle:FileTypes');

            $allf = $fileTypeRepo->findAll();

            $fileTypes = $fileTypeRepo->findAllWithLimitAndOffset($limit, ($page-1)*$limit);

            if(!$fileTypes)
            {
                $this->get('session')->getFlashBag()->add('fail', 'No hay ningún tipo de fichero registrado');
                return $this->redirect($this->generateUrl('mg_admin_homepage'));
            }

            $page_number = ceil(count($allf)/$limit);

            if($page_number == $page)
            {
                $last = true;
            }

            return $this->render('MGAdminBundle:FileTypes:filetypegestion.html.twig', array(
                'fileTypes' => $fileTypes,
                'pages' => $page_number,
                'first' => $first,
                'last' => $last,
                'page' => $page

            ));
        }else
        {
            $mensaje = "No tienes permisos para visualizar esta página.";
            $msjTipo = "error";
            return $this->render('MGAdminBundle:Msg:messages.html.twig', array('mensaje' => $mensaje, 'tipo' => $msjTipo));
        }
    }


}
<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Merkury
 * Date: 11/09/13
 * Time: 14:27
 * To change this template use File | Settings | File Templates.
 */

namespace MG\AdminBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AdminContratacionController extends Controller
{

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

            $sol_repo = $this->getDoctrine()->getRepository('MGAdminBundle:Contratacion');

            $allc = $sol_repo->findAll();


            foreach($allc as $c)
            {
                if($c->getReaded() == false)
                {
                    $c->setReaded(true);
                    $em = $this->getDoctrine()->getManager();
                    $em->merge($c);
                    $em->flush();
                }

            }

            $contrataciones = $sol_repo->findAllWithLimitAndOffset($limit, ($page-1)*$limit);

            if(!isset($contrataciones) || !$contrataciones )
            {
                $this->get('session')->getFlashBag()->add('fail', 'No hay ninguna solicitud de contratación registradas');
                return $this->redirect($this->generateUrl('mg_admin_homepage'));
            }

            $page_number = ceil(count($allc)/$limit);

            if($page_number == $page)
            {
                $last = true;
            }

            return $this->render('MGAdminBundle:Contratacion:indexcontratacion.html.twig',
                array(
                    'contrataciones' => $contrataciones,
                    'pages' => $page_number,
                    'first' => $first,
                    'last' => $last,
                    'page' => $page
                ));

        }
        else
        {
            $mensaje = "No estas autorizado a acceder a esta zona.";
            $msjTipo = "error";
            return $this->render('MGAdminBundle:Msg:messages.html.twig', array('mensaje' => $mensaje, 'tipo' => $msjTipo));
        }
    }

}
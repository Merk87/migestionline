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

use MG\RepoBundle\Entity\Categoria;
use MG\RepoBundle\Form\CategoriaType;

class AdminCategoriasController extends Controller
{
    public function indexAction($page = NULL, $limit = 10)
    {
        if($this->getUser()->getRolId() == 1)
        {
            $rolUser = $this->getUser()->getRolId();
            $first = true;
            $last = false;

            if(!isset($page) || $page < 2)
            {
                $page = 1;
                $first = false;
            }

            $cateRepo = $this->getDoctrine()->getRepository('MGRepoBundle:Categoria');

            $allc = $cateRepo->findAll();

            $categorias = $cateRepo->findAllWithLimitAndOffset($limit, ($page-1)*$limit, $this->getUser()->getId());

            if(!$categorias)
            {
                $this->get('session')->getFlashBag()->add('fail', 'No hay categorías actualmente registrados');
                return $this->redirect($this->generateUrl('mg_admin_homepage'));
            }

           $page_number = ceil(count($allc)/$limit);

            if($page_number == $page)
            {
                $last = true;
            }

            $em = $this->getDoctrine()->getManager();

            $empresas = $em->getRepository('MGAdminBundle:Empresa')
                ->findAll();

            if(!$empresas)
            {
                $this->get('session')->getFlashBag()->add('fail', 'No existen empresas disponibles');
                return $this->redirect($this->generateUrl('mg_repoadmin_homepage', array('page'=> 1)));
            }

            return $this->render('MGAdminBundle:Categorias:catgestion.html.twig', array(
                'categorias' => $categorias,
                'pages' => $page_number,
                'first' => $first,
                'last' => $last,
                'page' => $page,
                'role' => $rolUser,
                'empresas' => $empresas
            ));
        }elseif($this->getUser()->getRolId() == 2 )
        {
            $empresas = $this->getUser()->getEmpresas();
            if(sizeof($empresas) == 1)
            {
                return $this->redirect($this->generateUrl('mg_cat_by_empresa', array('empId' => $empresas[0]->getId(),'page'=> 1)));
            }
            else
            {
                return $this->render('MGAdminBundle:Categorias:selectempcat.html.twig', array('empresas' => $empresas));
            }
        }
        else
        {
            $mensaje = "No tienes permisos para visualizar estas categorías.";
            $msjTipo = "error";
            return $this->render('MGAdminBundle:Msg:messages.html.twig', array('mensaje' => $mensaje, 'tipo' => $msjTipo));
        }
    }

    public function createAction(Request $request, $empId = 0)
    {
        if($this->getUser()->getRolId() < 3)
        {
            $categoria = new Categoria();

            $userId = $this->getUser()->getId();
            $roleId = $this->getUser()->getRolId();

            $form = $this->createForm(new CategoriaType(), $categoria, array('userId' => $userId, 'roleId' => $roleId, 'empId' => $empId));

            if($request->isMethod('POST'))
            {
                $form->bind($request);
                if($form->isValid())
                {
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($categoria);
                    $em->flush();
                    $this->get('session')->getFlashBag()->add('notice', 'Categoría introducida correctamente');
                    if($empId != 0)
                    {
                        return $this->redirect($this->generateUrl('mg_cat_by_empresa', array('page' => 1, 'empId'=>$empId)));
                    }else
                    {
                        return $this->redirect($this->generateUrl('mg_catadmin_homepage', array('page' => 1)));
                    }
                }
            }
            return $this->render('MGAdminBundle:Categorias:newcat.html.twig',
                array(
                    'newCategoriaForm' => $form->createView(),
                    'empId' => $empId
                ));
        }else
        {
            $mensaje = "No tienes permisos para visualizar estas categorías.";
            $msjTipo = "error";
            return $this->render('MGAdminBundle:Msg:messages.html.twig', array('mensaje' => $mensaje, 'tipo' => $msjTipo));
        }

    }

    public function updateAction($catId, Request $request, $empId = 0)
    {

        $em = $this->getDoctrine()->getManager();

        $catToEdit = $em->getRepository('MGRepoBundle:Categoria')
            ->find($catId);

        if(!$catToEdit)
        {
            $this->get('session')->getFlashBag()->add('fail', 'No existe la categoría a editar');
            return $this->redirect($this->generateUrl('mg_catadmin_homepage', array('page' => 1)));
        }

        $tieneEmpresa = 0;
        foreach ($this->getUser()->getEmpresas() as $empresa) {
            if($catToEdit->getServicio()->getEmpresaId() == $empresa->getId())
            {
                $tieneEmpresa = 1;
            }
        }

        if(($tieneEmpresa == 1 && $this->getUser()->getRolId() == 2) || $this->getUser()->getRolId() == 1)
        {
            $userId = $this->getUser()->getId();
            $roleId = $this->getUser()->getRolId();

            $form = $this->createForm(new CategoriaType(), $catToEdit, array('userId' => $userId, 'roleId' => $roleId, 'empId' => $empId));

            if($request->isMethod('POST'))
            {
                $form->bind($request);
                if($form->isValid())
                {
                    $em->persist($catToEdit);
                    $em->flush();
                    $this->get('session')->getFlashBag()->add('notice', 'Categoría modificada correctamente');
                    if($empId != 0)
                    {
                        return $this->redirect($this->generateUrl('mg_cat_by_empresa', array('page' => 1, 'empId'=>$empId)));
                    }else
                    {
                        return $this->redirect($this->generateUrl('mg_catadmin_homepage', array('page' => 1)));
                    }
                }
            }
            return $this->render('MGAdminBundle:Categorias:updatecat.html.twig', array(
                'updateCategoriaForm' => $form->createView(),
                'catId' => $catId,
                'empId' => $empId
            ));

        }else
        {
            $mensaje = "No tienes permisos para actualizar esta categoría.";
            $msjTipo = "error";
            return $this->render('MGAdminBundle:Msg:messages.html.twig', array('mensaje' => $mensaje, 'tipo' => $msjTipo));
        }

       }

    public function detailAction($catId, $empId = 0)
    {

        $em = $this->getDoctrine()->getManager();

        $catToShow = $em->getRepository('MGRepoBundle:Categoria')
            ->find($catId);

        if(!$catToShow)
        {
            $this->get('session')->getFlashBag()->add('fail', 'No existe la categoría a editar');
            return $this->redirect($this->generateUrl('mg_catadmin_homepage', array('page' => 1)));
        }

        $tieneEmpresa = 0;
        foreach ($this->getUser()->getEmpresas() as $empresa) {
            if($catToShow->getServicio()->getEmpresaId() == $empresa->getId())
            {
                $tieneEmpresa = 1;
            }
        }

        if(($tieneEmpresa == 1 && $this->getUser()->getRolId() == 2) || $this->getUser()->getRolId() == 1)
        {
            $em = $this->getDoctrine()->getManager();

            $categoria = $em->getRepository('MGRepoBundle:Categoria')
                ->find($catId);

            if(!$categoria)
            {
                $this->get('session')->getFlashBag()->add('fail', 'No existe la categoría a editar');
                return $this->redirect($this->generateUrl('mg_catadmin_homepage', array('page' => 1)));
            }
            return $this->render('MGAdminBundle:Categorias:detailcat.html.twig', array('categoria' => $categoria, 'empId' => $empId));
        }else
        {
            $mensaje = "No tienes permisos para visualizar esta categoría.";
            $msjTipo = "error";
            return $this->render('MGAdminBundle:Msg:messages.html.twig', array('mensaje' => $mensaje, 'tipo' => $msjTipo));
        }

    }

    public function blockAction($catId)
    {
        $em = $this->getDoctrine()->getManager();

        $cat = $em->getRepository('MGRepoBundle:Categoria')
            ->find($catId);

        if(!$cat)
        {
            $this->get('session')->getFlashBag()->add('fail', 'Categoría no encontrada');
            return $this->redirect($this->generateUrl('mg_catadmin_homepage', array('page'=> 1)));
        }

        if($cat->isEnabled() == true)
        {
            $cat->setEnabled(false);
            $em->persist($cat);
            $em->flush();
            $this->get('session')->getFlashBag()->add('fail', 'Categoría bloqueada');
            return $this->redirect($this->generateUrl('mg_cat_by_empresa', array('page'=> 1, 'empId'=> $cat->getServicio()->getEmpresaId())));
        }else
        {
            $cat->setEnabled(true);
            $em->persist($cat);
            $em->flush();
            $this->get('session')->getFlashBag()->add('notice', 'Categoría desbloqueada');
            return $this->redirect($this->generateUrl('mg_catadmin_homepage', array('page'=> 1)));
        }
    }

    public function listCatEmpresaAction($empId, $page = 1, $limit = 10)
    {
        $tieneEmpresa = 0;
        foreach ($this->getUser()->getEmpresas() as $empresa) {
            if($empId == $empresa->getId())
            {
                $tieneEmpresa = 1;
            }
        }

        if(($tieneEmpresa > 0 && $this->getUser()->getRolId() <= 2) || $this->getUser()->getRolId() == 1 )
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
                return $this->redirect($this->generateUrl('mg_catadmin_homepage', array('page' => 1)));
            }

            $alls = $empresa->getServicios();

            if(!$alls)
            {

                if(sizeof($this->getUser()->getEmpresas()) == 1)
                {
                    $this->get('session')->getFlashBag()->add('fail', 'No existen categorias para esta empresa');
                    return $this->redirect($this->generateUrl('mg_admin_homepage'));
                }else
                {
                    $this->get('session')->getFlashBag()->add('fail', 'No existen categorias para esta empresa');
                    return $this->redirect($this->generateUrl('mg_catadmin_homepage', array('page' => 1)));
                }

            }

            foreach ($alls as $servicio) {
               if($servicio->hasCategories() == true)
               {
                  $allc_pre[] = $servicio->getCategoria();
               }
            }

            if(!isset($allc_pre) || sizeof($allc_pre) < 1)
            {
                if(sizeof($this->getUser()->getEmpresas()) == 1)
                {
                    $this->get('session')->getFlashBag()->add('fail', 'No existen categorias para esta empresa');
                    return $this->redirect($this->generateUrl('mg_admin_homepage'));
                }else
                {
                    $this->get('session')->getFlashBag()->add('fail', 'No existen categorias para esta empresa');
                    return $this->redirect($this->generateUrl('mg_catadmin_homepage', array('page' => 1)));
                }
            }

            foreach ($allc_pre as $pre_cat)
            {
                foreach ($pre_cat as $cat) {
                   $allc[] = $cat;
                }

            }

            if(!isset($allc) || sizeof($allc) < 1)
            {
                if(sizeof($this->getUser()->getEmpresas()) == 1)
                {
                    $this->get('session')->getFlashBag()->add('fail', 'No existen categorias para esta empresa');
                    return $this->redirect($this->generateUrl('mg_admin_homepage'));
                }
                else
                {
                    $this->get('session')->getFlashBag()->add('fail', 'No existen categorias para esta empresa');
                    return $this->redirect($this->generateUrl('mg_catadmin_homepage', array('page' => 1)));
                }


            }

            for($i = $offset; $i < $limit+$offset; $i++)
            {
                if(isset($allc[$i]))
                {
                    $categorias[] = $allc[$i];
                }
            }

            $page_number = ceil(count($allc)/$limit);

            if($page_number == $page)
            {
                $last = true;
            }

            if(!isset($categorias) || !$categorias)
            {
                $this->get('session')->getFlashBag()->add('fail', 'No existen categorias para esta empresa');
                return $this->redirect($this->generateUrl('mg_catadmin_homepage', array('page' => 1)));
            }

            if($this->getUser()->getRolId() == 1)
            {
                $empresas = $em->getRepository('MGAdminBundle:Empresa')
                    ->findAll();
            }else
            {
                $empresas = $this->getUser()->getEmpresas();
            }

            return $this->render('MGAdminBundle:Categorias:catgestionfiltered.html.twig', array(
                'categorias' => $categorias,
                'pages' => $page_number,
                'first' => $first,
                'last' => $last,
                'page' => $page,
                'empresas' => $empresas,
                'empId' => $empId
            ));
        }else
        {
            $mensaje = "No tienes permisos para visualizar estas categorías.";
            $msjTipo = "error";
            return $this->render('MGAdminBundle:Msg:messages.html.twig', array('mensaje' => $mensaje, 'tipo' => $msjTipo));
        }
    }

}

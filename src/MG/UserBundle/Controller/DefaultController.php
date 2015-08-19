<?php

namespace MG\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use MG\UserBundle\Form\ClientUpdateType;

class DefaultController extends Controller
{
    public function showAction()
    {
        $user = $this->getUser();
        if($user->getRolId() < 4)
        {
            $empresas = $user->getEmpresas();
            return $this->render('MGUserBundle:Profile:adminprofile.html.twig', array('user' => $user, 'empresas' => $empresas));
        }else
        {
            $empresas = $user->getEmpresas();
            return $this->render('MGUserBundle:Profile:userprofile.html.twig', array('user' => $user, 'empresas' => $empresas));
        }
    }

    public function clientUpdateAction(Request $request)
    {
        $user = $this->getUser();

        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm(new ClientUpdateType('mg_clientbundle_userupdatetype'),$user);

        if($request->isMethod('POST'))
        {
            $form->bind($request);
            if($form->isValid())
            {
                $em->merge($user);
                $em->flush();
                $this->get('session')->getFlashBag()->add('notice', '¡Actualización realizada con exito!');
                return $this->redirect($this->generateUrl('mg_user_profile'));
            }
        }

        return $this->render('MGUserBundle:Updates:updateuser.html.twig',
            array('userToEdit' => $user, 'editUserForm' => $form->createView()));

    }

}

<?php

namespace MG\LandingFrontBundle\Controller;

use MG\AdminBundle\Entity\Contratacion;
use MG\AdminBundle\Form\ContratacionType;
use MG\LandingFrontBundle\Entity\Contact;
use MG\LandingFrontBundle\Entity\ConversacionPublica;
use MG\LandingFrontBundle\Form\ContactResType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use MG\LandingFrontBundle\Form\ContactType;

class LandingFrontController extends Controller
{
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $contact = new Contact();
        $publicConver = new ConversacionPublica();
        $form = $this->createForm(new ContactType(), $contact);

        if ($request->isMethod('POST')) {
            $form->bind($request);

            if ($form->isValid()) {

                $message = \Swift_Message::newInstance()
                    ->setContentType('text/html')
                    ->setSubject($form->get('Asunto')->getData())
                    ->setFrom($form->get('Email')->getData())
                    ->setTo('contacto@migestionline.com')
                    ->setBody(
                        $this->renderView(
                            'MGLandingFrontBundle:MailTemplates:contact.html.twig',
                            array(
                                'ip' => $request->getClientIp(),
                                'name' => $form->get('Nombre')->getData(),
                                'message' => $form->get('Mensaje')->getData(),
                                'publiok' => $form->get('Publicidad')->getData()
                            )
                        )
                    );

                $this->get('mailer')->send($message);

                $secureHash = sha1(md5(uniqid(rand())));

                $publicConver->setFechaCreacion(new \DateTime());
                $publicConver->setFechaUltimo(new \DateTime());
                $publicConver->addMensajesCli($contact);
                $publicConver->setHashConv($secureHash);
                $publicConver->setActiva(true);
                $contact->setConversacionPublica($publicConver);
                $contact->setFechaEnvio(new \DateTime());
                $contact->setLeido(false);
                $em->persist($publicConver);
                $em->persist($contact);
                $em->flush();

                $request->getSession()->getFlashBag()->add('notice', '¡Tu mensaje ha sido enviado! ¡Gracias!');

                return $this->redirect($this->generateUrl('mg_landing_front_homepage'));
            }
        }

        return $this->render('MGLandingFrontBundle:Default:index.html.twig', array('contactForm' => $form->createView()));
    }

    public function checkRolRAction()
    {
        $userR = $this->getUser();

        if($userR->getRol()->getRolName() == 'ROLE_CLIENTE')
        {
            return $this->redirect($this->generateUrl('mg_repo_homepage'));
        }else
        {
            return $this->redirect($this->generateUrl('mg_admin_homepage'));
        }

    }

    public function contrataAction(Request $request)
    {
        $solicitud_contratacion = new Contratacion();

        $form = $this->createForm(new ContratacionType(), $solicitud_contratacion);

        if($request->isMethod('POST'))
        {
            $form->bind($request);
            if($form->isValid())
            {
                $em = $this->getDoctrine()->getManager();
                $solicitud_contratacion->setReaded(false);

                $message = \Swift_Message::newInstance()
                    ->setContentType('text/html')
                    ->setSubject('¡Solicitud de contratación en curso!')
                    ->setFrom(array('noreply@migestionline.com' => 'Robot Mi Gestión Online'))
                    ->setTo($solicitud_contratacion->getEmail())
                    ->setBody(
                        $this->renderView(
                            'MGLandingFrontBundle:MailTemplates:contratacionmail.html.twig',
                            array(
                                'name' => $solicitud_contratacion->getName(),
                                'ccc' => substr($solicitud_contratacion->getCuentaDomiciliacion(), 16),
                                'paquete' => $solicitud_contratacion->getPaquete()->getDisplayNombre(),
                                'precio' => $solicitud_contratacion->getPaquete()->getPrecio(),
                                'periodo' => $solicitud_contratacion->getPeriodo()->getDisplayDuracion(),
                                'descuento' => $solicitud_contratacion->getPeriodo()->getDescuento(),
                                'duracion' => $solicitud_contratacion->getPeriodo()->getDuracion()
                            )
                        )
                    );

                $message_ad = \Swift_Message::newInstance()
                    ->setContentType('text/html')
                    ->setSubject('Nueva solicitud de contratación')
                    ->setFrom(array('robot@migestionline.com' => 'Robot Mi Gestión Online'))
                    ->setTo('adolfo747@gmail.com')
                    ->setBody(
                        $this->renderView(
                            'MGLandingFrontBundle:MailTemplates:contratacionaviso.html.twig',
                            array(
                                'name' => $solicitud_contratacion->getName(),
                                'email' => $solicitud_contratacion->getEmail(),
                                'paquete' => $solicitud_contratacion->getPaquete()->getDisplayNombre(),
                                'periodo' => $solicitud_contratacion->getPeriodo()->getDisplayDuracion()
                            )
                        )
                    );


                $this->get('mailer')->send($message);
                $this->get('mailer')->send($message_ad);
                $solicitud_contratacion->setFechaContratacion(new \DateTime());
                $em->persist($solicitud_contratacion);
                $em->flush();

                $this->get('session')->getFlashBag()->add('notice', 'Solicitud completada con exito, en breves nos pondremos en contacto contigo.');
                return $this->redirect($this->generateUrl('mg_front_contratacion'));

                //TODO despues de hacer el persist y mostrar mensaje, envíar mail al usuario.


            }
        }

        return $this->render('MGLandingFrontBundle:Default:contrata.html.twig', array('newContractForm' => $form->createView()));
    }

    public function politicaAction()
    {
        return $this->render('MGLandingFrontBundle:Default:politicaprivacidad.html.twig');
    }

    public function condicionesAction()
    {
        return $this->render('MGLandingFrontBundle:Default:condiciones.html.twig');
    }

    public function avisoAction()
    {
        return $this->render('MGLandingFrontBundle:Default:avisolegal.html.twig');
    }

    public function clientRespuestaAction($hash, Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $conv = $em->getRepository('MGLandingFrontBundle:ConversacionPublica')
            ->findOneBy(array('hashConv' => $hash));


        if($conv->getActiva() == true)
        {
            $contact = new Contact();
            $form = $this->createForm(new ContactResType(), $contact);

            if($request->isMethod('POST'))
            {
                $form->bind($request);
                if($form->isValid())
                {

                    $newSecureHash = sha1(md5(uniqid(rand())));

                    $lastMessages = $conv->getMensajesCli();
                    $totalMsg = sizeof($lastMessages);

                    $contact->setConversacionPublica($conv);
                    $contact->setNombre($lastMessages[$totalMsg-1]->getNombre());
                    $contact->setEmail($lastMessages[$totalMsg-1]->getEmail());
                    $contact->setAsunto('Resuesta Cliente');
                    $contact->setPolitica($lastMessages[$totalMsg-1]->getPolitica());
                    $contact->setPublicidad($lastMessages[$totalMsg-1]->getPublicidad());
                    $contact->setLeido(false);
                    $contact->setFechaEnvio(new \DateTime());

                    $conv->setFechaUltimo(new \DateTime());
                    $conv->addMensajesCli($contact);
                    $conv->setHashConv($newSecureHash);

                    $em->persist($conv);
                    $em->persist($contact);
                    $em->flush();

                    $request->getSession()->getFlashBag()->add('notice', '¡Tu mensaje ha sido enviado! ¡Gracias!');

                    return $this->redirect($this->generateUrl('mg_public_client_response', array('hash' => $newSecureHash)));
                }
            }

            return $this->render('MGLandingFrontBundle:PublicMsg:clientresponsepublic.html.twig', array(
                'conversacion' => $conv,
                'respuesta' => $form->createView()
            ));
        }else
        {
            $mensaje = "La conversación ha sido finalizada.";
            $msjTipo = "error";
            return $this->render('MGLandingFrontBundle:PublicMsg:messages.html.twig', array('mensaje' => $mensaje, 'tipo' => $msjTipo));
        }

    }

    public function browseServicesClientAction(Request $request)
    {

        $em = $this->getDoctrine()->getManager();
        $contact = new Contact();
        $publicConver = new ConversacionPublica();
        $form = $this->createForm(new ContactType(), $contact);

        if ($request->isMethod('POST')) {
            $form->bind($request);

            if ($form->isValid()) {

                $message = \Swift_Message::newInstance()
                    ->setContentType('text/html')
                    ->setSubject($form->get('Asunto')->getData())
                    ->setFrom($form->get('Email')->getData())
                    ->setTo('contacto@migestionline.com')
                    ->setBody(
                        $this->renderView(
                            'MGLandingFrontBundle:MailTemplates:contact.html.twig',
                            array(
                                'ip' => $request->getClientIp(),
                                'name' => $form->get('Nombre')->getData(),
                                'message' => $form->get('Mensaje')->getData(),
                                'publiok' => $form->get('Publicidad')->getData()
                            )
                        )
                    );

                $this->get('mailer')->send($message);

                $secureHash = sha1(md5(uniqid(rand())));

                $publicConver->setFechaCreacion(new \DateTime());
                $publicConver->setFechaUltimo(new \DateTime());
                $publicConver->addMensajesCli($contact);
                $publicConver->setHashConv($secureHash);
                $publicConver->setActiva(true);
                $contact->setConversacionPublica($publicConver);
                $contact->setFechaEnvio(new \DateTime());
                $contact->setLeido(false);
                $em->persist($publicConver);
                $em->persist($contact);
                $em->flush();

                $request->getSession()->getFlashBag()->add('notice', '¡Tu mensaje ha sido enviado! ¡Gracias!');

                return $this->redirect($this->generateUrl('mg_landing_front_homepage'));
            }
        }

        return $this->render('MGLandingFrontBundle:Default:browseservices.html.twig', array(
            'contactForm' => $form->createView()
        ));
    }

    public function showServicesPublicAction($tipoServicio)
    {
        $em = $this->getDoctrine()->getManager();

        $servicios = $em->getRepository('MGRepoBundle:Servicios')
            ->findAllServicesActiveUsingName($tipoServicio);

        return $this->render('MGLandingFrontBundle:Default:showservclient.html.twig', array('servicios' => $servicios));
    }

}

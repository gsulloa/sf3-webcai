<?php

namespace Cai\FrontendBundle\Controller;

use Cai\ClubesBundle\Entity\Club;
use Cai\ClubesBundle\Entity\Etiqueta;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ClubController extends Controller
{
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $public = $this->get('cai_web.auxiliar')->getPublicInfo();
        $clubes = $em->getRepository('CaiClubesBundle:Club')->findAllAprobados();
        return $this->render('CaiFrontendBundle:club:index.html.twig', array(
            'public' => $public,
            'clubes' => $clubes
        ));
    }

    public function showAction($slug)
    {
        $em = $this->getDoctrine()->getManager();
        $contacto = $em->getRepository('CaiWebBundle:Contacto')->find(1);
        $categorias = $em->getRepository('CaiWebBundle:Categoria')->findAll();
        $auspicios_1 = $em->getRepository('CaiWebBundle:Slider')->findOneByTitulo('Auspicios_1');
        $auspicios_2 = $em->getRepository('CaiWebBundle:Slider')->findOneByTitulo('Auspicios_2');
        $menu = $em->getRepository('CaiWebBundle:Menu')->findOneByTitulo('Principal');
        $club = $em->getRepository('CaiClubesBundle:Club')->findOneBySlug($slug);
        if($club === null){
            throw $this->createNotFoundException('No se ha encontrado el Club');
        }
        return $this->render('CaiFrontendBundle:club:show.html.twig', array(
            'public' => $public,
            'club' => $club
        ));
    }

    public function newAction(Request $request)
    {
        $club = new Club();
        $form = $this->createForm('Cai\FrontendBundle\Form\ClubType', $club);
        $form->handleRequest($request);
        $em = $this->getDoctrine()->getManager();
        if ($form->isSubmitted() && $form->isValid()) {

            $aux = $this->get('cai_web.auxiliar');
            $club->setSlug($aux->toAscii($club->getNombre()))
                ->setAprobado(false)
                ->setAdmin($this->getUser())
            ;
            $etiquetas = trim($request->request->get('etiquetas'));
            if ($etiquetas != "") {
                $etiquetas = explode(',', $etiquetas);
                foreach ($etiquetas as $etiqueta) {
                    $etiqueta = trim($etiqueta);
                    if ($etiqueta != "") {
                        $etiqueta_slug = $aux->toAscii($etiqueta);
                        $etiqueta_found = $em->getRepository('CaiClubesBundle:Etiqueta')->findOneBySlug($etiqueta_slug);
                        if ($etiqueta_found !== null) {
                            $etiqueta = $etiqueta_found;
                        } else {
                            $etiqueta_aux = $etiqueta;
                            $etiqueta = new Etiqueta();
                            $etiqueta->setTitulo($etiqueta_aux)
                                ->setSlug($etiqueta_slug);
                            $em->persist($etiqueta);
                        }
                        $club->addEtiqueta($etiqueta);
                    }
                }
            }
            $em->persist($club);
            $em->flush();

            return $this->redirectToRoute('frontend_clubes');
        }
        $public = $this->get('cai_web.auxiliar')->getPublicInfo();
        return $this->render('CaiFrontendBundle:club:new.html.twig', array(
            'club' => $club,
            'form' => $form->createView(),
            'public' => $public
        ));

    }
}

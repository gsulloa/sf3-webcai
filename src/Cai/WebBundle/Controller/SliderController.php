<?php

namespace Cai\WebBundle\Controller;

use Cai\WebBundle\Entity\Slide;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Cai\WebBundle\Entity\Slider;
use Cai\WebBundle\Form\SliderType;

/**
 * Slider controller.
 *
 */
class SliderController extends Controller
{
    /**
     * Lists all Slider entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $sliders = $em->getRepository('CaiWebBundle:Slider')->findAll();

        return $this->render('CaiWebBundle:Slider:index.html.twig', array(
            'sliders' => $sliders,
        ));
    }

    /**
     * Creates a new Slider entity.
     *
     */
    public function newAction(Request $request)
    {
        $slider = new Slider();
        $form = $this->createForm('Cai\WebBundle\Form\SliderType', $slider);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($slider);
            $em->flush();

            return $this->redirectToRoute('slider_show', array('id' => $slider->getId()));
        }

        return $this->render('CaiWebBundle:Slider:new.html.twig', array(
            'slider' => $slider,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Slider entity.
     *
     */
    public function showAction(Slider $slider)
    {
        $auxiliar = $this->get('cai_web.auxiliar');
        $images = $auxiliar->getImages();
        $deleteForm = $this->createDeleteForm($slider);

        return $this->render('CaiWebBundle:Slider:show.html.twig', array(
            'slider' => $slider,
            'delete_form' => $deleteForm->createView(),
            'images'    => $images,
        ));
    }

    /**
     * Displays a form to edit an existing Slider entity.
     *
     */
    public function editAction(Request $request, Slider $slider)
    {
        $deleteForm = $this->createDeleteForm($slider);
        $editForm = $this->createForm('Cai\WebBundle\Form\SliderType', $slider);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($slider);
            $em->flush();

            return $this->redirectToRoute('slider_edit', array('id' => $slider->getId()));
        }

        return $this->render('CaiWebBundle:Slider:edit.html.twig', array(
            'slider' => $slider,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Slider entity.
     *
     */
    public function deleteAction(Request $request, Slider $slider)
    {
        $form = $this->createDeleteForm($slider);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($slider);
            $em->flush();
        }

        return $this->redirectToRoute('slider_index');
    }

    /**
     * Creates a form to delete a Slider entity.
     *
     * @param Slider $slider The Slider entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Slider $slider)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('slider_delete', array('id' => $slider->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

    public function slidesGenerateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('CaiWebBundle:Slider')->find($id);
        $data = $request->request->all();
        $slides = $entity->getSlides();
        if (sizeof($entity->getSlides()) < sizeof($data) / 2) {
            while (sizeof($entity->getSlides()) !== sizeof($data) / 2) {
                $slide = new Slide();
                $entity->addSlide($slide);
                $slide->setSlider($entity);
            }
        } elseif (sizeof($entity->getSlides()) > sizeof($data) / 2) {
            while (sizeof($entity->getSlides()) !== sizeof($data) / 2) {
                $slide_to_delete = $entity->getSlides()->last();
                $entity->removeSlide($slide_to_delete);
                $em->remove($slide_to_delete);
            }
        }
        $i = 0;
        foreach ($data as $key => $item) {
            if (strpos($key, 'path_slide') !== false) {
                $slides[intval($i / 2)]->setPath($item);
            } elseif (strpos($key, 'img_slide') !== false) {
                $image = $em->getRepository('CaiWebBundle:Imagen')->find(substr($item, 6));
                //$image->setSlide($slide);
                $slides[intval($i / 2)]->setImagen($image);
            }
            $slides[intval($i / 2)]->setPosicion(intval($i / 2));
            $i++;
        }
        foreach ($slides as $slide) {
            $em->persist($slide);
        }
        $em->flush();
        return $this->redirect($this->generateUrl('slider_show', array(
            'id' => $id
        )));
    }
}

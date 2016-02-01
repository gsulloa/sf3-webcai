<?php

namespace Cai\WebBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Cai\WebBundle\Entity\UserProfile;
use Cai\WebBundle\Form\UserProfileType;

/**
 * UserProfile controller.
 *
 */
class UserProfileController extends Controller
{
    /**
     * Lists all UserProfile entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $userProfiles = $em->getRepository('CaiWebBundle:UserProfile')->findAll();

        return $this->render('CaiWebBundle:userprofile:mantenimiento.html.twig', array(
            'userProfiles' => $userProfiles,
        ));
    }

    /**
     * Creates a new UserProfile entity.
     *
     */
    public function newAction(Request $request)
    {
        $userProfile = new UserProfile();
        $form = $this->createForm('Cai\WebBundle\Form\UserProfileType', $userProfile);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($userProfile);
            $em->flush();

            return $this->redirectToRoute('userprofile_show', array('id' => $userProfile->getId()));
        }

        return $this->render('CaiWebBundle:userprofile:new.html.twig', array(
            'userProfile' => $userProfile,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a UserProfile entity.
     *
     */
    public function showAction(UserProfile $userProfile)
    {
        $deleteForm = $this->createDeleteForm($userProfile);

        return $this->render('CaiWebBundle:userprofile:show.html.twig', array(
            'userProfile' => $userProfile,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing UserProfile entity.
     *
     */
    public function editAction(Request $request, UserProfile $userProfile)
    {
        $deleteForm = $this->createDeleteForm($userProfile);
        $editForm = $this->createForm('Cai\WebBundle\Form\UserProfileType', $userProfile);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($userProfile);
            $em->flush();

            return $this->redirectToRoute('userprofile_edit', array('id' => $userProfile->getId()));
        }

        return $this->render('CaiWebBundle:userprofile:edit.html.twig', array(
            'userProfile' => $userProfile,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a UserProfile entity.
     *
     */
    public function deleteAction(Request $request, UserProfile $userProfile)
    {
        $form = $this->createDeleteForm($userProfile);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($userProfile);
            $em->flush();
        }

        return $this->redirectToRoute('userprofile_index');
    }

    /**
     * Creates a form to delete a UserProfile entity.
     *
     * @param UserProfile $userProfile The UserProfile entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(UserProfile $userProfile)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('userprofile_delete', array('id' => $userProfile->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}

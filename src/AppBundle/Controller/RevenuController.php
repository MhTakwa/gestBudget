<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Revenu;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Revenu controller.
 *
 * @Route("gestion/revenu")
 */
class RevenuController extends Controller
{
    /**
     * Lists all revenu entities.
     *
     * @Route("/", name="gestion_revenu_index")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
         $session=$request->getSession()->get('user');
    if($session!=null){
     
        $em = $this->getDoctrine()->getManager();

        $revenus = $em->getRepository('AppBundle:Revenu')->findAll();
          foreach($revenus as $value ){
            $value->setDate($value->getDate()->format('d/m/Y'));
        }
        return $this->render('utilisateurs/index_revenu.html.twig', array(
            'revenus' => $revenus,
            'session'=>$session
        ));
    }
    return $this->render('default/login.html.twig');
    }

    /**
     * Creates a new revenu entity.
     *
     * @Route("/new", name="gestion_revenu_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
         $session=$request->getSession()->get('user');
    if($session!=null){
     
        $revenu = new Revenu();
        $form = $this->createForm('AppBundle\Form\RevenuType', $revenu);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $revenu->setMontant(abs($revenu->getMontant()));
            $em = $this->getDoctrine()->getManager();
            $em->persist($revenu);
            $em->flush();

            return $this->redirectToRoute('gestion_revenu_show', array('id' => $revenu->getId()));
        }

        return $this->render('revenu/new.html.twig', array(
            'revenu' => $revenu,
            'form' => $form->createView(),
            'session'=>$session
        ));
    }
    return $this->render('default/login.html.twig');
}
    
    /**
     * Finds and displays a revenu entity.
     *
     * @Route("/{id}", name="gestion_revenu_show")
     * @Method("GET")
     */
    public function showAction(Request $request,Revenu $revenu)
    {
         $session=$request->getSession()->get('user');
    if($session!=null){
     
        $deleteForm = $this->createDeleteForm($revenu);

        return $this->render('revenu/show.html.twig', array(
            'revenu' => $revenu,
            'delete_form' => $deleteForm->createView(),
            'session'=>$session
        ));
    }
    return $this->render('default/login.html.twig');
}

    /**
     * Displays a form to edit an existing revenu entity.
     *
     * @Route("/{id}/edit", name="gestion_revenu_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Revenu $revenu)
    {
         $session=$request->getSession()->get('user');
    if($session!=null){
     
        $deleteForm = $this->createDeleteForm($revenu);
        $editForm = $this->createForm('AppBundle\Form\RevenuType', $revenu);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('gestion_revenu_edit', array('id' => $revenu->getId()));
        }

        return $this->render('revenu/edit.html.twig', array(
            'revenu' => $revenu,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'session'=>$session
        ));
    }
    return $this->render('default/login.html.twig');
}

    /**
     * Deletes a revenu entity.
     *
     * @Route("/{id}", name="gestion_revenu_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Revenu $revenu)
    {
        $form = $this->createDeleteForm($revenu);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($revenu);
            $em->flush();
        }

        return $this->redirectToRoute('gestion_revenu_index');
    }


    /**
     * Creates a form to delete a revenu entity.
     *
     * @param Revenu $revenu The revenu entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Revenu $revenu)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('gestion_revenu_delete', array('id' => $revenu->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}

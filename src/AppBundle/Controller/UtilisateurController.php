<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Utilisateur;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;
use \Defuse\Crypto\Crypto;
use \Defuse\Crypto\Key;


/**
 * Utilisateur controller.
 *
 * @Route("admin")
 */
class UtilisateurController extends Controller
{ 
    /**
     * Lists all utilisateur entities.
     *
     * @Route("/", name="admin_index")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {    $session=$request->getSession()->get('admin');
        if($request->getSession()->get('admin')!=null){
        $em = $this->getDoctrine()->getManager();

        $utilisateurs = $em->getRepository('AppBundle:Utilisateur')->findAll();

        return $this->render('admin/index.html.twig', array(
            'utilisateurs' => $utilisateurs,'session'=>$session
        ));
    }
     return $this->render('default/login.html.twig',['session'=>$session]);
}

    /**
     * Creates a new utilisateur entity.
     *
     * @Route("/new", name="admin_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    { $session=$request->getSession()->get('admin');
        if($request->getSession()->get('admin')!=null){
        $utilisateur = new Utilisateur();
        $form = $this->createForm('AppBundle\Form\UtilisateurType', $utilisateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
       $key = Key::loadFromAsciiSafeString('def000006f289f278c817e85ca4fa1731fe08d16d0b621356e8f90b89a25a18bb3ce0265ff8fe0473b152cedd832fd85fa4148e8d03df7072e218ccb015a1e0a4eaeba97');
            $utilisateur->setPassword(Crypto::encrypt($utilisateur->getPassword(), $key));
            
            
            $key->saveToAsciiSafeString();
            $em = $this->getDoctrine()->getManager();
            $em->persist($utilisateur);
            $em->flush();

            return $this->redirectToRoute('admin_show', array('id' => $utilisateur->getId()));
        }

        return $this->render('utilisateur/new.html.twig', array(
            'utilisateur' => $utilisateur,
            'form' => $form->createView(),'session'=>$session
        ));
    }   return $this->render('default/login.html.twig');
}


    /**
     * Finds and displays a utilisateur entity.
     *
     * @Route("/{id}", name="admin_show")
     * @Method("GET")
     */
    public function showAction(Request $request,Utilisateur $utilisateur)
    { $session=$request->getSession()->get('admin');
        if($request->getSession()->get('admin')!=null){
           $deleteForm = $this->createDeleteForm($utilisateur);

        return $this->render('utilisateur/show.html.twig', array(
            'utilisateur' => $utilisateur,
            'delete_form' => $deleteForm->createView(),'session'=>$session        ));
    }
       return $this->render('default/login.html.twig');
}


    /**
     * Displays a form to edit an existing utilisateur entity.
     *
     * @Route("/{id}/edit", name="admin_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Utilisateur $utilisateur)
    {
         $session=$request->getSession()->get('admin');
        if($request->getSession()->get('admin')!=null){
            $key = Key::loadFromAsciiSafeString('def000006f289f278c817e85ca4fa1731fe08d16d0b621356e8f90b89a25a18bb3ce0265ff8fe0473b152cedd832fd85fa4148e8d03df7072e218ccb015a1e0a4eaeba97');
         
        $deleteForm = $this->createDeleteForm($utilisateur);
        $utilisateur->setPassword(Crypto::decrypt($utilisateur->getPassword(), $key));
        $editForm = $this->createForm('AppBundle\Form\UtilisateurType', $utilisateur);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
             $utilisateur->setPassword(Crypto::encrypt($utilisateur->getPassword(), $key));
 
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_edit', array('id' => $utilisateur->getId()));
        }

        return $this->render('utilisateur/edit.html.twig', array(
            'utilisateur' => $utilisateur,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'session'=>$session
        ));
    }
       return $this->render('default/login.html.twig');
}


    /**
     * Deletes a utilisateur entity.
     *
     * @Route("delete/{id}", name="admin_delete")
     * @Method("DELETE")
     */
    public function deleteAction( Utilisateur $utilisateur)
    {
         $session=$request->getSession()->get('admin');
        if($request->getSession()->get('admin')!=null){
        $form = $this->createDeleteForm($utilisateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($utilisateur);
            $em->flush();
        }

        return $this->redirectToRoute('admin_index');
    }
       return $this->render('default/login.html.twig',['session'=>$session]);
}


    /**
     * Creates a form to delete a utilisateur entity.
     *
     * @param Utilisateur $utilisateur The utilisateur entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Utilisateur $utilisateur)
    {
         return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_delete', array('id' => $utilisateur->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
 
}

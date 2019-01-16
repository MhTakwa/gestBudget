<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Depense;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Depense controller.
 *
 * @Route("gestion/depense")
 */
class DepenseController extends Controller
{
    /**
     * Lists all depense entities.
     *
     * @Route("/", name="gestion_depense_index")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    { $session=$request->getSession()->get('user');
    if($session!=null){
        $em = $this->getDoctrine()->getManager();

        $depenses = $em->getRepository('AppBundle:Depense')->findAll();
        foreach($depenses as $value ){
            $value->setDate($value->getDate()->format('d/m/Y'));
        }
        return $this->render('utilisateurs/index_depense.html.twig', array(
            'depenses' => $depenses,'session'=>$session
        ));
    } return $this->render('default/login.html.twig');
    }

    /**
     * Creates a new depense entity.
     *
     * @Route("/new", name="gestion_depense_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {

        $session=$request->getSession()->get('user');
        if($session!=null){
        $depense = new Depense();
        $form = $this->createForm('AppBundle\Form\DepenseType', $depense);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $depense->setMontant(abs($depense->getMontant()));
            $em = $this->getDoctrine()->getManager();
            $em->persist($depense);
            $em->flush();

            return $this->redirectToRoute('gestion_depense_show', array('id' => $depense->getId()));
        }

        return $this->render('depense/new.html.twig', array(
            'depense' => $depense,
            'form' => $form->createView(),
            'session'=>$session
        ));
    }
     return $this->render('default/login.html.twig');
}

    /**
     * Finds and displays a depense entity.
     *
     * @Route("/{id}", name="gestion_depense_show")
     * @Method("GET")
     */
    public function showAction(Request $request,Depense $depense)
    {
        $session=$request->getSession()->get('user');
        if($session!=null){
        $deleteForm = $this->createDeleteForm($depense);

        return $this->render('depense/show.html.twig', array(
            'depense' => $depense,
            'delete_form' => $deleteForm->createView(),
            'session'=>$session
        ));
    }
     return $this->render('default/login.html.twig');
 }

    /**
     * Displays a form to edit an existing depense entity.
     *
     * @Route("/{id}/edit", name="gestion_depense_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Depense $depense)
    {
        $session=$request->getSession()->get('user');
        if($session!=null){
        $deleteForm = $this->createDeleteForm($depense);
        $editForm = $this->createForm('AppBundle\Form\DepenseType', $depense);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $depense->setMontant(abs($depense->getMontant()));
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('gestion_depense_edit', array('id' => $depense->getId()));
        }

        return $this->render('depense/edit.html.twig', array(
            'depense' => $depense,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'session'=>$session
        ));
    }
     return $this->render('default/login.html.twig');
 }

    /**
     * Deletes a depense entity.
     *
     * @Route("delete/{id}", name="gestion_depense_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Depense $depense)
    {
        $session=$request->getSession()->get('user');
        if($session!=null){
        $form = $this->createDeleteForm($depense);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($depense);
            $em->flush();
        }

        return $this->redirectToRoute('gestion_depense_index');
    }
     return $this->render('default/login.html.twig');
 }

    /**
     * Creates a form to delete a depense entity.
     *
     * @param Depense $depense The depense entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Depense $depense)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('gestion_depense_delete', array('id' => $depense->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }




    public function piechartAction()
{
    $em = $this->getDoctrine()->getManager();
    $depenses = $em->getRepository('AppBundle:Revenu')->findAll();
 /*   foreach($depenses as $valeur ){
        foreach ($data as $key=>$value) {
            if($key==$valeur->getRole()){
                $value++;
            }
            else $data[$valeur->getRole()]=1;

        }
    }
  

  /*  $ob = new Highchart();
    $ob->chart->renderTo('container');
    $ob->chart->type('pie');
    $ob->title->text('My Pie Chart');
    $ob->series(array(array("data"=>$data)));
*/
    return $this->render('chart/pie.html.twig', [
        'mypiechart' => $ob
    ]);


}
}
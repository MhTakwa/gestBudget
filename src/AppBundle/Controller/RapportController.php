<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Revenu;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Depense;
use AppBundle\Entity\Rapport;
/**
 * Revenu controller.
 *
 * @Route("gestion/rapports")
 */
class RapportController extends Controller
{
    /**
     * @Route("/", name="index_rapports")
     */
   public function indexAction(Request $request)
    {
      $rapports=$this->getDoctrine()->getManager()->getRepository('AppBundle:Rapport')->findAll();
        return $this->render('utilisateurs/rapport.html.twig',['session'=>$request->getSession()->get('user'),'rapports'=>$rapports]);
    }

	/**
     * @Route("/new/", name="create_rapports")
     */
   public function mensuelAction(Request $request)
    {


    	 $session=$request->getSession()->get('user');
         if($session!=null){
          if($request->get('type')=="annee"){
          $date1=date($request->get('debut'));
          $date2=date('Y-m-d',strtotime($date1.'+365 day'));
          $type="Rapport annuel";
        
        }
        else
        {
          $date1=date($request->get('debut'));
          $date2=date('Y-m-d',strtotime($date1.'+30 month'));
          $type="Rapport mensuel";

        }
      }
      $rapports=$this->getDoctrine()->getManager()->getRepository('AppBundle:Rapport')->findAll();
           
       return $this->redirectToRoute('rapport_details', array('session'=>$session,'type'=>$type,'date1'=>$date1,'date2'=>$date2,'rapports'=>$rapports
        ));
}
      /**
     * @Route("/details/", name="rapport_details")
     */
   public function detailsAction(Request $request)
    { 

          $date1=$request->get('date1');
          $date2=$request->get('date2');
          $em=$this->getDoctrine()->getManager();
          $query1 = $em->createQuery('SELECT sum(d.montant) as mon,d.type FROM AppBundle\Entity\Depense d WHERE d.date between :date1 and :date2 GROUP BY d.type');
          $query1->setParameters(['date1'=>$date1,'date2'=>$date2]);

            $depense = $query1->getResult();
         $query2= $em->createQuery('SELECT sum(r.montant) as mon,r.type  FROM AppBundle\Entity\Revenu r  where r.date between :date1 and :date2 GROUP BY r.type ');
          $query2->setParameters(['date1'=>$date1,'date2'=>$date2]);

          $revenu = $query2->getResult();

         
           return $this->render('utilisateurs/details.html.twig',['session'=>$session=$request->getSession()->get('user'),'revenu'=>$revenu,'depense'=>$depense,'date1'=>$date1,'date2'=>$date2,'type'=>$request->get('type')]);
        

    }


    /**
     * @Route("/save", name="new_rapport")
     */
   public function newAction(Request $request)
    {
      $rapport=new Rapport();
      $rapport->setType($request->get('type'));
      $rapport->setDate1($request->get('date1'));
      $rapport->setDate2($request->get('date2'));
      $em=$this->getDoctrine()->getManager();
      $em->persist($rapport);
      $em->flush();

       return $this->RedirectToRoute('index_rapports');
    }




}
?>


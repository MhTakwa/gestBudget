<?php

namespace AppBundle\Controller;
use Symfony\Component\HttpFoundation\Session\Session;
use AppBundle\Entity\Utilisateur;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use \Defuse\Crypto\Crypto;
use \Defuse\Crypto\Key;
class DefaultController extends Controller
{
    /**
     * @Route("/login/", name="homepage")
     */
   public function indexAction(Request $request)
    {
      ///echo (print_r($_POST['login']));
        if(isset($_POST['login']) && isset($_POST['password'])){
          $key = Key::loadFromAsciiSafeString('def000006f289f278c817e85ca4fa1731fe08d16d0b621356e8f90b89a25a18bb3ce0265ff8fe0473b152cedd832fd85fa4148e8d03df7072e218ccb015a1e0a4eaeba97');
         
           $em = $this->getDoctrine()->getManager();
           $utilisateur=$em->getRepository('AppBundle:Utilisateur')->findOneBy( array('login' =>$_POST['login']));
           if($utilisateur!=null){
            $password=Crypto::decrypt($utilisateur->getPassword(), $key);
            if(strcmp($_POST['password'], $password)==0){
              
                 if( strcmp($utilisateur->getRole(),'admin')==0){
                         $session = new Session();
                         $session->set('admin',$utilisateur->getNom().' '.$utilisateur->getPrenom());
                           $session->start();

                     return $this->redirectToRoute('admin_index',['session'=>$session]);
                  }
                 else{
                  $session = new Session();
                         $session->set('user',$utilisateur->getNom().' '.$utilisateur->getPrenom());
                           $session->start();
                 return $this->redirectToRoute('gestion_depense_index',['session'=>$session]);
                   }
            }
           
        return $this->render('default/login.html.twig');
    }
}
   return $this->render('default/login.html.twig');
   
}
/**
     * @Route("/logout", name="logout")
     */
   public function logoutAction(Request $request)
    {
        $session=$request->getSession()->remove('admin');
        $session=$request->getSession()->remove('user');
       
        return $this->render('default/login.html.twig');

      }
    
}

   


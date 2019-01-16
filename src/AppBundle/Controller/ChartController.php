<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Depense;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Mukadi\Chart\Utils\RandomColorFactory;
use Mukadi\Chart\Chart;
use Mukadi\ChartJS\Chart\Builder;


/**
 * Chart controller.
 *
 * @Route("gestion/charts")
 */
class ChartController extends Controller
{
	 /**
     
     * @Route("/depense", name="chart_depense")
     * @Method("GET")
     */
	  public function piechartAction(Request $request)
{
         $builder = $this->get('mukadi_chart_js.dql');
        $builder->query('SELECT sum(d.montant) as mon,d.type FROM AppBundle\Entity\Depense d WHERE d.date between :date1 and :date2 GROUP BY d.type')->setParameters(['date1'=>$date1,'date2'=>$date2])

            ->addDataset('total','Total',[
                "backgroundColor" => RandomColorFactory::getRandomRGBAColors(6)
            ])
            ->labels('type')
        ;
        $chart = $builder->buildChart('my_chart',Chart::PIE);
        return $this->render('chart.html.twig',[
            "chart" => $chart,
        ]);
   


}
}

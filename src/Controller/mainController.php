<?php


namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\CoinbaseClient;

/**
 * @property CoinbaseClient requestclient
 */

class mainController extends AbstractController
{

    public function __construct(CoinbaseClient $requestClient)
    {
        $this->requestclient = $requestClient;
    }

    /**
     * @Route("/accueil", name="indexRoute")
     */
    public function index(){
        $this->requestclient->connexionWithApiKey();
        return $this->render('index.html.twig');
    }
}

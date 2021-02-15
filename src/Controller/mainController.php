<?php


namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\BinanceClient;

/**
 * @property BinanceClient requestclient
 */

class mainController extends AbstractController
{

    public function __construct(BinanceClient $requestClient)
    {
        $this->requestclient = $requestClient;
    }

    /**
     * @Route("/", name="indexRoute")
     */
    public function index(){
        $balances = $this->requestclient->getBalances();
        $history = $this->requestclient->historyCommands("BTCBUSD");

        $this->requestclient->marketSell("BTCBUSD");

        return $this->render('index.html.twig',[
            'balances' => $balances,
            'historys' => $history
        ]);
    }
}

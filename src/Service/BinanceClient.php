<?php


namespace App\Service;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Binance;

class BinanceClient extends AbstractController
{

    private $secret;
    private $key;
    /**
     * @var Binance\API
     */
    private $api;
    /**
     * @var Binance\RateLimiter
     */
    private $apiRateLimiter;

    public function __construct()
    {
        $this->key = $_ENV['API_PUBLIC_KEY'];
        $this->secret = $_ENV['API_PRIVATE_KEY'];
        $this->api = new Binance\API($this->key, $this->secret);
    }

    public function getBalances(){

        $ticker = $this->api->prices();
        dump($bookPrices = $this->api->bookPrices());
        dump($this->api->account());
        die;
        dump($ticker);
        return $this->api->balances($ticker);
    }

    public function marketBuy(){
        $quantity = 0.01;
        $order = $this->api->marketBuy("BTCBUSD", $quantity);
    }

    public function marketSell($symbol){
        $quantity = 0.01;
        $order = $this->api->marketSell($symbol, $quantity);
        dump($order);
    }

    public function historyCommands($symbol){
        $history = $this->api->history($symbol);
        return $history;
    }

}

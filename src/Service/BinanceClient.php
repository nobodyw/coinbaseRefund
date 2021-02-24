<?php


namespace App\Service;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Binance;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class BinanceClient extends AbstractController
{

    private $secret;
    private $key;
    /**
     * @var Binance\API
     */
    private $api;
    /**
     * @var SessionInterface
     */
    private $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
        $this->key = $_ENV['API_PUBLIC_KEY'];
        $this->secret = $_ENV['API_PRIVATE_KEY'];
        $this->api = new Binance\API($this->key, $this->secret);
    }

    public function shop(){
        //PROD
        $ticker = $this->api->prices();
        dump($ticker);
        $BTCaccountCurrencyMax = $this->api->balances($ticker)['BTC']['available'];
        $ETHaccountCurrencyMax = $this->api->balances($ticker)['ETH']['available'];
        $EURaccountCurrencyMax = $this->api->balances($ticker)['EUR']['available'];

        $priceBtcEuro = $this->api->price("BTCEUR");
        $priceEthEuro = $this->api->price("ETHEUR");

        // achete des btc en euro
        if($priceBtcEuro <= $_ENV['PRICE_SHOP_BTC'] && $this->session->get('BTCnorepeat') !== 'shop'){
            $this->marketBuy('BTCEUR', $EURaccountCurrencyMax / 2);
            $this->session->set('BTCnorepeat', 'shop');
        }
        // achete des eth en euro
        if($priceEthEuro <= $_ENV['PRICE_SHOP_ETH'] && $this->session->get('ETHnorepeat') !== 'shop'){
            $this->marketBuy('ETHEUR', $EURaccountCurrencyMax / 2);
            $this->session->set('ETHnorepeat', 'shop');
        }
        $BTCrendement = $_ENV['PRICE_SHOP_BTC'] / 5;
        $ETHrendement = $_ENV['PRICE_SHOP_ETH'] / 5;

        $BTCgoodPrice = $_ENV['PRICE_SHOP_BTC'] + $BTCrendement;
        $ETHgoodPrice = $_ENV['PRICE_SHOP_ETH'] + $ETHrendement;

        // vend des btc en euro
        if($priceBtcEuro >= $BTCgoodPrice && $this->session->get('BTCnorepeat') !== 'sell'){
            $this->marketSell('BTCEUR', $BTCaccountCurrencyMax);
            $this->session->set('BTCnorepeat', 'sell');
        }

        // vend des eth en euro
        if($priceEthEuro >= $ETHgoodPrice && $this->session->get('BTCnorepeat') !== 'sell'){
            $this->marketSell('ETHEUR', $ETHaccountCurrencyMax);
            $this->session->set('ETHnorepeat', 'sell');
        }

        return $this->api->balances($ticker);
        //PROD

//        $ticker = $this->api->prices();
//        dump($ticker);
//        dump($this->api->account());
////        $openorders = $this->api->openOrders("BTCEUR");
////        dump($openorders);
//        $quantityMaxCurrency = $this->api->balances($ticker)['BTC']['available'];
//        dump($quantityMaxCurrency);
////        dump($this->api->balances($ticker));
////        $this->marketSell('XRPBUSD', $quantityMaxCurrency);
//        return $this->api->balances($ticker);

    }

    public function marketBuy($symbol,$quantity){
        $order = $this->api->marketBuy($symbol, $quantity);
        dump($order);
    }

    public function marketSell($symbol, $quantity){
        $order = $this->api->marketSell($symbol,$quantity);
        dump($order);
    }

    public function historyCommands($symbol){
        $history = $this->api->history($symbol);
        return $history;
    }

}

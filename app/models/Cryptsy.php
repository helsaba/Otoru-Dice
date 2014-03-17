<?php

class Cryptsy extends Eloquent {

    public static function api_query($method, array $req = array()) {
        // API settings
        $key = 'b597d834906e60d58029a6dd6bba75da2f3b5bdd'; // your API-key
        $secret = 'b37d6e895e1a6b0f445ee750c1b29669481dadebc123f53bbe294ebfa99c9f542b8b6a07ac54b689'; // your Secret-key

        $req['method'] = $method;
        $mt = explode(' ', microtime());
        $req['nonce'] = $mt[1];

        // generate the POST data string
        $post_data = http_build_query($req, '', '&');

        $sign = hash_hmac("sha512", $post_data, $secret);

        // generate the extra headers
        $headers = array(
            'Sign: ' . $sign,
            'Key: ' . $key,
            );

        // our curl handle (initialize if required)
        static $ch = null;
        if (is_null($ch)) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; Cryptsy API PHP client; ' . php_uname('s') .
                '; PHP/' . phpversion() . ')');
        }
        curl_setopt($ch, CURLOPT_URL, 'https://api.cryptsy.com/api');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        // run the query
        $res = curl_exec($ch);

        if ($res === false)
            throw new Exception('Could not get reply: ' . curl_error($ch));
        $dec = json_decode($res, true);
        if (!$dec)
            throw new Exception('Invalid data received, please make sure connection is working and requested API exists');
        return $dec;
    }
    
    public static function GetMarket($currencyCode){
        $result = Cryptsy::api_query("getmarkets");
        if($currencyCode == 'BTC'){
            $btcRate = "0.00";
        }elseif ($result['success'] == 1) {
            foreach ($result['return'] as $coin) {
                if ($coin['primary_currency_code'] == $currencyCode) {
                    $btcRate = $coin['last_trade'];
                    break;
                }
            }
        }
        
        return $btcRate;
    }

}

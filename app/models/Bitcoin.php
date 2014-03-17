<?php

class Bitcoin extends Eloquent {

    public static function Withdraw($amount, $address){
        if(!$address) return "Address if empty fool!!!";

        if(Coin::where('code','BTC')->pluck('pot') >= $amount){
            $guid = "4e0f0682-d6bb-57fa-83eb-fd4b31620d3f";
            $main_password = "admins1234";
            $note = urlencode("Otoru Dice Withdrawal");
            $fee = 0.0001;

            $url = "https://blockchain.info/merchant/$guid/payment?password=$main_password&to=$address&amount=$amount&fee=$fee"."¬e=$note";
            $response = file_get_contents($url);

            DB::table('users')->where('id',Auth::user()->id)->decrement('btc_wallet_balance', $amount);
            Coin::where('code','BTC')->decrement('pot', $amount);

            return $response;
        }else{
            return "Sorry not enough in the bank to pay you. SORRY!!";
        }
    }

    public static function UpdateWalletBalance(){
        try{
            $guid = Auth::user()->btc_wallet_guid;
            $address = Auth::user()->btc_wallet_address;
            $main_password = Auth::user()->btc_wallet_password;
            $url = "https://blockchain.info/merchant/$guid/address_balance?password=$main_password&address=$address&confirmations=1";
            $json = file_get_contents($url);
            $array = json_decode($json);
            $balance = $array->balance / 100000000;

            $user = User::find(Auth::user()->id);
            $user->btc_wallet_balance = $balance;
            $user->save();
        }catch(Exception $e){
            return "Error getting wallet balance";
        }

        return $user->btc_wallet_balance;
    }

    public static function CreateWallet() {
        $fields = [
            'password' => urlencode(Auth::user()->btc_wallet_password),
            'api_code' => urlencode(Settings::Value('blockchain_info_api_code')),
            //'priv' => urlencode(''),
            'label' => urlencode(Auth::user()->username . " at Otoru Dice"),
            'email' => urlencode(Auth::user()->email),
        ];

        //url-ify the data for the POST
        $fields_string = '';
        foreach ($fields as $key => $value) {
            $fields_string .= $key . '=' . $value . '&';
        }
        rtrim($fields_string, '&');

        //open connection
        $ch = curl_init();

        //set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, 'https://blockchain.info/api/v2/create_wallet');
        curl_setopt($ch, CURLOPT_VERBOSE, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, count($fields));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);

        //execute post
        $raw = curl_exec($ch);
        $result = json_decode($raw, true);

        $wallet = Coin::BaseWallet();
        $wallet['CoinCode'] = 'btc';
        $wallet['guid'] = $result['guid'];
        $wallet['address'] = $result['address'];

        if($result){
            $user = User::find(Auth::user()->id);
            $user->btc_wallet_guid = $result['guid'];
            $user->btc_wallet_address = $result['address'];
            $user->save();
            $wallet['Reply'] = 'Success';
        }else {
            $wallet['Reply'] = 'Failure';
            $wallet['Err'] = $raw;
        }
        return $wallet;
    }

    public static function Price() {
        if (Coin::force() || !file_exists('/tmp/btc_price_date') || file_get_contents('/tmp/btc_price_date') <= time()) {
            $timestamp = strtotime('+10 minutes', time());
            file_put_contents('/tmp/btc_price_date', $timestamp);

            $price = file_get_contents('https://blockchain.info/q/24hrprice');
            Coin::where('code', 'BTC')->update(['price' => $price]);
        } else {
            $price = Coin::where('code', 'BTC')->pluck('price');
        }

        return $price;
    }

    public static function CurrentPot() {
        if (Coin::force() || !file_exists('/tmp/btc_pot_date') || file_get_contents('/tmp/btc_pot_date') <= time()) {
            $timestamp = strtotime('+10 minutes', time());
            file_put_contents('/tmp/btc_pot_date', $timestamp);

            $address = Coin::where('code', 'BTC')->pluck('address');
            $pot = file_get_contents("https://blockchain.info/q/addressbalance/$address") / 100000000;
            Coin::where('code', 'BTC')->update(['pot' => $pot]);
        } else {
            $pot = Coin::where('code', 'BTC')->pluck('pot');
        }

        return round($pot, 8);
    }

}

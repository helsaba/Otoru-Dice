<?php

class Auroracoin extends Eloquent {
    
    public static function Price(){
        if(Coin::force() || !file_exists('/tmp/aur_price_date') || file_get_contents('/tmp/aur_price_date') <= time()){
            $timestamp = strtotime('+10 minutes', time());
            file_put_contents('/tmp/aur_price_date', $timestamp);
            
            $price = Cryptsy::GetMarket('AUR');
            Coin::where('code', 'AUR')->update(['price' => $price]);
        }else{
            $price = Coin::where('code', 'AUR')->pluck('price');
        }
        
        return $price;
    }
    
    public static function CurrentPot(){
        if(Coin::force() || !file_exists('/tmp/aur_pot_date') || file_get_contents('/tmp/aur_pot_date') <= time()){
            $timestamp = strtotime('+10 minutes', time());
            file_put_contents('/tmp/aur_pot_date', $timestamp);

            $address = Coin::where('code', 'BTC')->pluck('address');
            $pot = file_get_contents("http://blockexplorer.auroracoin.eu/chain/AuroraCoin/q/addressbalance/$address");
            Coin::where('code', 'AUR')->update(['pot' => $pot]);
        }else{
            $pot = Coin::where('code', 'AUR')->pluck('pot');
        }
        
        return round($pot, 8);
    }
    
    public static function BlockCount(){
        return file_get_contents('http://blockexplorer.auroracoin.eu/chain/AuroraCoin/q/getblockcount');
    }
    
    public static function LatestBlock(){
        $result = file_get_contents('http://blockexplorer.auroracoin.eu/search?q='.Aurora::BlockCount());
        exit(print_r($result));
    }

}
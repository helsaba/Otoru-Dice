<?php

class ApiController extends BaseController {

    public function User(){
        $user = User::find(Input::get('user'));
        $data['id'] = Input::get('user');
        $data['username'] = Input::get('user');

        return Response::json($user);
    }

    public function Withdraw(){
        $coinCode = strtolower(Input::get('coinCode'));
        $address = Input::get('address');
        $amount = Input::get('amount');

        if($coinCode == 'btc'){
            return Bitcoin::Withdraw($amount, $address);
        }
    }

    public function RollHi(){
        return Coin::Roll(100 - Input::get('chance'));
    }

    public function RollLo(){
        return Coin::Roll(Input::get('chance'));
    }

    public function CreateWallet(){
        $wallet = Coin::BaseWallet();

        if(strtolower(Input::get('coin')) == 'btc'){
            $wallet = Bitcoin::CreateWallet();
        }elseif(strtolower(Input::get('coin')) == 'aur'){
            $wallet['CoinCode'] = 'aur';
            $wallet['Reply'] = 'Failure';
            $wallet['Err'] = 'Not Implemented Yet';
        }
        return $wallet;
    }

    public function submissions($coin = false) {
        if($coin){
            return DB::table('Submissions')->where('code', strtolower($coin))->where('date',date('mdy'))->get();
        }else{
            return DB::table('Submissions')->where('date',date('mdy'))->get();
        }
    }
}

<?php

class HomeController extends BaseController {

    public function index() {
        return Redirect::to('btc');
        $data['1'] = false;
        
        if(Session::get('alert')){
            $data['alert'] = Session::get('alert');
        }
        
        return View::make('index', $data);
    }

    public function BTCDice() {
        return View::make('dice', Coin::DiceGame('Bitcoin', 'BTC'));
    }

    public function AURDice() {
        return View::make('dice', Coin::DiceGame('Auroracoin', 'AUR'));
    }

    public function newSubmission($coin){
        $txid = Input::get('txid');
        $address = Input::get('address');
        $fields = ['address' => $address, 'code' => $coin, 'txid' => $txid, 'date' => date('mdy'), 'LuckyNumber' => rand(1, 100), 'created_at' => time(), 'updated_at' => time()];

        if($id=DB::table('Submissions')->insertGetId($fields)){
            $luckynumber = '<br />Your Lucky Number: ' . DB::table('Submissions')->where('id', $id)->pluck('LuckyNumber');
            return Redirect::to($coin)->with('alert', 'Submission Accepted');
        }else{
            return Redirect::to($coin)->with('alert', 'Submission Failed');
        }
    }

    public function Install() {
        Settings::CreateTable();
        History::CreateTable();
        User::CreateTable();
        Coin::CreateTable();

        return Redirect::to('/');
    }

}

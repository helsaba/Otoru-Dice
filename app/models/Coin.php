<?php

class Coin extends Eloquent {

    protected $table = 'Coin';
    
    public static function force(){
        return false;
    }

    public static function BaseWallet(){
        $result['guid'] = '';
        $result['address'] = '';
        $result['CoinCode'] = '';
        $result['Reply'] = '';
        $result['Err'] = '';
        return $result;
    }

    public static function DiceGame($name, $coinCode){
        $wallet_address = strtolower(strtolower($coinCode)) . "_wallet_address";
        $data['banner'] = URL::to('public/images/'.strtolower($coinCode).'.png');
        $data['balance'] = User::Balance($coinCode);
        $data['coin'] = $coinCode;
        $data['title'] = $name;

        if($coinCode == 'BTC'){
            $data['bankRoll'] = Bitcoin::CurrentPot();
        }else if($coinCode == 'AUR'){
            $data['bankRoll'] = Auroracoin::CurrentPot();
        }

        if(!Auth::guest() && !Auth::user()->$wallet_address){
            //$data['alert'] = "You currently don't have a wallet!!<br> Go to your <a href='account'>account</a> to make one.";
        }

        if(Session::get('alert')){
            $data['alert'] = Session::get('alert');
        }

        return $data;
    }

    public static function Roll($chance){
        $coinCode = Input::get('coinCode');
        $betSize = Input::get('betsize');
        $profit = Input::get('profit');
        $payout = Input::get('payout');

        $roll = rand(1, 100);

        if($roll < $chance){
            $result = 'Win';
        }else{
            $result = 'Lose';
        }

        $wallet_balance = strtolower($coinCode) . "_wallet_balance";
        if(Auth::user()->$wallet_balance >= $betSize) {
            if($result == 'Win') {
                $balance = Coin::Give($coinCode, $profit);
            }else {
                $balance = Coin::Take($coinCode, $betSize);
            }
            History::Add($betSize, $payout, $profit, $coinCode);
        }else{
            $result = "No Funds";
            $balance = Auth::user()->$wallet_balance;
        }

        return json_encode(['result'=>$result, 'profit'=>$balance]);
    }

    public static function Give($coinCode, $profit){
        $wallet_balance = strtolower($coinCode) . "_wallet_balance";
        DB::table('users')->where('id',Auth::user()->id)->increment($wallet_balance, $profit);

        //Take funds from the pot when giving it to the user.
        Coin::where('code',strtolower($coinCode))->decrement('pot', $profit);

        return  DB::table('users')->where('id',Auth::user()->id)->pluck($wallet_balance);
    }

    public static function Take($coinCode, $betsize){
        $wallet_balance = strtolower($coinCode) . "_wallet_balance";
        DB::table('users')->where('id',Auth::user()->id)->decrement($wallet_balance, $betsize);

        //Give funds to the pot when taking it from the user.
        Coin::where('code',strtolower($coinCode))->increment('pot', $betsize);

        return  DB::table('users')->where('id',Auth::user()->id)->pluck($wallet_balance);
    }

    public static function CreateTable() {
        Schema::dropIfExists('Coin');
        Schema::create('Coin', function ($table) {
            $table->increments('id');
            $table->string('code');
            $table->string('address');
            $table->string('password')->default("");
            $table->integer('entries')->default(0);
            $table->integer('price')->default(0);
            $table->integer('pot')->default(0);
            $table->boolean('active');
            $table->timestamps();
        });
        
        DB::table('Coin')->insert(array('code' => 'BTC', 'address' => '', 'active' => true, 'created_at' => time(), 'updated_at' => time()));
        DB::table('Coin')->insert(array('code' => 'AUR', 'address' => '', 'active' => true, 'created_at' => time(), 'updated_at' => time()));
        
        $table = "Submissions";
        Schema::dropIfExists($table);
        Schema::create($table, function ($table) {
            $table->increments('id');
            $table->string('address');
            $table->string('code');
            $table->string('txid');
            $table->string('date');
            $table->integer('LuckyNumber')->default(0);
            $table->boolean('winner')->default(false);
            $table->timestamps();
        });
    }

}

<?php

use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableInterface;

class User extends Eloquent implements UserInterface, RemindableInterface {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = array('password','btc_wallet_password','aur_wallet_password','btc_wallet_guid');

    /**
     * Get the unique identifier for the user.
     *
     * @return mixed
     */
    public function getAuthIdentifier() {
        return $this->getKey();
    }

    /**
     * Get the password for the user.
     *
     * @return string
     */
    public function getAuthPassword() {
        return $this->password;
    }

    /**
     * Get the e-mail address where password reminders are sent.
     *
     * @return string
     */
    public function getReminderEmail() {
        return $this->email;
    }

    public static function AccountTypes() {
        return ['Admin','Mod','User'];
    }

    public static function Id() {
        return Auth::user()->id;
    }

    public static function InitConnect() {
        if(!Auth::guest()){
            return json_encode(['Type'=>'Hello','id'=>Auth::user()->id, 'name'=>Auth::user()->username]);
        }else{
            return json_encode(['Type'=>'Hello','id'=>'0', 'name'=>'Guest'.rand()]);
        }
    }

    public static function Json(){
        return json_encode(User::where('id', Auth::user()->id)->first()->toArray());
    }
    
    public static function Balance($coinCode){
        $wallet_balance = strtolower($coinCode) . "_wallet_balance";
        
        if(Auth::user()){
            $balance = Auth::user()->$wallet_balance;
        }else{
            $balance = 0;
        }
        
        return $balance;
    }
    
    public static function CreateTable(){
        Schema::dropIfExists('users');
        Schema::create('users', function($table) {
            $table->increments('id');
            $table->string('email');
            $table->string('username');
            $table->string('password');
            $table->string('access')->default('User');
            
            $table->string('btc_wallet_guid')->default('');
            $table->string('btc_wallet_password')->default(User::genPassword());
            $table->string('btc_wallet_address')->default('');
            $table->integer('btc_wallet_balance')->default(0);
            
            $table->string('aur_wallet_guid')->default('');
            $table->string('aur_wallet_password')->default(User::genPassword());
            $table->string('aur_wallet_address')->default('');
            $table->integer('aur_wallet_balance')->default(0);
            
            $table->timestamps();
        });
        DB::table('users')->insert(
            array('email' => 'test@test.com', 'username' => 'Tsume', 'password' => Hash::make('test'), 'access' => 'Admin', 'btc_wallet_balance' => 100, 'created_at' => '', 'updated_at' => ''),
            array('email' => 'test@test.com', 'username' => 'test', 'password' => Hash::make('test'), 'access' => 'User', 'btc_wallet_balance' => 100, 'created_at' => '', 'updated_at' => '')
        );
    }

    public static function genPassword() {
        $alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
        $pass = array(); //remember to declare $pass as an array
        $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
        for ($i = 0; $i < 12; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return implode($pass); //turn the array into a string
    }

}

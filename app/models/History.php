<?php

class History extends Eloquent {

    protected $table = 'History';

    public static function Add($bet,$payout,$profit,$coinCode){
        DB::table('History')->insert([
            'userId' => User::Id(),
            'time' => time(),
            'bet' => $bet,
            'payout' => $payout,
            'profit' => $profit,
            'currency' => $coinCode,
            'created_at' => time(),
            'updated_at' => time()
        ]);
    }
    
    public static function CreateTable() {
        Schema::dropIfExists('History');
        Schema::create('History', function ($table) {
            $table->increments('id');
            $table->integer('userId');
            $table->integer('time');
            $table->integer('bet');
            $table->integer('payout')->default(0);
            $table->integer('profit')->default(0);
            $table->integer('currency')->default(0);
        });
    }

}

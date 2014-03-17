<?php

class Settings extends Eloquent {

    protected $table = 'settings';
    
    public static function Quote(){        
        $quotes[] = "Watch your mouth kid, or you'll find yourself floating home.";
        $quotes[] = "Let the force flow through you...";
        $quotes[] = "This will be a day long remembered.";
        $quotes[] = "It has seen the end of Kenobi, and will soon see the end of the rebellion.";
        $quotes[] = "What a piece of junk!";
        $quotes[] = "Don't call me a mindless philosopher, you overweight glob of grease.";
        $quotes[] = "I'm Luke Skywalker, I'm here to rescue you.";
        $quotes[] = "Evacuate in our moment of triumph? I think you overestimate their chances.";
        
        return "<span style='font-size: 16'>".$quotes[array_rand($quotes)]."</span>";
    }
    
    public static function LuckyNumber(){
        $result = Settings::where('name', 'LuckyNumber')->pluck('value');
        $date = Settings::where('name', 'LuckyNumber_Date')->pluck('value');
        
        if(!$result || $date != date('mdY')){
            Settings::where('name', 'LuckyNumber')->update(['value' => rand(1, 100)]);
            Settings::where('name', 'LuckyNumber_Date')->update(['value' => date('mdY')]);
        }
        
        return Settings::where('name', 'LuckyNumber')->pluck('value');
    }

    public static function Value($name) {
        if(!Settings::where('name', $name)->pluck('value')){
            Settings::insert(array('name' => $name, 'value' => 'null', 'created_at' => '', 'updated_at' => ''));
        }
        
        return Settings::where('name', $name)->pluck('value');
    }

    public static function CreateTable() {
        Schema::dropIfExists('settings');
        Schema::create('settings', function ($table) {
            $table->increments('id'); 
            $table->string('name'); 
            $table->string('value')->default('null'); 
            $table->timestamps(); 
        });
        
        DB::table('settings')->insert(array('name' => 'Name', 'value' => 'Otoru Dice', 'created_at' => '', 'updated_at' => ''));
        DB::table('settings')->insert(array('name' => 'LuckyNumber', 'value' => '', 'created_at' => '', 'updated_at' => ''));
        DB::table('settings')->insert(array('name' => 'LuckyNumber_Date', 'value' => '', 'created_at' => '', 'updated_at' => ''));
    }
    
    public static function Maintence(){
        return false;
        return (!Auth::user() || Auth::user()->access != 'Admin');
    }

}

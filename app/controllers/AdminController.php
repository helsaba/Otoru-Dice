<?php

class AdminController extends BaseController {
    
    public function Settings(){
        $data['user'] = Auth::user();
        
        if($data['user']->access != 'Admin') return 'Access Denied BITCH!!!';
        
        if(Input::get('submit') == 'update'){ 
            Settings::where('name', 'default_chance')->update(['value' => Input::get('default_chance')]);
            Settings::where('name', 'blockchain_info_api_code')->update(['value' => Input::get('btc_api_code')]);
            Settings::where('name', 'test_mode')->update(['value' => Input::get('test_mode') ? true : false]);

            Coin::where('code','BTC')->update(['address' => Input::get('btc_address')]);
            Coin::where('code','AUR')->update(['address' => Input::get('aur_address')]);

            Coin::where('code','BTC')->update(['pot' => Input::get('btc_pot')]);
            Coin::where('code','AUR')->update(['pot' => Input::get('aur_pot')]);

            if(Input::get('accounts') > 0) {
                $user = User::find(Input::get('accounts'));
                $user->username = Input::get('u_username');
                $user->email = Input::get('u_email');
                $user->btc_wallet_balance = Input::get('u_btc_balance');
                $user->aur_wallet_balance = Input::get('u_aur_balance');
                $user->save();
            }
            
            return Redirect::to('/')->with('alert', "Global Settings Updated!!");
        }
        else if(Input::get('submit') == 'delete'){
            $id = Input::get('accounts');
            DB::table('users')->where('id', $id)->delete();
            return Redirect::to('/')->with('alert', "Account '$id' Deleted");
        }
    }
}

<?php

class UserController extends BaseController {

    public function Account(){
        $data['user'] = Auth::user();

        if(Input::get('submit') == 'update'){
            $user = User::find($data['user']->id);
            foreach(Input::except('submit') as $key => $value){
                $user->$key = $value;
            }

            $user->save();
            $data['user'] = $user;
            $data['alert'] = "Account details updated!!";
        }

        return View::make('user.account', $data);
    }



    public function Login() {
        if (Input::get('submit')) {
            $user = Input::get('user');
            $pass = Input::get('pass');

            if(strlen($user) == 0) return Redirect::to('/')->with('alert', 'Error: Empty username');
            if(strlen($pass) == 0) return Redirect::to('/')->with('alert', 'Error: Empty password');

            if (Auth::attempt(array('username' => $user, 'password' => $pass), true)) {
                return Redirect::to('/')->with('alert', 'Login Accepted');
            }
        }

        return Redirect::to('/')->with('alert', 'Login Failed');
    }



    public function Signup() {
        if (Input::get('submit')) {
            $email = Input::get('email');
            $username = Input::get('user');
            $password = Input::get('pass');

            if(strlen($email) < 5) return 'Error: email incorrect.';
            if(strlen($username) < 5) return 'Error: username too short. At least 6 characters';
            if(strlen($password) < 5) return 'Error: password too short. At least 6 characters';

            $user = new User;
            $user->email = $email;
            $user->username = $username;
            $user->password = Hash::make($password);
            $user->btc_wallet_password = User::genPassword();
            $user->aur_wallet_password = User::genPassword();
            $user->save();

            Auth::login($user);
            return Redirect::to('/');
        }
    }

    public function Logout() {
        Auth::logout();
        return Redirect::to('/');
    }
}


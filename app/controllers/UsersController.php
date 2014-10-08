<?php

class UsersController extends BaseController{
    protected $layout = "layouts.main";
    
    public function __construct() {
        $this->beforeFilter('csrf',array('on'=>'post')); //protecting our post form from being accessed/used from somewhere else
        $this->beforeFilter('auth',array('only'=>array('getDashboard'))); //we se the auth filter, which checks if the current user is logged in
    }

        public function getRegister(){
        $this->layout->content = View::make('users.register');
    }
    public function postCreate(){
        $validator = Validator::make(Input::all(),User::$rules);
        if($validator->passes()){
            //validation has passed, saveuser in DB
            $user = new User;  //creating an isntanceof our user model
            $user->firstname = Input::get('firstname');
            $user->lastname = Input::get('lastname');
            $user->email = Input::get('email');
            $user->password = Hash::make(Input::get('password'));
            $user->save();
            
            return Redirect::to('users/login')->with('message','Thanks for Registering');
        }  else {
            //validation has failed, display error messages
            //i love this, short and precise
            return Redirect::to('users/register')->with('message','The following errors occured')->withErrors($validator)->withInput();
        }
    }
    public function getLogin(){
        $this->layout->content = View::make('users.login');
    }
    public function postSignin(){
        if(Auth::attempt(array('email'=>Input::get('email'),'password'=>Input::get('password')))){
            return Redirect::to('users/dashboard')->with('message','you are now logged in!');
        }else{
            return Redirect::to('users/login')->with('message','Your Username/password combination was incorrect')->withInput();
        }
    }
    
    
    public function getDashboard(){
        $this->layout->content = View::make('users.dashboard');
    }
    public function getLogout(){
        Auth::logout();
        return Redirect::to('users/login')->with('message','You are now logged out!');
    }
}


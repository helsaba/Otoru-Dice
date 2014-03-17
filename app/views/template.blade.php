<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, user-scalable=yes"/>
    
    <title>
    {{Settings::Value('Name')}}
    @if(isset($title))
     | {{$title}}
    @endif
    </title>
    
        
    {{ HTML::style('//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css') }}
    {{ HTML::style('//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css') }}

    {{ HTML::script('//code.jquery.com/jquery-2.1.0.min.js') }}
    {{ HTML::script('//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js') }}

    {{ HTML::style('public/css/master.css') }}
    {{ HTML::script('public/js/master.js') }}
</head>
<body>
    
	<div class="navbar-wrapper">
		<div class="container">
			<div class="navbar navbar-default navbar-fixed-top" role="navigation">
				<div class="container">
					<div class="navbar-header">
						<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
							<span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span><span
								class="icon-bar"></span><span class="icon-bar"></span>
						</button>
						<a class="navbar-brand" href="{{URL::to('')}}">{{Settings::Value('Name')}}</a>
					</div>
					<div class="navbar-collapse collapse">
						<ul class="nav navbar-nav">
							<li><a href="{{URL::to('btc')}}"><img src="{{URL::to('public/images/logo/btc.png')}}" alt="" width="20"/> BitCoin</a></li>
							<!--<li><a href="{{URL::to('aur')}}"><img src="{{URL::to('public/images/logo/aur.png')}}" alt="" width="20"/> AuroraCoin</a></li>-->
						</ul>
                        
                        @if(Auth::guest())
                        <a class="navbar-brand pull-right" href="#" data-toggle="modal" data-target="#registerModal"><strong>Register</strong></a>
                        <a class="navbar-brand pull-right">|</a>
						<a class="navbar-brand pull-right" href="#" data-toggle="modal" data-target="#loginModal"><strong>Login</strong></a>
                        @else
						<a class="navbar-brand pull-right" href="{{URL::to('logout')}}"><strong>Logout</strong></a>
                        <a class="navbar-brand pull-right">|</a>
						<a class="navbar-brand pull-right" href="{{URL::to('account')}}"><strong>Account</strong></a>
                        @if(!Auth::guest() && Auth::user()->access == 'Admin')
                        <a class="navbar-brand pull-right">|</a>
                        <a class="navbar-brand pull-right" href="#" data-toggle="modal" data-target="#admin"><strong>Admin</strong></a>
                        @endif
                        <a class="navbar-brand pull-right">|</a>
                        <a class="navbar-brand pull-right" href="{{URL::to('account')}}">Welcome, <strong>{{Auth::user()->username}}</strong></a>
                        @endif

                        
                        @if(isset($btcRate))
                        <a class="navbar-brand pull-right" style="margin-right: 5%;" href="{{URL::to('')}}">Market Price: {{$btcRate}}</a>
                        @endif
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<div class="container" style="width: 60%">
    
        @if(!Auth::guest() && Auth::user()->access == 'Admin')
        <div class="modal fade" id="admin" tabindex="-1" role="dialog" aria-labelledby="admin" aria-hidden="true">
            <div class="modal-dialog" style="width: 75%">
                <form method="post" action="{{URL::to('global/update')}}">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h4 class="modal-title">Administration Panel</h4>
                        </div>
                        <div class="modal-body">

                            <div class="col-md-12">

                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="input-group">
                                            <span class="input-group-addon" style="width: 90px;">BTC Bank</span>
                                            <input class="form-control" type="text" value="{{Coin::where('code','BTC')->pluck('pot')}}" style="width: 150px;" disabled="" />
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="input-group">
                                            <span class="input-group-addon" style="width: 90px;">AUR Bank</span>
                                            <input class="form-control" type="text" value="{{Coin::where('code','AUR')->pluck('pot')}}" style="width: 150px;" disabled="" />
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="input-group">
                                            <span class="input-group-addon" style="width: 90px;">Accounts</span>
                                            <select class="form-control" id="accounts" name="accounts">
                                                <option value="0">Select User</option>
                                                @foreach(User::get() as $user)
                                                <option value="{{$user->id}}">{{$user->username}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="input-group">
                                            <span class="input-group-addon" style="width: 90px;">Account Type</span>
                                            <select class="form-control" id="access" name="access">
                                                @foreach(User::groupBy('access')->orderBy('id','desc')->get() as $user)
                                                <option value="{{$user->access}}" selected="<?= $user->access=='Admin' ? 'selected' : '' ?>" >{{$user->access}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <br />
                                <div class="row">
                                    <div class="col-md-6">
                                        Default Chance To Win
                                        <input class="form-control" type="text" name="default_chance" value="{{Settings::Value('default_chance')}}" /><br />

                                        Bitcoin API Code <a href="https://blockchain.info/api/api_create_code" target="_blank">(Get One)</a>
                                        <input class="form-control" type="text" name="btc_api_code" value="{{Settings::Value('blockchain_info_api_code')}}" /><br />

                                        Bitcoin Bank Address
                                        <input class="form-control" type="text" name="btc_address" value="{{Coin::where('code','BTC')->pluck('address')}}" /><br />

                                        Auroracoin Bank Address
                                        <input class="form-control" type="text" name="aur_address" value="{{Coin::where('code','AUR')->pluck('address')}}" /><br />

                                        <input type="checkbox" name="test_mode" value="1" <?=Settings::Value('test_mode') ? 'checked=""' : ''?> />Test Mode<br />

                                        @if(Settings::Value('test_mode'))
                                        <br />Bitcoin Profits
                                        <input class="form-control" type="text" name="btc_pot" value="{{Coin::where('code','BTC')->pluck('pot')}}" /><br />

                                        Aurora Profits
                                        <input class="form-control" type="text" name="aur_pot" value="{{Coin::where('code','AUR')->pluck('pot')}}" /><br />

                                        @endif
                                    </div>

                                    <div class="col-md-6">
                                        Username
                                        <input class="form-control" type="text" id="u_username" name="u_username" /><br />

                                        E-Mail
                                        <input class="form-control" type="text" id="u_email" name="u_email" /><br />

                                        Bitcoin Balance
                                        <input class="form-control" type="text" id="u_btc_balance" name="u_btc_balance" /><br />

                                        Aurora Balance
                                        <input class="form-control" type="text" id="u_aur_balance" name="u_aur_balance" /><br />
                                        <button type="submit" name="submit" value="delete" class="btn btn-danger">Delete</button>
                                    </div>
                                </div>

                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="submit" name="submit" value="update" class="btn btn-primary">Submit</button>
                        </div>
                    </div><!-- /.modal-content -->
                </form>
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
        @endif
    
        @if(Auth::guest())
        <form method="post" action="{{URL::to('login')}}">
            <div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="loginModal" aria-hidden="true">
            	<div class="modal-dialog">
            		<div class="modal-content">
            			<div class="modal-header">
            				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            				<h4 class="modal-title">Login to Protect Your Winnings</h4>
            			</div>
            			<div class="modal-body">
            				<input class="form-control" type="text" name="user" placeholder="Username" /><br />
                            <input class="form-control" type="password" name="pass" placeholder="Password" /><br />
            			</div>
            			<div class="modal-footer">
            				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            				<button type="submit" name="submit" value="login" class="btn btn-primary">Login</button>
            			</div>
            		</div><!-- /.modal-content -->
            	</div><!-- /.modal-dialog -->
            </div><!-- /.modal -->
        </form>
        
        <form method="post" action="{{URL::to('signup')}}">
            <div class="modal fade" id="registerModal" tabindex="-1" role="dialog" aria-labelledby="registerModal" aria-hidden="true">
            	<div class="modal-dialog">
            		<div class="modal-content">
            			<div class="modal-header">
            				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            				<h4 class="modal-title">Registration</h4>
            			</div>
            			<div class="modal-body">
            				<input class="form-control" name="user" placeholder="Username" /><br />
                            <input class="form-control" name="pass" placeholder="Password" /><br />
                            <input class="form-control" name="email" placeholder="E-Mail Address" /><br />
            			</div>
            			<div class="modal-footer">
            				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            				<button type="submit" name="submit" value="reg" class="btn btn-primary">Submit</button>
            			</div>
            		</div><!-- /.modal-content -->
            	</div><!-- /.modal-dialog -->
            </div><!-- /.modal -->
        </form>
        @endif
        
        @if(isset($alert))
            <div class="alert alert-warning">{{$alert}}</div>
        @endif
    
        @yield('content')
         
    </div>

    {{ HTML::script('public/js/bottom.js') }}
    
</body>
</html>
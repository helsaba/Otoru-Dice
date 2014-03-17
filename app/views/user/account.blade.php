@extends('template')
@section('content')

<div class="page-header">
  <h1><small>{{Settings::Quote()}}</small></h1>
</div>

<div class="row">
    
    <div class="col-lg-6">
        <div class="page-header">
            <h3>Account</h3>
        </div>
        
        <form method="post" action="">
            <div class="panel panel-default">
                <div class="panel-heading">General
                    <button type="submit" name="submit" value="update" class="btn btn-sm btn-default pull-right" style="margin-top: -5px;">Update</button>
                </div>
                <div class="panel-body">
                    <div class="col-md-6">
                        <label for="username">Username</label>
                        <input class="form-control" type="text" name="username" value="{{$user->username}}" />
                    </div>

                    <div class="col-md-6">
                        <label for="email">E-mail</label>
                        <input class="form-control" type="email" name="email" value="{{$user->email}}" />
                    </div>
                </div>
            </div>
        </form>
        
        <!--<form method="post" action="">
            <div class="panel panel-default">
                <div class="panel-heading">Bitcoin Wallet
                    @if(!$user->btc_wallet_address)
                    <button type="button" class="btn btn-sm btn-default pull-right" style="margin-top: -5px;" name="create_btc_Wallet" id="create_btc_Wallet">Create Wallet</button>
                    @endif
                </div>
                <div class="panel-body">
                    <div class="col-md-12">
                        <label for="btc_balance">Balance</label>
                        <input class="form-control" id="btc_balance" value="{{$user->btc_wallet_balance}}" disabled="disabled" />
                    </div>

                    <div class="col-md-12"><br>
                        <label for="btc_address">Address</label>
                        <input class="form-control" id="btc_address" value="{{$user->btc_wallet_address}}" disabled="disabled" />
                    </div>
                </div>
            </div>
        </form>
        
        <div class="panel panel-default">
            <div class="panel-heading">Auroracoin Wallet
                @if(!$user->aur_wallet_address)
                <button type="button" class="btn btn-sm btn-default pull-right" style="margin-top: -5px;" name="create_aur_Wallet" id="create_aur_Wallet">Create Wallet</button>
                @endif
            </div>
            <div class="panel-body">
                <div class="col-md-12">
                    <label for="aur_balance">Balance</label>
                    <input class="form-control" id="aur_balance" value="{{$user->aur_wallet_balance}}" disabled="disabled" />
                </div>
                
                <div class="col-md-12"><br>
                    <label for="aur_address">Address</label>
                    <input class="form-control" id="aur_address" value="{{$user->aur_wallet_address}}" disabled="disabled" />
                </div>
            </div>
        </div>-->
    
    </div>
    
    <div class="col-lg-6">
        <div class="page-header">
            <h3>History</h3>
        </div>
        
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Time</th>
                    <th>Bet</th>
                    <th>Payout</th>
                    <th>Total</th>
                    <th>Currency</th>
                </tr>
            </thead>            
            <tbody>
            @foreach(History::where('userId',$user->id)->orderBy('id','DESC')->limit(10)->get() as $row)
                <tr>
                    <td>{{$row->id}}</td>
                    <td>{{date('m-d-y @ H:m:s', $row->time)}}</td>
                    <td>{{$row->bet}}</td>
                    <td>{{$row->payout}}x</td>
                    <td><span class=""><strong>{{$row->profit}}</strong></span></td>
                    <td>{{$row->currency}}</td>
                </tr>
            </tbody>
            @endforeach
        </table>
        
    </div>
    
</div>

<script>
$( "#create_btc_Wallet" ).click(function() { newWallet('btc'); });
$( "#create_aur_Wallet" ).click(function() { newWallet('aur'); });
</script>

@stop
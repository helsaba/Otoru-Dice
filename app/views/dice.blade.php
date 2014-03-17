@extends('template')
@section('content')

<div class="row">

    <div class="page-header" style="height: 75px;">
        <div class="row-fluid">
            <h1 class="pull-right"><small>Bank: {{$bankRoll}}</small></h1>
            <img src="{{$banner}}" alt="" width="215" />
        </div>
    </div>

    <div class="col-md-12">

        <div class="panel panel-default">

            <div class="panel-body">

                <h3><small>{{Settings::Quote()}}</small>
                    <span class="pull-right">
                        @if(!Auth::guest())
                        <a class="btn btn-default" href="#" data-toggle="modal" data-target="#deposit">Deposit</a>
                        <a class="btn btn-default" href="#" data-toggle="modal" data-target="#withdraw">Withdraw</a>
                        @else
                        <strong>Login/Register to deposit/withdraw funds!</strong>
                        @endif
                        &nbsp;Balance: <span id="mybalance">{{$balance}}</span>
                    </span>
                </h3>
                <hr />
                <div class="row">

                    <div class="col-md-4">
                        <div class="input-group">
                            <span class="input-group-addon" style="width: 125px;">Chance To Win</span>
                            <input class="form-control" type="text" name="chance" id="chance" value="{{Settings::Value('default_chance')}}" />
                            <span class="input-group-addon">%</span>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="input-group">
                            <span class="input-group-addon" style="width: 125px;">Bet Size</span>
                            <input class="form-control" type="text" name="betsize" id="betsize" value="2" />
                            <span class="input-group-addon">BTC</span>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <button class="btn btn-default" id="rollhi" name="rollhi" style="width:100px;">Roll Hi</button>
                    </div>

                </div>
                <br />
                <div class="row">

                    <div class="col-md-4">
                        <div class="input-group">
                            <span class="input-group-addon" style="width: 125px;">Payout</span>
                            <input class="form-control" type="text" name="payout" id="payout" value="2" />
                            <span class="input-group-addon">x</span>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="input-group">
                            <span class="input-group-addon" style="width: 125px;">Profit</span>
                            <input class="form-control" type="text" name="profit" id="profit" value="2" />
                            <span class="input-group-addon">BTC</span>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <button class="btn btn-default" id="rolllo" name="rolllo" style="width:100px;">Roll Lo</button>
                    </div>

                </div>

            </div>

            <div class="panel-footing">
                <div class="col-md-12">
                    <button class="btn pull-right" id="result" style="width:100px;"></button>
                </div>
            </div>

        </div>

        <div class="modal fade" id="deposit" tabindex="-1" role="dialog" aria-labelledby="deposit" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">Deposit {{$title}}s</h4>
                    </div>
                    <div class="modal-body">
                        <p>
                            To deposit, send {{$title}}s to:
                            <br>
                            <strong>{{Coin::where('code', strtoupper($coin))->pluck('address')}}</strong>
                            <br>
                            Your deposit will need 1 confirmation before it is credited to your account.
                        </p>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->

        <div class="modal fade" id="withdraw" tabindex="-1" role="dialog" aria-labelledby="withdraw" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">Withdraw {{$title}}s</h4>
                    </div>
                    <div class="modal-body">
                        <p>
                            <strong>Note</strong>: Your withdrawal will come from a random hot wallet. Do not withdraw to SatoshiDice, bit365,
                            or any other site that uses the sending address to return coins because any winnings will probably
                            be credited to a different player.
                        </p>
                        <form method="post" action="{{URL::to('api/withdraw')}}">
                            <input name="coinCode" value="{{$coin}}" hidden="hidden" />
                            <input class="form-control" name="address" placeholder="Your Address" /><br>
                            <input class="form-control" name="amount" id="amount" placeholder="Amount" /><br>
                            <button class="btn btn-default" type="submit" name="submit" value="submit">Withdraw</button>
                        </form>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->

        <br />
        <div class="row">

            {{ HTML::style('public/css/client.css') }}

        	<div class="message-wrap col-lg-12" style="height: 400px;">
        		<div id="chat" class="msg-wrap">

                    <div class='media msg'>
                        <div class='media-body'>
                            <!--<small class='pull-right time'><i class='fa fa-clock-o'></i> 01/11/2014 07:38:00</small>
                            <h5 class='media-heading'>Example Name</h5>
                            <small class='col-lg-10'>Example Chat Text</small>-->
                        </div>
                    </div>

        		</div>

                <form method="post" id="sendForm">
                    <div class="send-wrap">
                        <input type="text" id="chatInput" class="form-control send-message" placeholder="Enter reply..."/>
                        <button id="sendMsg" type="button" class="btn btn-default pull-right" style="margin-top: 15px" role="button">Send</button>
                    </div>
                </form>
        	</div>
        </div>

        <div id="Connect"></div>

        <script>
            var init = '<?=User::InitConnect()?>';
        </script>

    </div>

</div>

@stop
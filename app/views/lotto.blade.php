@extends('template')
@section('content')

<link href='http://fonts.googleapis.com/css?family=VT323' rel='stylesheet'/>

<div class="row">
    
    <div class="page-header" style="height: 135px;">
        <div class="row-fluid">
            <img src="{{$banner}}" alt="" width="415px" />
            <h2 class="pull-right">
                Lottery Pot: <span style="color: gray;">{{$pot}}</span>
                
            </h2>
        </div>
    </div>
    
    <div style="text-align:center">
        <p>
            <strong>How It Works</strong><br />
            Each submission into the pot generates a random number from 1 to 100,<br />
            and assigns it to your address. Once the pot hits the payout limit,<br />
            75% is sent to the address closest to the lucky number,<br />
            the remaining is dispersed to everyone who pitched into the pot.<br />
        </p>
    </div>        
        
    <div class="col-md-6">
    
        <div class="panel panel-default">
        
            <div class="panel-body">
            
                <h3>Pot List
                <span id="result" class="pull-right">Lucky #: {{Settings::LuckyNumber()}}&nbsp;</span>
                </h3><hr />
                
                <div class="row">
                    
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>Address</th>
                                <th>Entries</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach(Coin::where('code',$coin)->get() as $row)
                            <tr>
                                <td>{{$row->id}}</td>
                                <td>{{$row->address}}</td>
                                <td>{{DB::table('Submissions')->where('code',strtolower($coin))->where('date', date('mdy'))->count()}}</td>
                                <td>{{round($row->pot, 8)}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                        
                </div>

            </div>        
        
        </div>
    
    </div>

    <div class="col-md-6">
    
        <div class="panel panel-default">
        
            <div class="panel-body">
                <h3>Recent Winners</h3><hr />
                
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Who</th>
                            <th>When</th>
                            <th>Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><a href="{{$url}}/AJeNjUZDyQhDEELexaZBJgSxjgA9XizJ58" target="_blank">AJeNjUZDyQhDEELexaZBJgSxjgA9XizJ58</a></td>
                            <td>03/10/2014</td>
                            <td>0.01</td>
                        </tr>
                    </tbody>
                
                </table>
                
            </div>        
        
        </div>
    
    </div>
        
    <form method="post" action="{{URL::to('/'.strtolower($coin).'/newsubmission')}}">
        <div class="col-md-6">
            <strong>
                Submit your wallet address and transaction id after you've sent your coins.<br />
                Unless you've added to the pot you have a 0% chance of winning.<br />
            </strong><br />
            <input class="form-control" name="txid" id="txid" placeholder="Transaction ID" /><br />
            <input class="form-control" name="address" id="address" placeholder="Wallet Address" /><br />
            <button type="submit" name="submit" class="btn btn-default" style="width: 150px;">Submit</button>
        </div>
    </form>
    
</div>

@stop

NumericOnly('chance');
NumericOnly('payout');
NumericOnly('profit');
NumericOnly('betsize');
NumericOnly('amount');

$("#chance").keyup(function(){
    $("#payout").val( getPayout() );
    $("#profit").val( getProfit() );
});

$("#payout").keyup(function(){
    $("#profit").val( getProfit() );
});

$("#profit").keyup(function(){
    $("#profit").val( getProfit() );
});

$("#betsize").keyup(function(){
    $("#profit").val( getProfit() );
});

$("#rollhi").click(function() {
    rollDice('hi', 'btc');
});

$("#rolllo").click(function() {
    rollDice('lo', 'btc');
});

$("#accounts").change(function() {
    var user = $(this).val();
    $.post( "./api/user", { user:user  }, function(data) {
        $("#u_username").val(data.username);
        $("#u_email").val(data.email);
        $("#u_btc_balance").val(data.btc_wallet_balance);
        $("#u_aur_balance").val(data.aur_wallet_balance);
    })
});

if ("WebSocket" in window)
{
    // Let us open a web socket
    var ws = new WebSocket("ws://192.95.26.184:56667/echo");
    ws.onopen = function()
    {
        ws.send(init);
        var name = jQuery.parseJSON(init).name;

        $( "#sendForm" ).submit(function( event ) {
            event.preventDefault();
            var msg = $("#chatInput").val();
            $("#chatInput").val('');
            ws.send('{"Type":"Chat","name":"'+name+'","message":"'+msg+'"}');
        });
    };
    ws.onmessage = function (evt)
    {
        var received_msg = evt.data;

        if(received_msg.toLowerCase().indexOf("update|") >= 0) {
        }else if(received_msg.toLowerCase().indexOf("init|") >= 0) {
            received_msg = received_msg.replace('Init|','');
            $('#chat').html("<h5 class='media-heading'>"+received_msg+"</h5><br/>");
        }
        else if(received_msg.toLowerCase().indexOf("chat|") >= 0) {
            var data = jQuery.parseJSON(received_msg);
            var message = $('<div />').text(data.message).html();
            $('#chat').append("<small class='col-lg-12'>["+data.date+"] <strong>"+data.name+"</strong>: "+message+"</small>");

            document.getElementById("chat").scrollTop = document.getElementById("chat").scrollHeight;
        }
        else {
            //$('#chat').append(received_msg + "<br/>");
        }
    };
    ws.onclose = function()
    {
        $('#chat').append("<h5 class='media-heading'>Disconnected, please refresh...</h5><br/>");
        //ws = new WebSocket("ws://tsumi.dyndns.org:56667/echo");
    };
}
else
{
    // The browser doesn't support WebSocket
    alert("WebSocket NOT supported by your Browser!");
}
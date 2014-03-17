function rollDice(HiOrLo, coinCode){
    $("#rollhi").prop("disabled", true);
    $("#rolllo").prop("disabled", true);
    var chance = $("#chance").val();
    var profit = $("#profit").val();
    var betsize = $("#betsize").val();
    var payout = $("#payout").val();

    $("#result").html('');

    var request = $.post( "./api/roll" + HiOrLo, { chance:chance, profit:profit, betsize:betsize, payout:payout, coinCode:coinCode  }, function(data) {
        var data = jQuery.parseJSON(data);
        $("#mybalance").html(data.profit);
        $("#result").html(data.result);
        $("#rollhi").prop("disabled", false);
        $("#rolllo").prop("disabled", false);
    })
}

function NumericOnly(elementId){
    $("#" + elementId).keydown(function (e) {
        if (e.which != 17 && e.which != 65 && e.which != 90 && e.which != 110 &&
            e.which != 190 && e.which != 8 && e.which != 0 &&
            (e.which < 48 || e.which > 57) && (e.which < 96 || e.which > 103)) {
            return false;
        }
    });
}

function getPayout(){
    payout = $("#payout").val();
    chance = $("#chance").val();
    
    return 99 / chance;
}
function getProfit(){
    payout = $("#payout").val();
    stake = $("#betsize").val();
    
    return ( payout - 1 ) * stake;
}
function newWallet(code) {
    var request = $.post( "./api/createwallet", { coin:code  }, function(data) {
        if(data.Reply == 'Success'){
            $("#" + data.CoinCode + "_address").val(data.address);
            $("#create_" + data.CoinCode + "_Wallet").remove();
        }else if(data.Reply == 'Failure'){
            $("#" + data.CoinCode + "_address").val(data.Err);
        }
    })
        .fail(function(data) {
            alert( "Error: " + data.Err );
        });
}
//used for index.php's username and usermanagement's username
function showRecord(isOrder) {
    var name = document.getElementById("username").value;
	var response;
	jQuery.ajax({
        type: "POST",
        url: 'lib/user.php',
        data: {functionname: 'checkBalance', arguments: name}, success:function(data) {
			if(data != ""){
				response = "Current balance: $" + data;
			}else{
				if(isOrder)
					response = "This is a new user, please register";
				else	
					response = "This is a new user";
			} 
			document.getElementById("dynamic").innerHTML = response;
        }
    });	
}

//used for index.php's total dynamic update
function updateTotal(){
	info = {};
	$("select option:selected").each(function() {
      //crete an array with the list of selected dish and their qty
	  if($(this).text() != "0"){
		  info[$(this).parent().attr("name")] = $(this).text();
	  }	
    });
	if(Object.keys(info).length == 0){
		$("#total").effect( "highlight", {color:"#C6DB82"}, 1000 );
		$("#total").html("0.00");
	}else{
		jQuery.ajax({
			type: "POST",
			url: 'lib/dish.php',
			data: {functionname: 'getTotal', arguments:info}, success:function(data) {
				$("#total").effect( "highlight", {color:"#C6DB82"}, 1000 );
				var tt = parseFloat(data).toFixed(2);
				$("#total").empty().append(tt);
				showChange();
			}
		});	 
	}
}	

function showChange(){
	//alert($("#paid").val());
	if(!$("#paid").val()){
		$("#checkPaidNaN").empty();
		$("#suggestChange").empty();
		return;
	}
	var paid = parseFloat($("#paid").val());
	if(isNaN(paid)){
		$("#checkPaidNaN").html("invalid input, not a number");
		$("#suggestChange").empty();
	}else{
		$("#checkPaidNaN").empty();
		var tot = parseFloat($("#total").html());
		var change = paid - tot;
		if(change > 0){
			$("#suggestChange").html("suggested change: $" + change); 
		}else if(change < 0){
			$("#suggestChange").html("No need to change, will deduct $" + change*(-1).toFixed(2) + " from <b>" + $("#username").val() + "</b>'s balance"); 
		}else{
			$("#suggestChange").html("No need to change");
		}
	}
}


function formvalidate()
{
	var p_no = document.getElementById("p_no");
	var same = document.getElementById("sameasabove");
	var first_name = document.getElementById("first_name");
	var middle_name = document.getElementById("middle_name");
	var last_name = document.getElementById("last_name");
	var city = document.getElementById("city");
	var state = document.getElementById("state");
	var address = document.getElementById("address");
	var email_formate = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
	var email = document.getElementById("email");
	//var phone = document.getElementById("phone");
	var phone_formate = /^\(\d{3}\) \d{3}-\d{4}$/;
	var card_no = document.getElementById("Card_N0");
	var expdate = document.getElementById("Expiration_Date");
	var card_code = document.getElementById("Card_Code");
	var payment_amount = document.getElementById("payment_amount");
	var billing_first_name = document.getElementById("billing_first_name");
	var billing_last_name = document.getElementById("billing_last_name");
	var billing_adress = document.getElementById("billing_adress");
	var billing_city = document.getElementById("billing_city");
	var billing_state = document.getElementById("billing_state");
	var billing_zip = document.getElementById("billing_zip_no");
	var billing_email = document.getElementById("billing_email");
	var billing_phone = document.getElementById("billing_phone");
	
	
	//errors 
	var p_no_error = document.getElementById("p_no_error");
	var first_name_error = document.getElementById("first_name_error");
	var last_name_error = document.getElementById("last_name_error");
	var address_error = document.getElementById("address_error");
	var city_error = document.getElementById("city_error");
	var state_error = document.getElementById("state_error");
	var email_error = document.getElementById("email_error");
	var phone_error = document.getElementById("phone_error");
	var Card_Number_error = document.getElementById("cardno_error");
	var Expiration_Date_error = document.getElementById("Expiration_Date_error");
	var Card_Code_error = document.getElementById("cardcode_error");
	var payment_amount_error = document.getElementById("payment_amount_error");
	var billing_first_name_error = document.getElementById("billing_first_name_error");
	var billing_last_name_error = document.getElementById("billing_last_name_error");
	var billing_adress_error = document.getElementById("billing_adress_error");
	var billing_city_error = document.getElementById("billing_city_error");
	var billing_state_error = document.getElementById("billing_state_error");
	var billing_zip_error = document.getElementById("billing_zip_error");
	var billing_email_error = document.getElementById("billing_email_error");
	var billing_phone_error = document.getElementById("billing_phone_error");
	
	//checks
	if ( p_no.value == "" )
	{
		 p_no.style.border = "1px solid red";
		 p_no_error.innerHTML = "<font color='red'>Please Enter your Account No / Patient No.</font>";
		 return false;
	}
	if ( first_name.value == "" )
	{
		 first_name.style.border = "1px solid red";
		 first_name_error.innerHTML = "<font color='red'>Please Enter Your First Name.</font>";
		 return false;
	}
	
	if ( last_name.value == "" )
	{
		 last_name.style.border = "1px solid red";
		 last_name_error.innerHTML = "<font color='red'>Please Enter Your Last Name.</font>";
		 return false;
	}
	
	
	if ( address.value == "" )
	{
		 address.style.border = "1px solid red";
		 address_error.innerHTML = "<font color='red'>Please Enter Your Address.</font>";
		 return false;
	}
	
	if ( city.value == "" )
	{
		 city.style.border = "1px solid red";
		 city_error.innerHTML = "<font color='red'>Please Enter City.</font>";
		 return false;
	}
	if ( state.value == "" )
	{
		 state.style.border = "1px solid red";
		 state_error.innerHTML = "<font color='red'>Please Enter State.</font>";
		 return false;
	}
	if ( email.value == "" )
	{
		 email.style.border = "1px solid red";
		 email_error.innerHTML = "<font color='red'>Please Enter your Email Address.</font>";
		 return false;
	}
	if(!email_formate.test(email.value))
	{
		 email.style.border = "1px solid red";
		 email_error.innerHTML = "<font color='red'>Please Enter Correct Email Formate.</font>";
		 return false;
	}
	
	/*if ( phone.value == "" )
	{
		 phone.style.border = "1px solid red";
		 phone_error.innerHTML = "<font color='red'>Please Enter your phone no.</font>";
		 return false;
	}*/
	//formats 
	if(!phone_formate.test(phone.value))
	{
		 phone.style.border = "1px solid red";
		 phone_error.innerHTML = "<font color='red'>format is incorrect.</font>";
		 return false;
	}
	
	if ( card_no.value == "" )
	{
		 card_no.style.border = "1px solid red";
		 Card_Number_error.innerHTML = "<font color='red'>Please Enter your Card no.</font>";
		 return false;
	}
	var card_code_regix = /[^A-Za-z\d\s]/;
	
	if ( card_code_regix.test(card_no.value) )
	{
		 card_no.style.border = "1px solid red";
		 Card_Number_error.innerHTML = "<font color='red'>Please Enter Correct Card no.</font>";
		 return false;
	}
	
	if ( Card_Code.value == "" )
	{
		 Card_Code.style.border = "1px solid red";
		 Card_Code_error.innerHTML = "<font color='red'>Please Enter your Card no.</font>";
		 return false;
	}
	
	if ( payment_amount.value == "" )
	{
		 payment_amount.style.border = "1px solid red";
		 payment_amount_error.innerHTML = "<font color='red'>Please Enter Amount.</font>";
		 return false;
	}
	
	if ( expdate.value == "" )
	{
		 expdate.style.border = "1px solid red";
		 Expiration_Date_error.innerHTML = "<font color='red'>Please Enter Expire Date.</font>";
		 return false;
	}
	
	if ( billing_first_name.value == "" )
	{
		 billing_first_name.style.border = "1px solid red";
		 billing_first_name_error.innerHTML = "<font color='red'>Please Enter your first name.</font>";
		 return false;
	}
	
	
	if ( billing_last_name.value == "" )
	{
		 billing_first_name.style.border = "1px solid red";
		 billing_last_name_error.innerHTML = "<font color='red'>Please Enter your Last Name.</font>";
		 return false;
	}
	
	if ( billing_adress.value == "" )
	{
		 billing_adress.style.border = "1px solid red";
		 billing_address_error.innerHTML = "<font color='red'>Please Enter your Address.</font>";
		 return false;
	}
	
	if ( billing_city.value == "" )
	{
		 billing_city.style.border = "1px solid red";
		 billing_city_error.innerHTML = "<font color='red'>Please Enter your City.</font>";
		 return false;
	}
	
	
	if ( billing_state.value == "" )
	{
		 billing_state.style.border = "1px solid red";
		 billing_state_error.innerHTML = "<font color='red'>Please Enter your State.</font>";
		 return false;
	}
	
	if ( billing_zip_no.value == "" )
	{
		 billing_zip_no.style.border = "1px solid red";
		 billing_zip_no_error.innerHTML = "<font color='red'>Please Enter your zip NO.</font>";
		 return false;
	}
	
	if ( billing_email.value == "" )
	{
		 billing_email.style.border = "1px solid red";
		 billing_email_error.innerHTML = "<font color='red'>Please Enter your Email Address.</font>";
		 return false;
	}
	
	if ( billing_phone.value == "" )
	{
		 billing_phone.style.border = "1px solid red";
		 billing_phone_error.innerHTML = "<font color='red'>Please Enter your Phone No.</font>";
		 return false;
	}
	
	
	
	
}
function showdiv() 
{
	if ( document.getElementById("sameasabove").checked )
	{
		 document.getElementById("billing_first_name").value = first_name.value;
		 document.getElementById("billing_middle_name").value = middle_name.value;
		 document.getElementById("billing_last_name").value = last_name.value;
		 document.getElementById("billing_city").value = city.value;
		 document.getElementById("billing_adress").value = address.value;
		 document.getElementById("billing_state").value = state.value;
		 document.getElementById("billing_email").value = email.value;
		 document.getElementById("billing_phone").value = phone.value;
		 return false;
	}
	else { 
	
		 document.getElementById("billing_first_name").value = "";
		 document.getElementById("billing_middle_name").value = "";
		 document.getElementById("billing_last_name").value = "";
		 document.getElementById("billing_city").value = "";
		 document.getElementById("billing_adress").value = "";
		 document.getElementById("billing_state").value = "";
		 document.getElementById("billing_email").value = "";
		 document.getElementById("billing_phone").value = "";
		 return false;
	 }
		
}
function showpopup () {
alert("Card Verification # is a three-digit number found in the signature area on the back of your VISA or MasterCard credit card. This three-digit number is displayed after your credit card number.");
}


function movecursor(field,nextField)
{
	if(field.value.length >= field.maxLength)
	{
	document.getElementById(nextField).focus();
	
	}

}
@extends('layouts.app')

@section('content')
<div class="container">
	<div class="row">
		<div class="col-xs-8 col-xs-offset-2">
			<div id="back-button"></div>
        </div>	
	</div>
	@if($guestEnabled)
		<div class="row"> <div><br></div> </div>
		<div class="row">
			<div class="col-xs-8 col-xs-offset-2">
				<div id="login-button" ></div>
			</div>	
		</div>
		<div class="row"> <div><br></div> </div>
		<div class="row">
			<div class="col-xs-8 col-xs-offset-2">
				<div id="register-button"></div>	
			</div>	
		</div>
	@endif
</div>
<script>	   	
	$(document).ready(function() {
		common.sessionPut('xdata', 'history.previous()')
	});				
	
	var buttonHeight = "40px";
	
	backButton=$("#back-button").dxButton({
		text: "Powrót",		
		type: "back",
		width: "30%",
		height: buttonHeight,
		hint: "powrót",	
		onClick: function(e) { 
			window.location = document.referrer;
//			DevExpress.ui.notify("The OK button was clicked");
		}
	}).dxButton('instance');
	
	loginButton=$("#login-button").dxButton({
		text: "Zaloguj się",		
		type: "default",
		width: "100%",
		height: buttonHeight,
		onClick: function(e) { 
			window.location = "/user/login";
//			DevExpress.ui.notify("The OK button was clicked");
		}
	}).dxButton('instance');
	
	registerButton=$("#register-button").dxButton({
		text: "Załóż konto",
		type: "default",
		width: "100%",
		height: buttonHeight,
		onClick: function(e) { 
			window.location = "/user/register";
//			DevExpress.ui.notify("The OK button was clicked");
		}
	}).dxButton('instance');	

	
</script>
@endsection



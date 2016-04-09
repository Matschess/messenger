$(document).ready(function(){

	// Place vertical middle
	$totalWidth = $(document).width();
	$totalHeight = $(document).height();
	$loginboxHeight = $('#loginbox').height();

	$marginTop = ($totalHeight - $loginboxHeight) / 2;
	$marginTopPercent = 100 * $marginTop / $totalWidth;

	$('#loginbox').css('margin-top', $marginTopPercent + '%').ready(function() {
		$('#loginbox').fadeIn(200);
		setTimeout(function () {
			$('#loginbox').removeClass('loginboxPre');
		}, 50);
	});

	 $('body').keydown(function() {
		getKeyCode();
	 });

	 var enter = 'startReset';
	 
	function getKeyCode(event) {
	   event = event || window.event;
	   if (event.keyCode == "13") {
		if(enter == 'startReset') {
			startReset();
		}
		else if(enter == 'authorize') {
			authorize();
		}
		else if(enter == 'changePassword') {
			changePassword();
		}
	   }
	}
	
	$('#okNow').click(function() {
		startReset();
	});
	
	$('#authorizeNow').click(function() {
		authorize();
	});
	
	$('#changePasswordNow').click(function() {
		changePassword();
	});
	
	$('#goBack').click(function() {
		window.location = 'login.php';
	});
	
	function startReset() {
		$email = $('#email').val();
		$.post("php/forgot_check.php",
		{
		  email: $email,
		},
		function(data){
			if(data == 'started') {
				$('body').load('authorize.php?email=' + $email);
				enter = 'authorize';
			}
			else if(data == 'locked') {
				$('#overlay').fadeIn(200);
				$('#popup').fadeIn(200);
			}
			else {
				wiggle('#okNow');
			}
		});
	};
	
	$('.closePopup').click(function() {
		$('#overlay').fadeOut(200);
		$('#popup').fadeOut(200);
		$('#email').val('');
	});
	
	function authorize() {
		$email = $('#emailConfirmed').val();
		$pin = $('#pin').val();
		$.post("php/authorize_check.php",
		{
		  email: $email,
		  pin: $pin
		},
		function(data){
			if(data == 'authorized') {
				// Reset Password
				$('#overlay').fadeIn(200);
				$('#popup').fadeIn(200);
				enter = 'changePassword';
			}
			else if(data == 'lockedPin') {
				// Reset Password
				$('#popupHeader').html('Pin abgelaufen');
				$('#popupText').html('Der Pin wurde zu oft falsch eingegeben.<br/>Wir haben ihn nun aus Sicherheitsgründen gesperrt.');
				$('#overlay').fadeIn(200);
				$('#popup').fadeIn(200);
				enter = 'restartForgot';
			}
			else if(data == 'locked') {
				$('#popupHeader').html('Konto gesperrt');
				$('#popupText').html('Entschuldigung, dein Konto ist gesperrt.<br/>Wenn dein Konto gesperrt ist, kannst du dein Passwort nicht zurücksetzten.');
				$('#overlay').fadeIn(200);
				$('#popup').fadeIn(200);
			}
			else if(data == 'lockedError') {
				// Reset Password
				$('#popupHeader').html('Fehler');
				$('#popupText').html('Ein Fehler mit der PIN trat auf. Bitte fordere die Pin erneut an.');
				$('#overlay').fadeIn(200);
				$('#popup').fadeIn(200);
				enter = 'restartForgot';
			}
			else {
				wiggle('#authorizeNow');
			}
		});
	};
	
	$('#passwordCheck').focus(function() {
		$('#passwordTrue').hide();
		$('#passwordFalse').hide();
	});
	
	$('#passwordCheck').focusout(function() {
		$password = $('#password').val();
		$passwordCheck = $('#passwordCheck').val();
		if($password == $passwordCheck && $password != '') {
			$('#passwordTrue').delay(200).fadeIn(200);
		}
		else {
			$('#passwordFalse').delay(200).fadeIn(200);
		}
	});
	
	function changePassword() {
		$email = $('#emailConfirmed').val();
		$password = $('#password').val();
		$passwordCheck = $('#passwordCheck').val();
		if($password != '') {
			if($password == $passwordCheck) {
				$.post("php/password_change.php",
				{
				  email: $email,
				  password: $password
				},
				function(data){
					if(data == 'changed') {
						// Reset Password
						$('#popupHeader').html('Erfolgreich');
						$('#popupText').html('Das Passwort wurde erfolgreich geändert. Vielleicht willst du es dir notieren, um es nicht nocheinmal zu vergessen.');
					}
					else {
						wiggle('#authorizeNow');
					}
				});
		}
		}
	};
	
	function wiggle($button) {
		setTimeout(function() {
			$($button).css("margin-left", "-5px");
			setTimeout(function() {
				$($button).css("margin-left", "5px");
					setTimeout(function() {
						$($button).css("margin-left", "-5px");
						setTimeout(function() {
							$($button).css("margin-left", "5px");
								setTimeout(function() {
									$($button).css("margin-left", "0px");
								}, 50);
							}, 50);
					}, 50);
				}, 50);
		}, 50);
	};
});
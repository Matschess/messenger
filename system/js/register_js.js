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

	function getKeyCode(event) {
	   event = event || window.event;
	   if (event.keyCode == "13") {
		registerNow();
	   }
	}

	$('#registerNow').click(function() {
		registerNow();
	});

	function registerNow() {
		$('#registerNow').removeClass('wiggle');
		$email = $('#email').val();
		$username = $('#username').val();
		$firstname = $('#firstname').val();
		$lastname = $('#lastname').val();
		$password = $('#password').val();
		$passwordCheck = $('#passwordCheck').val();
		if($password == $passwordCheck) {
			$.post("php/register_check.php",
			{
			  email: $email,
			  username: $username,
				firstname: $firstname,
				lastname: $lastname,
			  password: $password
			},
			function(data){
				if(data == 'registered') {
					$('#overlay').fadeIn(200);
					$('#popup').fadeIn(200);
					setTimeout(function(){
							window.location = 'login.php';
					}, 10000);
				}
				else {
					$('#registerNow').addClass('wiggle');
				}
			});
		}
	};

	$('#toLogin').click(function() {
		window.location = 'login.php';
	});

	$('#email').focus(function() {
		$('#emailTrue, #emailFalse').hide();
	});

	$('#email').focusout(function() {
		$email = $('#email').val();
		if($email.trim()) {
			$.post("php/user_exists.php",
			{
			  job: 'emailCheck',
			  email: $email,
			},
			function(data){
				$status = '';
				if(data == 'available') {
					$status = '#emailTrue';
				}
				else {
					$status = '#emailFalse';
				}
				setTimeout(function() {
					$($status).show();
				}, 200);
			});
		}
	});

	$('#username').focus(function() {
		$('#usernameTrue, #usernameFalse').hide();
	});

	$('#username').focusout(function() {
		$username = $('#username').val().trim();
		if($username) {
			$.post("php/user_exists.php",
				{
					job: 'usernameCheck',
					username: $username,
				},
				function (data) {
					$status = '';
					if (data == 'available') {
						$status = '#usernameTrue';
					}
					else {
						$status = '#usernameFalse';
					}
					setTimeout(function () {
						$($status).show();
					}, 200);
				});
		}
	});

	$('#firstname, #lastname').focus(function() {
		$('#nameTrue, #nameFalse').hide();
	});

	$('#firstname, #lastname').focusout(function() {
		$firstname = $('#firstname').val().trim();
		$lastname = $('#lastname').val().trim();
		if($firstname && $lastname) {
			setTimeout(function () {
				$('#nameTrue').show();
					}, 200);

		}
	});

	var $points = 0;
	$('#password').keyup(function() {
		$points = 0;
		$password = $('#password').val();
		$passwordCheck = $('#passwordCheck').val();
		$length = $password.length;
		$minChars = 6; // minimum Characters of password
		if(!$password.trim() || $length < $minChars) {
			$points = 0;
		}
		else {
			if(($length - $minChars) > 5) {
				$points = 5;
			}
			else {
				$points = $length - $minChars;
			}
			$upperChars = $password.match(/[A-Z Ä Ö Ü]/g);
			if($upperChars) {
				if($upperChars.length > 3) {
					$points = $points + 3;
				}
				else {
					$points = $points + $upperChars.length / 2;
				}
			}
			$numricChars = $password.match(/[0-9]/g);
			if($numricChars) {
				$points = $points + 1;
			}
			$specialChars = $password.match(/[!@#$%\^&*(){}[\]<>?/|\-]/g);
			if($specialChars) {
				$points = $points + 1;
			}
		}
		$points = $points * 10;
		if($points <= 30) {
			$color = '#FE2E2E';
		}
		else if ($points > 10 && $points <= 30) {
			$color = '#FE9A2E';
		}
		else if ($points > 30 && $points <= 70) {
			$color = '#F7FE2E';
		}
		else if ($points > 70 && $points <= 90) {
			$color = '#BFFF00';
		}
		else {
			$color = '#2EFE2E';
		}
		$('#passwordSafetyStatus').css({'background-color': $color, 'width': + $points + '%'});
	});

	$('#password').focus(function() {
		$('#passwordTrue, #passwordFalse').hide();
	});

	$('#password').focusout(function() {
		$password = $('#password').val();
		$passwordCheck = $('#passwordCheck').val();
		$status = '';
		if($password) {
			if ($points >= 50) {
				$status = '#passwordTrue';
			}
			else {
				$status = '#passwordFalse';
			}
			setTimeout(function () {
				$($status).show();
			}, 200);

		}
	});

	$('#passwordCheck').focus(function() {
		$('#passwordCheckTrue, #passwordCheckFalse').hide();
	});
	
	$('#passwordCheck').focusout(function() {
		$password = $('#password').val();
		$passwordCheck = $('#passwordCheck').val();
		$status = '';
		if($password && $passwordCheck && $points > 50) {
			if ($password == $passwordCheck) {
				$status = '#passwordCheckTrue';
			}
			else {
				$status = '#passwordCheckFalse';
			}
			setTimeout(function () {
				$($status).show();
			}, 200);
		}
	});
	
	function wiggle() {
		setTimeout(function() {
			$('#registerNow').css("margin-left", "-5px");
			setTimeout(function() {
				$('#registerNow').css("margin-left", "5px");
					setTimeout(function() {
						$('#registerNow').css("margin-left", "-5px");
						setTimeout(function() {
							$('#registerNow').css("margin-left", "5px");
								setTimeout(function() {
									$('#registerNow').css("margin-left", "0px");
								}, 50);
							}, 50);
					}, 50);
				}, 50);
		}, 50);
	};
});
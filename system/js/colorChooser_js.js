$(document).ready(function(){
	$('.colorButton').click(function() {
		$user_id = $('#currentUser').val();
		$color = this.id;
		$.post("php/color_change.php",
			{
			  user_id: $user_id,
			  color: '#' + $color
			},
			function(data){
				if(data == 'changed') {
					$('#overlay, #popup').fadeOut(200, function() {
						reloadProfile();
					});
				}
			});
	});

	function reloadProfile() {
		$user_id = $('#currentUser').val();
		$.post("subpages/profile.php",
			{
				user_id: $user_id
			},
			function(data){
				$('.content').html(data);
			});
	};
});
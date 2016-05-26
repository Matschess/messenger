$(document).ready(function(){
	$('.tooltip').tooltipster({
		contentAsHTML: true,
		animation: 'grow',
		delay: 250,
		theme: 'tooltipster-custom',
		trigger: 'hover'
	});
	
	$('.getFriends').click(function() {
		$user_id = $('#currentUser').val();
		$friend_id = this.id;
		$.post("php/friend_change.php",
			{
				job: 'add',
				user_id: $user_id,
				friend_id: $friend_id
			},
			function(data){
				if(data == 'updated' || data == 'added') {
					$('.content').load('subpages/friendsProfile.php?user_id=' + $user_id + '&friend_id=' + $friend_id);
					toContacts();
				}
			});
	});

	$('.acceptFriends').click(function() {
		$id = this.id;
		$.post("php/enquiry_change.php",
			{
				job: 'accept',
				id: $id
			},
			function(data){
				if(data == 'changed') {
					$('.content').load('subpages/friendsProfile.php?friend_id=' + $friend_id);
					toContacts();
				}
			});
	});
	
	$('.endFriends').click(function() {
		$friend_id = this.id;
		$('#popupHeader').html("Freundschaft beenden?");
		$('#popupContent').load('subpages/endFriendsReally.php?friend_id=' + $friend_id, function () {
			$('#overlay').fadeIn(200);
			$('#popup').fadeIn(200);
		});
	});
	
	$('#popupContent').on( "click", ".endFriendsNow", function() {
		$user_id = $('#currentUser').val();
		$friend_id = this.id;
		$.post("php/friend_change.php",
			{
			  job: 'remove',
			  user_id: $user_id,
			  friend_id: $friend_id
			},
			function(data){
				if(data == 'deleted') {
					$('.content').load('subpages/friendsProfile.php?user_id=' + $user_id + '&friend_id=' + $friend_id);
					$('#overlay').fadeOut(200);
					$('#popup').fadeOut(200);
					toContacts();
				}
			});
	});
	
	function toContacts() {
		$user_id = $('#currentUser').val();
		$('#contacts').load('subpages/contacts.php?user_id=' + $user_id);
		$('#toContacts').css({'opacity': '0.9'});
	};
});
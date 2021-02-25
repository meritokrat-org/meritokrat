var j = jQuery.noConflict();

j(document).ready(function() {
	status('Choose a file :)');

	// Check to see when a user has selected a file
	var timerId;
	timerId = setInterval(function() {
		if(j('#userPhotoInput').val() !== '') {
			clearInterval(timerId);

			j('#uploadForm').submit();
		}
	}, 500);

	j('#uploadForm').submit(function() {
		status('uploading the file ...');

		j(this).ajaxSubmit({

			error: function(xhr) {
				status('Error: ' + xhr.status);
			},

			success: function(response) {
				console.log(response);
			}
		});

		// Have to stop the form from submitting and causing
		// a page refresh - don't forget this
		return false;
	});

	function status(message) {
		j('#status').text(message);
	}
});
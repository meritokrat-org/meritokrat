var invitesController = new function () {
	this.event = function (obj, id, status) {
		var leads = '';
		if ($(obj).hasClass('promt')) {
			leads = prompt("Зi мною пiдуть (вкажiть кiлькiсть цифрою)", "0");
			if (!leads) return false;
		}

		$.post('/invites/edit', {'id': id, 'status': status, 'leads': leads}, function () {
			$('div#invite_item_' + id).fadeOut(150);
		});

		return false;
	};

	this.group = function (id, status) {
		$.post('/invites/edit', {'id': id, 'status': status}, function () {
			$('div#invite_item_' + id).fadeOut(150);
		});

		return false;
	};
};
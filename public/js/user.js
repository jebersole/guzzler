$(function() {
	$('#get-token').click(function() {
		$('#login-errors, #token').hide();
		let url = $(this).data('url');
		let data = {
			pass: $('#pass').val(),
			login: $('#login').val()
		};
		$.ajax({
			type: 'GET',
			url: url,
			data: data,
			success: function(res) {
				let token = '';
				if (res) {
					token = parseJson(token, res);
				}
				$('#token').text('Token: ' + token).show();
			},
			error: function(res) {
				let errors = '';
				if (res) {
					errors = parseJson(errors, res);
				}
				$('#login-errors').text(errors).show();
			}
		});
	});

	$('#get-user-data').click(function() {
		$('#get-user-data-errors, #get-user-data-result').hide();
		let url = $(this).data('url');
		let data = {
			token: $('#get-user-token').val(),
			username: $('#get-user-username').val()
		};
		$.ajax({
			type: 'GET',
			url: url,
			data: data,
			success: function(res) {
				let data = '';
				if (res) {
					data = JSON.stringify(parseJson(data, res), null, 2);
				}
				$('#get-user-data-result').text(data).show();
			},
			error: function(res) {
				let errors = '';
				if (res) {
					errors = parseJson(errors, res);
				}
				$('#get-user-data-errors').text(errors).show();
			}
		});
	});

	$('#change-permissions').click(function() {
		$('#permissions, #permissions-hint').show();
	});

	$('#send-user-data').click(function() {
		$('#send-user-data-errors, #send-user-data-result').hide();
		let url = $(this).data('url');
		let data = {
			token: $('#send-user-token').val(),
			name: $('#send-user-name').val(),
			userid: $('#send-user-id').val(),
			permissions: $('#permissions').val(),
			blocked: $('#send-user-blocked').is(':checked') ? 1 : 0,
			active: $('#send-user-active').is(':checked') ? 1 : 0
		};
		$.ajax({
			type: 'POST',
			url: url,
			data: data,
			success: function(res) {
				let data = '';
				if (res) {
					data = JSON.stringify(parseJson(data, res), null, 2);
				}
				$('#send-user-data-result').text(data).show();
			},
			error: function(res) {
				let errors = '';
				if (res) {
					errors = parseJson(errors, res);
				}
				$('#send-user-data-errors').text(errors).show();
			}
		});
	});

});

function parseJson(text, res) {
	try {
		let json = res.responseText ? JSON.parse(res.responseText) : JSON.parse(res);
		if (json.errors) {
			text = json.errors;
		} else if (json.token) {
			text = json.token;
		} else if (typeof json === 'object') {
			text = json;
		}
	} catch (error) {
		text = "Sorry, please try again later."
	}
	return text;
}
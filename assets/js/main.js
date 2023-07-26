function onSubmit()
{
	var email = $("#input_email").val();
	if(email.length === 0)
	{
		Swal.fire({
			icon: 'error',
			title: 'Oops...',
			text: 'You have to complete the email address field.'
		});
		return;
	}
	var name = $("#input_username").val();
	if(name.length === 0)
	{
		Swal.fire({
			icon: 'error',
			title: 'Oops...',
			text: 'You have to complete the name field.'
		});
		return;
	}
	var image = $('#input_image')[0].files[0];
	var consent = $("#input_consent").prop('checked');
	// nu este specificat daca trebuie sa verific in frontend daca imaginea exista/si-a dat acordul

	var formData = new FormData();
	formData.append('name', name);
	formData.append('email', email);
	formData.append('consent', consent);
	formData.append('image', image);
	formData.append('csrf',$("#csrf_token").val());

	$.ajax({
		url: 'api.php',
		type: 'POST',
   		data:  formData ,
   		contentType: false,
		cache: false,
		processData:false,
		success: function(response, status, xhr)
		{
			if(xhr.status == 201)
			{
				Swal.fire({
					icon: 'success',
					title: 'We registered successfully.',
					showConfirmButton: true
				});
				$("#csrf_token").val(response.csrf);
				$('#input_image').val('');
				$('#input_username').val('');
				$('#input_email').val('');
				$("#input_consent").prop('checked', false);
			}
		},
		error: function(xhr, status, error)
		{
			var text = "";
			if(xhr.status == 400)
			{
				switch(xhr.responseJSON.message)
				{
					case "invalidEmail":
					{
						text = "Please enter a valid email address.";
						break;
					}
					case "invalidName":
					{
						text = "Please enter a valid name.";
						break;
					}
					case "consentNotGiven":
					{
						text = "To upload an image, please accept the consent by checking the box.";
						break;
					}
					case "invalidImage":
					{
						text = "Invalid image file. Please upload a valid image (JPG, JPEG, PNG, BMP).";
						break;
					}
					case "emailExists":
					{
						text = "This email address is already registered. Please use a different email.";
						break;
					}
					case "invalidCSRF":
					{
						text = "We encountered an error. Please try to reload the page.";
						break;
					}

				}
			}
			else if(xhr.status == 500)
				text = "We encountered an error. Please try again later.";
			else
				return;
			Swal.fire({
					icon: 'error',
					title: 'Oops...',
					text: text
			});
		}
	});
}
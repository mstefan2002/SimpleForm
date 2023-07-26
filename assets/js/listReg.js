$( document ).ready(function() {
	$("#sortBy").val(sortBy);
	fPage(page)
});
function updateURLParameter(url, param, paramVal){
    var newAdditionalURL = "";
    var tempArray = url.split("?");
    var baseURL = tempArray[0];
    var additionalURL = tempArray[1];
    var temp = "";
    if (additionalURL) {
        tempArray = additionalURL.split("&");
        for (var i=0; i<tempArray.length; i++){
            if(tempArray[i].split('=')[0] != param){
                newAdditionalURL += temp + tempArray[i];
                temp = "&";
            }
        }
    }

    var rows_txt = temp + "" + param + "=" + paramVal;
    return baseURL + "?" + newAdditionalURL + rows_txt;
}
function fSort()
{
	sortBy = $("#sortBy").val();
	window.history.replaceState('', '', updateURLParameter(window.location.href, "sortBy", sortBy));
	fPage(page)
}
function download()
{
	$.ajax({
		url: 'api.php',
		type: 'GET',
   		data:  "sortBy="+sortBy ,
   		contentType: false,
		cache: false,
		processData:false,
		success: function(response, status, xhr)
		{
			const items = response;
			const replacer = (key, value) => value === null ? '' : value;
			const header = Object.keys(items[0]);
			const csv = [
					header.join(','),
					...items.map(row => header.map(fieldName => JSON.stringify(row[fieldName], replacer)).join(','))
			].join('\r\n');
			const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
			const url = URL.createObjectURL(blob);
			const link = document.createElement('a');
			link.setAttribute('href', url);
			link.setAttribute('download', 'data.csv');
			document.body.appendChild(link);
			link.click();
			URL.revokeObjectURL(url);
			document.body.removeChild(link);
		},
		error: function(xhr, status, error)
		{
			Swal.fire({
					icon: 'error',
					title: 'Oops...',
					text: "We encountered an error. Please try again later."
			});
		}
	});
}
function fPage(pg)
{
	page=pg;
	window.history.replaceState('', '', updateURLParameter(window.location.href, "page", page+1));

   	$.ajax({
		url: 'api.php',
		type: 'GET',
   		data:  "begin_pos="+(pg*10)+"&end_pos="+((1+pg)*10)+"&sortBy="+sortBy ,
   		contentType: false,
		cache: false,
		processData:false,
		success: function(response, status, xhr)
		{
			$('#tabel-body').html("");
			var countRows = 0;
			var maxRows = 0;
			response.forEach(obj => {
				if (obj.hasOwnProperty('maxRows'))
				{
					maxRows = obj.maxRows;
					return;
				}
				var newRow = $('<tr>');
				var th = $('<th scope="row">').text(obj.id);
				var td1 = $('<td>').text(obj.name);
				var td2 = $('<td>').text(obj.email);
				var td3 = $('<td>');
				if(obj.imagePath.length > 0)
				{
					var ahref = $('<a>').attr('href',obj.imagePath);
					var img = $('<img>').attr('src',obj.imagePath);
					ahref.append(img);
					td3.append(ahref);
				}
				else
					td3.text("No image");
				newRow.append(th);
				newRow.append(td1);
				newRow.append(td2);
				newRow.append(td3);

				$('#tabel-body').append(newRow);
				countRows++;
			});
			if(maxRows > 10)
			{
				$("#pagNav").css('display','');
				$("#pagination").html("");
				if(page-1 >= 0)
				{
					var liPrev = $('<li class="page-item"><button class="page-link" onclick="fPage('+(page-1)+')">Previous</button></li>');
					$("#pagination").append(liPrev);
				}
				var li
				for(var i=page-4;i!=page;++i)
				{
					if(i<0)
						continue;
					li = $('<li class="page-item"><button class="page-link" onclick="fPage('+(i)+')">'+(i+1)+'</button></li>');
					$("#pagination").append(li);
				}
				li = $('<li class="page-item active"><button class="page-link">'+(page+1)+'</button></li>');
				$("#pagination").append(li);

				for(var i=page+1;i<=Math.ceil(maxRows/10)-1 && 3>i-page-1;++i)
				{
					li = $('<li class="page-item"><button class="page-link" onclick="fPage('+(i)+')">'+(i+1)+'</button></li>');
					$("#pagination").append(li);
				}
				if(page != Math.ceil(maxRows/10)-1)
				{
					var liNext = $('<li class="page-item"><button class="page-link" href="" onclick="fPage('+(page+1)+')">Next</button></li>');
					$("#pagination").append(liNext);
				}
			}
			else
				$("#pagNav").css('display','none');
		},
		error: function(xhr, status, error)
		{
			var text = "";
			if(xhr.status == 400)
			{
				switch(xhr.responseJSON.message)
				{
					case "beginGreater":
					{
						text = "The value of begin_pos must be less than end_pos.";
						break;
					}
					case "endPosNull":
					{
						text = "The value of end_pos must be greater than 0.";
						break;
					}
					case "beginPosNull":
					{
						text = "The value of begin_pos must be greater than or equal to 0.";
						break;
					}
				}
			}
			else
				text = "We encountered an error. Please try again later.";
			Swal.fire({
					icon: 'error',
					title: 'Oops...',
					text: text
			});
		}
	});
}
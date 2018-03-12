
window.addEventListener("load", loadTable(), true);
document.getElementById('posting').addEventListener('submit', postName);
document.getElementById('posting').addEventListener('submit', loadTable);


function loadTable()
{	
    var xhr = new XMLHttpRequest();
	xhr.open('GET', 'post_list.php', true);

	xhr.onload = function()
	{
		if(this.status == 200){

			var postings = JSON.parse(this.responseText);
			var output = '';
			output += '<table class="table table-bordered table-striped table-hover" id="table-committee">';
			output += '<tr><th "class="col-md-1">Date</th><th "class="col-md-2">Income</th><th "class="col-md-2">Expenditure<th "class="col-md-1">Category</th><th "class="col-md-6">Note</th></tr>';
				for(var i in postings){
					var mydate = new Date(postings[i].Trans_Date);
					var dd = mydate.getDate();
						if(dd<10){
							dd='0'+dd;
						}
					var dd = dd+'/';
					var mm = mydate.getMonth()+1;
						if(mm<10){
							mm='0'+mm;
						}
					var mm = mm+'/';
					var yy = mydate.getFullYear();
					output +=
					'<tr><td>'+ dd+mm+yy +'</td><td>'+ postings[i].Income + '</td><td>'
					+ postings[i].Expenditure + '</td><td>' + postings[i].Category + '</td><td>' + postings[i].Note +'</td></tr>';	
				}
			output +='</table>'					
			document.getElementById('postings').innerHTML = output;
		}

	}
	xhr.send();
}

function postName(e)
{
	e.preventDefault();

	var pdate = document.getElementById('postdate').value;
	var inc = document.getElementById('income').value;
	var exp = document.getElementById('expenditure').value;
	var cat = document.getElementById('category').value;
	var note = document.getElementById('note').value;

	if (inc != 0 && exp != 0){
 		alert("Income or expenditure must be zero");
 		return false;
 	}

 	if (cat == 0){
 		alert("Please enter a category");
 		return false;
 	}

 	if (cat == 90 && note ==""){
 		alert("Please enter a note when using category 'Other'");	
 		return false;
 	}

	var params = ''+ 'pdate=' + window.encodeURIComponent(pdate) + '&inc=' + window.encodeURIComponent(inc)
	+ '&exp=' + window.encodeURIComponent(exp) + '&cat=' + window.encodeURIComponent(cat) + '&note=' + window.encodeURIComponent(note)   + '&newrev=' + window.encodeURIComponent('new');
	var xhr = new XMLHttpRequest();
	xhr.open("POST", "post_update.php", true);
	xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
	xhr.onload = function()
	{
//		alert(this.responseText);
	}
	xhr.send(params);
	document.getElementById('postdate').value='';
	document.getElementById('income').value='0.00';
	document.getElementById('expenditure').value='0.00';
	document.getElementById('category').value=0;
	document.getElementById('note').value='';
}




	




 

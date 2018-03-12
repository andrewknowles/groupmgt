document.getElementById('reversing').addEventListener('submit', postReverse);


function postReverse(e){

	e.preventDefault();

	var pdate = document.getElementById('postdate2').value;
	var inc = document.getElementById('income2').value;
	var exp = document.getElementById('expenditure2').value;
	var cat = document.getElementById('category2').value;
	var note = document.getElementById('note2').value;
	var transid = document.getElementById('transid1').value;

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
	+ '&exp=' + window.encodeURIComponent(exp) + '&cat=' + window.encodeURIComponent(cat) + '&note=' + window.encodeURIComponent(note) + '&oldtrans=' + window.encodeURIComponent(transid)  + '&newrev=' + window.encodeURIComponent('reverse');
	var xhr = new XMLHttpRequest();
	xhr.open("POST", "post_update.php", true);
	xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
	xhr.onload = function()
	{
		alert(this.responseText);
	}
	xhr.send(params);

}	
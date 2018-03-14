//document.getElementById('rectable').addEventListener('click', rec);
var tinc = 0;
var texp = 0;

function rec(e){
//var andrew = 1;
alert('ok');
	
var table = document.getElementById('rectable');
	for(var i=1; i<table.rows.length; i++)
		{
var name = "mycheck"+i;
								
//				var trans = Number(table.cells[0].innerHTML);
				var inc = Number(this.cells[2].innerHTML);
				var exp = Number(this.cells[3].innerHTML);
				var ak = this.cells[7];
				var ak1 = document.getElementById(name);
				if(document.getElementById(name).checked){
				tinc += inc;
				texp += exp;
				} else {
					tinc -= inc;
				texp -= exp;
				}	
	}
//	andrew++;
}
	

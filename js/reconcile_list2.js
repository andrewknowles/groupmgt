// call onload or in script segment below form
function attachCheckboxHandlers() {
    // get reference to element containing toppings checkboxes
    var el = document.getElementById('translist');

    // get reference to input elements in toppings container element
    var tops = el.getElementsByTagName('input');
    
    // assign updateTotal function to onclick property of each checkbox
    for (var i=0, len=tops.length; i<len; i++) {
        if ( tops[i].type === 'checkbox' ) {
            tops[i].onclick = updateTotal;
        }
    }
}
    
// called onclick of toppings checkboxes
function updateTotal(e) {
    // 'this' is reference to checkbox clicked on
    var form = this.form;

    
    // get current value in total text box, using parseFloat since it is a string
    var val = parseFloat( form.elements['total'].value );
    var val1 = parseFloat( form.elements['total1'].value );
    // if check box is checked, add its value to val, otherwise subtract it
    if ( this.checked ) {
    	if (this.value > 0){
        val += parseFloat(this.value);
    } else {
    	val1 += Math.abs(parseFloat(this.value));
    }
    } else {
    	if (this.value > 0){
        val -= parseFloat(this.value);
    } else {
    	 val1 -= Math.abs(parseFloat(this.value));
    }
    }
    
    // format val with correct number of decimal places
    // and use it to update value of total text box
    var ob = Number(form.elements['obal'].value); 
    form.elements['total'].value = val;
    var xinc = val; 
    form.elements['total1'].value = val1;
    var xexp =  val1;
    var cb = ob + xinc - xexp;
    form.elements['cbal'].value = formatDecimal(cb);
}
    
// format val to n number of decimal places
// modified version of Danny Goodman's (JS Bible)
function formatDecimal(val, n) {
    n = n || 2;
    var str = "" + Math.round ( parseFloat(val) * Math.pow(10, n) );
    while (str.length <= n) {
        str = "0" + str;
    }
    var pt = str.length - n;
    return str.slice(0,pt) + "." + str.slice(pt);
}

// in script segment below form
attachCheckboxHandlers();
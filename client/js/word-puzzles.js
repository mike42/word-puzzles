/* Some basic option toggling code */
var on = Array();
function toggle(id) {
	if ( typeof on[id] !== "undefined") {
		if(on[id]) {
			show = false;
		} else {
			show = true;
		}
	} else {
		show = true;
	}

	if(show) {
		on[id] = true;
		document.getElementById(id).style.display='block';
		document.getElementById(id + "-show").style.display='none';
		document.getElementById(id + "-hide").style.display='block';
		document.getElementById(id + "-sub").style.display='none';
	} else {
		on[id] = false;
		document.getElementById(id).style.display='none';
		document.getElementById(id + "-show").style.display='block';
		document.getElementById(id + "-hide").style.display='none';
		document.getElementById(id + "-sub").style.display='block';
	}
}

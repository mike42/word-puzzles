/* Some basic option toggling code */
function toggle(id) {
	show = ! $('#' + id).is(":visible");
	if (show) {
		$('#' + id).show();
		$('#' + id + '-show').hide();
		$('#' + id + '-hide').show();
		$('#' + id + '-sub').hide();
	} else {
		$('#' + id).hide();
		$('#' + id + '-show').show();
		$('#' + id + '-hide').hide();
		$('#' + id + '-sub').show();
	}
}

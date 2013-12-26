$(document).ready(function() {
	$('.edit-answer-link').click(function (e) {
		e.preventDefault();
		var href = $(this).attr('href');
		$.fn.dialog.open({
			width: 500,
			height: 390,
			modal: false,
			href: href,
			title: 'Edit Record'			
		});
	});
	
	$('.delete-answer-link').click(function (e) {
		e.preventDefault();
		var href = $(this).attr('href');
		$.fn.dialog.open({
			width: 340,
			height: 70,
			modal: false,
			href: href,
			title: 'Delete Record'			
		});
	});
});
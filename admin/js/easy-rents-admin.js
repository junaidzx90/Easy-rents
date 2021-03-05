(function( $ ) {
	'use strict';
	$('.delete_addr').on("click", function (e) {
		if (!confirm("Are you sure!"))
			e.preventDefault();
	});
})( jQuery );

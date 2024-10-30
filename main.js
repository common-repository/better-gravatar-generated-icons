jQuery(document).ready(function($) {

	function toDataUrl(obj) {   
		var img = new Image();
		img.crossOrigin = 'Anonymous';
		img.onload = function() {
			var canvas = document.createElement('CANVAS');
			var ctx = canvas.getContext('2d');
			var dataURL;
			canvas.height = this.height;
			canvas.width = this.width;
			ctx.drawImage(this, 0, 0);
			dataURL = canvas.toDataURL();
			//obj.attr("data-rel",dataURL.length)
			if(dataURL.length < 500) {
				obj.attr("src",obj.attr("data-rel"));
			}
		};

		img.src = obj.attr("src");
		if (img.complete || img.complete === undefined) {
			img.src = "data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///ywAAAAAAQABAAACAUwAOw==";
			img.src = obj.attr("src");
		}
	}


	$("img.avatar").each(function(){
		toDataUrl($(this));
	});


});
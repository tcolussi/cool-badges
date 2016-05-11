/*-----------------------------------------------------------------------------------

 	Custom JS - All front-end jQuery
 
-----------------------------------------------------------------------------------*/

$(function() {
	
	// Preloader
	$(window).load(function(){
		$('#preloader').fadeOut(500,function(){$(this).remove();});
	});
	
	// Scrollable
	$('.scrollable').scrollable({
		speed: 1000
	});

	// Tooltip
	$('.trigger').tooltip({
		relative: true, 
		position: 'bottom right', 
		effect: 'slide', 
		offset: [15, -180], 
		delay: 500 
	});

	// Tabs
	$('.tabs').tabs('.pane', {
	    effect: 'fade',
	    onBeforeClick: function(event, i) {
			var pane = this.getPanes().eq(i);
			if (pane.is(":empty")) {
				pane.load(this.getTabs().eq(i).attr("href"));
			}
	    }
	});
	
	// Alert Messages
	setTimeout(function() {
		$('#success, #error').slideUp(500);
	}, 10000);
	
	// Banner animation
	var offset = $(".banner").offset();
	var topPadding = 20;
	$(window).scroll(function() {
		if ($(window).scrollTop() > offset.top) {
			$(".banner").stop().animate({
				marginTop: $(window).scrollTop() - offset.top + topPadding
			});
		} else {
			$(".banner").stop().animate({
				marginTop: 0
			});
		};
	});

});

// Badge replacement
function change(i) {
	name = 'categories/badges/' + i + '.png';
	elem = document.getElementById('icon');
	elem.value = name;
	badge = i;
	$('#add-img').html('<img src="categories/badges/'+i+'.png" />');
}

// Badge validation
function validate(){
	val = document.getElementById('icon').value;
	if (val == ''){
		alert("Please select a badge for your profile picture");
	}else{												  
		$('#loading').slideDown(500);
		$('body,html').animate({scrollTop:0},800);
		message = document.getElementById('description').value;
		document.getElementById('message').value = message
		document.getElementById('send_form').submit();
	}
}
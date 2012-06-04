// var $ = $ || {};
// var window = window || {};
// var _gaq = _gaq || {};
// var location = location || {};
// var setTimeout = setTimeout || {};


function showContent(department, animate) {
	
	if (department === "") {
		$(".content").hide();
		$("#topfooter").show();
	}
	
	if (department != "people" && department != "products" && department != "facebook") {
		return;
	}
	
	window.location.hash = department;

	$(".content." + department).show();
	$("#topfooter").hide();

	if ($(window).scrollTop() < 300 && animate !== false) {
		$("html, body").stop().animate({
			scrollTop: $("#nav").offset().top + 20}, 200);
	}


	if (window._currentDepartment && window._currentDepartment != department) {
		$(".content." + window._currentDepartment).hide();
	}

	window._currentDepartment = department;
	_gaq.push(['_trackPageview', '/#' + department]);
}

function veryReady() {
	
	// Show the right content on load and hide all other content

	var hash = window.location.hash.replace("#", "");

	if (hash) {
		showContent(hash, false);
	}

	$(".show").click(function() {
		showContent($(this).attr("content"));
	});

	// Detect hash changes

	$(window).hashchange(function() {
		showContent(location.hash.replace("#", ""), false);
	});
	
	// Product hovers
	
	var fadeTime = 100;
	
	$(".product").children().hover(
		function() {
			var node = $(this);
			node.children("h6.sub").stop();
			node.children("h6.url").stop();
			
			node.children("h6.sub").fadeTo(fadeTime, 0.0, function() {
				node.children("h6.sub").hide();
				node.children("h6.url").fadeTo(fadeTime, 1.0);
			});
			
		},
		function() {
			var node = $(this);
			node.children("h6.sub").stop();
			node.children("h6.url").stop();
			node.children("h6.url").fadeTo(fadeTime, 0.0, function() {
				node.children("h6.url").hide();
				node.children("h6.sub").fadeTo(fadeTime, 1.0);
			});
		}
	);
	
	// Profile hovers

	function setupHovers() {
		$(".profile").hover(
			function() {
				$('img.fade', this).stop().fadeTo(200, 1);
				$(".facebook", this).stop().fadeTo(200, 1);
			},
			function() {
				$('img.fade', this).stop().fadeTo(50, 0);
				$('.facebook', this).stop().fadeTo(50, 0);
			}
		);
	}
	setupHovers();

}




$('document').ready(function() {
	
	// Preload the css file with the inlined font so they can draw fast. We
	// block displaying the site on this.
	
	var path = "static/css/font-unhinted.css";
	
	if ($.browser.msie) {
		// alert("hello");
		// $("head")[0].append($("<link rel='stylesheet' href='" + path + "' type='text/css'>"));
		$('#home').show();
		veryReady();
	
	} else {
		$.get(path, function(response) {
	
			if (!$('#fontCSS').length) {
				$('head').append('<style id="fontCSS"></style>');
				$('#fontCSS').text(response);
			}
		
			veryReady();
		
			setTimeout(function() {
				$('#home').show();
				// veryReady();
			}, 100);
		
		});
	}
});



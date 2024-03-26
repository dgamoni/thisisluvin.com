(function ($, root, undefined) {
	
	$(function () {
		
		'use strict';
		
		// DOM ready
		$.fn.lastWord = function() {
		  var text = this.text().trim().split(" ");
		  var last = text.pop();
		  this.html(text.join(" ") + (text.length > 0 ? " <span class='lastWord'>" + last + "</span>" : last));
		};
						
		$.fn.firstWord = function() {
		  var text = this.text().trim().split(" ");
		  var first = text.shift();
		  this.html((text.length > 0 ? "<span class='firstWord'>"+ first + "</span> " : first) + text.join(" "));
		};
		
		$(document).ready(function(){			
			$.each($(".nav ul>li>a"), function( index, value ) {
				$(value).lastWord();								   
			});
			$.each($("#mobile-menu ul>li>a"), function( index, value ) {
				$(value).lastWord();								   
			});
			
			$(".nav ul>li>a").on('click', function(e){								
				var anch = $(this).attr("href");
				if(anch[0] == '#'){
					e.preventDefault();
					var st = $(anch).offset().top-100;
					$("html, body").animate({ scrollTop: st }, 1000, 'swing');
				}
			});
			
			$("#mobile-menu ul>li>a").on('click', function(e){								
				var anch = $(this).attr("href");
				if(anch[0] == '#'){
					e.preventDefault();
					var st = $(anch).offset().top-100;
					$("html, body").animate({ scrollTop: st }, 1000, 'swing');
					$("#nav-icon").toggleClass('open');
					if($("#nav-icon").hasClass('open')){
						$('#mobile-menu').slideDown();	
					}
					else{
						
						$('#mobile-menu').slideUp();
					}
				}
			});
			
			$(".sticky").sticky({topSpacing:0});
			
			$.each($(".section_title"), function( index, value ) {
				$(value).lastWord();								   
			});
			
			/** LUVIN PEOPLE **/
			$.each($(".lp_name"), function( index, value ) {
				$(value).lastWord();								   
			});
			$('.lpeople-img a').on('click', function(e){
				e.preventDefault();										 
			});
			$('.stats_network').on('click', function(){
				var conn = $(this).data('conn');
				window.open(conn);
			});
			
			/** OUR PROJECTS **/
			$(".ver-mais a").on("click", function(e){
				e.preventDefault();
				load_posts();
			});
			
			
			/** NAV ICON **/
			$('#nav-icon').click(function(){
				$(this).toggleClass('open');
				if($(this).hasClass('open')){
					$('#mobile-menu').slideDown();	
				}
				else{
					
					$('#mobile-menu').slideUp();
				}
			});
			
			$.each($(".lpeople_a"), function( index, value ) {
			    var tube = $(this).data('tube');
				var fb = $(this).data('fb');
				var insta = $(this).data('insta');
				var id = $(this).attr('id');
				var lang = $(this).data('lang');
				get_info(fb, insta, id, lang, tube);
			});
		});
		
	});
	
	
	// LOAD MORE POSTS BY AJAX
	var ppp = 3; // Post per page
	var pageNumber = 1;
		
	function load_posts(){
		pageNumber++;
		$(".ver-mais a").append("...");
		$.post(ajax_posts.ajaxurl,{pageNumber:pageNumber, ppp:ppp, action:"more_post_ajax"})
		.done(function(data){
				data = data.split("|->|");
				var total = data[0];
				var $data = $(data[1]);
				var txt = $(".ver-mais a").text();
				txt = txt.split("...");
				$(".ver-mais a").text(txt[0]);
				if($data.length){
					$("#ajax-posts").append($data);					
					//$("#more_posts").attr("disabled",false);
				} else{
					$(".ver-mais a").hide();
					//$("#more_posts").attr("disabled",true);
				}
				
				if(total < 3) $(".ver-mais a").hide();
			})
			
	

		return false;
	}

	function get_info(fb, insta, id, lang, tube){
		
		$.post(ajax_path,{fb:fb, insta:insta, lang:lang, tube:tube})
		.done(function(data){
		    console.log(data);
						   if(data.fb != 'Not available'){
							   var html = '<div class="stats pull-left">';
							   html += '<p><span class="stats_network" data-conn="https://www.facebook.com/'+fb+'"><img src="http://www.thisisluvin.com/wp-content/uploads/2017/12/facebook.svg"></span></p>';
							   html += '<p>'+data.fb+'</p>';
							   html += '</div>';
							   $("#"+id+" .stats_wrapper").append(html);
						   }
						   if(data.insta != 'Not available'){
							    var html = '<div class="stats pull-right">';
							   html += '<p><span class="stats_network" data-conn="https://www.instagram.com/'+insta+'"><img src="http://www.thisisluvin.com/wp-content/uploads/2017/12/instagram.svg"></span></p>';
							   html += '<p>'+data.insta+'</p>';
							   html += '</div>';
							   $("#"+id+" .stats_wrapper").append(html);
						   }
						   if(data.tube != 'Not available'){
							    var html = '<div class="stats pull-right">';
							   html += '<p><span class="stats_network" data-conn="https://www.youtube.com/user/'+tube+'/"><img src="http://www.thisisluvin.com/wp-content/uploads/2017/12/youtube-2.svg"></span></p>';
							   html += '<p>'+data.tube+'</p>';
							   html += '</div>';
							   $("#"+id+" .stats_wrapper").append(html);
						   }
						   $('.stats_network').on('click', function(){
								var conn = $(this).data('conn');
								window.open(conn);
							});
					   });
		
		return false;
	}	
	function get_info_old(fb, insta, id, lang, tube){
		
		$.post(ajax_path,{fb:fb, insta:insta, lang:lang, tube:tube})
		.done(function(data){
		    console.log(data);
						   if(data.fb != 'Not available'){
							   var html = '<div class="stats pull-left">';
							   html += '<p><span class="stats_network" data-conn="https://www.facebook.com/'+fb+'">facebook</span></p>';
							   html += '<p>'+data.fb+'</p>';
							   html += '</div>';
							   $("#"+id+" .stats_wrapper").append(html);
						   }
						   if(data.insta != 'Not available'){
							    var html = '<div class="stats pull-right">';
							   html += '<p><span class="stats_network" data-conn="https://www.instagram.com/'+insta+'">instagram</span></p>';
							   html += '<p>'+data.insta+'</p>';
							   html += '</div>';
							   $("#"+id+" .stats_wrapper").append(html);
						   }
						   $('.stats_network').on('click', function(){
								var conn = $(this).data('conn');
								window.open(conn);
							});
					   });
		
		return false;
	}
	
})(jQuery, this);

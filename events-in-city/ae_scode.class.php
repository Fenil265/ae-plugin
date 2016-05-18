<?php
/*
Plugin Name: AE - Shortcode Generator
Plugin URI: http://allevents.in/pages/add-custom-plugin
Description: Generate shortcode for displaying city events and events organized by specific organizer into page/post/blog. It is as easy as it can get... and free!
Version: 2.0
Author: Allevents.in
Author URI: http://allevents.in/
License: GPL2
 */
if (class_exists(ae_shortcode)) {
	$ae_shortcode = new ae_shortcode;
}
class ae_shortcode {
	function __construct() {
		add_action('admin_menu', array($this, 'ae_shortcode_generator'));
		wp_enqueue_script("jquery", plugins_url('/js/jquery.js', __FILE__));
		wp_enqueue_script("jquery-migrate", plugins_url('/js/jquery-migrate.js', __FILE__));
		wp_enqueue_script("jquery-ui", plugins_url('/js/jquery-ui.js', __FILE__));
		wp_enqueue_style('ae_style.css', plugins_url('/css/style.css', __FILE__));
		wp_enqueue_style('ae_bootstrap', "//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css");
	}

	function ae_shortcode_generator() {
		add_menu_page("All Events in City ", "Allevents", 0, __FILE__, array($this, "all_events_in_city"), plugins_url('/images/ae.png', __FILE__));
		add_submenu_page(__FILE__, "Events In City", "Events In City", 0, "city_scode_slug", array($this, "city_events_shortcode"));
		add_submenu_page(__FILE__, "Events By Organizer", "Events By Organizer", 0, "org_scode_slug", array($this, "organizer_events_scode"));
	}

	function all_events_in_city() {
		?>
		<h1>All Events in City</h1>
		<div><h2>Revolutionizing the Way We Discover and Share Events</h2>
			<iframe style="width:850px; height: 480px;"src="https://www.youtube.com/embed/f8QUSpFnXT4?autoplay=0" frameborder="0" allowfullscreen></iframe>
		</div><br>
		<div>
			<p style='max-width:850px; font-size:15px;'>We’re small and diverse group on mission: to help people discover the events happening around them. Hundreds of events happens in our city, but its difficult to find them out. All Events in City helps you do that..!!</p>
			<p style='max-width:850px; font-size:15px;'>Uniquely harnessing the power of the social media, we have created a smarter way to discover and share information regarding events. Started in 2011, All Events in City has 50 million+ events posted, 25 million users from 33,000 cities all over the world.</p>
		</div>
		<div>
		<h4>To display events happening in your city, Downlaod <a href="https://wordpress.org/plugins/events-in-city/" target="_blank">Events in City</a> and for displaying upcoming events by organizer Downlaod <a href="https://wordpress.org/plugins/events-in-city/" target="_blank">Events by Organizer</a></h4>
		</div>
		<div>
		<hr class='footer-hr'>
		<span>
		<h4 align='center'>For more information regarding Events, Visit <a href='http://allevents.in/' target='_blank'>All Events in City</a></h4>
		</span>
		<span>
		<h4 align='center'>You can mail us at <a href="mailto:contact@allevents.in">contact@allevents.in</a> for any query related plugins. </h4>
		</span>
		</div>
		<div class='container'>
			<span class="footer-span">© Copyright 2016. All Rights Reserved.</span><br><span class="footer-span">Powered by All Events in City.</span>
				<ul class="social-network social-circle">
	                <li><a href="https://www.facebook.com/allevents.in?_rdr=p" target="_blank" class="icoFacebook" title="Facebook"><i class="fa fa-facebook"></i></a></li>
	                <li><a href="https://twitter.com/allevents_in" target="_blank" class="icoTwitter" title="Twitter"><i class="fa fa-twitter"></i></a></li>
	                <li><a href="https://plus.google.com/108979929341203332721" target="_blank" class="icoGoogle" title="Google +"><i class="fa fa-google-plus"></i></a></li>
	                <li><a href="https://www.instagram.com/allevents.in/" class="icoInstagram" target="_blank" title="Instagram"><i class="fa fa-instagram"></i></a></li>
            	</ul>
		</div>

	<?php
}

	function city_events_shortcode() {
		?>
		<h1>Events In City</h1>
		<div align='center' style='max-width:50%; font-size:15px; line-height:150%;'>Engage your visitors by displaying the best  events from All Events in City right onto your site. You can select the height and width below. If your site is of specialized nature, you can also select the category. When done, just integrate the HTML code into your web page.</div><hr>
		<h2>Step 1: Generate Shortcode</h2>
		Start typing into city field to select Proper City from auto suggestion box
				<script type="text/javascript">
				(function($) {
				    $(function(request) {
				        $("#city").autocomplete({
				            source: function(request, response) {
				                $.ajax({
				                    url: "http://allevents.in/api/index.php/geo/web/city_suggestions_full/" + request.term,
				                    dataType: "json",
				                    success: function(data) {
				                        $("#city").removeClass('ui-autocomplete-loading');
				                        if (data.length > 0) {
				                            response($.map(data, function(item) {

				                                try {
				                                    return {
				                                        label: item.city + ',  ' + item.region_code + ',  ' + item.country,
				                                        value: item.query,
				                                        region: item.region_code,
				                                        country: item.country,
				                                        link: item.url
				                                    }
				                                } catch (e) {
				                                    console.log(e)
				                                }
				                            }))
				                        }
				                    },
				                    error: function() {
				                        response([]);
				                        $("#city").removeClass('ui-autocomplete-loading');
				                    }
				                });
				            },
				            delay: 500,
				            open: function() {
            				$("#city").data("uiAutocomplete").menu.element.addClass("ae-scode-autoc")
        					},
				            minLength: 2
				        });
				    });

				})(jQuery);
			</script>
			<script type="text/javascript">

	  		function generate_city_scode(){
				jQuery('#div2').show("slow");
				var city = document.getElementById("city").value;
				var cat = document.getElementById("cat").value;
				var width = document.getElementById("city_width").value;
				var height = document.getElementById("city_height").value;
				document.getElementById("city_display").value = "[city-events city='" + city + "' category='" + cat +"' width='"+ width +"' height='"+ height +"']";
			}
			jQuery(document).ready(function($){
	    		$(".button-primary").click(generate_city_scode);
	   		});

   		</script>
   			<style>
				select {
					display: block;
					width: 250px;
				}
				input {
					width: 250px;
				}
			</style>
			<table style="margin-left:8%" cellspacing="5">
			<tr><td>
		        <label>City :</label> </td>
		    <td><input id="city" name="city" type="text" value="New York" />
    	    </td></tr>
    		<tr><td>
    			<label>Event Category :</label> </td>
    		   	<td><select id="cat" name="cat">
				<option value="All" checked="All">All</option>
				<option value="Business" checked="Bussiness">Business</option>
				<option value="Concerts" checked="Concerts">Concerts</option>
				<option value="Exhibitions" checked="Exhibitions">Exhibitions</option>
				<option value="Festivals" checked="Festivals">Festivals</option>
				<option value="Meetups" checked="Meetups">Meetups</option>
				<option value="Music" checked="Music">Music</option>
				<option value="Parties" checked="Parties">Parties</option>
				<option value="Performance" checked="Performance">Performance</option>
				<option value="Sports" checked="Sports">Sports</option>
				<option value="Workshops" checked="Workshops">Workshops</option>
				</select></td></tr>
			<tr><td>
		        <label>Width :</label> </td>
		   	<td><input id="city_width" name="width" type="text" value="100%" />
    		</td></tr>
    		<tr><td>
		        <label>Height :</label> </td>
		       <td> <input id="city_height" name="height1" type="text" value="500px" />
    		</td></tr>
    		</table>
						<p style="margin-left:14.5%">
							<button class="button-primary">Generate Shortcode</button>
						</p>
						<div id="div2">
							<p style="margin-top: 26px; margin-bottom: 4px; font-weight: bold; text-align: center; width: 570px;">Please copy and paste below shortcode into page or post.</p>
							<textarea class="scode_textarea" style="background: #ECFFF2;" onclick="select()" id="city_display"  readonly="readonly">[city-events city='New York' category='All' width='100%' height='500px']</textarea>
							<p style="font-weight: bold; text-align: center; width: 570px;">(Note* : Make sure that Events in City plugin is activated before using shortcode.)</p>
						</div>
						<br><hr>

	<?php
echo "<h2>Step 2: Please copy and paste above shortcode into page or post.</h2>";
		echo "<div><img src='" . plugins_url('/events-in-city/images/screenshot-3.jpg') . "' style='height:500px;'></div>";
		echo "<hr class='footer-hr'><h4 style='' align='center'>For more information regarding Plugin, Visit <a href='https://wordpress.org/plugins/events-in-city/' target='_blank'>Events in city</a></h4>";
		?>
				<span>
				<h4 align='center'>You can mail us at <a href="mailto:contact@allevents.in">contact@allevents.in</a> for any query related plugins. </h4>
				</span>
				<div class='container'>
				<span class="footer-span">© Copyright 2016. All Rights Reserved.</span><br><span class="footer-span">Powered by All Events in City.</span>
						<ul class="social-network social-circle">
                        <li><a href="https://www.facebook.com/allevents.in?_rdr=p" target="_blank" class="icoFacebook" title="Facebook"><i class="fa fa-facebook"></i></a></li>
                        <li><a href="https://twitter.com/allevents_in" target="_blank" class="icoTwitter" title="Twitter"><i class="fa fa-twitter"></i></a></li>
                        <li><a href="https://plus.google.com/108979929341203332721" target="_blank" class="icoGoogle" title="Google +"><i class="fa fa-google-plus"></i></a></li>
                        <li><a href="https://www.instagram.com/allevents.in/" class="icoInstagram" target="_blank" title="Instagram"><i class="fa fa-instagram"></i></a></li>
                    	</ul>
				</div>

	<?php }
	function organizer_events_scode() {
		?>
				<h1>Events By Organizer</h1>
				<div align='center' style='max-width:60%; font-size:15px; line-height:150%;'>Plugin lets you show your upcoming events. Enter your facebook page name or id,get the code and integrate it into your HTML.</div></center><br>
				<h2>Step 1: Generate Shortcode</h2>
				Start typing into Organizer field to select proper organizer from auto suggestion box
				<script type="text/javascript">
					jQuery(document).ready(function($) {
				    $(function(request) {
				    $("#org").autocomplete({
					           	source: function(request, response) {
					        	var q = $("#org").val();
					        	$.ajax({
					                url: 'http://allevents.in/api/index.php/organizer/web/search',
					                data: '{"query":"' + q + '"}',
					                dataType: "json",
					                type: "POST",
					                success: function(data) {
					                	$("#org").removeClass('ui-autocomplete-loading');
					                    if (data.error == 0) {
					                    response( $.map( data.data, function( item ) {

					                    try {
							                return{
							                        label: item.name,
							                        value: item.name + "," + item.organizer_id,
							                        organizer_id: item.organizer_id,
							                        count: item.followers_count,
                        							thumb_url: item.thumb_url,
                        							upcoming_events: item.upcoming_events
										    }
										} catch( e ) {
											console.log(e)
										}
							            }))
					                 }
					                }, error: function() {
				                        response([]);
				                        $("#org").removeClass('ui-autocomplete-loading');
				                    }
					            });
					            },		select: function (event, ui) {
					            		$("#org").val(ui.item.label);
							    		$("#organizer_id").val(ui.item.organizer_id);
							    		var organizer = ui.item.organizer;
							    		return false;
						     	  },
							     	    delay: 500,
						                minLength: 2,
						                open: function() {
				            	     			$("#org").data("uiAutocomplete").menu.element.addClass("ae-scode-autoc")
							        	}
						        }) .autocomplete("instance")._renderItem = function(ul,item){
								   return $("<li></li>")
								   .append("<span style='display: inline-block; float: left; clear:left '>"+"<img style='width:32px; height:32px;' src='" + item.thumb_url +"'>" + "</span><span style='display: inline-block; width: 100%; margin-left: 13px; height: 31px; line-height: 31px; box-sizing: border-box;'>" + item.label  +  "</span>" )
								  .appendTo(ul);
								  };
							});
					    });

				function generate_org_scode(){
					jQuery('#div1').show("slow");
					var organizer_id = document.getElementById("organizer_id").value;
					var width = document.getElementById("width").value;
					var height = document.getElementById("height").value;
					document.getElementById("org_display").value = "[org-events organizer_id='" + organizer_id +"' width='"+ width +"' height='"+ height +"']";
				}

				jQuery(document).ready(function($){
    				$("#btn1").click(generate_org_scode);
   				});
				</script>
				<style>
					input {
						width: 250px;
					}
				</style>

				<table style="margin-left: 8%" cellspacing="10">
					<tr><td>
					<label>Organizer :</label></td>
				<td><input type="text" name="org" value="SUNBURN Festival" id="org" />
					<input type="hidden" name="organizer_id" value="198763" id="organizer_id" />
				</td></tr>
				<tr><td>
					<label>Width :</label>	</td>
					<td><input type="text" name="width" value="100%" id="width">
				</td></tr>
				<tr><td>
					<label>Height :</label>	</td>
					<td><input type="text" name="height" value="500px" id="height">
				</td></tr></table>

						<p style="margin-left: 13.5%"><button id="btn1" class="button-primary">Generate Shortcode</button></p>
						<div id="div1">
								<p style="margin-top: 26px; margin-bottom: 4px; font-weight: bold; text-align: center; width: 570px;">Please copy and paste below shortcode into page or post.</p>
								<textarea class="scode_textarea" style="background: #ECFFF2;" id="org_display" onclick="select()" readonly="readonly">[org-events organizer_id='198763' width='100%' height='500px']</textarea>
								<p style="font-weight: bold; text-align: center; width: 570px;">(Note* : Make sure that Events by Organizer plugin is activated before using shortcode.)</p>
						</div>
						<br><hr>
				<?php
		echo "<h2 style='height:30px; line-height:30px; '>Step 2: Copy and paste above code into your page or post</h2>";
		echo "<div><img src='" . plugins_url('/events-in-city/images/screenshot-7.png') . "' style='height:500px;'></div>";
		?>
				<hr class='footer-hr'>
				<h4 align='center'>For more information regarding Plugin, Visit <a href='https://wordpress.org/plugins/events-in-city/' target='_blank'>Events By Organizer</a></h4>
				<h4 align='center'>You can mail us at <a href="mailto:contact@allevents.in">contact@allevents.in</a> for any query related plugins. </h4>
				<div class='container'>
				<span class="footer-span">© Copyright 2016. All Rights Reserved.</span><br><span class="footer-span">Powered by All Events in City.</span>
						<ul class="social-network social-circle">
                        <li><a href="https://www.facebook.com/allevents.in?_rdr=p" target="_blank" class="icoFacebook" title="Facebook"><i class="fa fa-facebook"></i></a></li>
                        <li><a href="https://twitter.com/allevents_in" target="_blank" class="icoTwitter" title="Twitter"><i class="fa fa-twitter"></i></a></li>
                        <li><a href="https://plus.google.com/108979929341203332721" target="_blank" class="icoGoogle" title="Google+"><i class="fa fa-google-plus"></i></a></li>
                        <li><a href="https://www.instagram.com/allevents.in/" class="" ss="icoInstagram" target="_blank" title="Instagram"><i class="fa fa-instagram"></i></a></li>
                    	</ul>
				</div>

		<?php
}
}
?>
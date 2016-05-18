<?php
/*
Plugin Name: AE - Events In City
Plugin URI: http://allevents.in/pages/add-custom-plugin
Description: Engage your visitors by displaying the best events from All Events in City right onto your site. You can select the height and width below. If your site is of specialized nature, you can also select the category. When done, just integrate the HTML code into your web page. It is as easy as it can get... and free!
Version: 2.0
Author: Allevents.in
Author URI: http://allevents.in/
License: GPL2
 */

class ae_city_widget extends WP_Widget {

	/*
		 * Register widget with WordPress
	*/
	function ae_city_widget() {
		parent::WP_Widget(false, $name = __('Events In City', 'WP_Widget_plugin'), array('description' => __('Display Events Happening In Your City', 'wpb_widget_domain')));
		wp_enqueue_script("jquery", plugins_url('/js/jquery.js', __FILE__));
		wp_enqueue_script("jquery-migrate", plugins_url('/js/jquery-migrate.js', __FILE__));
		wp_enqueue_script("jquery-ui", plugins_url('/js/jquery-ui.js', __FILE__));
		wp_enqueue_style('ae_style.css', plugins_url('/css/style.css', __FILE__));
	}

	/* * Front-end display of widget.
		     * @see WP_Widget::widget()
		     * @param array $args  Widget arguments.
			 * @param array $instance Saved values from database.
			 * @return void
	*/
	function widget($arg, $instance) {
		global $wp_version;
		extract($arg);
		echo $before_widget;
		$title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance, $this->id_base);
		if ($title) {
			echo $before_title . $title . $after_title;
			echo $box_html;
		}
		echo '<iframe style=" width: ' . $instance['width'] . 'px; height: ' . $instance['height'] . 'px; border: 0;"
				src="https://allevents.in/plugin/wp-city-events.php?city=' . $instance['city'] . '&keywords=' . $instance['category'] . '&width=' . $instance['width'] . '&height=' . $instance['height'] . '&header=' . $instance['header'] . '&ref=' . get_site_url() . '&ver=' . $wp_version . '" scrolling="yes"></iframe>';
		echo $after_widget;
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['city'] = strip_tags($new_instance['city']);
		$instance['category'] = strip_tags($new_instance['category']);
		$instance['width'] = strip_tags($new_instance['width']);
		$instance['height'] = strip_tags($new_instance['height']);
		$instance['header'] = empty($instance['title']) ? 0 : 1;
		return $instance;
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 * @return void
	 */
	function form($instance) {
		global $wp_version;
		$title = esc_attr($instance['title']);
		$city = esc_attr($instance['city']);
		$category = esc_attr($instance['category']);
		$width = esc_attr($instance['width']);
		$height = esc_attr($instance['height']);
		$header = !isset($instance['title']) ? (bool) $instance['header'] : false;
		?>
			<script type="text/javascript">
					(function($) {
				    $(function(request) {
				    	$("#<?php echo $this->get_field_id('city'); ?>").autocomplete({
				            source: function(request, response) {
				                $.ajax({
				                    url: "http://allevents.in/api/index.php/geo/web/city_suggestions_full/" + request.term,
				                    dataType: "json",
				                    success: function(data) {
				                        $("#<?php echo $this->get_field_id('city'); ?>").removeClass('ui-autocomplete-loading');
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
				                        $("#<?php echo $this->get_field_id('city'); ?>").removeClass('ui-autocomplete-loading');
				                    }
				                });
				            },
				            delay: 500,
				            open: function() {
            				$("#<?php echo $this->get_field_id('city'); ?>").data("uiAutocomplete").menu.element.addClass("ae-city-autoc")
        					},
				            minLength: 2
				        });
				    });

				})(jQuery);
			</script>
		 	<p>
				<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title (Optional) :');?></label>
			    <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $instance['title']; ?>" />
	    	</p>
    		<p>
		        <label for="<?php echo $this->get_field_id('city'); ?>"><?php _e('City :');?></label>
		        <input class="widefat" id="<?php echo $this->get_field_id('city'); ?>" name="<?php echo $this->get_field_name('city'); ?>" type="text" value="<?php if (isset($instance['city'])) {echo $instance['city'];} else {echo "New York";}?>" />
    	    </p>
    		<p>
    			<label for="<?php echo $this->get_field_id('category'); ?>"><?php _e('Event Category :');?></label>
    		</p>
    		<p>
			   	<select id="<?php echo $this->get_field_id('category'); ?>" name="<?php echo $this->get_field_name('category'); ?>" >
				<option value="All" <?php selected($instance['category'], 'All');?>>All</option>
				<option value="Business" <?php selected($instance['category'], 'Business');?>>Business</option>
				<option value="Concerts" <?php selected($instance['category'], 'Concerts');?>>Concerts</option>
				<option value="Exhibitions" <?php selected($instance['category'], 'Exhibitions');?>>Exhibitions</option>
				<option value="Festivals" <?php selected($instance['category'], 'Festivals');?>>Festivals</option>
				<option value="Meetups" <?php selected($instance['category'], 'Meetups');?>>Meetups</option>
				<option value="Music" <?php selected($instance['category'], 'Music');?>>Music</option>
				<option value="Parties" <?php selected($instance['category'], 'Parties');?>>Parties</option>
				<option value="Performance" <?php selected($instance['category'], 'Performance');?>>Performance</option>
				<option value="Sports" <?php selected($instance['category'], 'Sports');?>>Sports</option>
				<option value="Workshops" <?php selected($instance['category'], 'Workshops');?>>Workshops</option>
				</select>
			</p>
			<p>
		        <label for="<?php echo $this->get_field_id('width'); ?>"><?php _e('Width :');?></label>
		        <input class="widefat" id="<?php echo $this->get_field_id('width'); ?>" name="<?php echo $this->get_field_name('width'); ?>" type="text" value="<?php if (isset($instance['width'])) {echo htmlentities($instance['width']);} else {echo "300";}?>" />
    		</p>
    		<p>
		        <label for="<?php echo $this->get_field_id('height'); ?>"><?php _e('Height :');?></label>
		        <input class="widefat" id="<?php echo $this->get_field_id('height'); ?>" name="<?php echo $this->get_field_name('height'); ?>" type="text" value="<?php if (isset($instance['height'])) {echo htmlentities($instance['height']);} else {echo "300";}?>" />
    		</p>
	<?php
}

	function city_event_scode($atts) {
		global $wp_version;
		$atts = shortcode_atts(array(
			'city' => 'new york',
			'category' => 'all',
			'width' => '100%',
			'height' => '500px',
		), $atts, 'city-events');

		$url = 'https://allevents.in/plugin/wp-city-events.php?city=' . $atts['city'] . '&keywords=' . $atts['category'] . '&height=' . $atts['height'] . '&ref=' . get_site_url() . '&ver=' . $wp_version;
		return '<iframe style=" width: ' . $atts['width'] . '; height: ' . $atts['height'] . '; border:0;" src="' . $url . '"></iframe>';
	}

	function embed_event_scode( $atts ) {
    		global $wp_version;
    		$atts = shortcode_atts( array(
			'event_id' => '192895701069404',
			'width' => '100%',
        	'height' => '700px',
        	), $atts, 'embed-events' );
		    
		    $url =  'https://allevents.in/e/'. $atts['event_id'] .'?&height=' . $atts['height'] . '&ref=' . get_site_url() . '&ver=' . $wp_version;
		    return '<iframe style=" width: '. $atts['width'] .'; height: '. $atts['height'] .'; border:1;" src="'.$url.'"></iframe>';

    }

}
add_action('widgets_init', function () {
	register_widget('ae_city_widget');
});
add_shortcode('city-events', array('ae_city_widget', 'city_event_scode'));
add_shortcode( 'embed-events', array( 'ae_city_widget', 'embed_event_scode') );
?>

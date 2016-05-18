<?php
/*
Plugin Name: AE - Events By Organizer
Plugin URI: http://allevents.in/pages/add-custom-plugin
Description: Plugin lets you show your upcoming events.You can select the height and width below.It is as easy as it can get... and free!
Version: 2.0
Author: Allevents.in
Author URI: http://allevents.in/
License: GPL2
 */

class ae_org_widget extends WP_Widget {

	/*
		 * Register widget with WordPress
	*/
	public function ae_org_widget() {
		parent::WP_Widget(false, $name = __('Events By Organizer', 'WP_Widget_plugin'), array('description' => __('Lets Organizer Show Their Upcoming Events', 'wpb_widget_domain')));
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
	public function widget($arg, $instance) {
		global $wp_version;
		extract($arg);

		$title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance, $this->id_base);

		echo $before_widget;
		if ($title) {
			echo $before_title . $title . $after_title;
			echo $box_html;
		}
		echo '<iframe style=" width: ' . $instance['width'] . 'px; height: ' . $instance['height'] . 'px; border: 0;"
				src="https://allevents.in/plugin/wp-events-by-org.php?org_id=' . $instance['organizer_id'] . '&width=' . $instance['width'] . '&height=' . $instance['height'] . '&header=' . $instance['header'] . '&ref=' . get_site_url() . '&ver=' . $wp_version . '" scrolling="yes"></iframe>';
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
	public function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['organizer'] = strip_tags($new_instance['organizer']);
		$instance['organizer_id'] = strip_tags($new_instance['organizer_id']);
		$instance['width'] = strip_tags($new_instance['width']);
		$instance['height'] = strip_tags($new_instance['height']);
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
	public function form($instance) {
		global $wp_version;
		$organizer = esc_attr($instance['organizer']);
		$organizer_id = esc_attr($instance['organizer_id']);
		$width = esc_attr($instance['width']);
		$height = esc_attr($instance['height']);
	?>
			<script type="text/javascript">
		 		jQuery(document).ready(function($) {
				    $(function(request) {
				    $("#<?php echo $this->get_field_id('organizer'); ?>").autocomplete({
					           	source: function(request, response) {
					        	var q = $("#<?php echo $this->get_field_id('organizer'); ?>").val();
					        	$.ajax({
					                url: 'http://allevents.in/api/index.php/organizer/web/search',
					                data: '{"query":"' + q + '"}',
					                dataType: "json",
					                type: "POST",
					                success: function(data) {
					                	$("#<?php echo $this->get_field_id('organizer'); ?>").removeClass('ui-autocomplete-loading');
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
				                        $("#<?php echo $this->get_field_id('organizer'); ?>").removeClass('ui-autocomplete-loading');
				                    }
					            });
					            },		select: function (event, ui) {
					            		$("#<?php echo $this->get_field_id('organizer'); ?>").val(ui.item.label);
							    		$("#<?php echo $this->get_field_id('organizer_id'); ?>").val(ui.item.organizer_id);
							    		var organizer = ui.item.organizer;
							    		return false;
						     	  },
							     	    delay: 500,
						                minLength: 2,
						                open: function() {
				            	     			$("#<?php echo $this->get_field_id('organizer'); ?>").data("uiAutocomplete").menu.element.addClass("ae-org-autoc")
							        	}
						        }) .autocomplete("instance")._renderItem = function(ul,item){
								   return $("<li></li>")
								   .append("<span style='display: inline-block; float: left; clear:left '>"+"<img style='width:32px; height:32px;' src='" + item.thumb_url +"'>" + "</span><span style='display: inline-block; width: 100%; margin-left: 13px; height: 31px; line-height: 31px; box-sizing: border-box;'>" + item.label  +  "</span>" )
								  .appendTo(ul);
								  };
							});
				    });
			</script>
		 	<p>
		        <label for="<?php echo $this->get_field_id('organizer'); ?>"><?php _e('Organizer :');?></label>
		        <input style="vertical-align: middle;" class="widefat" id="<?php echo $this->get_field_id('organizer'); ?>" name="<?php echo $this->get_field_name('organizer'); ?>" type="text" value="<?php if (isset($instance['organizer'])) {echo htmlentities($instance['organizer']);} else {echo "SUNBURN Festival";}?>"/>
		        <input style="vertical-align: middle;" type="hidden" id="<?php echo $this->get_field_id('organizer_id'); ?>" name="<?php echo $this->get_field_name('organizer_id'); ?>" value="<?php if (isset($instance['organizer_id'])) {echo htmlentities($instance['organizer_id']);} else {echo "198763";}?>"/>
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

	public function org_event_scode($atts) {
		global $wp_version;
		$atts = shortcode_atts(array(
			'organizer_id' => '198763',
			'width' => '100%',
			'height' => '500px',
		), $atts, 'org-events');

		$url = 'https://allevents.in/plugin/wp-events-by-org.php?org_id=' . $atts['organizer_id'] . '&width=' . $atts['width'] . '&height=' . $atts['height'] . '&ref=' . get_site_url() . '&ver=' . $wp_version;
		return '<iframe style=" width: ' . $atts['width'] . '; height: ' . $atts['height'] . '; border:0;" src="' . $url . '"></iframe>';
	}

}
add_action('widgets_init', function () {
	register_widget('ae_org_widget');
});

add_shortcode('org-events', array('ae_org_widget', 'org_event_scode'));
?>

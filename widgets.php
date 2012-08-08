<?php
/**
 * @package OpenMenu
 * @version 1.6.9
 */
/*

Copyright 2010, 2011, 2012  OpenMenu, LLC

*/

// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
// ** Widgets:
// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
	/* Add our function to the widgets_init hook. */
	add_action('widgets_init', create_function('', 'return register_widget("openmenu_restaurant_location");'));
	add_action('widgets_init', create_function('', 'return register_widget("openmenu_specials");'));
	add_action('widgets_init', create_function('', 'return register_widget("openmenu_tagcloud");'));
	add_action('widgets_init', create_function('', 'return register_widget("openmenu_menu");'));
	add_action('widgets_init', create_function('', 'return register_widget("openmenu_qrcode");'));
	add_action('widgets_init', create_function('', 'return register_widget("openmenu_filter");'));

	class openmenu_filter extends WP_Widget {  
		function openmenu_filter() {  
			/* Widget settings. */
			$widget_ops = array( 'classname' => 'om-filter', 'description' => __('Display a list of menu items that match all defined filters.') );

			/* Widget control settings. */
			$control_ops = array( 'width' => 450, 'height' => 350, 'id_base' => 'om-filter' );

		    parent::WP_Widget('om-filter', 'OpenMenu: Filter', $widget_ops, $control_ops );
		}
		
		function form($instance) {  
		     // outputs the options form on admin
		     
			/* Set up some default widget settings. */
			$defaults = array( 
							'title' => 'Menu Items', 
							'omf_url' => 'http://', 
							'tag_filter' => '', 
							'menuitem_filter' => '', 
							'menu_filter' => '', 
							'group_filter' => '', 
							'tag_special' => false,
							'tag_gluten_free' => false,
							'tag_vegan' => false,
							'tag_vegetarian' => false,
							'tag_kosher' => false,
							'tag_halal' => false
						);
			$instance = wp_parse_args( (array) $instance, $defaults ); ?>
			<p>
				<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title'); ?></label>
				<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" />
			</p>
			<p>
				<label for="<?php echo $this->get_field_id( 'omf_url' ); ?>"><?php _e('Location of the OpenMenu (URL)'); ?></label>
				<input class="widefat" id="<?php echo $this->get_field_id( 'omf_url' ); ?>" name="<?php echo $this->get_field_name( 'omf_url' ); ?>" value="<?php echo $instance['omf_url']; ?>" />
			</p>
			
			<strong>Filters:</strong> <br />
			<p style="padding-left:10px">
				<label for="<?php echo $this->get_field_id( 'menuitem_filter' ); ?>"><?php _e('All or part of a menu item name / description to filter on'); ?></label>
				<input class="widefat" id="<?php echo $this->get_field_id( 'menuitem_filter' ); ?>" name="<?php echo $this->get_field_name( 'menuitem_filter' ); ?>" value="<?php echo $instance['menuitem_filter']; ?>" />
			</p>
			<p style="padding-left:10px">
				<label for="<?php echo $this->get_field_id( 'menu_filter' ); ?>"><?php _e('Menu Name to display items from (exact match)'); ?></label>
				<input class="widefat" id="<?php echo $this->get_field_id( 'menu_filter' ); ?>" name="<?php echo $this->get_field_name( 'menu_filter' ); ?>" value="<?php echo $instance['menu_filter']; ?>" />
			</p>
			<p style="padding-left:10px">
				<label for="<?php echo $this->get_field_id( 'group_filter' ); ?>"><?php _e('Menu Group to display items from (exact match)'); ?></label>
				<input class="widefat" id="<?php echo $this->get_field_id( 'group_filter' ); ?>" name="<?php echo $this->get_field_name( 'group_filter' ); ?>" value="<?php echo $instance['group_filter']; ?>" />
			</p>
				
			<strong>Item Tags:</strong> <br />
			<p style="padding-left:10px">
				<input class="checkbox" type="checkbox" <?php checked($instance['tag_special'], true) ?> id="<?php echo $this->get_field_id('tag_special'); ?>" name="<?php echo $this->get_field_name('tag_special'); ?>" /> 
				<label for="<?php echo $this->get_field_id('tag_special'); ?>"><?php _e('Special'); ?></label>
				&nbsp;&nbsp;
				<input class="checkbox" type="checkbox" <?php checked($instance['tag_gluten_free'], true) ?> id="<?php echo $this->get_field_id('tag_gluten_free'); ?>" name="<?php echo $this->get_field_name('tag_gluten_free'); ?>" /> 
				<label for="<?php echo $this->get_field_id('tag_gluten_free'); ?>"><?php _e('Gluten Free'); ?></label>
				&nbsp;&nbsp;
				<input class="checkbox" type="checkbox" <?php checked($instance['tag_vegetarian'], true) ?> id="<?php echo $this->get_field_id('tag_vegetarian'); ?>" name="<?php echo $this->get_field_name('tag_vegetarian'); ?>" /> 
				<label for="<?php echo $this->get_field_id('tag_vegetarian'); ?>"><?php _e('Vegetarian'); ?></label>
				&nbsp;&nbsp;
				<input class="checkbox" type="checkbox" <?php checked($instance['tag_vegan'], true) ?> id="<?php echo $this->get_field_id('tag_vegan'); ?>" name="<?php echo $this->get_field_name('tag_vegan'); ?>" /> 
				<label for="<?php echo $this->get_field_id('tag_vegan'); ?>"><?php _e('Vegan'); ?></label>
				&nbsp;&nbsp;
				<input class="checkbox" type="checkbox" <?php checked($instance['tag_kosher'], true) ?> id="<?php echo $this->get_field_id('tag_kosher'); ?>" name="<?php echo $this->get_field_name('tag_kosher'); ?>" /> 
				<label for="<?php echo $this->get_field_id('tag_kosher'); ?>"><?php _e('Kosher'); ?></label>
				&nbsp;&nbsp;&nbsp;
				<input class="checkbox" type="checkbox" <?php checked($instance['tag_halal'], true) ?> id="<?php echo $this->get_field_id('tag_halal'); ?>" name="<?php echo $this->get_field_name('tag_halal'); ?>" /> 
				<label for="<?php echo $this->get_field_id('tag_halal'); ?>"><?php _e('Halal'); ?></label>
			<p>
		<?php
		}
		
		function update($new_instance, $old_instance) {  
		     // processes widget options to be saved  
			$instance = $old_instance;

			/* Strip tags (if needed) and update the widget settings. */
			$instance['title'] = strip_tags( $new_instance['title'] );
			$instance['omf_url'] = $new_instance['omf_url'];
			$instance['menuitem_filter'] = $new_instance['menuitem_filter'];
			$instance['menu_filter'] = $new_instance['menu_filter'];
			$instance['group_filter'] = $new_instance['group_filter'];
			$instance['tag_special'] = isset($new_instance['tag_special']) ? 1 : 0 ;
			$instance['tag_gluten_free'] = isset($new_instance['tag_gluten_free']) ? 1 : 0 ;
			$instance['tag_vegan'] = isset($new_instance['tag_vegan']) ? 1 : 0 ;
			$instance['tag_vegetarian'] = isset($new_instance['tag_vegetarian']) ? 1 : 0 ;
			$instance['tag_kosher'] = isset($new_instance['tag_kosher']) ? 1 : 0 ;
			$instance['tag_halal'] = isset($new_instance['tag_halal']) ? 1 : 0 ;
			return $instance;
		}
		
		function widget($args, $instance) {  
			extract( $args );

			/* User-selected settings. */
			$title = apply_filters('widget_title', $instance['title'] );
			$omf_url = isset( $instance['omf_url'] ) ? $instance['omf_url'] : false;
			$menuitem_filter = isset( $instance['menuitem_filter'] ) ? $instance['menuitem_filter'] : false;
			$menu_filter = isset( $instance['menu_filter'] ) ? $instance['menu_filter'] : false;
			$group_filter = isset( $instance['group_filter'] ) ? $instance['group_filter'] : false;

			/* Before widget (defined by themes). */
			echo $before_widget;

			/* Title of widget (before and after defined by themes). */
			if ( $title )
				echo $before_title . $title . $after_title;
			
			if ( $omf_url ) {
				$omf_details = _get_menu_details($omf_url);
				
				// Build the tag array 
				$tags = array();
				if ( isset( $instance['tag_special'] ) && $instance['tag_special'] ) { $tags[] = 'special'; }
				if ( isset( $instance['tag_gluten_free'] ) && $instance['tag_gluten_free'] ) { $tags[] = 'gluten_free'; }
				if ( isset( $instance['tag_vegan'] ) && $instance['tag_vegan'] ) { $tags[] = 'vegan'; }
				if ( isset( $instance['tag_vegetarian'] ) && $instance['tag_vegetarian'] ) { $tags[] = 'vegetarian'; }
				if ( isset( $instance['tag_kosher'] ) && $instance['tag_kosher'] ) { $tags[] = 'kosher'; }
				if ( isset( $instance['tag_halal'] ) && $instance['tag_halal'] ) { $tags[] = 'halal'; }

				echo get_items_by_filter( $omf_details, $tags, $menuitem_filter, $menu_filter, $group_filter );
				unset($omf_details);
				
			}

			/* After widget (defined by themes). */
			echo $after_widget;
		}  
	}
	
	class openmenu_menu extends WP_Widget {  
		function openmenu_menu() {  
			/* Widget settings. */
			$widget_ops = array( 'classname' => 'om-menu', 'description' => __('Display a list of Menus and their Menu Groups. Supports local linking.') );

			/* Widget control settings. */
			$control_ops = array( 'width' => 400, 'height' => 350, 'id_base' => 'om-menu' );

		    parent::WP_Widget('om-menu', 'OpenMenu: Menu Listing', $widget_ops, $control_ops );
		}
		
		function form($instance) {  
		     // outputs the options form on admin
		     
			/* Set up some default widget settings. */ 
			$defaults = array( 
							'title' => 'Our Menu', 
							'omf_url' => 'http://', 
							'menu_url' => '', 
							'menu_url_title' => 'See Our Menu', 
							'display_menugroups' => true,
							'menu_filter' => '', 
						);
			$instance = wp_parse_args( (array) $instance, $defaults ); ?>
			<p>
				<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title'); ?></label>
				<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" />
			</p>
			<p>
				<label for="<?php echo $this->get_field_id( 'omf_url' ); ?>"><?php _e('Location of the OpenMenu (URL)'); ?></label>
				<input class="widefat" id="<?php echo $this->get_field_id( 'omf_url' ); ?>" name="<?php echo $this->get_field_name( 'omf_url' ); ?>" value="<?php echo $instance['omf_url']; ?>" />
			</p>
			<p>
				<label for="<?php echo $this->get_field_id( 'menu_filter' ); ?>"><?php _e('Menu Filter'); ?></label>
				<input class="widefat" id="<?php echo $this->get_field_id( 'menu_filter' ); ?>" name="<?php echo $this->get_field_name( 'menu_filter' ); ?>" value="<?php echo $instance['menu_filter']; ?>" />
			</p>
			<p>
				<input class="checkbox" type="checkbox" <?php checked($instance['display_menugroups'], true) ?> id="<?php echo $this->get_field_id('display_menugroups'); ?>" name="<?php echo $this->get_field_name('display_menugroups'); ?>" />
				<label for="<?php echo $this->get_field_id('display_menugroups'); ?>"><?php _e('Display Menu Groups'); ?></label><br />
			</p>
			<p>
				<label for="<?php echo $this->get_field_id( 'menu_url' ); ?>"><?php _e('Location of the menu on this site (URL)'); ?></label>
				<input class="widefat" id="<?php echo $this->get_field_id( 'menu_url' ); ?>" name="<?php echo $this->get_field_name( 'menu_url' ); ?>" value="<?php echo $instance['menu_url']; ?>" />
			</p>
<p>
				<label for="<?php echo $this->get_field_id( 'menu_url_title' ); ?>"><?php _e('Title for the Menu Link'); ?></label>
				<input class="widefat" id="<?php echo $this->get_field_id( 'menu_url_title' ); ?>" name="<?php echo $this->get_field_name( 'menu_url_title' ); ?>" value="<?php echo $instance['menu_url_title']; ?>" />
			</p>
		<?php
		}
		
		function update($new_instance, $old_instance) {  
		     // processes widget options to be saved  
			$instance = $old_instance;

			/* Strip tags (if needed) and update the widget settings. */
			$instance['title'] = strip_tags( $new_instance['title'] );
			$instance['omf_url'] = $new_instance['omf_url'];
			$instance['menu_url'] = $new_instance['menu_url'];
			$instance['menu_url_title'] = $new_instance['menu_url_title'];
			$instance['menu_filter'] = strip_tags($new_instance['menu_filter']);
			$instance['display_menugroups'] = isset($new_instance['display_menugroups']) ? 1 : 0 ;
			
			return $instance;
		}
		
		function widget($args, $instance) {  
			extract( $args );

			/* User-selected settings. */
			$title = apply_filters('widget_title', $instance['title'] );
			$omf_url = isset( $instance['omf_url'] ) ? $instance['omf_url'] : false;
			$menu_url = isset( $instance['menu_url'] ) ? $instance['menu_url'] : false;
			$menu_url_title = isset( $instance['menu_url_title'] ) && !empty($instance['menu_url_title']) ? $instance['menu_url_title'] : 'See Our menu';
			$menu_filter = isset( $instance['menu_filter'] ) ? $instance['menu_filter'] : false;
			$display_menugroups = isset( $instance['display_menugroups'] ) ? $instance['display_menugroups'] : false;
			
			/* Before widget (defined by themes). */
			echo $before_widget;

			/* Title of widget (before and after defined by themes). */
			if ( $title )
				echo $before_title . $title . $after_title;
			
			if ( $omf_url ) {
				$omf_details = _get_menu_details($omf_url);

				echo _get_menus_and_groups( $omf_details, $menu_filter, $display_menugroups);

				unset($omf_details);
				
				if ( $menu_url ) {
					echo '<div id="om_widget_menu_link"><a href="'.$menu_url.'">'.$menu_url_title.'</a></div>';
				}
			}

			/* After widget (defined by themes). */
			echo $after_widget;
		}  
	}
	
	class openmenu_tagcloud extends WP_Widget {  
		function openmenu_tagcloud() {  
			/* Widget settings. */
			$widget_ops = array( 'classname' => 'om-tagcloud', 'description' => __('Display a tag cloud for the cusines types') );

			/* Widget control settings. */
			// $control_ops = array( 'width' => 400, 'height' => 350, 'id_base' => 'om-tagcloud' );
			$control_ops = array( 'id_base' => 'om-tagcloud' );
			
		    parent::WP_Widget('om-tagcloud', 'OpenMenu: Tag Cloud', $widget_ops, $control_ops );
		}
		
		function form($instance) {  
		     // outputs the options form on admin
		     
			/* Set up some default widget settings. */
			$defaults = array( 'title' => 'Cuisine Types' );
			$instance = wp_parse_args( (array) $instance, $defaults ); ?>
			<p>
				<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title'); ?></label>
				<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" />
			</p>
		<?php
		}
		
		function update($new_instance, $old_instance) {  
		     // processes widget options to be saved  
			$instance = $old_instance;

			/* Strip tags (if needed) and update the widget settings. */
			$instance['title'] = strip_tags( $new_instance['title'] );

			return $instance;
		}
		
		function widget($args, $instance) {  
			extract( $args );

			/* User-selected settings. */
			$title = apply_filters('widget_title', $instance['title'] );

			/* Before widget (defined by themes). */
			echo $before_widget;

			/* Title of widget (before and after defined by themes). */
			if ( $title )
				echo $before_title . $title . $after_title;
			
			// tag cloud
			$args = array(
			    'smallest'  => 8, 
			    'largest'   => 22,
			    'unit'      => 'pt', 
			    'number'    => 0,  				// Number of tags to display (0 = all)
			    'format'    => 'flat',
			    'separator' => ' ',
			    'orderby'   => 'name', 
			    'order'     => 'ASC',
			    'link'      => 'view', 
			    'taxonomy'  => 'cuisine_type',
			    'echo'      => true );
			
			echo '<div style="margin-top:10px">';
			wp_tag_cloud( $args );
			echo '</div>';
			
			/* After widget (defined by themes). */
			echo $after_widget;
		}  
	}
	
	class openmenu_specials extends WP_Widget {  
		function openmenu_specials() {  
			/* Widget settings. */
			$widget_ops = array( 'classname' => 'om-specials', 'description' => __('Display a list of specials as defined in an OpenMenu') );

			/* Widget control settings. */
			$control_ops = array( 'width' => 400, 'height' => 350, 'id_base' => 'om-specials' );

		    parent::WP_Widget('om-specials', 'OpenMenu: Specials', $widget_ops, $control_ops );
		}
		
		function form($instance) {  
		     // outputs the options form on admin
		     
			/* Set up some default widget settings. */
			$defaults = array( 
							'title' => 'Our Specials', 
							'omf_url' => 'http://', 
							'menu_filter' => '', 
						);
			$instance = wp_parse_args( (array) $instance, $defaults ); ?>
			<p>
				<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title'); ?></label>
				<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" />
			</p>
			<p>
				<label for="<?php echo $this->get_field_id( 'omf_url' ); ?>"><?php _e('Location of the OpenMenu (URL)'); ?></label>
				<input class="widefat" id="<?php echo $this->get_field_id( 'omf_url' ); ?>" name="<?php echo $this->get_field_name( 'omf_url' ); ?>" value="<?php echo $instance['omf_url']; ?>" />
			</p>
			<p>
				<label for="<?php echo $this->get_field_id( 'menu_filter' ); ?>"><?php _e('Filter - Menu Name to display specials from'); ?></label>
				<input class="widefat" id="<?php echo $this->get_field_id( 'menu_filter' ); ?>" name="<?php echo $this->get_field_name( 'menu_filter' ); ?>" value="<?php echo $instance['menu_filter']; ?>" />
			</p>
		<?php
		}
		
		function update($new_instance, $old_instance) {  
		     // processes widget options to be saved  
			$instance = $old_instance;

			/* Strip tags (if needed) and update the widget settings. */
			$instance['title'] = strip_tags( $new_instance['title'] );
			$instance['omf_url'] = $new_instance['omf_url'];
			$instance['menu_filter'] = $new_instance['menu_filter'];
			
			return $instance;
		}
		
		function widget($args, $instance) {  
			extract( $args );

			/* User-selected settings. */
			$title = apply_filters('widget_title', $instance['title'] );
			$omf_url = isset( $instance['omf_url'] ) ? $instance['omf_url'] : false;
			$menu_filter = isset( $instance['menu_filter'] ) ? $instance['menu_filter'] : false;
			
			/* Before widget (defined by themes). */
			echo $before_widget;

			/* Title of widget (before and after defined by themes). */
			if ( $title )
				echo $before_title . $title . $after_title;
			
			if ( $omf_url ) {
				$omf_details = _get_menu_details($omf_url);

				echo get_items_by_filter( $omf_details, array('special'), false, $menu_filter);
				unset($omf_details);
				
			}

			/* After widget (defined by themes). */
			echo $after_widget;
		}  
	}
	
	class openmenu_restaurant_location extends WP_Widget {  
		function openmenu_restaurant_location() {  
			/* Widget settings. */
			$widget_ops = array( 'classname' => 'example', 'description' => __('Display a restaurant\'s location as defined in an OpenMenu') );

			/* Widget control settings. */
			$control_ops = array( 'width' => 400, 'height' => 350, 'id_base' => 'om-restaurant-location' );

		    parent::WP_Widget('om-restaurant-location', 'OpenMenu: Restaurant Location', $widget_ops, $control_ops );
		}
		
		function form($instance) {  
		     // outputs the options form on admin
		     
			/* Set up some default widget settings. */
			$defaults = array( 
							'title' => 'Our Location', 
							'omf_url' => 'http://',
							'include_hours' => true,
						);
			$instance = wp_parse_args( (array) $instance, $defaults ); ?>
			<p>
				<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title'); ?></label>
				<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" />
			</p>
			<p>
				<label for="<?php echo $this->get_field_id( 'omf_url' ); ?>"><?php _e('Location of the OpenMenu (URL)'); ?></label>
				<input class="widefat" id="<?php echo $this->get_field_id( 'omf_url' ); ?>" name="<?php echo $this->get_field_name( 'omf_url' ); ?>" value="<?php echo $instance['omf_url']; ?>" />
			</p>
			<p>
				<input class="checkbox" type="checkbox" <?php checked($instance['include_hours'], true) ?> id="<?php echo $this->get_field_id('include_hours'); ?>" name="<?php echo $this->get_field_name('include_hours'); ?>" />
				<label for="<?php echo $this->get_field_id('include_hours'); ?>"><?php _e('Include hours'); ?></label><br />
			</p>
		<?php
		}
		
		function update($new_instance, $old_instance) {  
		     // processes widget options to be saved  
			$instance = $old_instance;

			/* Strip tags (if needed) and update the widget settings. */
			$instance['title'] = strip_tags( $new_instance['title'] );
			$instance['omf_url'] = $new_instance['omf_url'];
			$instance['include_hours'] = isset($new_instance['include_hours']) ? 1 : 0 ;
			
			return $instance;
		}
		
		function widget($args, $instance) {  
			extract( $args );

			/* User-selected settings. */
			$title = apply_filters('widget_title', $instance['title'] );
			$omf_url = isset( $instance['omf_url'] ) ? $instance['omf_url'] : false;
			$include_hours = isset( $instance['include_hours'] ) ? $instance['include_hours'] : false;
			
			/* Before widget (defined by themes). */
			echo $before_widget;

			/* Title of widget (before and after defined by themes). */
			if ( $title )
				echo $before_title . $title . $after_title;
			
			// Get the OpenMenu details
			if ( $omf_url ) {
				$omf_details = _get_menu_details($omf_url);

		        echo _get_restaurant_location($omf_details, $include_hours);
				unset($omf_details);

			}

			/* After widget (defined by themes). */
			echo $after_widget;
		}  
	}

	class openmenu_qrcode extends WP_Widget {  
		function openmenu_qrcode() {  
			/* Widget settings. */
			$widget_ops = array( 'classname' => 'om-qrcode', 'description' => __('Displays a QR Code to your mobile site on OpenMenu') );

			/* Widget control settings. */
			$control_ops = array( 'id_base' => 'om-qrcode' );

		    parent::WP_Widget('om-qrcode', 'OpenMenu: QR Code', $widget_ops, $control_ops );
		}
		
		function form($instance) {  
		     // outputs the options form on admin
		     
			/* Set up some default widget settings. */
			$defaults = array( 
							'title' => 'QR Code', 
							'openmenu_id' => '', 
							'qr_size' => '128',
							'include_link' => false,
						);
			$instance = wp_parse_args( (array) $instance, $defaults ); ?>
			<p>
				<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title'); ?></label>
				<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" />
			</p>
			<p>
				<label for="<?php echo $this->get_field_id( 'openmenu_id' ); ?>"><?php _e('OpenMenu ID'); ?></label>
				<input class="widefat" id="<?php echo $this->get_field_id( 'openmenu_id' ); ?>" name="<?php echo $this->get_field_name( 'openmenu_id' ); ?>" value="<?php echo $instance['openmenu_id']; ?>" />
				<br /><span style="font-size:.9em">(use the OpenMenu ID of <em>sample</em> for testing)</span>
			</p>
			<p>
				<label for="<?php echo $this->get_field_id( 'qr_size' ); ?>"><?php _e('Size (max: 500): '); ?></label>
				<input id="<?php echo $this->get_field_id( 'qr_size' ); ?>" name="<?php echo $this->get_field_name( 'qr_size' ); ?>" value="<?php echo $instance['qr_size']; ?>" size="3" />
			</p>
			<p>
				<input class="checkbox" type="checkbox" <?php checked($instance['include_link'], true) ?> id="<?php echo $this->get_field_id('include_link'); ?>" name="<?php echo $this->get_field_name('include_link'); ?>" />
				<label for="<?php echo $this->get_field_id('include_link'); ?>"><?php _e('Include Mobile Site Link'); ?></label><br />
			</p>
		<?php
		}
		
		function update($new_instance, $old_instance) {  
		     // processes widget options to be saved  
			$instance = $old_instance;

			/* Strip tags (if needed) and update the widget settings. */
			$instance['title'] = strip_tags( $new_instance['title'] );
			$instance['openmenu_id'] = $new_instance['openmenu_id'];
			$instance['qr_size'] = $new_instance['qr_size'];
			$instance['include_link'] = isset($new_instance['include_link']) ? 1 : 0 ;
			
			return $instance;
		}
		
		function widget($args, $instance) {  
			extract( $args );

			/* User-selected settings. */
			$title = apply_filters('widget_title', $instance['title'] );
			$openmenu_id = isset( $instance['openmenu_id'] ) ? $instance['openmenu_id'] : false;
			$qr_size = isset( $instance['qr_size'] ) ? $instance['qr_size'] : '128';
			$include_link = isset( $instance['include_link'] ) ? $instance['include_link'] : false;
			
			/* Before widget (defined by themes). */
			echo $before_widget;

			/* Title of widget (before and after defined by themes). */
			if ( $title )
				echo $before_title . $title . $after_title;
			
			if ( $openmenu_id ) {
				// QR Code
				echo '<div style="text-align:center">'.openmenu_qrcode($openmenu_id, $qr_size).'</div>';
				
				if ( $include_link ) {
					echo '<p style="text-align:center"><a href="http://openmenu.com/m/restaurant/'.$openmenu_id.'">'.__('mobile site').'</a></p>';
				}
			}

			/* After widget (defined by themes). */
			echo $after_widget;
		}  
	}
	
// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
// ** Functions for non-widget users:
// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
	// Restaurant Location
	function openmenu_location( $post_id, $title = false ) {
		// ------------------------------------- 
		//  Return a box (widget) of a restaurants locations
		// ------------------------------------- 
		
		if ( empty($post_id) ) {
			return;
		}
		
		$title = ( !empty($title) ) ? $title : __('Our Location') ;
		
		$custom = get_post_custom( $post_id );
		$omf_url = $custom["_omf_url"][0];
		$omf_details = _get_menu_details($omf_url);
		
		?>
		<style type="text/css">
			.om_header { font-weight:bold;font-size:1.2em }
		</style>
		<div class="om_block">
			<div class="om_header"><?php echo $title; ?></div>

<?php 
	if ( empty($omf_details) ) {
		echo '<p>information not available</p>';
	} else {
        echo _get_restaurant_location($omf_details);
        unset($omf_details);
	} 
?>
	   </div>
<?php
	}

	// Specials
	function openmenu_specials( $post_id, $title = 'Our Specials' ) {
		// ------------------------------------- 
		//  Return a box (widget) of specials
		// ------------------------------------- 
		
		if ( empty($post_id) ) {
			return;
		}
		
		$title = ( !empty($title) ) ? $title : __('Our Specials') ;
		
		$custom = get_post_custom( $post_id );
		$omf_url = $custom["_omf_url"][0];
		$omf_details = _get_menu_details($omf_url);
			
		?>
		<style type="text/css">
			.om_header { font-weight:bold;font-size:1.2em }
		</style>
		<div class="om_block">
			<div class="om_header"><?php echo $title; ?></div>

<?php 
	if ( empty($omf_details) ) {
		echo '<p>information not available</p>';
	} else {
		echo get_items_by_filter( $omf_details, array('special'));
		unset($omf_details);
	} 
?>
	   </div>
<?php
	}

// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
// ** Private functions:
// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
	function _get_restaurant_location ( $omf_details, $include_hours = true ) {
		// ------------------------------------- 
		//  Return a a restaurants address
		// ------------------------------------- 
		$location = '';
		
		if ( !empty($omf_details) ) {
			$location .= '<div style="margin-top:5px;">';
			$location .= '<p><strong>Address:</strong><br />';
		    $location .= $omf_details['restaurant_info']['address_1'].'<br />';
		    $location .= $omf_details['restaurant_info']['city_town'].', ';
		    $location .= (!empty($omf_details['restaurant_info']['state_province'])) ? $omf_details['restaurant_info']['state_province'].', ' : '' ;
		    $location .= $omf_details['restaurant_info']['country'].' '.
		    		    $omf_details['restaurant_info']['postal_code'].'<br />'.
		    	        '<strong>Phone: </strong> '.$omf_details['restaurant_info']['phone'];
		    $location .= '<br /></p>';
		    
		    if ($include_hours) {
			    $location .= '<p><strong>Our Hours:</strong><br />';
			
				foreach ($omf_details['operating_days']['printable'] AS $daytime) {
					$location .= $daytime.'<br />';
				}
				
				$location .= '</p>';
			}
			
			$location .= '</div>';
		}
		
		return $location;
	}

	function get_items_by_filter ( $omf_details, $tags = false, $menuitem_filter = false, 
					$menu_filter = false, $group_filter = false ) {
		// ------------------------------------- 
		//  Return a preformatted HTML list of menu items that match set filters
		//    $tags = array of tags to check if valid (special, vegan, halal...)
		// ------------------------------------- 
		
		$options = get_option( 'openmenu_options' );
		$show_prices = ( isset($options['hide_prices']) && $options['hide_prices'] ) ? false : true ;
		
		$items = '';
		if ( isset($omf_details['menus']) ) {
			include_once OPENMENU_PATH.'/toolbox/class-omf-render.php'; 
			$render = new cOmfRender; 
			
			$items .= '<div style="margin-top:5px;">';
			foreach ( $omf_details['menus'] AS $menu ) {
				// Do we have any groups and is this menu not being filtered
				if ( isset($menu['menu_groups']) && 
				   (!$menu_filter || strcasecmp($menu_filter, $menu['menu_name']) == 0) ) {
					foreach ($menu['menu_groups'] AS $group) {
						//  Do we have any items and is this group no being filtered
						if ( isset($group['menu_items']) && 
						   (!$group_filter || strcasecmp($group_filter, $group['group_name']) == 0)) {
							foreach ($group['menu_items'] AS $item) {
								if ( item_matches_text($item, $menuitem_filter) && 
								  item_matches_tag($item, $tags) ) {
									$price = ( $show_prices && !empty($item['menu_item_price']) ) ? ' - '.$render->fix_price($item['menu_item_price'], $menu['currency_symbol']) : '' ;
									$items .= '<p><strong>'.$item['menu_item_name'].
										$price.'</strong> ';
									$items .= '<br />'.$item['menu_item_description'];
									$items .= '</p>';
								}
							}
						}
					}
				}
			}
			$items .= '</div>';
			
			unset($render);
		}
		return $items;
	}
	
	function item_matches_text ($item, $search = false) {
		// ------------------------------------- 
		//  Search a menu item by text
		// ------------------------------------- 
		return !$search || 
				stripos($item['menu_item_name'], $search) !== false || 
				stripos($item['menu_item_description'], $search) !== false;
	}

	function item_matches_tag ($item, $tags = false) {
		// ------------------------------------- 
		//  Search a menu item by an item tag (all tags must match)
		//    if tags is not an array or not set than pass back found
		// ------------------------------------- 
		$retval = false;
		if ( is_array($tags) && !empty($tags) ) {
			foreach ($tags AS $tag) {
				if (isset($item[$tag]) && $item[$tag] ) {
					$retval = true;
				} else { 
					$retval = false; 
					break;
				}
			}
		} else { $retval = true; }
		return $retval;
	}
	
	function _get_menus_and_groups ( $omf_details, $menu_filter = false, $include_groups = false ) {
		// ------------------------------------- 
		//  Return a preformatted HTML list of Menus and Menu Groups
		// ------------------------------------- 
		
		$menus = '';
		if ( isset($omf_details['menus']) ) {
			$menus .= '<div style="margin-top:5px;">';
			foreach ( $omf_details['menus'] AS $menu ) {
				if ( !$menu_filter || strcasecmp($menu_filter, $menu['menu_name']) == 0 ) {
					
					$menus .= '<strong>'.$menu['menu_name'].'</strong>';
					
					if ( $include_groups && isset($menu['menu_groups']) ) {
						$menus .= '<ul>';
						foreach ($menu['menu_groups'] AS $group) {
							$menus .= '<li>'.$group['group_name'].'</li>';
						}
						$menus .= '</ul>';
					}
					$menus .= '<br />';
				}
			}
			$menus .= '</div>';
		}
		return $menus;
	}
?>
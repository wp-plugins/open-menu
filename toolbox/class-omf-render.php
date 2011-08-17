<?php
// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
// ** Open Menu, LLC http://OpenMenu.com
// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
// ** Copyright (C) 2011 OpenMenu, All rights reserved
// **		Authored By: Chris Hanscom
// **
// **		This library is copyrighted software by Open Menu; you can not
// **		redistribute it and/or modify it in any way without expressed written
// **		consent from Open Menu or Author.
// **
// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
// ** Version: 1.2.1
//
// ** Compatible with Open Menu Format v1.5
// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
// ** Includes: 
// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=

// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
// ** Constants: 
// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=

	
// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
// ** Class
// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=

class cOmfRender { 
	
	// How many columns to render the menu in (1 | 2)
	public $columns = 1;
	
	// What do we split the columns on (item | group)
	public $split_on = 'group';
	
	// Determines if the attribute to OpenMenu is displayed
	public $hide_attribute = false;

	// Should we disable cleaning the data with html entities
	public $disable_entities = false;

	// Switches for data display
	public $show_allergy_information = true;
	public $show_calories = false;

	function get_restaurant_information ($omf_details) {
		// ------------------------------------- 
		//  Create a block of restaurant information
		// ------------------------------------- 
		
		$retval = '';
		
		if ( !empty($omf_details) ) {
			$retval .= '<div id="ri_block">';
			$retval .= '<div id="restaurant_name">'.$omf_details['restaurant_info']['restaurant_name'].'</div>';
			$retval .= '<div id="restaurant_type"><strong>Type:</strong> ' . $omf_details['environment_info']['cuisine_type_primary']. '</div>';
			$retval .= '<p>'.$omf_details['restaurant_info']['brief_description'].'</p>';
			
	        $retval .= '<p>';
			$retval .= '<strong>Address:</strong><br />';
			$retval .= $omf_details['restaurant_info']['address_1'].'<br />';
			$retval .= $omf_details['restaurant_info']['city_town'].', '.
					  	$omf_details['restaurant_info']['state_province'] .' '.
						$omf_details['restaurant_info']['postal_code'].'<br />'.
						$omf_details['restaurant_info']['country'];
			$retval .= '</p><p>';
			$retval .= '<strong>Phone: </strong> '.$omf_details['restaurant_info']['phone'].'<br />';
			$retval .= '<strong>Website: </strong> <a href="' . $omf_details['restaurant_info']['website_url'] . '">' . 
						$omf_details['restaurant_info']['website_url'] . '</a>';
			$retval .= '</p><p>';
			$retval .= '<strong>Our Hours:</strong><br />';

			foreach ($omf_details['operating_days']['printable'] AS $daytime) {
				$retval .= $daytime.'<br />';
			}

			$retval .= '</p>';
 
			$retval .= '</div>';
		}
		
		return $retval;
		
	}
	
	function get_menu_from_details ($omf_details, $menu_filter = '', $group_filter = '', 
				$hl_primary = '', $hl_secondary = '') {
		// ------------------------------------- 
		//  Create a menu display from OMF Details
		//   Compatible with OpenMenu Format v1.5 
		//   $menu_filter / $group_filter = filter the display to a specific menu or menu group
		//   $hl_primary / $hl_secondary = Provide highlighting of text
		// ------------------------------------- 

		$retval = '';

		// If an invalid split on is passed force a one column display
		$one_column = ($this->columns == '2') ? false : true ;
		$one_column = (($this->split_on != 'group' && $this->split_on != 'item') || $one_column ) ? true : false ;
		
		// Make sure a proper split on was passed
		$this->split_on = ($this->split_on == 'group' || $this->split_on == 'item') ? $this->split_on : 'group' ;
		
		if ( !empty($omf_details) ) {
			$retval .= '<div id="om_menu">';

		  if ( isset($omf_details['menus']) && !empty($omf_details['menus']) ) {
			foreach ($omf_details['menus'] AS $menu) {

				// Check for a filter
				if ( !$menu_filter || strcasecmp($menu_filter, $menu['menu_name']) == 0 ) {

					// Start a new menu
					$retval .= '<div class="menu_name">';
						if ( !empty($menu['menu_name']) ) {
							$retval .= $this->clean($menu['menu_name']);
						} else {
							$retval .= $this->clean(ucwords($menu['menu_duration_name']));
						}
					// Check for a description
					if ( !empty($menu['menu_description']) ) {
						$retval .= '<br /><span class="sm_norm">'.$menu['menu_description'].'</span>';
					}
					$retval .= '</div><div class="menu_content">';

					// How many groups or items are there in this menu
					//  used for 2 column displays
					$current_group = 1;
					$current_item = 1;
					if ( $this->split_on == 'group' && !$one_column ) {
						$group_count = count($menu['menu_groups']);
					} elseif ( $this->split_on == 'item' && !$one_column ) {
						$item_count = $this->get_menu_item_count($menu);
					}

					foreach ($menu['menu_groups'] AS $group) {
						// Check for a group filter
						if ( !$group_filter || strcasecmp($group_filter, $group['group_name'] ) == 0 ) {
								
							// Should we start the left or right column 
							if ( !$one_column && $this->split_on == 'group' ) {
								if ($current_group == 1) { 
									// Start the left Column
									$retval .= '<div class="left-menu">';
								} elseif ($current_group == (1 + (int)($group_count/2)) ) {
									// Close the left column and start the right
									$retval .= '</div><!-- END left menu -->';
									$retval .= '<div class="right-menu">';
								}
							}
							
							// Start a group
							$retval .= '<h2>'.$this->clean($group['group_name']);
							
							if ( !empty($group['group_description']) ) {
								$retval .= '<br /><span class="sm_norm">'.$group['group_description'].'</span>';
							}
							$retval .= '</h2>'."\n";
							
							if ( !empty($group['menu_items']) ) {
								foreach ($group['menu_items'] AS $item) {
									// Should we start the left or right column 
									if ( !$one_column && $this->split_on == 'item' ) {
										if ($current_item == 1) { 
											// Start the left Column
											$retval .= '<div class="left-menu">';
										} elseif ($current_item == (1 + (int)($item_count/2)) ) {
											// Close the left column and start the right
											$retval .= '</div><!-- END left menu -->';
											$retval .= '<div class="right-menu">';
										}
									}
									
									$is_special = ($item['special'] == 1) ? '<span class="item_tag special">Special</span>' : '' ;
									$is_vegetarian = ($item['vegetarian'] == 1) ? '<span class="item_tag vegetarian">Vegetarian</span>' : '' ;
									$is_vegan = ($item['vegan'] == 1) ? '<span class="item_tag vegan">Vegan</span>' : '' ;
									$is_kosher = ($item['kosher'] == 1) ? '<span class="item_tag kosher">Kosher</span>' : '' ;
									$is_halal = ($item['halal'] == 1) ? '<span class="item_tag halal">Halal</span>' : '' ;
									$tags = $is_special.$is_vegetarian.$is_vegan.$is_kosher.$is_halal;
									
									$price = $this->fix_price($item['menu_item_price'], $menu['currency_symbol']);
									// See if a thumbnail exists
									$thumbnail = '';
									if ( isset($item['menu_item_images']) ) {
										$thumbnail = $this->extract_thumbnail($item['menu_item_images']);
										if ($thumbnail) {
											$thumbnail = '<img class="mi_thumb" src="'.$thumbnail.'" width="32" height="32" />';
										}
									}
										
						            $retval .= '<dl>';
						            
						            $retval .= '<dt class="pepper_' . $item['menu_item_heat_index'] . '">' . $thumbnail . $tags .
						            		 $this->hl_food( $this->clean($item['menu_item_name']), $hl_primary, $hl_secondary ) . '</dt>';
						            $retval .= '<dd class="price">'.$price.'</dd>';

						            if ( $this->show_calories && !empty($item['menu_item_calories']) ) {
						            	$retval .= '<dd class="om_calories">'.$item['menu_item_calories'].' calories</dd>';
						        	}

						            $retval .= '<dd class="description">' . $this->hl_food( $this->clean($item['menu_item_description']), $hl_primary, $hl_secondary ) . '</dd>';

									// Check for Allergy / Allergen information
									if ( $this->show_allergy_information && (!empty($item['menu_item_allergy_information']) ||
											 !empty($item['menu_item_allergy_information_allergens'])) ) {
										$retval .= '<dd class="allergy">';
										
										$retval .= 'Allergy Information: ' . $this->clean($item['menu_item_allergy_information']);
											
										if (!empty($item['menu_item_allergy_information_allergens'])) {
											$retval .= ' [ allergens: ';
											$retval .= $item['menu_item_allergy_information_allergens'] . ' ]';
										}

										$retval .= '</dd>';
									} 
									
						            // Check for item size
						            if ( !empty($item['menu_item_sizes']) && is_array($item['menu_item_sizes']) ) {
						            	$retval .= '<dd class="sizes">';
							            foreach ($item['menu_item_sizes'] AS $size) {
							            	$size_price = ' - '.$this->fix_price($size['menu_item_size_price'], $menu['currency_symbol']);
							            	$retval .= '<span>'.$this->clean($size['menu_item_size_name']).$size_price.'</span>';
							            }
							            $retval .= '</dd>';
							        }
							        
							    	// Check for options
						            if ( isset($item['menu_item_options']) && !empty($item['menu_item_options']) && is_array($item['menu_item_options']) ) {
						            	$retval .= '<dd class="item_options">';
							            foreach ($item['menu_item_options'] AS $option) {
							            	$retval .= '<div><strong>'.$this->clean($option['item_options_name']).'</strong>: ';

							            	 if ( isset($option['option_items']) && !empty($option['option_items']) ) {
							            	 	 foreach($option['option_items'] AS $option_item) { 
							            	 	 	$opt_price = $this->fix_price($option_item['menu_item_option_additional_cost'], $menu['currency_symbol'], ' - ');
							            	 	 	$retval .= $this->clean($option_item['menu_item_option_name']).$opt_price.' | ';
							            	 	 }
							            	 	 // Strip the trailing |
												$retval = rtrim($retval, ' | ');
							            	 }
							            	
							            	$retval .= '</div>';
							            }
							            $retval .= '</dd>';
							        }
							        
							        // close the item
						            $retval .= '</dl>';
						            
									$current_item++;
									
								} // end item
							}
							// Display Group Options
							if ( isset($group['menu_group_options']) && is_array($group['menu_group_options']) ) { 
								foreach($group['menu_group_options'] AS $option) { 
									$retval .= '<div class="goptions">';
									$retval .= '<div class="goptions-title">'.$this->clean($option['group_options_name']);
									if ( !empty($option['menu_group_option_information']) ) {
										$retval .= '<br /><span class="goptions-desc">'.$option['menu_group_option_information'].'</span>';
									}
									$retval .= '</div>';
									
									// Check for Option Items
									if ( isset($option['option_items']) && is_array($option['option_items']) ) { 
										foreach($option['option_items'] AS $option_item) { 
											$opt_price = $this->fix_price($option_item['menu_group_option_additional_cost'], $menu['currency_symbol'], ' - ');
											$retval .= $this->clean($option_item['menu_group_option_name']).$opt_price.' | ';
										}
										// Strip the trailing |
										$retval = rtrim($retval, ' | ');
									}
									
									$retval .= '</div>';
								} 
							}

							// End a group
							if ( $one_column ) {
								$retval .= '<span class="separator big"></span>';
							} elseif ( $this->split_on == 'group' ) {
								$retval .= '<span class="separator small"></span>';
							}

							$current_group++;
							
						} // end group filter
					} // end group
					
					if ( !$one_column ) {
						// Close the menu colums
						if ( $current_group > 1 || $item_group > 1 ) {
							$retval .= '</div><!-- END right menu -->';
						}
						$retval .= '<div class="clear"></div>';
					}
					
					// Close the menu 
					$retval .= '</div><br clear="all" /><!-- END #menu -->';
					
					if ( !$one_column ) {
						$retval .= '<div class="page-break"></div>';
					}
				
				} // end menu filter
				
			} // end menu loop
		} else {
			$retval .= 'There was an error displaying this menu. Please contact <a href="http://openmenu.com" target="_blank">OpenMenu</a> for assistance.';
		}

		$retval .= '</div><!-- #om_menu -->';
		}
		
		return $retval;
	}

// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
// ** Helper functions (publically available)
// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
	function fix_price ( $price, $currency_code, $prefix = '', $suffix = '' ) { 
		// -------------------------------------
		// Handles localization of prices with adding currency symbols
		// -------------------------------------
		$retval = '' ;
		if ( !empty($price) ) {
			$retval = number_format($price, 2);
			$retval = get_currency_symbol($currency_code, $retval, true);
			$retval = $prefix.$retval.$suffix;
		}
		return $retval;
	}
	
// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
// ** Private functions
// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
	private function get_menu_item_count ($menu) { 
		// -------------------------------------
		// Count the items in a all groups for a menu
		// -------------------------------------
		
		$retval = 0;
		if ( !empty($menu['menu_groups']) ) {
			foreach ($menu['menu_groups'] AS $group) {
				$retval += count($group['menu_items']);
			}
		}
		
		return $retval;
	}
	
	
	private function clean ($str) { 
		// -------------------------------------
		// Clean menu information for displaying
		// -------------------------------------
		
		return ($this->disable_entities) ? $str : htmlentities($str, ENT_COMPAT, 'UTF-8');
	}

	private function extract_thumbnail( $item_images, $skip = false  ) {
		// ------------------------------------- 
		//  Attempt to extract a thumbnail image from a menu item's image list
		//    Looks for a Thumbnail for a media type of Web
		// ------------------------------------- 
		if ( $skip ) {
			return '';
		}
		
		$retval = '';
		foreach ($item_images AS $img) {
			if ( strcasecmp($img['image_type'], 'Thumbnail' ) === 0 && 
				 strcasecmp($img['image_media'], 'Web' ) === 0 &&
				 !empty($img['image_url']) ) {
				$retval = $img['image_url'];
			}
		}

		return $retval;
	}
	
	private function hl_food ($text, $primary, $secondary = false, $make_bold = false) {
		// ------------------------------------- 
		//  Highlight food in a passed string
		//   If primary is found then its highlight is passed back
		//   If not then we look at the secondary
		// ------------------------------------- 
		
		if (!empty($primary)) {
			// Highlight the primary
			$count = 0;
			$replace = ($make_bold) ? '<strong>$1</strong>' : '<span class="hl_a">$1</span>' ;
			$new_text = preg_replace("/(".$primary.")/i", $replace, $text, -1, $count);
			if ($count > 0) {
				return $new_text;
			} elseif ( !empty($secondary) ) {
				// Try and highlight the secondary 
				$replace = ($make_bold) ? '<strong>$1</strong>' : '<span class="hl_b">$1</span>' ;
				return preg_replace("/(".$secondary.")/i", $replace, $text);
			} else {
				return $text;
			}
		} else { 
			if (!empty($secondary)) {
				// Highlight the secondary
				$replace = ($make_bold) ? '<strong>$1</strong>' : '<span class="hl_b">$1</span>' ;
				return preg_replace("/(".$secondary.")/i", $replace, $text);
			} else { 
				return $text;
			}
		}
	}

	private function get_currency_symbol ($currency_code, $amount = '', $html_encode = false) {
		
		$currency_symbols = array(
			'AFN' => '',
			'ALL' => 'Lek',
			'DZD' => '',
			'AOA' => '',
			'ARS' => '',
			'AMD' => '',
			'AWG' => 'ƒ',
			'AUD' => '$',
			'AZN' => '',
			'BSD' => '$',
			'BHD' => '',
			'BDT' => '',
			'BBD' => '$',
			'BYR' => '',
			'BZD' => '',
			'BMD' => '$',
			'BTN' => '',
			'BOB' => '$b',
			'BAM' => '',
			'BWP' => '',
			'BRL' => 'R$',
			'BND' => '$',
			'BGN' => '',
			'BIF' => '',
			'KHR' => '',
			'CAD' => '$',
			'CVE' => '',
			'KYD' => '$',
			'CLP' => '$',
			'CNY' => '¥',
			'COP' => '',
			'XOF' => '',
			'XAF' => '',
			'KMF' => '',
			'XPF' => '',
			'CDF' => '',
			'CRC' => '',
			'HRK' => 'kn',
			'CUP' => '',
			'CYP' => '',
			'CZK' => ' Kč',
			'DKK' => ' kr',
			'DJF' => '',
			'DOP' => 'RD$',
			'XCD' => '$',
			'EGP' => '£',
			'SVC' => '$',
			'ERN' => '',
			'EEK' => '',
			'ETB' => '',
			'EUR' => '€',
			'FKP' => '£',
			'FJD' => '',
			'GMD' => '',
			'GEL' => '',
			'GHS' => '',
			'GIP' => '£',
			'XAU' => '',
			'GTQ' => 'Q',
			'GGP' => '',
			'GNF' => '',
			'GYD' => '$',
			'HTG' => '',
			'HNL' => 'L',
			'HKD' => '$',
			'HUF' => 'Ft',
			'ISK' => ' kr',
			'INR' => '',
			'IDR' => '',
			'XDR' => '',
			'IRR' => '',
			'IQD' => '',
			'IMP' => '£',
			'ILS' => '',
			'JMD' => 'J$',
			'JPY' => '¥',
			'JEP' => '£',
			'JOD' => '',
			'KZT' => '',
			'KES' => '',
			'KPW' => '',
			'KRW' => '',
			'KWD' => '',
			'KGS' => '',
			'LAK' => '',
			'LVL' => '',
			'LBP' => '£',
			'LSL' => '',
			'LRD' => '$',
			'LYD' => '',
			'LTL' => ' Lt',
			'MOP' => '',
			'MKD' => '',
			'MGA' => '',
			'MWK' => '',
			'MYR' => '',
			'MVR' => '',
			'MTL' => '',
			'MRO' => '',
			'MUR' => '',
			'MXN' => '$',
			'MDL' => '',
			'MNT' => '',
			'MAD' => '',
			'MZN' => '',
			'MMK' => '',
			'NAD' => '$',
			'NPR' => '',
			'ANG' => '',
			'NZD' => '',
			'NIO' => 'C$',
			'NGN' => '',
			'NOK' => ' kr',
			'OMR' => '',
			'PKR' => '',
			'XPD' => '',
			'PAB' => '',
			'PGK' => '',
			'PYG' => 'Gs',
			'PEN' => '',
			'PHP' => '',
			'XPT' => '',
			'PLN' => '',
			'QAR' => '',
			'RON' => '',
			'RUB' => '',
			'RWF' => '',
			'STD' => '',
			'SHP' => '£',
			'WST' => '',
			'SAR' => '',
			'SPL' => '',
			'RSD' => '',
			'SCR' => '',
			'SLL' => '',
			'XAG' => '',
			'SGD' => '$',
			'SBD' => '',
			'SOS' => 'S',
			'ZAR' => 'R',
			'LKR' => '',
			'SDG' => '',
			'SRD' => '$',
			'SZL' => '',
			'SEK' => ' kr',
			'CHF' => 'CHF',
			'SYP' => '£',
			'TWD' => '',
			'TJS' => '',
			'TZS' => '',
			'THB' => ' ฿',
			'TOP' => '',
			'TTD' => 'TT$',
			'TND' => '',
			'TRY' => 'TL',
			'TMM' => '',
			'TVD' => '$',
			'UGX' => '',
			'UAH' => '',
			'AED' => '',
			'GBP' => '£',
			'USD' => '$',
			'UYU' => '',
			'UZS' => '',
			'VUV' => '',
			'VEB' => '',
			'VEF' => '',
			'VND' => '₫',
			'YER' => '',
			'ZMK' => '',
			'ZWD' => 'Z$'
		);
		
		$symbol_after_amount = array(
				"ISK", "ITL", "LTL", "NOK", "SEK", "THB", "CZK", "DKK"
		);
		
		$currency_symbol = '';
		if ( isset($currency_symbols[$currency_code]) ) {
			$currency_symbol = ( $html_encode ) ? htmlentities($currency_symbols[$currency_code], ENT_COMPAT, 'UTF-8') : $currency_symbols[$currency_code] ; 
		}
		
		// formatted value
		if ( strlen($amount) > 0 ) {
			// Format with the passed amount
			$retval = ( in_array($currency_code, $symbol_after_amount) ) ? $amount.$currency_symbol : $currency_symbol.$amount ;
		} else {
			// Just return any found currency symbol
			$retval = $currency_symbol;
		}
		
		return $retval;
	}
	
} // END CLASS

?>
<?php
// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
// ** OpenMenu, LLC http://openmenu.org
// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
// ** Copyright (C) 2010 - 2012 Open Menu, LLC
// **		
// ** Licensed under the MIT License:
// ** http://www.opensource.org/licenses/mit-license.php
// ** 
// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
// ** Version: 1.0
// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
// ** Compatible with OpenMenu Deals v1.0
// ** 
// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
// ** Class
// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=

class cOmdRender { 
	
	// Site URL
	public $site_url = '/';
	public $coupon_url = 'http://openmenu.com/coupon/';
	
	// Determines the width of the coupon and the units (px or %)
	public $width = '600';
	public $width_unit = 'px';
	
	// Compact view: shows only the headline
	public $compact_view = false;
	
	// determines is the deal is centered
	public $center_deal = true;

	// Should we disable display of an image
	public $show_image = true;
	
	// Should we show the action bar (clip / print / view)
	public $show_action_bar = false;
	public $link_to_new_window = false;
	
	// Should we disable cleaning the data with html entities
	public $disable_entities = false;
	
	function render_deal ($deal) {
		// ------------------------------------- 
		//  Create a deal/coupon from a deal
		// ------------------------------------- 
		
		$retval = '';
		
		if ( !empty($deal) ) {
			$new_window = ($this->link_to_new_window) ? ' target="_blank"' : '' ;
			$center = ($this->center_deal) ? ' center_deal' : '' ;
			
			if ( $this->compact_view ) {
				$retval .= '<div class="deal_compact'.$center.'" style="width:'.$this->width.$this->width_unit.'">'.
							'<a href="'.$this->coupon_url.$deal['deal_uid'].'"' . $new_window . '>'.
							'<img src="'.$this->site_url.'images/ico-16-deal.png" width="16" height="16" alt="Deals / Coupons" title="'.$deal['headline'].'" />'.$deal['headline'].'</a></div>';
			} else {
				$center = ($this->center_deal) ? ' center_deal' : '' ;
				$retval .= '<div class="deal'.$center.'" style="width:'.$this->width.$this->width_unit.'">';
				$retval .= '<div class="deal_details">';
				
				if ($this->show_image) {
					$image_url = $this->set_image($deal['image_url'], $this->width, $this->width_unit);
					$retval .= '<img id="deal_image" src="'.$image_url.'" alt="Coupon for '.$deal['restaurant_name'].'" title="'.$this->clean($deal['headline']).'" />';
				}
				
				if ( $this->show_action_bar ) {
					$retval .= '<div id="action_bar">';
					$retval .= '<img src="'.$this->site_url.'images/ico-16-star.png" width="16" height="16" alt="Clip/Print" title="Clip or Print Deal" /> <a href="'.$this->coupon_url.$deal['deal_uid'].'"' . $new_window . '>Clip / Print</a>';
					$retval .= '</div>';
				}
				
				$retval .= '<div id="deal_local">'.$deal['restaurant_name'] . ': ' . $deal['address_1'] . ', ' . $deal['city_town'].'</div>';
				$retval .= '<h3>'.$this->clean($deal['headline']).'</h3>';
				$retval .= '<p id="deal_desc">'.$this->clean($deal['description']).'</p>';
				
				// determine if there are any date/time/day restrictions 
				if ( !empty($deal['date_start']) || !empty($deal['date_end']) || !empty($deal['hours_start']) 
					|| !empty($deal['hours_end']) || !empty($deal['day_mon']) || !empty($deal['day_tue']) 
					|| !empty($deal['day_wed']) || !empty($deal['day_thu']) || !empty($deal['day_fri']) 
					|| !empty($deal['day_sat']) || !empty($deal['day_sun']) 
					) {
					$retval .= '<div id="deal_validity"><strong>valid: </strong>';
					$retval .= $this->get_date_restrictions($deal).'</div>';
				}
				
				$retval .= (!empty($deal['disclaimer'])) ? '<div id="deal_disclaimer">'.$this->clean($deal['disclaimer']).'</div>' : '' ;
				
				$retval .= '</div></div>';
			}
		} 
		
		return $retval;
		
	}
	
	function render_deals_from_details ($omd, $deal_uid = false) {
		// ------------------------------------- 
		//  Create a deal/coupon list from the details
		//   or show only a single deal
		// ------------------------------------- 
		
		$retval = '';
		foreach ($omd['deals'] AS $deal) {
			if ( !$deal_uid || ($deal_uid && $deal['deal_uid'] == $deal_uid) ) {
				$retval .= $this->render_deal($deal);
			}
		}
		
		return $retval;
	}
// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
// ** Private functions
// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
	private function get_date_restrictions ($deal) { 
		// -------------------------------------
		// Gets the date restrictions as a single string
		// -------------------------------------
		
		$dates = '';
		if ( !empty($deal['date_start']) && !empty($deal['date_end']) ) { 
			$dates = date('M jS, Y', strtotime($deal['date_start'])).' to '.
						 date('M jS, Y', strtotime($deal['date_end'])).' | ';
		} elseif ( !empty($deal['date_start']) ) {
			$dates = 'starting '.date('M jS, Y', strtotime($deal['date_start'])).' | ';
		} elseif ( !empty($deal['date_end']) ) {
			$dates = 'ending '.date('M jS, Y', strtotime($deal['date_end'])).' | ';
		}
		
		$days = '';
		$days .= ( $deal['day_mon'] == '1' ) ? 'Mon - ' : '' ;
		$days .= ( $deal['day_tue'] == '1' ) ? 'Tue - ' : '' ;
		$days .= ( $deal['day_wed'] == '1' ) ? 'Wed - ' : '' ;
		$days .= ( $deal['day_thu'] == '1' ) ? 'Thu - ' : '' ;
		$days .= ( $deal['day_fri'] == '1' ) ? 'Fri - ' : '' ;
		$days .= ( $deal['day_sat'] == '1' ) ? 'Sat - ' : '' ;
		$days .= ( $deal['day_sun'] == '1' ) ? 'Sun - ' : '' ;
		$days = rtrim($days, ' - ');
		$days .= (!empty($days)) ? ' only | ' : '' ;
		
		$hours = '';
		if ( !empty($deal['hours_start']) && !empty($deal['hours_end']) ) { 
			$hours = date('g:ia', strtotime($deal['hours_start'])).' to '.
						 date('g:ia', strtotime($deal['hours_end']));
		}

		$retval = rtrim($dates.$days.$hours, ' | ');
		return $retval;
	}
	
	private function set_image ($image_url, $width, $unit) { 
		// -------------------------------------
		// Sets or returns an image to use
		// -------------------------------------

		if (empty($image_url)) {
			// default ico size: 64 > 500 / 48 for 400 - 500 / 24 < 400
			// Since we don't know the % width we default to 48
			if ( $unit == 'px' ) {
				if ( $width > 500 ) {
					$retval = $this->site_url.'images/ico-64-deal.png';
				} elseif ( $width <= 500 && $width > 400 ) {
					$retval = $this->site_url.'images/ico-48-deal.png';
				} elseif ( $width <= 400 ) {
					$retval = $this->site_url.'images/ico-24-deal.png';
				} else {
					$retval = $this->site_url.'images/ico-64-deal.png';
				}
			} else {
				$retval = $this->site_url.'images/ico-32-deal.png';
			}
		} else {
			$retval = $image_url;
		}
		
		return $retval;
	}

	private function clean ($str) { 
		// -------------------------------------
		// Clean information for displaying
		// -------------------------------------
		return ($this->disable_entities) ? $str : htmlentities($str, ENT_COMPAT, 'UTF-8');
	}
	
} // END CLASS

?>
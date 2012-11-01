<?php
// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
// ** OpenMenu, LLC http://openmenu.org
// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
// ** Copyright (C) 2012 Open Menu, LLC
// **	
// ** Licensed under the MIT License:
// ** http://www.opensource.org/licenses/mit-license.php
// ** 
// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
// ** Version: 1.0
// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
// 
// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
// ** Constants: 
// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
	
// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
// ** Class
// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=

class cOmdReader {
	
	// Set if the data is being used for display purposes on a website
	//   this forces special characters into html entities
	public $use_htmlspecialchars = true;
	
	// Flags to see if we have things
	public $has_deal = false;
	
	// Determine whether disabled deals are included
	public $include_disabled = false;

	function read_file($omd_url) {
		// -------------------------------------
		// Crawl a OpenMenu Deals location and return an array of the values
		// -------------------------------------
		
		// Get the XML contents for the OMD file
		$xml = $this->get_xml_from_url($omd_url);

		$omd = array();
		// Now parse it
		if ($xml) {
			// OMD information
			$omd['omf_uuid'] = $this->_clean(@$xml['openmenu_id']);
			$omd['version'] = $this->_clean(@$xml['version']);

			
		    // Get Deals
		    $omd['deals'] = array();
		    if (isset($xml->deals)) {
		    	$i=0;
		    	foreach ($xml->deals->deal AS $deal) {
		    		// Restaurant information 
					$omd['deals'][$i]['restaurant_name'] = $this->_clean(@$deal->restaurant_name, 255);
					$omd['deals'][$i]['address_1'] = $this->_clean(@$deal->address_1, 120);
					$omd['deals'][$i]['city_town'] = $this->_clean(@$deal->city_town, 50);
		    		
		    		// Deal information
		    		$omd['deals'][$i]['deal_uid'] = $this->_clean(@$deal['uid']);
		    		$omd['deals'][$i]['disabled'] = $this->check_true_attribute(@$deal['disabled']);
		    		$omd['deals'][$i]['created_date'] = $this->_clean(@$deal['created_date']);
		    		$omd['deals'][$i]['force_print'] = $this->check_true_attribute(@$deal['force_print']);
					$omd['deals'][$i]['provider'] = $this->_clean(@$deal->provider, 75);
					$omd['deals'][$i]['provider_url'] = $this->_clean(@$deal->provider_url, 120);
					$omd['deals'][$i]['headline'] = $this->_clean(@$deal->headline, 100);
					$omd['deals'][$i]['description'] = $this->_clean(@$deal->description, 255);
					$omd['deals'][$i]['disclaimer'] = $this->_clean(@$deal->disclaimer, 125);
					$omd['deals'][$i]['date_start'] = $this->_clean(@$deal->date_start);
					$omd['deals'][$i]['date_end'] = $this->_clean(@$deal->date_end);
					$omd['deals'][$i]['hours_start'] = $this->_clean(@$deal->hours_start);
					$omd['deals'][$i]['hours_end'] = $this->_clean(@$deal->hours_end);
					$omd['deals'][$i]['image_url'] = $this->_clean(@$deal->image_url, 120);
					
					// Handle valid days
					if ( isset($deal->days_valid) ) {
						$omd['deals'][$i]['day_mon'] = $this->check_true_attribute(@$deal->days_valid['mon']);
						$omd['deals'][$i]['day_tue'] = $this->check_true_attribute(@$deal->days_valid['tue']);
						$omd['deals'][$i]['day_wed'] = $this->check_true_attribute(@$deal->days_valid['wed']);
						$omd['deals'][$i]['day_thu'] = $this->check_true_attribute(@$deal->days_valid['thu']);
						$omd['deals'][$i]['day_fri'] = $this->check_true_attribute(@$deal->days_valid['fri']);
						$omd['deals'][$i]['day_sat'] = $this->check_true_attribute(@$deal->days_valid['sat']);
						$omd['deals'][$i]['day_sun'] = $this->check_true_attribute(@$deal->days_valid['sun']);
					}
					
					$i++;
		    	}
		    }
		    
		}

		return $omd;
	}


// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
// ** Private functions
// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=

	private function _clean ($data, $length = false) {
		// -------------------------------------
		// Clean crawled data
		// -------------------------------------
		
		// Trim to length if required
		if ($length) {
			$data = substr($data, 0, $length);
		} else {
			$data = (string)$data;
		}
		
		// Return the cleaned and trimmed data
		return ($this->use_htmlspecialchars) ? htmlspecialchars($data) : $data;
	}
	
	private function check_true_attribute ($set_value) {
		// -------------------------------------
		// Check for an attribute for being true (1) or false (0)
		// -------------------------------------
		return ( strcasecmp('1', $set_value) === 0 ) ? 1 : 0 ;
	
	}

	private function get_xml_from_url( $omd_url ) {
		// -------------------------------------
		// Get the XML from the URL
		// -------------------------------------
		
		$xml = false;
		
		// Get the XML contents for the OMF file
		if ( false && function_exists('simplexml_load_file') ) {
			$xml = @simplexml_load_file($omd_url);
		} else {
			if ( function_exists( 'curl_init' ) ) {

				$curl = curl_init ();
				curl_setopt ( $curl, CURLOPT_RETURNTRANSFER, 1 );
				curl_setopt ( $curl, CURLOPT_URL, $omd_url );
				$contents = curl_exec ( $curl );
				curl_close ( $curl );

				if ( $contents )
					$xml = @simplexml_load_string($contents);
				else 
					$xml = false;
					
			} else {
				$xml = file_get_contents ( $omd_url );
				$xml = simplexml_load_string($xml);
			}
		}

		return $xml;
	}
	
} // END CLASS

?>
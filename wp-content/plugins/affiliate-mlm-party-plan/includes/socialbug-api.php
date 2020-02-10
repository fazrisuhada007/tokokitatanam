<?php
	// define any variables needed
    // $api_key = 'cef1183e-4914-373b-f9f9-bce72b3cd918';
	// $api_url = 'http://wordpress.mlm-scientists.com/api/v101/';
    $api_key = get_option( 'sb_api_key' );
	$api_url = get_option( 'sb_api_url' );
	/*api_url is the URL for the backoffice not his website url.  Remember we need to call socialbug server for affiliate data.*/

	// get specific affiliate information
	function get_basic_affiliate_info( $s ){
		global $api_key;
		global $api_url;
//		if ( false !== ( $luv_affiliate = get_transient( 'getaffiliate_'.$s ) ) ) {
//			return $luv_affiliate;
//		}
		$luv_username = $api_url.'GetAffiliate/'.$api_key.'?Username='.$s;
    /*
		$session = curl_init($luv_username);
		curl_setopt($session, CURLOPT_HEADER, FALSE);
		curl_setopt($session, CURLOPT_RETURNTRANSFER, TRUE);
		$affiliate_information = curl_exec($session);
		curl_close($session);
    */
        $result = wp_remote_get( $luv_username );

        if ( !is_wp_error( $result ) ) {
            $affiliate_information = $result['body'];
            $luv_affiliate = json_decode( $affiliate_information );
    //		set_transient( 'getaffiliate_'.$s, $luv_affiliate, 30 * DAY_IN_SECONDS );

            return( $luv_affiliate );
        }
        return null;
	}

	function get_basic_affiliate_by_id( $s ){
		global $api_key;
		global $api_url;
//		if ( false !== ( $luv_affiliate = get_transient( 'getaffiliatebyid_'.$s ) ) ) {
//			return $luv_affiliate;
//		}

		$luv_username = $api_url.'GetAffiliateById/'.$api_key.'?AffiliateId='.$s;
		/*
    $session = curl_init($luv_username);
		curl_setopt($session, CURLOPT_HEADER, FALSE);
		curl_setopt($session, CURLOPT_RETURNTRANSFER, TRUE);
		$affiliate_information = curl_exec($session);
		curl_close($session);
    */
    
        $result = wp_remote_get( $luv_username );
        if ( !is_wp_error( $result ) ) {
            $affiliate_information = $result['body'];
    
            $luv_affiliate = json_decode( $affiliate_information );
            //		set_transient( 'getaffiliatebyid_'.$s, $luv_affiliate, 30 * DAY_IN_SECONDS );

            return( $luv_affiliate );
        }
        return null;
	}

	function get_affiliate_social_links( $s ){
		global $api_key;
		global $api_url;
		$luv_username = $api_url.'GetAffiliateSocialLinks/'.$api_key.'?Username='.$s;
		/*
    $session = curl_init($luv_username);
		curl_setopt($session, CURLOPT_HEADER, FALSE);
		curl_setopt($session, CURLOPT_RETURNTRANSFER, TRUE);
		$affiliate_information = curl_exec($session);
		curl_close($session);
    */
        $result = wp_remote_get( $luv_username );

        if ( !is_wp_error( $result ) ) {
            $affiliate_information = $result['body'];
    
            $luv_social_links = json_decode( $affiliate_information );

            return( $luv_social_links );
        }
        return null;
	}

	function get_parties( $affiliate_name ) {
		global $api_key;
		global $api_url;
		$v_parties = $api_url.'GetAffiliateEvents/'.$api_key.'?Username='.$affiliate_name;
		/*
    $session = curl_init($v_parties);
		curl_setopt($session, CURLOPT_HEADER, FALSE);
		curl_setopt($session, CURLOPT_RETURNTRANSFER, TRUE);
		$v_parties_json = curl_exec($session);
		curl_close($session);
    */
        $result = wp_remote_get( $v_parties );
        if (!is_wp_error($result)) {
            $v_parties_json = $result['body'];
    
            $parties = json_decode( $v_parties_json );

            return( $parties );
        }
        return null;
	}

	function get_states() {
		global $api_key;
		global $api_url;
		$luv_states = $api_url."GetAllStateProvinces/" . $api_key;
		/*
    $session = curl_init($luv_states);
		curl_setopt($session, CURLOPT_HEADER, FALSE);
		curl_setopt($session, CURLOPT_RETURNTRANSFER, TRUE);
		$get_states = curl_exec($session);
		curl_close($session);
    */
        $result = wp_remote_get( $luv_states );

        if ( !is_wp_error( $result ) ) {
            $get_states = $result['body'];
    
            $states = json_decode( $get_states );

            return( $states );
        }
        return null;
	}

	function get_affiliates( $fname, $lname, $email, $city, $state, $zip ) {
		//test if any variables are empty
		if ( empty( $fname ) ) {
			$fname = '';
		}
		if ( empty( $lname ) ) {
			$lname = '';
		}
		if ( empty( $email ) ) {
			$email = '';
		}
		if ( empty( $city ) ) {
			$city = '';
		}
		if ( empty( $state ) ) {
			$state = '';
		}
		if ( empty( $zip ) ) {
			$zip = '';
		}

		global $api_key;
		global $api_url;
		$luv_affiliates = $api_url."GetAffiliateSearch/" . $api_key 
			. "?FirstName=" . $fname
			. "&LastName="  . $lname
			. "&Email="     . $email
			. "&City="      . $city
			. "&StateProvince=" . $state
			. "&ZipCode=" . $zip;

     /*
		$session = curl_init($luv_affiliates);
		curl_setopt($session, CURLOPT_HEADER, FALSE);
		curl_setopt($session, CURLOPT_RETURNTRANSFER, TRUE);
		$get_affils = curl_exec($session);
		curl_close($session);
    */
    
        $result = wp_remote_get( $luv_affiliates );

        if ( !is_wp_error( $result ) ) {
            $get_affils = $result['body'];
    
            $luv_affils = json_decode( $get_affils );
            return( $luv_affils );
        }
        return null;
	}

	function post_luv_data( $data ) {
		global $api_key;
		global $api_url;
		//print_r($data);
		//echo "<br /> <br />";
		$data1 = json_encode( $data );
		//print_r($data1);

		$service_url =  $api_url.'PostRegister/'. $api_key;
    /*
		$curl = curl_init($service_url);
		//curl_setopt($curl, CURLOPT_POST, TRUE);
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($curl, CURLOPT_POSTFIELDS, $data1);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array(                                                   
		    'Content-Type: application/json',                                                                           
		    'Content-Length: ' . strlen($data1))                                                                       
		); 
		$curl_response = curl_exec($curl);
		curl_close($curl);
    */
    
        $args =  array(
			'headers'   => array(                                                   
  		        'Content-Type: application/json',                                                                           
  		        'Content-Length: ' . strlen($data1)                                                                       
  		    ),
			'body' => $data1,
		);
    
        $result = wp_remote_post( $service_url, $args );
        if ( !is_wp_error( $result ) ) {
            $curl_response = $result['body'];
    
            $decoded = json_decode( $curl_response );
            if ( isset( $decoded->response->status) && $decoded->response->status == 'ERROR' ) {
                echo 'it is dying <br />';
                die( 'error occured: ' . $decoded->response->errormessage) ;
            }
            //echo 'response ok -- testing';
            //var_export($decoded->response);
            //print_r($decoded);
            return( $decoded );
        }
        return null;
	}	

	// cleans up data on POST
	function test_input( $data ) {
		$data = trim( $data );
		$data = stripslashes( $data );
		$data = htmlspecialchars( $data );
		return $data;
	}

?>
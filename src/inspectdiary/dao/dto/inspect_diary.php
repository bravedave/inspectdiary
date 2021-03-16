<?php
/*
 * David Bray
 * BrayWorth Pty Ltd
 * e. david@brayworth.com.au
 *
 * MIT License
 *
*/

namespace inspectdiary\dao\dto;

use inspectdiary\config;
use dao\dto\_dto;

class inspect_diary extends _dto {
  public $id = 0;
  public $date = '';
  public $time = '10 am';
  public $property_id = 0;
  public $type = config::inspectdiary_openhome;
  public $inspect_id = 0;
  public $address_street = '';
  public $contact_id = 0;
  public $contact_name = '';
  public $contact_mobile = '';
  public $contact_email = '';
  public $property_contact_id = 0;
  public $property_contact_name = '';
  public $property_contact_mobile = '';
  public $property_contact_email = '';
  public $team = '';

  public function __construct( $row = null ) {
    $this->date = date( 'Y-m-d');
		parent::__construct( $row);

	}

}
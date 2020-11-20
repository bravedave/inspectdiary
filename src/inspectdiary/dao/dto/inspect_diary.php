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

use dao\dto\_dto;

class inspect_diary extends _dto {
  public $id = 0;
  public $date = '';
  public $time = '10 am';
  public $property_id = 0;
  public $type = 'OH Inspect';
  public $inspect_id = 0;
  public $address_street = '';
  public $contact_id = 0;
  public $contact_name = '';
  public $contact_mobile = '';
  public $contact_email = '';

  public function __construct( $row = null ) {
    $this->date = date( 'Y-m-d');
		parent::__construct( $row);

	}

}
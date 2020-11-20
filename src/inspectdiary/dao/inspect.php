<?php
/*
 * David Bray
 * BrayWorth Pty Ltd
 * e. david@brayworth.com.au
 *
 * MIT License
 *
*/

namespace inspectdiary\dao;

use dao\_dao;

class inspect extends _dao {
	protected $_db_name = 'inspect';

	public function Insert( $a) {
		$a[ 'created'] = $a['updated'] = self::dbTimeStamp();
		return parent::Insert( $a);

	}

	public function UpdateByID( $a, $id) {
		$a['updated'] = self::dbTimeStamp();
		return parent::UpdateByID( $a, $id);

	}

}
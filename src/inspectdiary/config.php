<?php
/*
 * David Bray
 * BrayWorth Pty Ltd
 * e. david@brayworth.com.au
 *
 * MIT License
 *
*/

namespace inspectdiary;

use Json;

class config extends \config {
  const label = 'Smoke Alarms 2022';
	const inspectdiary_db_version = 0.2;

  static protected $_INSPECTDIARY_VERSION = 0;

	static protected function inspectdiary_version( $set = null) {
		$ret = self::$_INSPECTDIARY_VERSION;

		if ( (float)$set) {
			$j = Json::read( $config = self::inspectdiary_config());

			self::$_INSPECTDIARY_VERSION = $j->inspectdiary_version = $set;

			Json::write( $config, $j);

		}

		return $ret;

	}

	static function inspectdiary_checkdatabase() {
		if ( self::inspectdiary_version() < self::inspectdiary_db_version) {
      $dao = new dao\dbinfo;
			$dao->dump( $verbose = false);

			config::inspectdiary_version( self::inspectdiary_db_version);

		}

	}

	static function inspectdiary_config() {
		return implode( DIRECTORY_SEPARATOR, [
      rtrim( self::dataPath(), '/ '),
      'inspectdiary.json'

    ]);

	}

  static function inspectdiary_init() {
    $_a = [
      'inspectdiary_version' => self::$_INSPECTDIARY_VERSION,

    ];

		if ( file_exists( $config = self::inspectdiary_config())) {

      $j = (object)array_merge( $_a, (array)Json::read( $config));

      self::$_INSPECTDIARY_VERSION = (float)$j->inspectdiary_version;

		}

	}

}

config::inspectdiary_init();

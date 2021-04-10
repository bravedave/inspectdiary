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
  const label = 'Inspect';
	const inspectdiary_db_version = 0.34;

	const inspectdiary_openhome = 'OH Inspect';
	const inspectdiary_inspection = 'Inspect';

	static $INSPECTDIARY_DEVELOPER = false;
	static $INSPECTDIARY_ENABLE_SINGULAR_INSPECTION = false;
  static $INSPECTDIARY_ROUTE_PEOPLE = 'people';
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
      'inspectdiary_enable_singular_inspection' => self::$INSPECTDIARY_ENABLE_SINGULAR_INSPECTION,
      'inspectdiary_developer' => self::$INSPECTDIARY_DEVELOPER,

    ];

		if ( file_exists( $config = self::inspectdiary_config())) {

      $j = (object)array_merge( $_a, (array)Json::read( $config));

      self::$_INSPECTDIARY_VERSION = (float)$j->inspectdiary_version;
      self::$INSPECTDIARY_ENABLE_SINGULAR_INSPECTION = (float)$j->inspectdiary_enable_singular_inspection;
      self::$INSPECTDIARY_DEVELOPER = (float)$j->inspectdiary_developer;

		}

	}

}

config::inspectdiary_init();

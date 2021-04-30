<?php
/*
 * David Bray
 * BrayWorth Pty Ltd
 * e. david@brayworth.com.au
 *
 * MIT License
 *
*/

class currentUser extends dvc\currentUser {
  static function option( $key, $val = null ) {
    return sys::option( $key, $val);

  }

}

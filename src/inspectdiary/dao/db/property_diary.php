<?php
/*
 * David Bray
 * BrayWorth Pty Ltd
 * e. david@brayworth.com.au
 *
 * MIT License
 *
*/

namespace dao;

$dbc = \sys::dbCheck( 'property_diary');

$dbc->defineField( 'inspect_diary_id', 'bigint');

$dbc->check();

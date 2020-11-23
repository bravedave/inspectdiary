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

$dbc = \sys::dbCheck( 'people');

$dbc->defineField( 'property2sell', 'varchar', 100);

$dbc->check();

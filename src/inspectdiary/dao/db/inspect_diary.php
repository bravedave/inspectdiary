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

$dbc = \sys::dbCheck( 'inspect_diary');
$dbc->defineField('created', 'datetime');
$dbc->defineField('updated', 'datetime');
$dbc->defineField('date', 'date');
$dbc->defineField('time', 'varchar', 10);
$dbc->defineField('property_id', 'bigint', 20 );
$dbc->defineField('type', 'varchar', 20 );
$dbc->defineField('inspect_id', 'bigint', 20 );
$dbc->defineField('auto', 'tinyint');
$dbc->defineField('team', 'varchar');
$dbc->defineField('team_players', 'varchar');

$dbc->defineIndex('date', 'date' );
$dbc->defineIndex('tdpt', 'type ASC, date ASC, property_id ASC, time ASC' );

$dbc->check();


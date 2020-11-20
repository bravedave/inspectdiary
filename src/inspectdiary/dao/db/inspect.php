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

$dbc = \sys::dbCheck( 'inspect');

$dbc->defineField('created', 'datetime');
$dbc->defineField('updated', 'datetime');
$dbc->defineField('date', 'date');
$dbc->defineField('inspect_time', 'varchar', 10);
$dbc->defineField('property_id', 'bigint', 20 );
$dbc->defineField('property_address', 'varchar', 100 );
$dbc->defineField('type', 'varchar', 20 );
$dbc->defineField('person_id', 'bigint', 20 );
$dbc->defineField('name', 'varchar', 100 );
$dbc->defineField('mobile', 'varchar', 100 );
$dbc->defineField('email', 'varchar', 100 );
$dbc->defineField('home_address', 'varchar', 100 );
$dbc->defineField('property2sell', 'varchar', 100 );
$dbc->defineField('comment', 'text' );
$dbc->defineField('notes', 'text');
$dbc->defineField('tasks', 'text');
$dbc->defineField('fu_info', 'varchar', 3 );
$dbc->defineField('fu_info_complete', 'datetime');
$dbc->defineField('fu_task', 'varchar', 3 );
$dbc->defineField('fu_task_complete', 'datetime');
$dbc->defineField('fu_sms', 'varchar', 3 );
$dbc->defineField('fu_sms_complete', 'datetime');
$dbc->defineField('fu_sms_bulk', 'tinyint' );
$dbc->defineField('email_sent', 'datetime');
$dbc->defineField('fu_nsl', 'varchar', 3 );
$dbc->defineField('fu_buyer', 'varchar', 3 );
$dbc->defineField('fu_interested_party', 'varchar', 3 );
$dbc->defineField('fu_neighbour', 'varchar', 3 );
$dbc->defineField('reminder', 'bigint' );
$dbc->defineField('user_id', 'bigint' );
$dbc->defineField('archived', 'tinyint' );
$dbc->defineField('archive_date', 'datetime');
$dbc->defineField('inspection_id', 'bigint' );

$dbc->defineIndex('date', 'date' );

$dbc->check();


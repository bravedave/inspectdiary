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

use dvc\icon;
use strings;	?>

<ul class="nav flex-column mt-2">
	<li class="nav-item h6 pl-3 my-0">Templates</li>
	<li class="nav-item"><a class="nav-link" href="#" id="<?= $_uidSMSTemplate = strings::rand() ?>"><i class="fa fa-fw fa-clone"></i>SMS</a></li>
	<li class="nav-item"><a class="nav-link" href="#" id="<?= $_uidOwnerReportTemplate = strings::rand() ?>"><i class="fa fa-fw fa-clone"></i>Owner Report</a></li>

</ul>
<script>
( _ => $(document).ready( () => {
	$('#<?= $_uidSMSTemplate ?>').on( 'click', function( e) {
		e.stopPropagation(); e.preventDefault();

		_.get.modal( _.url( '<?= $this->route ?>/editTemplate'));

	});

	$('#<?= $_uidOwnerReportTemplate ?>').on( 'click', function( e) {
		e.stopPropagation(); e.preventDefault();

		_.get.modal( _.url( '<?= $this->route ?>/editTemplate?t=ownerreport'));

	});

}))( _brayworth_);
</script>
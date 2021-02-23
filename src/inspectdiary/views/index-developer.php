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

use strings;

$dao = new dao\properties;
$dto = $dao->getByID(1);

if ( !$dto) return;

?>

<ul class="nav flex-column mt-2">
	<li class="nav-item h6 pl-3 my-0">Developer</li>
	<li class="nav-item"><a class="nav-link" href="#" id="<?= $_uid = strings::rand() ?>"><i class="bi bi-file-text"></i> Book On <?= $dto->address_street ?></a></li>

</ul>
<script>
( _ => $(document).ready( () => {
	$('#<?= $_uid ?>').on( 'click', function( e) {
		e.stopPropagation(); e.preventDefault();

		// _.get.modal( _.url( '<?= $this->route ?>/editTemplate?t=ownerreport'));

	});

}))( _brayworth_);
</script>
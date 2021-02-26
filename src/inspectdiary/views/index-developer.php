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

?>

<ul class="nav flex-column mt-2">
	<li class="nav-item h6 pl-3 my-0">Developer</li>
	<li class="nav-item"><a class="nav-link" href="#" id="<?= $_uid = strings::rand() ?>"><i class="bi bi-file-text"></i> Quick Book</a></li>
	<script>
	( _ => {
		$('#<?= $_uid ?>').on( 'click', function( e) {
			e.stopPropagation(); e.preventDefault();

			_.get.modal( _.url( '<?= $this->route ?>/quickbook/'));

		});

	})( _brayworth_);
	</script>

	<?php if ( $dto) {	?>
		<li class="nav-item"><a class="nav-link" href="#" id="<?= $_uid = strings::rand() ?>" data-property="<?= $dto->id ?>"><i class="bi bi-file-text"></i> Book On <?= $dto->address_street ?></a></li>
		<script>
		( _ => {
			$('#<?= $_uid ?>').on( 'click', function( e) {
				e.stopPropagation(); e.preventDefault();

				let _me = $(this);
				let _data = _me.data();

				_.get.modal( _.url( '<?= $this->route ?>/quickbook/?property_id=' + _data.property));

			});

		})( _brayworth_);
		</script>

		<?php
			$dao = new dao\people;
			$dtoP = $dao->getByID(1);

			if ( $dtoP) {	?>
				<li class="nav-item"><a class="nav-link" href="#" id="<?= $_uid = strings::rand() ?>" data-property="<?= $dto->id ?>" data-people="<?= $dtoP->id ?>"><i class="bi bi-file-text"></i> Book <?= $dtoP->name ?> On <?= $dto->address_street ?></a></li>
				<script>
				( _ => {
					$('#<?= $_uid ?>').on( 'click', function( e) {
						e.stopPropagation(); e.preventDefault();

						let _me = $(this);
						let _data = _me.data();

						_.get.modal( _.url( '<?= $this->route ?>/quickbook/?property_id=' + _data.property + '&people_id=' + _data.people));

					});

				})( _brayworth_);
				</script>

		<?php
			}	?>

	<?php }	?>

</ul>
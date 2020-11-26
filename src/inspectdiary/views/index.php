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

<style>
.icon {
	font-size: 1.1rem;
	line-height: 1rem;

}
.fa-fw { width: 1.25rem }
</style>

<ul class="nav flex-column">
	<li><a href="<?php print self::$url ?>"><h6>Inspect Home</h6></a></li>
	<?php	if ( isset( $this->data->seed)) {	?>
		<li class="nav-item">
			<div class="input-group">

				<div class="input-group-prepend">
					<a class="input-group-text" href="<?= strings::url( sprintf( '%s/?filter=lastweek&seed=%s', $this->route, urlencode( $this->data->seed))) ?>" title="previous week">
						<?= icon::get( icon::chevronLeft ) ?>

					</a>

				</div>


				<input type="text" readonly class="form-control text-center" value="<?php print $this->data->scope ?>" />

				<div class="input-group-append">
					<a class="input-group-text" href="<?= strings::url( sprintf( '%s/?filter=nextweek&seed=%s', $this->route, urlencode( $this->data->seed))) ?>" title="next week">
						<?= icon::get( icon::chevronRight ) ?>

					</a>

				</div>

			</div>

		</li>

	<?php	}	// if ( isset( $this->data->seed)) {	?>

	<li class="nav-item"><a class="nav-link" href="#" id="<?= $_addNew = strings::rand() ?>"><i class="icon"><?= icon::get( icon::plus) ?></i>Set New Inspect</a></li>
	<li class="nav-item"><a class="nav-link" href="#" id="<?= $_OpenThisWeek = strings::rand() ?>"><i class="fa fa-fw fa-opera"></i>Open This Week</a></li>
	<li class="nav-item"><a class="nav-link" href="#" id="<?= $_addSMSTemplate = strings::rand() ?>"><i class="fa fa-fw fa-clone"></i>SMS Template</a></li>

</ul>
<script>
( _ => $(document).ready( () => {
	$('#<?= $_addNew ?>').on('click', (e) => {
		e.stopPropagation(); e.preventDefault();

		_.get.modal( _.url( '<?= $this->route ?>/edit/'))
		.then( modal => {
			modal.on( 'success', () => window.location.reload());

		});

	});

	$('#<?= $_OpenThisWeek ?>').on( 'click', function( e) {
		e.stopPropagation();
		_.get.modal( _.url( '<?= $this->route ?>/openThisWeek'))
		.then( modal => {
			modal.on( 'rebook', (e, data) => {
				_.get.modal( _.url( '<?= $this->route ?>/edit/'))
				.then( modal => {

					let form = modal.closest('form');
					let d = _.dayjs( data.date);
					$('input[name="date"]', form).val( d.format('YYYY-MM-DD'))
					$('input[name="time"]', form).val( d.format('h:mm a')).trigger('change');
					$('input[name="type"]', form).val( 'OH Inspect').trigger('change');
					$('input[name="property_id"]', form).val( data.property_id).trigger('resolve');

					console.log( data);
					console.log( $('input[name="property_id"]', form));

					modal.on( 'success', () => window.location.reload());

				});

			});

		});

	})

	$('#<?= $_addSMSTemplate ?>').on( 'click', function( e) {
		e.stopPropagation(); e.preventDefault();

		_.get.modal( _.url( '<?= $this->route ?>/editSMSTemplate'));

	})

}))( _brayworth_);
</script>
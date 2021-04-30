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


				<input type="text" readonly class="form-control text-center" value="<?= $this->data->scope ?>" aria-label="date range">

				<div class="input-group-append">
					<a class="input-group-text" href="<?= strings::url( sprintf( '%s/?filter=nextweek&seed=%s', $this->route, urlencode( $this->data->seed))) ?>" title="next week">
						<?= icon::get( icon::chevronRight ) ?>

					</a>

				</div>

			</div>

		</li>

	<?php	}	// if ( isset( $this->data->seed)) {	?>

	<li class="nav-item"><a class="nav-link" href="#" id="<?= $_addNew = strings::rand() ?>"><i class="bt bi-plus"></i> New Inspection</a></li>
	<li class="nav-item"><a class="nav-link" href="#" id="<?= $_OpenThisWeek = strings::rand() ?>"><i class="bi bi-door-open"></i> Open This Week</a></li>

</ul>
<script>
( _ => {
	$(document).ready( () => {
		$('#<?= $_addNew ?>').on('click', (e) => {
			e.stopPropagation(); e.preventDefault();

			_.get.modal( _.url( '<?= $this->route ?>/edit/'))
			.then( modal => {
				modal
				.trigger('show-add-candidate')
				.on( 'add-candidate', (e,d) => {
					if ( Number( d.id) > 0) {
						_.post({
							url : _.url('<?= $this->route ?>'),
							data : {
								action : 'get-by-id',
								id : d.id
							},

						}).then( d => {
							// console.log( d)
							// console.log( _.url( '<?= $this->route ?>?filter=thisweek&seed=' + d.data.date + '&activate=' + d.data.id + '&add=yes'));
							// http://localhost:1900/inspectdiary?filter=thisweek&seed=2021-04-30&activate=20&add=yes
							window.location.href = _.url( '<?= $this->route ?>?filter=thisweek&seed=' + d.data.date + '&activate=' + d.data.id + '&add=yes');

						});

					}
					else {
						window.location.reload();

					}

				})
				.on( 'success', (e,d) => window.location.reload());

			});

		});

		$(document).on( 'opens-this-week', function( e) {
			e.stopPropagation();
			_.get.modal( _.url( '<?= $this->route ?>/openThisWeek'))
			.then( modal => {
				modal
				.on( 'new', (e, data) => {
					e.stopPropagation();

					_.get.modal( _.url( '<?= $this->route ?>/edit/'))
					.then( modal => {
						modal.on( 'success', () => window.location.reload());

					});

				})
				.on( 'rebook', (e, data) => {
					e.stopPropagation();

					_.get.modal( _.url( '<?= $this->route ?>/edit/'))
					.then( modal => {

						let form = modal.closest('form');
						let d = _.dayjs( data.date);
						$('input[name="date"]', form).val( d.format('YYYY-MM-DD'))
						$('input[name="time"]', form).val( d.format('h:mm a')).trigger('change');
						$('input[name="type"]', form).val( '<?= config::inspectdiary_openhome ?>').trigger('change');
						$('input[name="property_id"]', form).val( data.property_id).trigger('resolve');

						// console.log( data);
						// console.log( $('input[name="property_id"]', form));

						modal.on( 'success', () => window.location.reload());

					});

				});

			});

		})

		$('#<?= $_OpenThisWeek ?>').on( 'click', e => {
			e.stopPropagation();e.preventDefault();
			$(document).trigger('opens-this-week');

		});

	});

})( _brayworth_);
</script>
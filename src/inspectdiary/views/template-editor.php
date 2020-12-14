<?php
/*
 * David Bray
 * BrayWorth Pty Ltd
 * e. david@brayworth.com.au
 *
 * MIT License
 *
*/	?>

<form id="<?= $_form = strings::rand() ?>" autocomplete="off">
	<input type="hidden" name="action" value="<?= $this->data->action ?>" />

	<div class="modal fade" tabindex="-1" role="dialog" id="<?= $_modal = strings::rand() ?>" aria-labelledby="<?= $_modal ?>Label" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content">
				<div class="modal-header bg-secondary text-white py-2">
					<h5 class="modal-title" id="<?= $_modal ?>Label"><?= $this->title ?></h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="form-row mb-2">
						<div class="col">
							<textarea class="form-control"
								rows="10" cols="30" name="text"><?= $this->data->text ?></textarea>

						</div>

					</div>

					<div class="row mt-2">
						<div class="col">
							<div>use <strong>{address}</strong> to represent the address</div>
							<?php if ('owner-report' == $this->data->template) {	?>
							<div>use <strong>{stats}</strong> for summary statistics</div>

							<?php	}	?>

						</div>

					</div>

				</div>

				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
					<button type="submit" class="btn btn-primary">Save</button>

				</div>

			</div>

		</div>

	</div>

	<script>
	( _ => {
		$('#<?= $_modal ?>').on('shown.bs.modal', e => $('textarea[name="text"]','#<?= $_form ?>').focus());

		$(document).ready( () => {
			$('#<?= $_form ?>')
			.on( 'submit', function( e) {
				let _form = $(this);
				let _data = _form.serializeFormJSON();

				// console.table( _data);
				_.post({
					url : _.url( '<?= $this->route ?>'),
					data : _data,

				})
				.then( function( d) {
					_.growl( d);
					if ( 'ack' == d.response) {
						$('#<?= $_modal ?>').trigger('success');

					}
					$('#<?= $_modal ?>').modal('hide');

				});

				return false;

			});

		});

	})( _brayworth_);
	</script>

</form>

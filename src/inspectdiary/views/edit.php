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
use strings;

$dto = $this->data->dto;
?>

<form id="<?= $_form = strings::rand() ?>" autocomplete="off">
	<input type="hidden" name="id" value="<?= (int)$dto->id ?>">
	<input type="hidden" name="property_id" value="<?= (int)$dto->property_id ?>">
	<input type="hidden" name="inspect_id" value="<?= (int)$dto->inspect_id ?>">
	<input type="hidden" name="contact_id" value="<?= (int)$dto->contact_id ?>">
	<input type="hidden" name="action" value="inspect-diary-save">

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
					<div class="row">
						<div class="col">
							<div class="form-row"><!-- date/time -->
								<label class="d-none col-md-block col-md-2 col-form-label" for="<?= $_uid = strings::rand() ?>">Date</label>

								<div class="col-7 col-md-5 pb-2">
									<input type="date" class="form-control" name="date" required id="<?= $_uid ?>"
										value="<?= $dto->date ?>" />

								</div>

								<div class="col-5 col-md-5 pb-2">
									<div class="input-group">
										<div class="input-group-prepend">
											<label class="input-group-text" for="<?= $_uid = strings::rand() ?>">@</label>

										</div>

										<input type="text" class="form-control" name="time" id="<?= $_uid ?>" value="<?= $dto->time ?>" required />

									</div>

								</div>

							</div>

							<div class="form-row mb-2"><!-- type -->
								<label class="col-3 col-md-2 col-form-label" for="<?= $_uid = strings::rand() ?>">Type</label>

								<div class="col-9 col-md-10">
									<select class="form-control" name="type" id="<?= $_uid ?>" <?php if ( $dto->inspect_id > 0) print 'disabled' ?> >
										<option value="Inspect" <?= $dto->type == 'Inspect' ? 'selected' : '' ?>>Buyer Inspection</option>
										<option value="OH Inspect" <?= $dto->type == 'OH Inspect' ? 'selected' : '' ?>>OH Inspection</option>

									</select>

								</div>

							</div>

							<div class="form-row mb-2"><!-- address -->
								<label class="col-3 col-md-2 col-form-label" for="<?= $_uid = strings::rand() ?>">Address</label>

								<div class="col-9 col-md-10">
									<input type="text" name="address_street" class="form-control" id="<?= $_uid ?>"
										value="<?= $dto->address_street ?>" required>

								</div>

							</div>

						</div>

					</div>

					<div class="form-row" id="<?= $_contactDetails = strings::rand() ?>"><!-- contact -->
						<label class="col-sm-3 col-md-2" for="<?= $_uid = strings::rand() ?>">Contact</label>

						<div class="col">
							<div class="form-row mb-2">
								<div class="col">
									<input type="text" name="contact_name" class="form-control" id="<?= $_uid ?>"
										value="<?= $dto->contact_name ?>" placeholder="name">

								</div>

							</div>

							<div class="form-row mb-2">
								<div class="col">
									<div class="input-group">
										<div class="input-group-prepend">
											<div class="input-group-text"><?= icon::get( icon::phone ) ?></div>

										</div>

										<input type="text" name="contact_mobile" class="form-control" placeholder="mobile"
											pattern=".{8,}" title="0000 000 000, minimum 8 character"
											value="<?= $dto->contact_mobile ?>">

									</div>

								</div>

							</div>

							<div class="form-row mb-2">
								<div class="col">
									<div class="input-group">
										<div class="input-group-prepend">
											<div class="input-group-text">@</div>
										</div>

										<input type="text" name="contact_email" class="form-control"
											value="<?= $dto->contact_email ?>" placeholder="@">

									</div>

								</div>

							</div>

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
		_.CheckTimeFormat = function( obj ) {

			if ( ( ! obj ) || ( ! obj.tagName )) {
				// obj is probably an Event ..
				if ( this.tagName )
					if ( /input/i.test(this.tagName)) obj = this;

				// otherwise fall through and error ..

			}

			let strVal = String( obj.value.trim());
			if (strVal == '') return true;

			let sSuffix = 'am';
			let iHours = 0, iMinutes = 0, ampmThreshHold = 0;

			try {
				if ( /p$/i.test( strVal)) {
					sSuffix = 'pm';
					strVal = strVal.replace(/p$/i, '');

				}
				else if ( /a$/i.test( strVal)) {
					strVal = strVal.replace(/a$/i, '');

				}
				else if ( /m$/i.test( strVal)) {
					let eTag = strVal.substring( strVal.length -2, strVal.length ).toLowerCase();
					if ( 'pm' == eTag) {
						sSuffix = 'pm';
						strVal = strVal.substring(0, strVal.length -2 ).trim();

					}
					else if ( 'am' == eTag) {
						strVal = strVal.substring(0, strVal.length -2 ).trim();

					}

				}
				else {
					if ( /number/.test( typeof obj.ampmThreshHold )) {
						ampmThreshHold = obj.ampmThreshHold;

					}
					else {
						ampmThreshHold = 7;

					}

				}

				if ( strVal.indexOf(':') > 0 ) {
					// hours and minutes
					let a = strVal.split(':');
					iHours = parseInt(a[0],10);
					iMinutes = parseInt(a[1],10);

				}
				else if ( strVal.indexOf('.') > 0 ) {
					// hours and minutes
					let a = strVal.split('.');
					iHours = parseInt(a[0],10);
					iMinutes = parseInt(a[1],10);

				}
				else {
					iHours = parseInt( strVal, 10);
					if ( iHours > 24) {
						if ( /^1(0|1|2)/.test( String( iHours))) {
							iHours = strVal.charAt(0) + strVal.charAt(1);
							iMinutes = strVal.substring( 2);

						}
						else {
							iHours = strVal.charAt(0);
							iMinutes = strVal.substring( 1);

						}

					}
					//~ console.log( iHours, ',', iMinutes);

				}


				if ( iHours >= 12 ) {
					sSuffix = 'pm';
					if ( iHours > 12 ) iHours -= 12;

				}
				else if ( ampmThreshHold > iHours ) {
					sSuffix = 'pm';

				}

				let sHours = String(iHours)
				let sMinutes = String(iMinutes)
				obj.value = sHours + ':' + sMinutes.padStart(2,'0') + ' ' + sSuffix;

				return true;

			}
			catch(e) {
				alert( 'unable to parse time format : ' + e.description  );
				return false;

			}

		};

		$('#<?= $_modal ?>').on('shown.bs.modal', e => $('input[name="date"]', '#<?= $_form ?>').focus());

		$('input[name="property_id"]', '#<?= $_form ?>').on('resolve', function(e) {
			let _me = $(this);
			let id = Number( _me.val());

			if ( id > 0) {
				_.post({
					url : _.url('<?= $this->route ?>'),
					data : {
						action : 'get-property-by-id',
						id : id

					},

				}).then( d => {
					if ( 'ack' == d.response) {
						$('input[name="address_street"]', '#<?= $_form ?>').val( d.data.address_street);

					}
					else {
						_.growl( d);

					}

				});

			}

		});

		$(document).ready( () => {
			$('input[name="time"]', '#<?= $_form ?>').on('change', _.CheckTimeFormat);	// cms has a function for this

			$('input[name="address_street"]', '#<?= $_form ?>').autofill({
				autoFocus: true,
				source: _.search.address,
				select: ( e, ui) => {
					let o = ui.item;
					$('input[name="property_id"]', '#<?= $_form ?>').val( o.id);

				},

			});

			$('input[name="contact_name"]', '#<?= $_form ?>').autofill({
				autoFocus: true,
				source: _.search.inspectdiary_people,
				select: ( e, ui) => {
					let o = ui.item;
					$('input[name="contact_id"]', '#<?= $_form ?>').val( o.id);
					$('input[name="contact_mobile"]', '#<?= $_form ?>').val( o.mobile).trigger('change');
					$('input[name="contact_email"]', '#<?= $_form ?>').val( o.email).trigger('change');

				},

			});

			$('input[name="contact_mobile"]', '#<?= $_form ?>').on( 'change', function(e) {
				let _me = $(this);
				$('input[name="contact_email"]', '#<?= $_form ?>')
				.prop( 'required', !(String( _me.val()).IsPhone()))

			});

			$('input[name="contact_email"]', '#<?= $_form ?>').on( 'change', function(e) {
				let _me = $(this);
				$('input[name="contact_mobile"]', '#<?= $_form ?>')
				.prop( 'required', !(String( _me.val()).isEmail()))

			});

			$('select[name="type"]', '#<?= $_form ?>').on( 'change', function(e) {
				let _me = $(this);

				if ( 'Inspect' == _me.val()) {
					$('#<?= $_contactDetails ?>').removeClass( 'd-none');
					$('input[name="contact_name"]', '#<?= $_form ?>').trigger( 'required', true);
					$('input[name="contact_mobile"], input[name="contact_email"]', '#<?= $_form ?>').trigger( 'change');

				}
				else {
					$('#<?= $_contactDetails ?>').addClass( 'd-none');
					$('input[name="contact_name"], input[name="contact_mobile"], input[name="contact_email"]', '#<?= $_form ?>').prop( 'required', false);

				}

			})
			.trigger('change');

			$('#<?= $_form ?>')
			.on( 'submit', function( e) {
				let _form = $(this);
				let _data = _form.serializeFormJSON();

				// console.table( _data);
				_.post({
					url : _.url('<?= $this->route ?>'),
					data : _data,

				}).then( d => {

					if ( 'ack' == d.response) {
						$('#<?= $_modal ?>').trigger('success');

					}
					else {
						_.growl( d);

					}

					$('#<?= $_modal ?>').modal('hide');

				});

				return false;

			});

		});

	})( _brayworth_);
	</script>

</form>

<form>

<script>
$(document).ready( function() {
	return;

	$('#inspect-diary-delete').on('click', function( e) {
		e.stopPropagation(); e.preventDefault();
		_brayworth_.modal({
			title : 'Are you Sure ?',
			text : '<p>This will delete the date.<br />Note if there are inspects for this date, the date will be recreated</p>',
			width: 350,
			buttons : {
				yes : function() {
					_post({
						'format' : 'json',
						'action' : 'delete',
						'id' :  <?php print (int)$dto->id ?>,
					});

				}

			}

		});

	});

});
</script>

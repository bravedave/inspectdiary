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
use theme;

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
				<div class="modal-header <?= theme::modalHeader() ?> py-2">
					<h5 class="modal-title" id="<?= $_modal ?>Label"><?= $this->title ?></h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>

				</div>

				<div class="modal-body">
					<div class="row">
						<div class="col">
							<div class="form-row"><!-- date/time -->
								<label class="d-none d-md-block col-md-2 col-form-label" for="<?= $_uid = strings::rand() ?>">Date</label>

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

							<div class="form-row mb-2 <?php if ( !config::$INSPECTDIARY_ENABLE_SINGULAR_INSPECTION) print 'd-none'; ?>"><!-- type -->
								<label class="col-3 col-md-2 col-form-label" for="<?= $_uid = strings::rand() ?>">Type</label>

								<div class="col-9 col-md-10">
									<select class="form-control" name="type" id="<?= $_uid ?>" <?php if ( $dto->inspect_id > 0) print 'disabled' ?> >
										<option value="<?= config::inspectdiary_inspection ?>" <?= $dto->type == config::inspectdiary_inspection ? 'selected' : '' ?>>Buyer Inspection</option>
										<option value="<?= config::inspectdiary_openhome ?>" <?= $dto->type == config::inspectdiary_openhome ? 'selected' : '' ?>>OH Inspection</option>

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

							<div class="form-row mb-2"><!-- team -->
								<label class="col-3 col-md-2 col-form-label" for="<?= $_uid = strings::rand() ?>">Team</label>

								<div class="col-9 col-md-10">
									<select name="team" class="form-control" id="<?= $_uid ?>">
										<option value=""></option>
									<?php	foreach ($this->data->teams as $team) {
										printf(
											'<option value="%s" %s>%s</option>',
											$team,
											$team == $dto->team ? 'selected' : '',
											$team

										);

									}	?>
									</select>

								</div>

							</div>

							<div class="form-row mb-2"><!-- team members attending -->
								<div class="offset-3 col-9 offset-md-2 col-md-10"
									id="<?= $_uidTeamMembers = strings::rand() ?>"
									data-team_players="<?= $dto->team_players ?>"></div>

							</div>

							<div class="form-row d-none">
								<div class="offset-3 col-9 offset-md-2 col-md-10">
									<div class="form-check">
										<input type="checkbox" class="form-check-input" name="next" value="add candidate" id="<?= $uid = strings::rand() ?>">
										<label class="form-check-label" for="<?= $uid ?>">add candidate</label>

									</div>

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
					<button type="button" class="btn btn-outline-secondary" data-dismiss="modal">close</button>
					<button type="submit" id="<?= $_btnAddCandidate = strings::rand() ?>" class="btn btn-primary d-none">Add Candidate</button>
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

		$('#<?= $_modal ?>')
		.on('show-add-candidate', e => $('#<?= $_btnAddCandidate ?>').removeClass('d-none'))
		.on('shown.bs.modal', e => $('input[name="date"]', '#<?= $_form ?>').focus());

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

			if ( '<?= config::inspectdiary_inspection ?>' == _me.val()) {
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

		$('select[name="team"]', '#<?= $_form ?>').on( 'change', function( e) {
			let _me = $(this);
			let team = _me.val();

			if ( '' == _me.val()) {
				$('#<?= $_uidTeamMembers ?>').html('');

			}
			else {
				$('#<?= $_uidTeamMembers ?>').html('<div class="text-center"><i class="spinner-border spinner-border-sm"></i></div>');

				_.post({
					url : _.url('<?= $this->route ?>'),
					data : {
						action : 'get-team',
						team : _me.val()

					},

				}).then( d => {
					if ( 'ack' == d.response) {
						$('#<?= $_uidTeamMembers ?>').html('');
						let players = String( $('#<?= $_uidTeamMembers ?>').data('team_players'));
						if ( '' != players) {
							players = players.split(',');

						}

						d.data.forEach(player => {

							let uid = 'player_' + parseInt(Math.random() * 1000000);
							let ctrl = $('<input type="checkbox" class="form-check-input" name="team_players[]" id="' + uid + '">');
							let label = $('<label class="form-check-label" for="' + uid + '"></label>');

							ctrl.val( player.id).prop('checked', players.indexOf( String( player.id)) > -1);
							label.html( player.name);

							$('<div class="form-check form-check-inline"></div>').append( ctrl).append( label).appendTo('#<?= $_uidTeamMembers ?>')

						});

					}
					else {
						_.growl( d);

					}

				});

			}

		})
		.trigger( 'change');

		$('#<?= $_btnAddCandidate ?>').on( 'click', function( e) {
			let _me = $(this)
			$('input[name="next"]', '#<?= $_form ?>').prop('checked', true);
			$('input[name="next"]', '#<?= $_form ?>').closest('.form-row').removeClass('d-none');

			_me.addClass('d-none');

		})

		$('#<?= $_form ?>')
		.on( 'submit', function( e) {
			let _form = $(this);
			let _data = _form.serializeFormJSON();

			_.post({
				url : _.url('<?= $this->route ?>'),
				data : _data,

			}).then( d => {

				if ( 'ack' == d.response) {
					if ( 'add candidate' == _data.next) {
						$('#<?= $_modal ?>').trigger('add-candidate', d);

					}
					else {
						$('#<?= $_modal ?>').trigger('success', d);

					}

				}
				else {
					_.growl( d);

				}

				$('#<?= $_modal ?>').modal('hide');

			});

			return false;

		});

		$(document).ready( () => {});

	})( _brayworth_);
	</script>

</form>

<form>

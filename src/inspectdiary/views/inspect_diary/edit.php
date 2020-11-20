<?php
/*
	David Bray
	BrayWorth Pty Ltd
	e. david@brayworth.com.au

	This work is licensed under a Creative Commons Attribution 4.0 International Public License.
		http://creativecommons.org/licenses/by/4.0/

	*/	?>
<form method="POST" action="<?php url::write( 'inspect_diary'); ?>">
	<input type="hidden" name="id" value="<?php print $this->data->id ?>" />
	<input type="hidden" name="property_id" id="property_id" value="<?php print (int)$this->data->property_id ?>" />
	<input type="hidden" name="inspect_id" id="inspect_id" value="<?php print (int)$this->data->inspect_id ?>" />
	<input type="hidden" name="contact_id" id="contact_id" value="<?php print (int)$this->data->contact_id ?>" />

	<div class="container">
		<h1 class="d-none d-print-block"><?php print $this->title ?></h1>

		<div class="form-group row">
			<label class="col-3 col-form-label" for="-inspect-diary-date-">Date</label>

			<div class="col-4 col-md-3">
				<input type="text" id="-inspect-diary-date-" name="date" class="form-control" required autofocus
					value="<?php print date( config::$DATE_FORMAT, strtotime( $this->data->date)) ?>" />

			</div>

			<div>
			</div>

			<div class="col-5 col-md-4">
				<div class="input-group">
					<div class="input-group-prepend">
						<label class="input-group-text" for="-inspect-diary-time-">Time</label>

					</div>

					<input type="text" id="-inspect-diary-time-" name="time" class="form-control" value="<?php print $this->data->time ?>" required />

				</div>

			</div>

		</div><!-- div class="row" -->

		<div class="form-group row">
			<label class="col-3 col-form-label" for="type">Type</label>

			<div class="col-9 col-md-7">
				<select name="type" id="type" class="form-control">
					<option value="Inspect" <?php if ( $this->data->type == 'Inspect' ) print 'selected' ?>>Buyer Inspection</option>
					<option value="OH Inspect" <?php if ( $this->data->type == 'OH Inspect' ) print 'selected' ?>>OH Inspection</option>

				</select>

			</div>

		</div><!-- div class="row" -->

		<div class="form-group row">
			<label class="col-3 col-form-label" for="property_address">Address</label>

			<div class="col-9 col-md-7">
				<input type="text" id="address_street" class="form-control" value="<?php print $this->data->address_street ?>" required />

			</div>

		</div><!-- div class="row" -->

		<div class="form-group row d-none" data-role="contact-details">
			<div class="col-3">
				<label for="contact_name">Contact</label>
			</div>

			<div class="col-9 col-md-6">
				<input type="text" id="contact_name" name="contact_name" class="form-control" value="<?php print $this->data->contact_name ?>" placeholder="name" />

			</div>

		</div><!-- div class="row" -->

		<div class="form-group row d-none" data-role="contact-details">
			<div class="col-3">
				<label for="contact_mobile">&nbsp;m.</label>
			</div>

			<div class="col-9 col-md-6">
				<input type="text" id="contact_mobile" name="contact_mobile" class="form-control" value="<?php print $this->data->contact_mobile ?>" placeholder="mobile" />

			</div>

		</div><!-- div class="row" -->

		<div class="form-group row d-none" data-role="contact-details">
			<div class="col col-3">
				<label for="contact_email">&nbsp;e.</label>
			</div>

			<div class="col-9 col-md-6">
				<input type="text" id="contact_email" name="contact_email" class="form-control" value="<?php print $this->data->contact_email ?>" placeholder="email" />

			</div>

		</div><!-- div class="row" -->

		<div class="row">
			<div class="offset-3 col-9 col-md-6 pt-1">
				<input type="submit" name="action" class="btn btn-primary" value="Save/Update" />
<?php	if ( $this->data->id) {	?>
				<input type="submit" name="action" id="action-delete" class="btn btn-warning" value="delete" />
<?php	}	// if ( $this->data->id)	?>
				<a class="btn btn-light" href="<?php url::write('inspect_diary') ?>">cancel</a>

			</div>

		</div><!-- div class="row" -->

	</div><!-- div class="container" -->

</form>
<script>
$(document).ready( function() {
	$('#address_street').autofill({
		autoFocus: true,
		source: _cms_.search.property,
		select: function( evt, ui) {
			var o = ui.item;
			$('#property_id').val( o.id);

		},

	});

	$('#contact_name').autofill({
		autoFocus: true,
		source: _cms_.search.people,
		select: function( evt, ui) {
			var o = ui.item;
			$('#contact_id').val( o.id);
			$('#contact_mobile').val( o.mobile);
			$('#contact_email').val( o.email);

		},

	});

	var exposeContact = function() {
		$(this).val() == 'Inspect' ?
			$('[data-role="contact-details"]').removeClass( 'd-none') :
			$('[data-role="contact-details"]').addClass( 'd-none');

	}

	exposeContact.call( $('#type').get(0));

	$('#type').on( 'change', exposeContact);
	$('#-inspect-diary-time-').on('blur', CheckTimeFormat);

	(function( d) {
		d.on( 'blur', CheckDateFormat);
		if ( _cms_.currentUser.calendarHelpers) {
			d.calendarHelper();

		}

	})( $('#-inspect-diary-date-'));

	$('#action-delete').on( 'click', function( e) {
		e.stopPropagation(); e.preventDefault();

		//~ alert( 'stop!');
		_brayworth_.modal({
			width : 350,
			title : 'Are you Sure ?',
			text : 'This will delete the date.<br />Note if there are inspects for this date, the date will be recreated',
			buttons : {
				yes : function() {
					this.close();
					_cms_.nav( 'inspect_diary/delete/<?php print $this->data->id ?>');

				}

			}

		});

	})

})
</script>

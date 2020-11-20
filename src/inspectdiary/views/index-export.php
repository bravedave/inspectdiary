<?php
/*
 * David Bray
 * BrayWorth Pty Ltd
 * e. david@brayworth.com.au
 *
 * MIT License
 *
*/	?>
<div class="modal fade" id="<?= $uidExport = strings::rand() ?>" tabindex="-1" role="dialog" aria-labelledby="<?= $uidExport ?>Label" aria-hidden="true">
	<form method="post" action="<?= strings::url( 'inspect') ?>">
		<div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content">
				<input type="hidden" name="action" value="export" />

				<div class="modal-header py-2">
					<h5 class="modal-title" id="<?= $uidExport ?>Label">Export Inspections</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>

					</button>

				</div>

				<div class="modal-body">
					<div class="form-group">
						<label>Start</label>
						<input type="date" class="form-control" name="start" value="<?= date('Y-01-01') ?>" />

					</div>

					<div class="form-group">
						<label>End</label>
						<input type="date" class="form-control" name="end" value="<?= date('Y-m-d') ?>" />

					</div>

				</div>

				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
					<button type="submit" class="btn btn-primary">Export</button>

				</div>

			</div>

		</div>

	</form>

</div>
<script>
$('#<?= $uidExport ?> > form').on( 'submit', function(e) {
	let _form = $(this);

	setTimeout( () => {
		$('#<?= $uidExport ?>').modal( 'hide');

	}, 500);

	return true;

});
</script>

<div class="row">
	<div class="col">
		<ul class="list-unstyled">
			<li><a href="<?php print self::$url ?>"><h6>Inspect Home</h6></a></li>
<?php	if ( isset( $this->data->seed)) {	?>
			<li class="pb-2"><div class="input-group" style="max-width: 280px;" >

				<div class="input-group-prepend">
					<a class="btn btn-light" href="<?php url::write('inspect_diary/?filter=lastweek&seed=' . urlencode( $this->data->seed)) ?>" title="previous week">
						<i class="fa fa-chevron-left"></i>

					</a>

				</div>


				<input type="text" readonly class="form-control text-center" value="<?php print $this->data->scope ?>" />

				<div class="input-group-append">
					<a class="btn btn-light" href="<?php url::write('inspect_diary/?filter=nextweek&seed=' . urlencode( $this->data->seed)) ?>" title="next week">
						<i class="fa fa-chevron-right"></i></a>

					</a>

				</div>

			</div></li>
<?php	}	// if ( isset( $this->data->seed)) {	?>
			<li><a href="#" id="inspect-diary-schedule"><i class="fa fa-fw fa-calendar-plus-o"></i>Set New Inspect</a></li>
			<li class="mt-2"><a href="#" id="-open-this-week-"><i class="fa fa-fw fa-opera"></i>Open This Week</a></li>
			<li class="mt-2"><a href="#" id="inspect-new"><i class="fa fa-fw fa-plus"></i>New Inspect Form</a></li>
			<li class="mt-2"><a href="#" id="add-sms-template"><i class="fa fa-fw fa-clone"></i>SMS Template</a></li>
			<li><a href="#" id="inspect-app-link"><i class="fa fa-fw fa-mobile"></i>App</a></li>

			<li><a href="#" data-toggle="modal" data-target="#<?= $uidExport ?>"><i class="fa fa-fw fa-table"></i>Export</a></li>

		</ul>

	</div>

</div><!-- div class="row" -->

<script>
$(document).ready( function() {
	console.log( '<?= $uidExport ?>');
	$('#add-sms-template').on( 'click', function( e) {
		e.stopPropagation(); e.preventDefault();
		$(this).blur();

		_cms_.getInspectDefaultSMSText().then( _cms_.inspect.report.editSMSTemplate);

	})

	$('#inspect-app-link').on( 'click', function( e) {
		e.stopPropagation(); e.preventDefault();

		$(this).blur();

		_cms_.modal.browser({
			className : 'modal-iphone-style',
			closeIcon : 'fa-power-off',
			url: _brayworth_.url( 'inspect/?frame=yes'),
			width: 385,
			height: 667,

		});


	})

	$('#-open-this-week-').on( 'click', function( e) {
		e.stopPropagation();
		_cms_.inspect.OpenThisWeek();

	})

	$('#inspect-new').on( 'click', function( e) {
		e.stopPropagation(); e.preventDefault();

		if ( e.shiftKey) {
			window.location.href = _brayworth_.url( 'inspect/frame/0');

		}
		else {
			var _el = $(this);
			_el.blur();
			_cms_.modal.browser({ url: _cms_.urlwrite( 'inspect/frame/0'),
				width: 880,
				height: 600,
				onClose : function() {
					window.location.reload();
					//~ refreshRow( _el);
				}

			});

		}

	});

	$('#inspect-diary-schedule').on('click', function(e) {
		e.stopPropagation(); e.preventDefault();

		var modal = _cms_.modal.browser({
			url: _cms_.url( 'inspect_diary/edit/0/frame'),
			width: 620,
			height: 390,
			onOpen : function() {
				console.log( this);

			},

			onLoad : function() {
				this.closeFrame = function() {
					modal.modal('close');

				}

			},

			onClose : function() {
				window.location.reload();

			}

		});

	});

});
</script>
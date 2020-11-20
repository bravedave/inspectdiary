<?php
/*
 * David Bray
 * BrayWorth Pty Ltd
 * e. david@brayworth.com.au
 *
 * MIT License
 *
*/	?>

<div class="container-fluid">
	<div class="row">
		<div class="col">
			<ul class="list-unstyled">
				<li><a href="<?php print self::$url ?>"><h3>Inspect Home</h3></a></li>
<?php	if ( isset( $this->data->seed)) {	?>
				<li><div class="input-group" style="max-width: 200px;" >

				<span class="input-group-addon"><a href="<?php url::write('inspect_diary/?filter=lastweek&seed=' . urlencode( $this->data->seed)) ?>" title="previous week">
					<i class="fa fa-chevron-left" style="cursor: pointer"></i></a></span>

				<input type="text" readonly class="form-control text-center" value="<?php print $this->data->scope ?>" />

				<span class="input-group-addon"><a href="<?php url::write('inspect_diary/?filter=nextweek&seed=' . urlencode( $this->data->seed)) ?>" title="next week">
					<i class="fa fa-chevron-right"></i></a></span>

				</div></li>
<?php	}	// if ( isset( $this->data->seed)) {	?>
				<li>&nbsp;</li>
				<li><a href="#" id="inspect-diary-schedule"><i class="fa fa-fw fa-calendar-plus-o"></i>Set New Inspect</a></li>
				<li>&nbsp;</li>
				<li><a href="#" id="-open-this-week-"><i class="fa fa-fw fa-opera"></i>Open This Week</a></li>
				<li>&nbsp;</li>
				<li><a href="#" id="inspect-new"><i class="fa fa-fw fa-plus"></i>New Inspect Form</a></li>
				<li>&nbsp;</li>
				<li><a href="#" id="add-sms-template"><i class="fa fa-fw fa-clone"></i>SMS Template</a></li>
				<li><a href="#" id="inspect-app-link"><i class="fa fa-fw fa-mobile"></i>App</a></li>
				<li>&nbsp;</li>
				<li><a href="<?php url::write( 'inspection') ?>"><i class="fa fa-fw fa-icon-blank"></i>legacy Inspection</a></li>

			</ul>

		</div>

	</div><!-- div class="row" -->

</div><!-- div class="container" -->
<script>
$(document).ready( function() {
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
			url: _cms_.urlwrite( 'inspect/?frame=yes'),
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
			window.location.href = _cms_.urlwrite( 'inspect/frame/0');

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
			width: 680,
			height: 430,
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
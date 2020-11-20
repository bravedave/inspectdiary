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
</style>

<ul class="nav flex-column">
	<li><a href="<?php print self::$url ?>"><h6>Inspect Home</h6></a></li>
	<?php	if ( isset( $this->data->seed)) {	?>
		<li class="nav-link">
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

	<li class="nav-link"><a class="nav-item" href="#" id="<?= $_addNew = strings::rand() ?>"><i class="icon"><?= icon::get( icon::plus) ?></i>Set New Inspect</a></li>
	<li class="nav-link"><a class="nav-item" href="#" id="-open-this-week-"><i class="fa fa-fw fa-opera"></i>Open This Week</a></li>
	<li class="nav-link"><a class="nav-item" href="#" id="add-sms-template"><i class="fa fa-fw fa-clone"></i>SMS Template</a></li>

	<li class="nav-link"><a class="nav-item" href="#" id="inspect-new">New Inspection</a></li>

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

}))( _brayworth_);

$(document).ready( function() {
	$('#inspect-new').on( 'click', function( e) {
		e.stopPropagation(); e.preventDefault();

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

	});

	$('#add-sms-template').on( 'click', function( e) {
		e.stopPropagation(); e.preventDefault();
		$(this).blur();

		_cms_.getInspectDefaultSMSText().then( _cms_.inspect.report.editSMSTemplate);

	})

	$('#-open-this-week-').on( 'click', function( e) {
		e.stopPropagation();
		_cms_.inspect.OpenThisWeek();

	})

});
</script>
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

use currentUser;
use strings;	?>

<div class="nav flex-column">
	<div class="nav-item">
		<div class="nav-link">
			<div class="form-check">
				<input type="checkbox" class="form-check-input" name="use-this-interfacce"
					<?php if ( !currentUser::option( 'inspect-interface')) print 'checked'; ?>
					id="<?= $_uid = strings::rand()  ?>">

				<label class="form-check-label" for="<?= $_uid ?>">
					Use this Interface
					<div><em class="text-muted">(hide other versions of inspect)</em></div>

				</label>

			</div>

		</div>

	</div>

</div>
<script>
( _ => $(document).ready( () => {
	$('#<?= $_uid ?>').on('change', function(e) {
		let _me = $(this);

		_.post({
			url : _.url('<?= $this->route ?>'),
			data : {
				action : 'set-inspect-interface',
				value : _me.prop('checked') ? '' : 'legacy'

			},

		}).then( d => _.growl( d));

	});

}))( _brayworth_);
</script>
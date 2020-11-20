<h1 class="d-none d-print-block">
	<?php print $this->title ?>

</h1>
<style>
#tblRentalDiary:not(.show-oh) tr[data-type="OH Inspect"] { display: none; }
#tblRentalDiary:not(.show-insp) tr[data-type="Inspect"] { display: none; }
#tblRentalDiary:not(.show-roh) tr[data-type="ROH Inspect"] { display: none; }
@media (max-width: 767px) {
	.short-form-hide { display: none; }

}
</style>


<div class="row">
	<div class="col p-0">
		<form class="form" method="POST" action="<?php url::write( 'inspect_diary'); ?>" data-submit="false">
			<input type="hidden" name="property_id" id="property_id" />
			<input type="hidden" name="contact_id" id="contact_id" />
			<input type="hidden" name="contact_mobile" id="contact_mobile" />
			<input type="hidden" name="contact_email" id="contact_email" />

			<input type="hidden" name="action" id="action" value="Save/Update" />
			<table class="table table-sm table-striped d-none" id="tblRentalDiary">

				<tbody>


				</tbody>

				<tfoot>
					<tr>
						<td colspan="8">
							<a href="#" class="button button-raised" data-role="insect-report-view-all">View All</a>

						</td>

					</tr>

				</tfoot>

			</table>

		</form>

	</div><!-- div class="col-md-6" -->

</div><!-- div class="row" -->
<script>
$(document).ready( function() {
	_cms_.inspect.report.inspectViewers();

	$('tr[data-role="item"]').each( function( i, el) {
		var tr = $(el);
		var editURL = _cms_.url('inspect_diary/edit/' + tr.data( 'id'));

		tr.addClass('pointer').on( 'click', function( e) {
			//~ window.location.href = editURL;
			$('i[data-role="inspect-viewer"]', tr).trigger( 'click', e);

		})
		.on( 'contextmenu', function( e) {
			if ( e.shiftKey)
				return;

			e.stopPropagation(); e.preventDefault();

			_brayworth_.hideContexts();

			var _context = _brayworth_.context();

			_context.append( $('<a href="#"><i class="fa fa-fw fa-table"></i><strong>Inspect Matrix</strong></a>').on( 'click', function( e) {
				_context.close();
				$('i[data-role="inspect-viewer"]', tr).trigger( 'click');

			}));

			_context.append( '<hr />');

			_context.append( $('<a><i class="fa fa-fw fa-pencil"></i>Open Record</a>').attr( 'href', editURL));

			_context.append( $('<a href="#"><i class="fa fa-fw fa-clone"></i>clone record</a>').on('click', function(evt) {
				_context.close();
				e.stopPropagation(); e.preventDefault();

				popNewDiaryEntry({ url: _cms_.urlwrite( 'inspect_diary/edit/0/frame?clone=' + tr.data( 'id'))});

			}));

			if ( tr.data('hasappointment') == 1 ) {
				_context.append( $('<a href="#"><i class="fa fa-fw fa-calendar"></i>edit appointment</a>').on('click', function(e) {
					_context.close();
					e.stopPropagation(); e.preventDefault();

					_cms_.appointment.edit( tr.data('property_diary_id'));

				}));

			}
			else {
				_context.append( $('<a href="#"><i class="fa fa-fw fa-calendar-plus-o"></i>create appointment</a>').on('click', function(evt) {
					_context.close();
					e.stopPropagation(); e.preventDefault();

					var m = _cms_.moment( tr.data('time'));

					var data = {
						inspect_diary_id : tr.data('id'),
						isEntry : true,
						subject : ( tr.data('type') == 'Inspect' ? 'Buy Insp' : 'OH Insp'),
						person : $('td[data-role="contact_name"]', tr).text(),
						person_id : tr.data( 'person_id'),
						location : $('td[data-role="address_street"]', tr).text(),
						property_id : tr.data( 'property_id'),
						startUTC : m.format(),
						endUTC : m.add(30,'minutes').format(),
						use_ajax : 'yes',
						onPost : function() {
							_cms_.inspect.report._id_refresh( tr);

						}

					}
					//~ console.log( data);
					_cms_.appointment.POP.call( data);

				}));

			}

			_context.open( e);

		})

	})

	/*--- ---[ new entry controls ]--- ---*/
	function popNewDiaryEntry( params) {
		var modal;
		var options = {
			url: _cms_.urlwrite( 'inspect_diary/edit/0/frame'),
			width: 680,
			height: 460,
			onLoad : function() {
				this.closeFrame = function() {
					modal.modal('close');

				}

			},

			onClose : function() {
				window.location.reload();

			}

		}

		$.extend( options, params);
		modal = _cms_.modal.browser(options);

	};

	$('i[data-role="new-inspect-diary-control"]').css({'cursor':'pointer'})
	.on('click', function( e) { popNewDiaryEntry(); })
	.on( 'keydown', function( e) {
		if ( e.keyCode == _cms_.keyCode.space) {
			popNewDiaryEntry();
			return ( false);

		}

	});

	/*--- ---[ end : new entry controls ]--- ---*/


	$('#tblRentalDiary').removeClass( 'd-none');

})
</script>

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

$_report = strings::rand();
$_candidates = strings::rand();
$_candidate = strings::rand();

?>
<style>
  .border-1 { border-width: .1rem!important; }
  .border-2 { border-width: .2rem!important; }
  .border-3 { border-width: .3rem!important; }
  .border-4 { border-width: .4rem!important; }

  @media screen and ( max-width: 767px) {
    .navbar-brand { max-width: 55%; font-size: 1rem; padding-top: .5rem;}

  }
</style>

<div id="<?= $_collapse = strings::rand() ?>" style="margin-left: -15px; margin-right: -15px;">
  <div class="collapse" id="<?= $_candidate ?>" data-parent="#<?= $_collapse ?>">
    <nav class="<?= $this->theme['navbar'] ?> border-bottom border-3 py-1" style="padding-left: 15px; padding-right: 15px;">
      <div class="d-flex flex-fill">
        <div class="navbar-brand mr-auto text-truncate" id="<?= $_title = strings::rand() ?>-candidate">Candidate</div>

        <button type="button" class="btn <?= $this->theme['navbutton'] ?> d-none" data-toggle="collapse"
          id="<?= $_docsButton = strings::rand() ?>"><?= icon::get( icon::documents ) ?><span class="d-none d-md-inline">docs</span></button>
        <button type="button" class="btn <?= $this->theme['navbutton'] ?> d-none" aria-label="context menu"
          id="<?= $_contextCandidate = strings::rand() ?>"><?= icon::get( icon::menu_up ) ?></button>
        <button type="button" class="btn <?= $this->theme['navbutton'] ?>" title="add inspection"
          id="<?= $_addInspection = strings::rand() ?>-candidate"><?= icon::get( icon::person_plus ) ?></button>
        <button type="button" class="btn <?= $this->theme['navbutton'] ?>" aria-label="Close" data-toggle="collapse"
          id="<?= $_candidate ?>-goto-list"><?= icon::get( icon::list ) ?></button>

      </div>

    </nav>

    <div id="<?= $_candidate ?>content" class="container-fluid pt-1"></div>

  </div>

  <div class="collapse" id="<?= $_candidates ?>" data-parent="#<?= $_collapse ?>">
    <nav class="<?= $this->theme['navbar'] ?> py-1" style="padding-left: 15px; padding-right: 15px;">
      <div class="d-flex flex-fill">
        <div class="navbar-brand mr-auto" id="<?= $_title ?>-candidates">Inspection</div>
        <button type="button" class="btn <?= $this->theme['navbutton'] ?> d-none" aria-label="context menu"
          id="<?= $_context = strings::rand() ?>"><?= icon::get( icon::menu_up ) ?></button>
        <button type="button" class="btn <?= $this->theme['navbutton'] ?>"
          title="add inspection"
          id="<?= $_addInspection ?>-candidates"><?= icon::get( icon::person_plus ) ?></button>
        <button type="button" class="btn <?= $this->theme['navbutton'] ?>" aria-label="Close" data-toggle="collapse"
          data-target="#<?= $_report ?>"><?= icon::get( icon::x ) ?></button>

      </div>

    </nav>

    <div id="<?= $_candidates ?>content" class="container-fluid"></div>

  </div>

  <div class="collapse" id="<?= $_report ?>" data-parent="#<?= $_collapse ?>">
    <?php
      $_uid = strings::rand();
      if ( false) { ?>
      <div class="form-row mb-2">
        <div class="col">
          <div class="form-check form-check-inline">
            <input type="checkbox" class="form-check-input" id="<?= $_uid ?>chk-open-homes" />
            <label class="form-check-label" for="<?= $_uid ?>chk-open-homes">
              OH

            </label>

          </div>

          <div class="form-check form-check-inline">
            <input type="checkbox" class="form-check-input" id="<?= $_uid ?>chk-inspections" />
            <label class="form-check-label" for="<?= $_uid ?>chk-inspections">
              Inspections

            </label>

          </div>

          <div class="form-check form-check-inline">
            <input type="checkbox" class="form-check-input" id="<?= $_uid ?>chk-rental-open-homes" />
            <label class="form-check-label" for="<?= $_uid ?>chk-rental-open-homes">
              Rental OH

            </label>

          </div>

        </div>

      </div>
      <script>
      ( _ => $(document).ready( () => {
        (params => {
          let j = {
            OH : true,
            inspections : true,
            ROH : true,

          }

          $.extend( j, JSON.parse( params));

          $('#<?= $_uid ?>chk-open-homes').prop( 'checked', j.OH).trigger( 'change');
          $('#<?= $_uid ?>chk-inspections').prop( 'checked', j.inspections).trigger( 'change');
          $('#<?= $_uid ?>chk-rental-open-homes').prop( 'checked', j.ROH).trigger( 'change');

        })( localStorage.getItem('inspect_diary'));

        let setDefault = () => {
          let j = {
            OH : $('#<?= $_uid ?>chk-open-homes').prop( 'checked'),
            inspections : $('#<?= $_uid ?>chk-inspections').prop( 'checked'),
            ROH : $('#<?= $_uid ?>chk-rental-open-homes').prop( 'checked'),

          }

          localStorage.setItem( 'inspect_diary', JSON.stringify( j));

        };

        let setVisibility = () => {
          $('#<?= $_uid ?>chk-open-homes').prop('checked') ?
            $('#<?= $_uid ?>RentalDiary').addClass('show-oh') :
            $('#<?= $_uid ?>RentalDiary').removeClass('show-oh');

          $('#<?= $_uid ?>chk-inspections').prop('checked') ?
            $('#<?= $_uid ?>RentalDiary').addClass('show-insp') :
            $('#<?= $_uid ?>RentalDiary').removeClass('show-insp');

          $('#<?= $_uid ?>chk-rental-open-homes').prop('checked') ?
            $('#<?= $_uid ?>RentalDiary').addClass('show-roh') :
            $('#<?= $_uid ?>RentalDiary').removeClass('show-roh');

        }

        $('#<?= $_uid ?>chk-open-homes').on( 'change', () => { setVisibility(); setDefault();});
        $('#<?= $_uid ?>chk-inspections').on( 'change', () => { setVisibility(); setDefault(); });
        $('#<?= $_uid ?>chk-rental-open-homes').on( 'change', () => { setVisibility(); setDefault(); });

        setVisibility();

      }))( _brayworth_);
      </script>

    <?php } ?>

    <div class="container-fluid">
      <div class="row" id="<?= $_uid ?>RentalDiary">
        <div class="col">
          <div class="row bg-light border-bottom">
            <div class="d-none d-md-block col-1 text-center">
              <i class="fa fa-calendar-plus-o" data-role="new-inspect-diary-control" title="add new"></i>

            </div>
            <div class="col-3">date</div>
            <div class="col">address</div>
            <div class="col-2">
              <div class="row">
                <div class="col text-center">type</div>
                <div class="col d-none d-md-block text-center">no.</div>

              </div>

            </div>

            <div class="col d-none d-md-block">person</div>

          </div>

          <?php
            $i = 0;
            foreach ( $this->data->data as $dto) {	?>
            <div class="row py-1 border-bottom"
              data-role="item"
              data-id="<?= $dto->id ?>"
              data-property_id="<?= $dto->property_id ?>"
              data-address_street=<?= json_encode( $dto->address_street, JSON_UNESCAPED_SLASHES) ?>
              data-pretty_street=<?= json_encode( strings::GoodStreetString( $dto->address_street), JSON_UNESCAPED_SLASHES) ?>
              data-short_time="<?= rtrim( strings::AMPM( $dto->time), 'm') ?>"
              data-person_id="<?= $dto->contact_id ?>"
              data-inspect_id="<?= $dto->inspect_id ?>"
              data-inspections="<?= $dto->inspections ?>"
              data-type="<?= $dto->type ?>">

              <div class="d-none d-md-block col-1 text-center small"><?= ++$i ?></div>
              <div class="col-3">
                <div class="row">
                  <div class="col-md-6 pr-1" data-field="date">
                    <?= strings::asShortDate( $dto->date) ?>

                  </div>
                  <div class="col-md-6 pr-1" data-field="time"><?= rtrim( strings::AMPM( $dto->time), 'm') ?></div>

                </div>

              </div>

              <div class="col-7 col-md-3">
                <div class="row">
                  <div class="col">
                    <div class="" data-field="street">
                      <?= $dto->address_street ?>

                    </div>

                  </div>

                </div>

                <div class="row d-md-none">
                  <div class="col text-muted">
                    <div class="text-truncate" data-field="contact_name">
                      <?php  if ( $dto->type == 'Inspect') print $dto->contact_name; ?>

                    </div>

                  </div>

                </div>

              </div>

              <div class="col-2"><!-- type -->
                <div class="row">
                  <div class="col text-center" data-field="type">
                    <?php
                      if ( 'OH Inspect' == $dto->type)
                        print 'OH';
                      elseif ( 'Inspect' == $dto->type)
                        print 'Insp';
                      else
                        print $dto->type;

                    ?>
                  </div>

                  <div class="col text-center" inspections><?= $dto->inspections ?></div>

                </div>

              </div>

              <div class="d-none d-md-block col text-truncate"><?php
                if ( $dto->type == 'Inspect') print $dto->contact_name;

              ?></div>

            </div>
          <?php
            }	// foreach ( $this->data as $dto)	?>

        </div>

      </div>

    </div>

  </div>

</div>

<!-- style>
#<?= $_uid ?>RentalDiary div[data-role="item"]:nth-of-type(odd) {
    background-color: rgba(0,0,0,.05)
}
</style -->

<script>
( _ => {
  $('#<?= $_collapse ?>')
  .on( 'hidden.bs.collapse', function(e) {
    let _el = $(e.target);
    let id = _el.attr('id');

    if ( '<?= $_candidates ?>' == id) {
      setTimeout(() => {
        $('#<?= $_candidates ?>content').html('');
        $('#<?= $_context ?>').addClass( 'd-none').off( 'click');
        // console.log( 'remove people content');

      }, 50);

    }
    else if ( '<?= $_candidate ?>' == id) {
      setTimeout(() => {
        $('#<?= $_candidate ?>content').html('');
        $('#<?= $_contextCandidate ?>').addClass( 'd-none').off( 'click');
        // console.log( 'remove candidate content');

      }, 50);

    }

  });

  let resetSaveState = () => {
    return new Promise( resolve => {
      let el = $('#<?= $_candidate ?> .navbar');
      el.removeClass('border-primary border-warning border-danger border-success');
      resolve( el);

    });

  };

  window.documentsButton = () => $('#<?= $_docsButton ?>');

  window.gotoPeopleList = () => {
    $('#<?= $_candidates ?>content').trigger( 'refresh');
    $('#<?= $_candidates ?>').collapse( 'show');


  };

  $('#<?= $_candidate ?>-goto-list').on( 'click', function( e) {
    e.stopPropagation();
    _.hideContexts();
    gotoPeopleList();

  });

  $('#<?= $_candidate ?>')
  .on( 'add-inspection', function(e) {
    e.stopPropagation();

    let type = $('#<?= $_candidates ?>content').data('type');
    if ( 'Inspect' == String( type)) {
      _.get.modal( _.url( '<?= $this->route ?>/noinspectoninspect'));

    }
    else {

      let inspectionID = $('#<?= $_candidates ?>content').data('id');

      $(this).collapse( 'show');

      $('#<?= $_candidate ?>content')
      .data( 'inspect_diary_id', inspectionID)
      .trigger( 'add-inspection');

    }

  })
  .on( 'load-candidate', function(e) {
    let _me = $(this);
    let _data = _me.data();

    resetSaveState();
    _me.collapse( 'show');

    $('#<?= $_candidate ?>content')
    .data('id', _me.id)
    .trigger( 'refresh');

  });

  $('#<?= $_candidate ?>content')
  .on( 'add-inspection', function(e) {
    e.stopPropagation();

    let _me = $(this);
    let _data = _me.data();

    _me.append('<div class="bg-white d-flex position-absolute w-100" style="top: 56px;left: 0;z-index: 1000;height: calc(100%);opacity: .5;"><div class="spinner-border mx-auto my-5" role="status"><span class="sr-only">Loading...</span></div></div>');

    _.get( _.url( '<?= $this->route ?>/inspection/?idid=' + _data.inspect_diary_id))
    .then( html => _me.html( html));

  })
  .on( 'refresh', function(e) {
    e.stopPropagation();

    let _me = $(this);
    let _data = _me.data();

    _me.html('<div class="d-flex"><div class="spinner-border mx-auto my-5" role="status"><span class="sr-only">Loading...</span></div></div>');

    _.get( _.url( '<?= $this->route ?>/inspection/' + _data.id))
    .then( html => _me.html( html));

  });

  $('#<?= $_candidates ?>content')
  .on( 'refresh', function(e) {

    let _me = $(this);
    _me.html('<div class="d-flex"><div class="spinner-border mx-auto my-5" role="status"><span class="sr-only">Loading...</span></div></div>');

    let _data = _me.data();

    _.get( _.url( '<?= $this->route ?>/inspects/' + _data.id))
    .then( html => _me.html( html));

  })
  .on( 'view-inspection', function(e, id) {
    e.stopPropagation();

    $('#<?= $_candidate ?>content').data('id', id);
    $('#<?= $_candidate ?>content').trigger( 'load-candidate');

  });

  $('#<?= $_addInspection ?>-candidate, #<?= $_addInspection ?>-candidates').on( 'click', e => {
    e.stopPropagation();
    _.hideContexts();

    $('#<?= $_candidate ?>').trigger('add-inspection');

  });

  window.confirmDeleteAction = () => {
    return new Promise( resolve => {
      _.ask({
        headClass: 'text-white bg-danger',
        text: 'Are you sure ?',
        title: 'Confirm Delete',
        buttons : {
          yes : function(e) {
            $(this).modal('hide');
            resolve();

          }

        }

      });

    });

  };

  window.deleteInspection = id => {
    return new Promise( resolve => {
      _.post({
        url : _.url('<?= $this->route ?>'),
        data : {
          action : 'inspection-delete',
          id : id
        },

      }).then( d => {
        if ( 'ack' == d.response) {
          resolve();

        }
        else {
          _.growl( d);

        }

      });

    });

  };

  window.refreshPeople = () => $('#<?= $_candidates ?>content').trigger('refresh');

  window.setPeopleContext = context => {
    $('#<?= $_context ?>')
    .removeClass( 'd-none')
    .off( 'click')
    .on( 'click', context);

  };

  window.setPersonContext = context => {
    $('#<?= $_contextCandidate ?>')
    .removeClass( 'd-none')
    .off( 'click')
    .on( 'click', context);

  };

  window.viewInspection = id => $('#<?= $_candidates ?>content').trigger('view-inspection', id);

  $(document)
  .on( 'candidate-saved', e => resetSaveState().then( el => el.addClass('border-success')))
  .on( 'candidate-saving', e => resetSaveState().then( el => el.addClass('border-primary')))
  .on( 'candidate-saving-error', e => resetSaveState().then( el => el.addClass('border-danger')))
  .on( 'candidate-unsaved', e => resetSaveState().then( el => el.addClass('border-warning')))
  .on( 'add-inspection', e => $('#<?= $_candidate ?>').trigger('add-inspection'))
  .on( 'change-inspection-of-inspect', function( e, id) {
    e.stopPropagation();

    _.get.modal( _.url( '<?= $this->route ?>/changeInspectionofInspect/' + id))
    .then( modal => modal.on( 'success', e => {
      $('#<?= $_candidates ?>content').trigger('refresh');
      $(document).trigger('invalidate-counts');


    }));

  })
  .on( 'edit-inspection-by-id', (e, id) => {
    $('#<?= $_report ?>').collapse('show');

    let found = false;
    $('div[data-role="item"]', '#<?= $_uid ?>RentalDiary').each( ( i, row) => {
      let _row = $(row);
      let _data = _row.data();

      if ( id == _data.id) {
        _row.trigger('edit');

        found = true;
        return false;

      }

    });

  })
  .on( 'invalidate-counts', ( e) => {
    $('div[data-role="item"]', '#<?= $_uid ?>RentalDiary')
    .each( ( i, row) => $('[inspections]', row).addClass( 'text-warning'));

  })
  .on( 'load-inspects', ( e, data) => {

    // console.log( data);
    $('#<?= $_title ?>-candidates, #<?= $_title ?>-candidate').html( data.pretty_street + '&nbsp;&nbsp;' + data.short_time);

    $('#<?= $_candidates ?>content')
    .data('id', data.id)
    .data('type', data.type)
    .trigger( 'refresh');

    $('#<?= $_candidates ?>').collapse('show');

  })
  .on( 'refresh-inspects', ( e) => $('#<?= $_candidates ?>content').trigger( 'refresh'))
  .ready( () => {
    $('div[data-role="item"]', '#<?= $_uid ?>RentalDiary').each( ( i, row) => {

      $(row)
      .addClass('pointer')
      .on( 'click', function( e) {
        e.stopPropagation();e.preventDefault();

        let _me = $(this);
        let _data = _me.data();

        if ( _data.inspect_id > 0) {
          _me.trigger( 'view-inspection');

        }
        else {
          $(document).trigger( 'load-inspects', _data);

        }
        // console.log( _data);

      })
      .on( 'contextmenu', function( e) {
        if ( e.shiftKey)
          return;

        e.stopPropagation();e.preventDefault();

        _.hideContexts();

        let _me = $(this);
        let _data = _me.data();
        let _context = _.context();

        if ( 'Inspect' == _data.type) {
          if ( _data.inspect_id > 0) {
            _context.append(
              $('<a class="font-weight-bold" href="#">inspection</a>')
              .on( 'click', e => {
                e.stopPropagation();e.preventDefault();
                _context.close();

                _me.trigger( 'view-inspection');

              })

            );

          }
          else {
            _context.append(
              $('<a class="font-weight-bold text-danger" href="#">inspection not found</a>')
              .on( 'click', e => {
                e.stopPropagation();e.preventDefault();
                _context.close();

              })

            );

          }

        }
        else {
          _context.append( $('<a class="font-weight-bold" href="#">inspections</a>').on( 'click', function( e) {
            e.stopPropagation();e.preventDefault();

            _context.close();

            $(document).trigger( 'load-inspects', _data);

          }));

        }

        _context.append( $('<a href="#"><i class="fa fa-pencil"></i>edit</a>').on( 'click', function( e) {
          e.stopPropagation();e.preventDefault();

          _context.close();
          _me.trigger('edit');

        }));

        if ( 'Inspect' == _data.type || 0 == Number( _data.inspections)) {
          _context.append( $('<a href="#"><i class="fa fa-trash"></i>delete</a>').on( 'click', function( e) {
            e.stopPropagation();e.preventDefault();

            _context.close();
            _me.trigger( 'delete', _data.inspect_id);

          }));

        }

        _context.append( '<hr>');
        _context.append( $('<a href="#">close</a>').on( 'click', function( e) {
          e.stopPropagation();e.preventDefault();

          _context.close();

        }));

        _context.open( e);

      })
      .on( 'delete', function(e) {
        let _me = $(this);

				_.ask({
					headClass: 'text-white bg-danger',
					text: 'Are you sure ?',
					title: 'Confirm Delete',
					buttons : {
						yes : function(e) {
							$(this).modal('hide');
							_me.trigger( 'delete-confirmed');

						}

					}

				});

      })
      .on( 'delete-confirmed', function(e) {
        let _me = $(this);
        let _data = _me.data();

        _.post({
          url : _.url('<?= $this->route ?>'),
          data : {
            action : 'inspect-diary-delete',
            id : _data.id
          },

        }).then( d => {
          if ( 'ack' == d.response) {
            window.location.reload();

          }
          else {
            _.growl( d);

          }

        });

      })
      .on( 'edit', function(e) {
        let _me = $(this);
        let _data = _me.data();

        _.get.modal( _.url( '<?= $this->route ?>/edit/' + _data.id))
        .then( modal => {
          modal.on( 'success', () => _me.trigger('refresh'));

        });

      })
      .on( 'refresh', function(e) {
        let _me = $(this);
        let _data = _me.data();

        _.post({
          url : _.url('inspectdiary'),
          data : {
            action : 'get-by-id',
            id : _data.id

          },

        }).then( d => {
          if ( 'ack' == d.response) {
            _me.data('property_id', d.data.property_id);
            _me.data('address_street', d.data.address_street);
            _me.data('pretty_street', d.data.pretty_street);
            _me.data('short_time', d.data.shorttime);
            _me.data('person_id', d.data.contact_id);
            _me.data('inspect_id', d.data.inspect_id);
            _me.data('type', d.data.type);

            $('[data-field="date"]', _me).html( d.data.shortdate);
            $('[data-field="time"]', _me).html( d.data.shorttime);
            $('[data-field="street"]', _me).html( d.data.address_street);
            $('[data-field="contact_name"]', _me).html( d.data.contact_name);
            if ( 'OH Inspect' == d.data.type) {
              $('[data-field="type"]', _me).html( 'OH');

            }
            else if ( 'Inspect' == d.data.type) {
              $('[data-field="type"]', _me).html( 'Insp');

            }
            else {
              $('[data-field="type"]', _me).html( d.data.type);

            }

            // $('[data-field]', _me).each( (i, el) => console.log( $(el).data('field')));
            console.log( d.data);
            _me.removeClass( 'bg-warning');

          }
          else {
            _.growl(d);

          }

        });

        _me.addClass( 'bg-warning');

      })
      .on( 'view-inspection', function(e) {
        e.stopPropagation();

        let _me = $(this);
        let _data = _me.data();

        $('#<?= $_title ?>-candidates, #<?= $_title ?>-candidate').html( _data.pretty_street + '&nbsp;&nbsp;' + _data.short_time);

        $('#<?= $_candidates ?>content')
        .data('id', _data.id)
        .data('type', _data.type);

        $('#<?= $_candidates ?>content').trigger('view-inspection', _data.inspect_id);

      });

    });

    // var someTabTriggerEl = document.querySelector('#someTabTrigger')
    // var tab = new bootstrap.Tab(someTabTriggerEl)

    $('[data-role="content-primary"]')
    .removeClass('pt-3')
    .addClass('pt-0 pt-md-3')

    $('#<?= $_report ?>').collapse('show');

  });

})( _brayworth_);
</script>

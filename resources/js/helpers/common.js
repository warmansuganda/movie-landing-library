// ProgressBar
function PageProgress() {
    return new ProgressBar.Line('#page-progress', {
        easing: 'easeInOut',
        color: '#3490dc',
        trailColor: 'none',
        svgStyle: {width: '100%', height: '2px', display: 'block'},
        trailWidth: 1,
        duration: 300,
        from: {color: '#FFEA82'},
        to: {color: '#3490dc'},
            step: (state, bar) => {
            bar.path.setAttribute('stroke', state.color);
        }
    });
}

// Page Setup
function initPage() {
    if ($('select.select2').length) {
        $.each($('select.select2'), function(){
            var opt = {
                width: '100%',
                theme: "bootstrap"
            };

            if (typeof $(this).data('search') != 'undefined' && $(this).data('search') == false) {
                opt = $.extend(true, opt, {minimumResultsForSearch: -1});
            }
            $(this).select2(opt);
        });
    }

    $('.form-datepicker').datepicker();
    $('.form-daterangepicker').daterangepicker({
      autoUpdateInput: false
    }, function(start_date, end_date) {
        var cb = this.element.attr('data-callback');
        this.element.val(start_date.format('DD/MM/YYYY') + ' - ' + end_date.format('DD/MM/YYYY'));
        if (cb != 'undefined') {
            $(document).trigger(cb);
        }
    });

    $(".positive-integer").numeric({ decimal: false, negative: false });
    $('[rel="tooltip"]').tooltip();

    $('.cb-dynamic-label input[type=checkbox]').change(function() {
        cbCustomInput(this);
    });

    if ($('.cb-dynamic-label input[type=checkbox]').length > 0) {
        $.each($('.cb-dynamic-label input[type=checkbox]'), function(){
            cbCustomInput(this);
        });
    }

    $('.multiSelect').multiSelect();
    
    $('.multiSelect-with-serach').multiSelect({
      selectableHeader: "<input type='text' class='search-input' autocomplete='off'>",
      selectionHeader: "<input type='text' class='search-input' autocomplete='off'>",
      afterInit: function(ms){
        var that = this,
            $selectableSearch = that.$selectableUl.prev(),
            $selectionSearch = that.$selectionUl.prev(),
            selectableSearchString = '#'+that.$container.attr('id')+' .ms-elem-selectable:not(.ms-selected)',
            selectionSearchString = '#'+that.$container.attr('id')+' .ms-elem-selection.ms-selected';

        that.qs1 = $selectableSearch.quicksearch(selectableSearchString)
        .on('keydown', function(e){
          if (e.which === 40){
            that.$selectableUl.focus();
            return false;
          }
        });

        that.qs2 = $selectionSearch.quicksearch(selectionSearchString)
        .on('keydown', function(e){
          if (e.which == 40){
            that.$selectionUl.focus();
            return false;
          }
        });
      },
      afterSelect: function(){
        this.qs1.cache();
        this.qs2.cache();
      },
      afterDeselect: function(){
        this.qs1.cache();
        this.qs2.cache();
      }
    });
}

function cbCustomInput(e) {
    var textOn = $(e).data('text-on');
    var textOff = $(e).data('text-off');
    var name = $(e).attr('id');
    if (typeof name == 'undefined' || name == '') {
        name = $(e).attr('name');
    }

    var target = $('label[for='+ name +']');
    if($(e).is(":checked")) {
        target.html(textOn);
    } else {
        target.html(textOff);
    }
}

//REMOTE MODAL
function initModalAjax(selector) {
    var selector_triger = typeof selector !== 'undefined' ? selector : '[data-toggle="modal"]';
    $(selector_triger).click(function(e) {
        /* Parameters */
        var url = $(this).attr('href');
        var container = $(this).attr('data-target');

        if (url.indexOf('#') == 0) {
            $(container).modal();
        } else {
            /* XHR */
            var bar = PageProgress();
            bar.animate(1,{
                duration: 800
            });
            $(container).modal();
            $('.modal-content', $(container)).html('').load(url, function(){
                bar.destroy();
            });
        }
        return false;
    });
}

// DATA TABLES
function initDatatableAction(table_id, callback) {
    $('.btn-delete', table_id).click(function(){
        $(this).myAjax({
              success: function (data) {
                  callback();
              }
          }).delete();

        return false;
    });

}

function initDatatableTools(table_id, oTable) {
    var header_id = $('[data-table="#'+ $(table_id).attr('id') +'"]');
    var $form_filter = $(table_id).attr('data-table-filter');

    $($form_filter).submit(function (e) {
        e.preventDefault();
        oTable.reload();
    });

    $('.filter-select', $($form_filter)).change(function (event) {
        event.preventDefault();

        var $auto_filter = $(table_id).attr('data-auto-filter');
        if ($auto_filter == 'true') {
            oTable.reload();
        }
    });

    $('a[href="#btn-checked-all"], a[href="#btn-unchecked-all"]', table_id).click(function () {
        var id = $(this).attr('href');
        if (id == '#btn-checked-all') {
          $('.dt-checkbox', table_id).prop('checked', true);
        } else {
          $('.dt-checkbox', table_id).prop('checked', false);
        }
        return false;
    });

    $('.btn-delete-selected', table_id).click(function () {
        var id = [];

        $.each($('.dt-checkbox', table_id), function(){
            if ($(this).is(':checked')) {
                id.push($(this).val());
            }
        });

        if (id.length > 0) {
            $(this).myAjax({
                data: {
                    _id: id
                },
                success: function (data) {
                    oTable.reload();
                }
            }).delete({confirm : {text: __('helpers.common.multiple_delete', {total: id.length})}});
        } else {
            command: toastr["warning"](__('helpers.common.nf_cb_selected'));
        }

        return false;
    });

    $('.auto_filter', header_id).on('switchChange.bootstrapSwitch', function(event, state) {
      $('table#main-table').attr('data-auto-filter', $(this).is(':checked') ? 'true' : 'false');
    });

    $('.reload-table', header_id).click(function(){
      oTable.reload();
      return false;
    });

    $('.reset-filter', header_id).click(function(){
      oTable.filterReset();
      return false;
    });
}

function dd_cascade(target, url, data, selected) {
    $(target).html('<option value="">Loading...</option>');

    $.get(url, data, function(out){
        var options = '';
        $.each(out.data, function(idx, val){
            options += '<option value="' + idx + '">' + val + '</option>';
        });

        var checked = typeof selected != 'undefined' ? selected : '';
        $(target).html(options);
        $(target).val(checked);
    }, 'json');
}

function generate_code(selector, url) {
    var target = $(selector);

    if (typeof url == 'undefined') {
        url = $(selector).data('g-code');
    }

    if (url) {
        $.get(url, {}, function(out){
            $(selector).val(out.data.code);
        }, 'json');
    } else {
        console.log('undefined url');
    }
}

function number_format(number) {
    // var dec = 2;
    // var dec_point = ",";
    // var tho_sep = ".";
    // var n = Number(number);
    // var n_dec = n.toFixed(dec);
    // var result = n.toFixed(0).replace(/./g, function(c, i, a) {
    //     return i && c !== "." && ((a.length - i) % 3 === 0) ? tho_sep + c : c;
    // });

    // // decimal
    // var n_string = n_dec.toString();
    // var n_split = n_string.split('.');

    // if (typeof n_split[1] != 'undefined') {
    //     result += dec_point + n_split[1];
    // }

    var result = numeral(Number(number)).format('0,0.00');

    return result;
}

function convert_to_number(n) {
    if (typeof n != 'string')
        return 0;

    var s = n.split(',');
    var r = [];
    r.push(s[0].replace(/\./g, ''));
    if (typeof s[1] != 'undefined') {
        r.push(s[1]);
    }
    var x  = r.join('.');
    // console.log('convert_to_number('+ n +');' + x);
    return x;
}

function do_export(form, url) {
    var data = $(form).serialize();

    window.open(url + '?' + data, '_blank');
}

function bytesToSize(bytes) {
   var sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
   if (bytes == 0) return '0 Byte';
   var i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
   return Math.round(bytes / Math.pow(1024, i), 2) + ' ' + sizes[i];
}

function stepwizard(id) {
    var navListItems = $('div.setup-panel div a'),
        allWells = $('.stepwizard-content');

        allWells.hide();
        $('#' + id).show();

        navListItems.removeClass('btn-success').addClass('btn-default');
        $('[data-wizard=' + id + ']').addClass('btn-success');

        navListItems.attr('disabled', 'disabled');
        var enable = $('[data-wizard=' + id + ']').data('wizard-enable');
        if (typeof enable != 'undefined') {
            $.each(enable.split(','), function(i,v){
                $('[data-wizard=' + v + ']').removeAttr('disabled');
            });
        }
  }

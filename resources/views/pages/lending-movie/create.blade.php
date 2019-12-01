@extends('layouts.modal')

@section('title', __('Form Lending'))

@section('content')
    {{ Form::open(['id' => 'my-form', 'route' => [$module . '.store', encrypt($data->id)], 'method' => 'put', 'autocomplete' => 'off']) }}
    <input type="hidden" name="movie_id" value="{{$data->id}}">
    <div class="modal-body">

        <div class="row mb-4">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body pb-2">
                        <div class="row">
                            <div class="col-md-1">
                                <i class="material-icons" style="font-size: 50px;top: -3px !important">movie</i>
                            </div>
                            <div class="col-md-auto">
                                <h5 class="card-title mb-0">{{ $data->title }}</h5>
                                <p class="card-text">{{ $data->genre }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group row">
            <label for="title" class="col-sm-3 col-form-label">{{ __('Member') }}<sup>*</sup></label>
            <div class="col-sm-9">
                <select name="member_id" class="js-example-data-ajax"></select>
            </div>
        </div>

        <div class="form-group row">
            <label for="returned_date" class="col-sm-3 col-form-label">{{ __('Returned Date') }}<sup>*</sup></label>
            <div class="col-sm-9">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="basic-addon1"><i class="fa fa-calendar"></i></span>
                    </div>
                    <input type="text" class="form-control form-datepicker" name="returned_date" id="returned_date">
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Cancel') }}</button>
        <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>
    </div>
    {!! Form::close() !!}
@endsection

@push('js')
<script type="text/javascript">
var formatOptions = function(option) {
    if (option.loading) {
        return option.text;
    }

    var $container = $(
        "<div class='select2-result-repository clearfix'>" +
        "<div class='select2-result-repository__meta'>" +
        "<div class='select2-result-repository__title'></div>" +
        "<div class='select2-result-repository__description'></div>" +
        "<div class='select2-result-repository__more'></div>" +
        "</div>" +
        "</div>"
    );

    $container.find(".select2-result-repository__title").text(option.name);
    $container.find(".select2-result-repository__description").text(option.address);

    var more_info = [];
    if (option.dob){
        more_info.push(`Age: ${moment().diff(option.dob, 'years')} years old`)
    }
    if (option.dob){
        more_info.push(`Join at: ${moment(option.join_date).fromNow()}`)
    }

    $container.find(".select2-result-repository__more").text(more_info.join(' | '));

    return $container;
}

var formatOptionsSelection = function (option) {
    return option.name || option.text;
}
</script>
<script type="text/javascript">
  $(function(){
    myCommon.initPage();

    $(".js-example-data-ajax").select2({
        width: '100%',
        ajax: {
            url: "{{ route($module. '.members') }}",
            dataType: 'json',
            delay: 250,
            data: function(params) {
                return {
                    q: params.term, // search term
                    page: params.page
                };
            },
            processResults: function(data, params) {
                // parse the results into the format expected by Select2
                // since we are using custom formatting functions we do not need to
                // alter the remote JSON data, except to indicate that infinite
                // scrolling can be used
                params.page = params.page || 1;

                return {
                    results: data.items,
                    pagination: {
                        more: (params.page * 30) < data.total_count
                    }
                };
            },
            cache: true
        },
        placeholder: 'Search for a repository',
        minimumInputLength: 1,
        templateResult: formatOptions,
        templateSelection: formatOptionsSelection
    });

    $('form#my-form').submit(function(e){
      e.preventDefault();
      $(this).myAjax({
          waitMe: '.modal-dialog',
          success: function (data) {
              $('.modal').modal('hide');
              oTable.reload();
          }
      }).submit();
    });
  })
</script>
@endpush

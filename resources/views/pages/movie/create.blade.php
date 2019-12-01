@extends('layouts.modal')

@section('title', __('Form Add'))

@section('content')
    {{ Form::open(['id' => 'my-form', 'route' => $module . '.store', 'method' => 'post', 'autocomplete' => 'off']) }}
    <div class="modal-body">
        <div class="form-group row">
            <label for="title" class="col-sm-3 col-form-label">{{ __('Title') }}<sup>*</sup></label>
            <div class="col-sm-9">
                <input type="text" class="form-control" name="title" id="title">
            </div>
        </div>
        <div class="form-group row">
            <label for="genre" class="col-sm-3 col-form-label">{{ __('Genre') }}<sup>*</sup></label>
            <div class="col-sm-9">
                <input type="text" class="form-control" name="genre" id="genre">
            </div>
        </div>
        <div class="form-group row">
            <label for="release_date" class="col-sm-3 col-form-label">{{ __('Release Date') }}<sup>*</sup></label>
            <div class="col-sm-5">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="basic-addon1"><i class="fa fa-calendar"></i></span>
                    </div>
                    <input type="text" class="form-control form-datepicker" name="release_date" id="release_date">
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
  $(function(){
    myCommon.initPage();

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

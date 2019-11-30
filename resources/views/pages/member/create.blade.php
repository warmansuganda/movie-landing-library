@extends('layouts.modal')

@section('title', __('Form Add'))

@section('content')
    {{ Form::open(['id' => 'my-form', 'route' => $module . '.store', 'method' => 'post', 'autocomplete' => 'off']) }}
    <div class="modal-body">
        <div class="form-group row">
            <label for="name" class="col-sm-3 col-form-label">{{ __('Name') }}<sup>*</sup></label>
            <div class="col-sm-9">
                <input type="text" class="form-control" name="name" id="name">
            </div>
        </div>
        <div class="form-group row">
            <label for="dob" class="col-sm-3 col-form-label">{{ __('Date of birth') }}<sup>*</sup></label>
            <div class="col-sm-5">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="basic-addon1"><i class="fa fa-calendar"></i></span>
                    </div>
                    <input type="text" class="form-control form-datepicker" name="dob" id="dob">
                </div>
            </div>
        </div>
        <div class="form-group row">
            <label for="address" class="col-sm-3 col-form-label">{{ __('Address') }}<sup>*</sup></label>
            <div class="col-sm-9">
                <textarea class="form-control" name="address" id="address"></textarea>
            </div>
        </div>
        <div class="form-group row">
            <label for="telephone" class="col-sm-3 col-form-label">{{ __('Telephone') }}</label>
            <div class="col-sm-5">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="basic-addon1"><i class="fa fa-phone"></i></span>
                    </div>
                    <input type="text" class="form-control" name="telephone" id="telephone">
                </div>
            </div>
        </div>
        <div class="form-group row">
            <label for="identity" class="col-sm-3 col-form-label">{{ __('Identity Number') }}</label>
            <div class="col-sm-5">
                <input type="text" class="form-control" name="identity" id="identity">
            </div>
        </div>
        <div class="form-group row">
            <label for="join_date" class="col-sm-3 col-form-label">{{ __('Join Date') }}<sup>*</sup></label>
            <div class="col-sm-5">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="basic-addon1"><i class="fa fa-calendar"></i></span>
                    </div>
                    <input type="text" class="form-control form-datepicker" name="join_date" value="{{date('d/m/Y')}}" id="join_date">
                </div>
            </div>
        </div>
        <div class="form-group row">
            <label for="is_active" class="col-sm-3 col-form-label">{{ __('Status') }}</label>
            <div class="col-sm-9">
                <input name="is_active" type="checkbox" value="1" checked data-toggle="toggle" data-style="ios" data-on="Active" data-off="Inactive">
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

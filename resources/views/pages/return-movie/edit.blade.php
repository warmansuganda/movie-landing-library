@extends('layouts.modal')

@section('title', __('Form Return'))

@section('content')
    {{ Form::open(['id' => 'my-form', 'route' => [$module . '.update', encrypt($data->id)], 'method' => 'put', 'autocomplete' => 'off']) }}
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
                                <h5 class="card-title mb-0">{{ $data->movie->title }}</h5>
                                <p class="card-text">{{ $data->movie->genre }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <h5>Lending Information:</h5>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group row mb-1">
                    <label for="lateness_charge" class="col-sm-4 col-form-label">{{ __('Lending Date') }}</label>
                    <div class="col-sm-8 py-2">
                        : {{ $data->lending_date ? $data->lending_date->format('d/m/Y') : '' }}
                    </div>
                </div>

                <div class="form-group row">
                    <label for="lateness_charge" class="col-sm-4 col-form-label">{{ __('Member') }}</label>
                    <div class="col-sm-8 py-2">
                        : {{ $data->member ? $data->member->name : '' }}
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group row mb-1">
                    <label for="lateness_charge" class="col-sm-4 col-form-label">{{ __('Return Date') }}</label>
                    <div class="col-sm-8 py-2">
                        : {{ $data->return_date ? $data->return_date->format('d/m/Y') : '' }}
                    </div>
                </div>

                <div class="form-group row">
                    <label for="lateness_charge" class="col-sm-4 col-form-label">{{ __('Notes') }}</label>
                    <div class="col-sm-8 py-2">
                        : {!! $data->return_date->isPast() ? '<span class="badge badge-danger">Late to Returned</span>' : '-' !!}
                        <input type="hidden" name="notes" value="{{ $data->return_date->isPast() ? 'late' : 'none' }}">
                    </div>
                </div>

                <div class="form-group row" style="display: {{ $data->return_date->isPast() ? '' : 'none' }}">
                    <label for="lateness_charge" class="col-sm-4 col-form-label">{{ __('Lateness Charge') }}<sup>*</sup></label>
                    <div class="col-sm-8">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1">$</span>
                            </div>
                            <input type="text" class="form-control" name="lateness_charge" id="lateness_charge">
                        </div>
                    </div>
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

    new AutoNumeric('input[name=lateness_charge]', {
        decimalCharacter : ',',
        digitGroupSeparator : '.',
    })
  })
</script>
@endpush

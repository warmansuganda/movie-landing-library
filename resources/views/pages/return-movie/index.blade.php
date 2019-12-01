@extends('layouts.app')

@section('content')
<div class="container">
    @include('components.page-title', ['breadcrumb' => ['Home', 'Return']])

    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    @include('components.tools-filter', ['table_id' => '#main-table' ])
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            {{ Form::open(['id' => 'form-filter', 'autocomplete' => 'off']) }}
                                <div class="form-row">
                                    <div class="col-md-4">
                                        <div class="form-group row">
                                            <label for="code" class="col-sm-3 col-form-label">{{ __('Title') }}</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control filter-select" name="title" id="title">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group row">
                                            <label for="lending_date" class="col-sm-3 col-form-label">{{ __('Lending Date') }}</label>
                                            <div class="col-sm-9">
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text" id="basic-addon1"><i class="material-icons">date_range</i></span>
                                                    </div>
                                                    <input type="text" class="form-control form-daterangepicker filter-select" name="lending_date" id="lending_date">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group row">
                                            <label for="member_name" class="col-sm-3 col-form-label">{{ __('Member') }}</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control filter-select" name="member_name" id="member_name">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="col-md-4">
                                        <div class="form-group row">
                                            <label for="return_date" class="col-sm-3 col-form-label">{{ __('Return Date') }}</label>
                                            <div class="col-sm-9">
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text" id="basic-addon1"><i class="material-icons">date_range</i></span>
                                                    </div>
                                                    <input type="text" class="form-control form-daterangepicker filter-select" name="return_date" id="return_date">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group row">
                                            <label for="status" class="col-sm-3 col-form-label">{{ __('Status') }}</label>
                                            <div class="col-sm-9">
                                                {{ Form::select('status', ['' => '-All-', '1' => 'Has been returned', '2' => 'Borrowed', '3' => 'Not been returned'], '', ['class' => 'form-control filter-select select2', 'data-search' => 'false']) }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-2">
        <div class="col">
            <div class="card card-small mb-4">
                <div class="card-body p-0">
                    @include('components.datatables', [
                        'toolsTable' => false,
                        'id' => 'main-table',
                        'form_filter' => '#form-filter',
                        'header' => ['Title', 'Lending Date', 'Member', 'Return Date', 'Status', 'Lateness Charge'],
                        'data_source' => route($module . '.data')
                    ])
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script type="text/javascript">
    var oTable = $('#main-table').myDataTable({
        actions: [
            {
                id : 'edit',
                title: 'Return',
                icon : '<i class="material-icons">repeat</i>',
                modal: '#modal-lg',
                url: '{{ route($module . '.edit', ['id' => '__grid_doc__']) }}'
            }
        ],
        columns: [
            {data: 'movie.title', name:'movie.title'},
            {data: 'lending_date', name:'lending_date', className: 'text-center'},
            {data: 'member.name', name:'member.name'},
            {data: 'return_date', name:'return_date', className: 'text-center'},
            {data: 'status', name:'returned_date_actual', className: 'text-center', width: '15%'},
            {data: 'lateness_charge', name:'lateness_charge', className: 'text-center'},
            {data: 'action', className: 'text-center'}
        ],
        onDraw : function() {
            myCommon.initModalAjax('[data-toggle="modal-edit"]');
            myCommon.initDatatableAction($(this), function(){
              oTable.reload();
            });
        },
        onComplete: function() {
            myCommon.initModalAjax();
        },
        customRow: function(row, data) {
            if (data.returned_date !== '-'){
                $('td:eq(6)', row).html('<i class="material-icons">done_all</i>');
                $('td:eq(4)', row).html(`Has been returned at ${data.returned_date}`).addClass('text-success');
            } else {
                if (moment().diff(moment(data.return_date, "DD/MM/YYYY"), 'days') > 0) {
                    $('td:eq(4)', row).html('Not been returned').addClass('text-danger');
                } else {
                    $('td:eq(4)', row).html('Borrowed').addClass('text-primary');
                }
            }
        }
    });
</script>
<script type="text/javascript">
    $(function(){
        myCommon.initPage();
        myCommon.initDatatableTools($('#main-table'), oTable);

        $('#form-filter .form-daterangepicker').on('apply.daterangepicker', function(ev, picker) {
            oTable.reload();
        });
    })
</script>
@endpush

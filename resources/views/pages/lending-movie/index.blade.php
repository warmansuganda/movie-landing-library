@extends('layouts.app')

@section('content')
<div class="container">
    @include('components.page-title', ['breadcrumb' => ['Home', 'Lending']])

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
                                    <div class="col-md-3">
                                        <div class="form-group row">
                                            <label for="code" class="col-sm-3 col-form-label">{{ __('Title') }}</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control filter-select" name="title" id="title">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group row">
                                            <label for="genre" class="col-sm-3 col-form-label">{{ __('Genre') }}</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control filter-select" name="genre" id="genre">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label for="release_date" class="col-sm-3 col-form-label">{{ __('Released Date') }}</label>
                                            <div class="col-sm-9">
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text" id="basic-addon1"><i class="material-icons">date_range</i></span>
                                                    </div>
                                                    <input type="text" class="form-control form-daterangepicker filter-select" name="release_date" id="release_date">
                                                </div>
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
                        'header' => ['Title', 'Genre', 'Released Date', 'Created At'],
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
                title: 'Lending',
                icon : '<i class="material-icons">playlist_add</i>',
                modal: '#modal-lg',
                url: '{{ route($module . '.create', ['movie_id' => '__grid_doc__']) }}'
            }
        ],
        columns: [
            {data: 'title', name:'title'},
            {data: 'genre', name:'genre'},
            {data: 'release_date', name:'release_date', className: 'text-center'},
            {data: 'created_at', name:'created_at', className: 'text-center'},
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

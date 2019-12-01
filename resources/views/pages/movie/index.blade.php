@extends('layouts.app')

@section('content')
<div class="container">
    @include('components.page-title', ['breadcrumb' => ['Home', 'Movies']])

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
                                            <label for="genre" class="col-sm-3 col-form-label">{{ __('Genre') }}</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control filter-select" name="genre" id="genre">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group row">
                                            <label for="release_date" class="col-sm-3 col-form-label">{{ __('Released Date') }}</label>
                                            <div class="col-sm-9">
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text" id="basic-addon1"><i class="fa fa-calendar"></i></span>
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
                        'id' => 'main-table',
                        'form_filter' => '#form-filter',
                        'header' => ['Title', 'Genre', 'Released Date', 'Created At'],
                        'data_source' => route($module . '.data'),
                        'delete_action' => route($module . '.destroys')
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
        buttons: [
            {
                id: 'add',
                modal: '#modal-lg',
                url: '{{ route($module . ".create") }}'
            }
        ],
        actions: [
            {
                id : 'edit',
                modal: '#modal-lg',
                url: '{{ route($module . '.edit', ['movie' => '__grid_doc__']) }}'
            },
            {
                id : 'delete',
                url: '{{ route($module . '.destroy', ['id' => '__grid_doc__']) }}'
            }
        ],
        columns: [
            {data: 'checkbox'},
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

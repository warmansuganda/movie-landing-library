<table id="{{ isset($id) ? $id :  'dt-basic' }}"
       class="table-striped table-hover"
       width="100%" style="margin-top: 0 !important;"
       data-table-source="{{ isset($data_source) ? $data_source : '' }}"
       data-table-filter="{{ isset($form_filter) ? $form_filter :  '#form-filter' }}"
       data-auto-filter="true">
    <thead>
	    <tr>
            <th class="toolsTable no-sort">
                <div class="btn-group">
                    <button type="button" class="btn btn-outline-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-ellipsis-h"></i>
                    </button>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="#btn-checked-all"><i class="far fa-check-square"></i> {{ __('Select All') }}</a>
                        <a class="dropdown-item" href="#btn-unchecked-all"><i class="far fa-square"></i> {{ __('Deselect All') }}</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item btn-delete-selected" href="{{isset($delete_action) ? $delete_action : ''}}"><i class="fas fa-trash-alt"></i> {{ __('Delete Selected') }}</a>
                    </div>
                </div>
            </th>
	    	@if (isset($header) && count($header) > 0)
	    		@foreach($header as $key => $value)
		    		<th>{{ __($value) }}</th>
		    	@endforeach
		    @endif
		    <th class="no-sort">{{ __('Action') }}</th>
	    </tr>
    </thead>
</table>

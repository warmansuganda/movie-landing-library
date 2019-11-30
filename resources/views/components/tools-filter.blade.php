<h6 class="m-0 mt-1 float-left">
    {{ __('Filter') }}
</h6>

<div class="toolsFilter btn-group float-right" data-table="{{ $table_id }}">
    <button type="button" class="btn btn-outline-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="material-icons">settings</i>
    </button>
    <div class="dropdown-menu dropdown-menu-right">
        <button class="dropdown-item reload-table" type="button">{{ __('Reaload') }}</button>
        <button class="dropdown-item reset-filter" type="button">{{ __('Reset Filter') }}</button>
    </div>
</div>

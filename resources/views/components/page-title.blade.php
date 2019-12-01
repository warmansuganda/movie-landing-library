<div class="row">
    <div class="col-md-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                @foreach($breadcrumb as $i => $b)
                    <li class="breadcrumb-item active"><a>{!! $i == 0 ? '<i class="material-icons">home</i>' : '' !!} {{ __($b) }}</a></li>
                @endforeach
            </ol>
        </nav>
    </div>
</div>

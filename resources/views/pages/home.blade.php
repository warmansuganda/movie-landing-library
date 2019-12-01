@extends('layouts.app')

@section('content')
<div class="container">
    @include('components.page-title', ['breadcrumb' => ['Home']])

    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                    @endif

                    <div class="row">
                        <div class="col-sm-12">
                            <h3>Welcome to Movie Lending Library</h3>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-sm-8">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title text-center mb-0">Lending Chart</h5>
                                    <h6 class="card-title text-center">{{ date('Y') }}</h6>
                                    <canvas id="myChart" height="130"></canvas>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-4">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="card">
                                        <div class="card-body">
                                            <h5 class="card-title">Total Movies</h5>
                                            <h1 id="total-movie">-</h1>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12 mt-2">
                                    <div class="card">
                                        <div class="card-body">
                                            <h5 class="card-title">Total Members</h5>
                                            <h1 id="total-member">-</h1>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script type="text/javascript">
    var getTotal = function(container, url) {
        $.get(url, function(out) {
            $(container).html(out)
        }, 'json');
    }

    var lendingChart = function() {
        $.get('{{ route('home.lending-chart') }}', function(out) {
            console.log(out)
            var ctx = document.getElementById('myChart').getContext('2d');
            var myChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: out.labels,
                    datasets: [{
                        label: '# of Lending',
                        data: out.datas,
                        backgroundColor: '#027bff4f',
                        borderColor: '#027bff4f',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true
                            }
                        }]
                    }
                }
            });
        }, 'json');
    }
</script>

<script type="text/javascript">
$(function () {
    getTotal('#total-movie', '{{ route('home.total-movie') }}');
    getTotal('#total-member', '{{ route('home.total-member') }}');

    lendingChart();
})
</script>
@endpush

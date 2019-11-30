<nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
    <div class="container">

        <a class="navbar-brand pr-4" href="{{ url('/') }}">
            <!-- {{ config('app.name', 'Laravel') }} -->
            <img src="{{ url('images/tnr-01.png') }}" style="height: 28px" >
        </a>

        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="navbar-collapse" id="navbarSupportedContent">
            <!-- Left Side Of Navbar -->
            <ul class="navbar-nav mr-auto">
                @auth
                <li class="nav-item active">
                    <a class="nav-link" href="/home"><i class="fa fa-home"></i> {{__('Home')}} <span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/movie"><i class="fa fa-film"></i> {{__('Movies')}}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/member"><i class="fa fa-address-card"></i> {{__('Members')}}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/lending-movie"><i class="fa fa-edit"></i> {{__('Lending')}}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/return-movie"><i class="fa fa-retweet"></i> {{__('Return')}}</a>
                </li>
                @endauth
            </ul>

            <!-- Right Side Of Navbar -->
            <ul class="navbar-nav ml-auto">
                <!-- Authentication Links -->
                @guest
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                    </li>
                    @if (Route::has('register'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                        </li>
                    @endif
                @else
                    <li class="nav-item dropdown">

                        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            {{ Auth::user()->name }} <span class="caret"></span>
                        </a>

                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="{{ route('logout') }}"
                               onclick="event.preventDefault();
                                             document.getElementById('logout-form').submit();">
                                {{ __('Logout') }}
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </div>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>

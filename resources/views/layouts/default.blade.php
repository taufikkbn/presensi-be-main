<!DOCTYPE html>
<html lang="en">

<head>
    @include('includes.header')
    @stack('custom-style')
</head>

<body>
    <div id="app">
        @include('includes.sidebar')
        <div id="main">
            <header class="mb-3">
                @yield('alert-section')
                <a href="#" class="burger-btn d-block d-xl-none">
                    <i class="bi bi-justify fs-3"></i>
                </a>
            </header>

            @include('includes.heading')
            <section class="section">
                <div class="card">
                    @yield('card-title')
                    <div class="card-body">
                        @yield('content')
                    </div>
                </div>
            </section>
        </div>
        @include('includes.footer')
    </div>
    </div>
    <script src="{{ asset('assets/js/bootstrap.js') }}"></script>
    <script src="{{ asset('assets/js/app.js') }}"></script>
    <script src="{{ asset('assets/extensions/toastify-js/src/toastify.js') }}"></script>
    @stack('custom-script')

</body>

</html>

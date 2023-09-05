<!-- Navbar & Hero Start -->
    <nav class="navbar navbar-expand-lg navbar-light px-4 px-lg-5 py-3 py-lg-0">
        <a href="" class="navbar-brand p-0">
        {{--    <h3 class="m-0">WE-TRANSFERTCASH</h3>--}}
           <img src="{{asset('img/logo.png')}}" alt="Logo">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
            <span class="fa fa-bars"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarCollapse">
            <div class="navbar-nav mx-auto py-0">
                <a href="{{route('home')}}" class="nav-item nav-link active">Accueil</a>
                <a href="{{route('about')}}" class="nav-item nav-link">Apropos</a>
                <div class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Service</a>
                    <div class="dropdown-menu m-0">
                        <a href="{{route('service_transfert')}}" class="dropdown-item">Transfert bancaire</a>
                        <a href="{{route('service_collete')}}" class="dropdown-item">Collecte marchand</a>
                        <a href="{{route('service_sms')}}" class="dropdown-item">Bulk SMS</a>
                    </div>
                </div>
                <a href="https://sandbox.wetransfertcash.com/documentation" class="nav-item nav-link">Devellopeur</a>

                <a href="{{route('contact')}}" class="nav-item nav-link">Contact</a>
            </div>
            <a href="https://sandbox.wetransfertcash.com/login" class="btn rounded-pill py-2 px-4 ms-3 d-none d-lg-block">Login</a>
            <a href="https://sandbox.wetransfertcash.com/register" class="btn rounded-pill py-2 px-4 ms-3 d-none d-lg-block">Register</a>
        </div>
    </nav>

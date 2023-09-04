@extends('base')
@section('content')
    <div class="container-xxl bg-primary hero-header">
        <div class="container px-lg-5">
            <div class="row g-5 align-items-end">
                <div class="col-lg-6 text-center text-lg-start">
                    <h1 class="text-white mb-4 animated slideInDown">Wetransfertcash</h1>
                    <p class="text-white pb-3 animated slideInDown">Digitalisez vos structures a partir de nos APIS.Envoyer l'argent a partir de notre plateforme vers des comptes bancaires.Transformer vos sites en boutiques E-commerce </p>
                    <a href="" class="btn btn-secondary py-sm-3 px-sm-5 rounded-pill me-3 animated slideInLeft">Savoir plus</a>
                    <a href="" class="btn btn-light py-sm-3 px-sm-5 rounded-pill animated slideInRight">Nous contactez</a>
                </div>
                <div class="col-lg-6 text-center text-lg-start">
                    <img class="img-fluid animated zoomIn" src="img/hero.png" alt="">
                </div>
            </div>
        </div>
    </div>
    <!-- Feature Start -->
    <div class="container-xxl py-5">
        <div class="container py-5 px-lg-5">
            <div class="row g-4">
                <div class="col-lg-4 wow fadeInUp" data-wow-delay="0.1s">
                    <div class="feature-item bg-light rounded text-center p-4">
                        <i class="fa fa-3x fa-mail-bulk text-primary mb-4"></i>
                        <h5 class="mb-3">Transfert d'argent</h5>
                        <p class="m-0">Erat ipsum justo amet duo et elitr dolor, est duo duo eos lorem sed diam stet diam sed stet lorem.</p>
                    </div>
                </div>
                <div class="col-lg-4 wow fadeInUp" data-wow-delay="0.3s">
                    <div class="feature-item bg-light rounded text-center p-4">
                        <i class="fa fa-3x fa-search text-primary mb-4"></i>
                        <h5 class="mb-3">Envoyer les SMS en masse</h5>
                        <p class="m-0">Erat ipsum justo amet duo et elitr dolor, est duo duo eos lorem sed diam stet diam sed stet lorem.</p>
                    </div>
                </div>
                <div class="col-lg-4 wow fadeInUp" data-wow-delay="0.5s">
                    <div class="feature-item bg-light rounded text-center p-4">
                        <i class="fa fa-3x fa-laptop-code text-primary mb-4"></i>
                        <h5 class="mb-3">Collectez les fonds</h5>
                        <p class="m-0">Erat ipsum justo amet duo et elitr dolor, est duo duo eos lorem sed diam stet diam sed stet lorem.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Feature End -->


    <!-- About Start -->
    <div class="container-xxl py-5">
        <div class="container py-5 px-lg-5">
            <div class="row g-5 align-items-center">
                <div class="col-lg-6 wow fadeInUp" data-wow-delay="0.1s">
                    <p class="section-title text-secondary">Apropos de nous<span></span></p>
                    <h1 class="mb-5">Wetransfertcash c'est plus de 5ans d'experience</h1>
                    <p class="mb-4">Diam dolor diam ipsum et tempor sit. Aliqu diam amet diam et eos labore. Clita erat ipsum et lorem et sit, sed stet no labore lorem sit clita duo justo eirmod magna dolore erat amet</p>
                    <div class="skill mb-4">
                        <div class="d-flex justify-content-between">
                            <p class="mb-2">Transfert d'argent</p>
                            <p class="mb-2">85%</p>
                        </div>
                        <div class="progress">
                            <div class="progress-bar bg-primary" role="progressbar" aria-valuenow="85" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                    <div class="skill mb-4">
                        <div class="d-flex justify-content-between">
                            <p class="mb-2">Bulk SMS</p>
                            <p class="mb-2">90%</p>
                        </div>
                        <div class="progress">
                            <div class="progress-bar bg-secondary" role="progressbar" aria-valuenow="90" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                    <div class="skill mb-4">
                        <div class="d-flex justify-content-between">
                            <p class="mb-2">Collete des fonds</p>
                            <p class="mb-2">95%</p>
                        </div>
                        <div class="progress">
                            <div class="progress-bar bg-dark" role="progressbar" aria-valuenow="95" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                    <a href="" class="btn btn-primary py-sm-3 px-sm-5 rounded-pill mt-3">Lire plus</a>
                </div>
                <div class="col-lg-6">
                    <img class="img-fluid wow zoomIn" data-wow-delay="0.5s" src="img/about.png">
                </div>
            </div>
        </div>
    </div>
    <!-- About End -->

    <!-- Navbar & Hero End -->
@endsection

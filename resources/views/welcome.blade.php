<!DOCTYPE html>
<html lang="en">
    
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Clinica El Buen Pastor</title>
    <meta name="description" content="">
    <meta name="keywords" content="">

    <!-- Favicons -->
    <link href="assets/img/favicon.png" rel="icon">
    <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com" rel="preconnect">
    <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,
        100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&display=swap"
        rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="assets/vendor/aos/aos.css" rel="stylesheet">
    <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
    <link href="assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

    <link href="{{ asset('lib/animate/animate.min.css') }}" rel="stylesheet">
    <link href="{{ asset('lib/owlcarousel/assets/owl.carousel.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">

    <!-- Main CSS File -->
    <link href="{{ asset('assets/css/main.css') }}" rel="stylesheet">
</head>

<body class="index-page">

    <header id="header" class="header fixed-top">

        <div class="branding d-flex align-items-cente">

            <div class="container position-relative d-flex align-items-center justify-content-between">
                <a href="#hero" class="logo d-flex align-items-center">
                    <img src="assets/img/bg/logoCL.jpg" alt="logo" class="img-fluid">
                    <h1 class="sitename">El Buen Pastor</h1>
                </a>

                <nav id="navmenu" class="navmenu">

                    <ul>
                        <li><a href="#hero">Inico</a></li>
                        <li><a href="#featured-departments">Nuestros Servicos</a></li>
                        <li><a href="#find-a-doctor">Doctor</a></li>
                        <li><a href="#find-contact">Contáctanos</a></li>
                        {{-- INICIO DE LÓGICA DE LOGIN --}}
                        @if (Route::has('login'))
                        @auth
                        <li><a href="{{ url('/dashboard') }}" class="btn-get-started">Dashboard</a></li>
                        @else
                        <li><a href="{{ route('login') }}" class="btn-get-started">Iniciar Sesión</a></li>
                        @if (Route::has('register'))
                        <li><a href="{{ route('register') }}">Registrarse</a></li>
                        @endif
                        @endauth
                        @endif
                        {{-- FIN DE LÓGICA DE LOGIN --}}
                    </ul>
                    <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
                </nav>
            </div>
        </div>
    </header>

    <main class="main">

        <!-- primera sección -->
        <section id="hero" class="hero section">

            <div class="container" data-aos="fade-up" data-aos-delay="100">

                <div class="row align-items-center">
                    <div class="col-lg-6">
                        <div class="hero-content">
                            <div class="trust-badges mb-4" data-aos="fade-right" data-aos-delay="200">
                                <div class="badge-item">
                                    <i class="bi bi-shield-check"></i>
                                    <span>atención especializada</span>
                                </div>

                                <div class="badge-item">
                                    <i class="bi bi-star-fill"></i>
                                    <span>4.9/5 clasificación</span>
                                </div>
                            </div>

                            <h1 data-aos="fade-right" data-aos-delay="300">
                                Nuestro compromiso está con la <span class="highlight">salud</span> de los pacientes.
                            </h1>

                            <p class="hero-description" data-aos="fade-right" data-aos-delay="400">
                                Contamos con personal médico calificado y tecnología adecuada para
                                brindar diagnósticos precisos y tratamientos oportunos, priorizando siempre el
                                bienestar, la seguridad y la confidencialidad de nuestros pacientes.
                            </p>

                            <div class="hero-stats mb-4" data-aos="fade-right" data-aos-delay="500">
                                <div class="stat-item">
                                    <h3>
                                        <span data-purecounter-start="0" data-purecounter-end="15" data-purecounter-duration="2"
                                            class="purecounter">
                                        </span>+
                                    </h3>
                                    <p>Años de experiencia</p>
                                </div>
                                
                                <div class="stat-item">
                                    <h3><span data-purecounter-start="0" data-purecounter-end="2000" data-purecounter-duration="2"
                                            class="purecounter"></span>+</h3>
                                    <p>Pacientes tratados</p>
                                </div>
                            </div>

                            <div class="hero-actions" data-aos="fade-right" data-aos-delay="600">
                                <a href="{{ route('login') }}" class="btn btn-primary">Iniciar Sesíon</a>
                                <a href="{{ route('register') }}" class="btn btn-outline ">Registrarme</a>
                            </div>

                            <div class="emergency-contact" data-aos="fade-right" data-aos-delay="700">
                                <div class="emergency-icon">
                                    <i class="bi bi-telephone-fill"></i>
                                </div>

                                <div class="emergency-info">
                                    <small>Para mayor informacón contáctanos al número</small>
                                    <strong>+505 8792-2112</strong>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="hero-visual" data-aos="fade-left" data-aos-delay="400">
                            <div class="main-image">
                                <img src="assets/img/bg/f1.jpg" alt="foto de la dra y la clin" class="img-fluid">
                                <div class="floating-card appointment-card">
                                    <div class="card-icon">
                                        <i class="bi bi-calendar-check"></i>
                                    </div>

                                    <div class="card-content">
                                        <h6>Lunes - Viernes</h6>
                                        <p>De 01:00 PM - 06:00 PM</p>
                                        <small>Dr. Jennifer Reyes</small>
                                    </div>
                                </div>

                                <div class="floating-card rating-card">
                                    <div class="card-icon">
                                        <i class="bi bi-calendar-check"></i>
                                    </div>

                                    <div class="card-content">
                                        <h6>Domingo</h6>
                                        <p>De 08:00 AM - 12:00 PM</p>
                                        <small>Dr. Jennifer Reyes</small>
                                    </div>
                                </div>
                            </div>

                            <div class="background-elements">
                                <div class="element element-1"></div>
                                <div class="element element-2"></div>
                                <div class="element element-3"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- segunda sección -->
        <section id="featured-departments" class="featured-departments section">
            <div class="container section-title" data-aos="fade-up">
                <h2>Servicios Destacados</h2>
            </div>

            <div class="container" data-aos="fade-up" data-aos-delay="100">
                <div class="row g-5">
                    <div class="col-lg-4" data-aos="fade-up" data-aos-delay="100">
                        <div class="department-highlight">
                            <div class="highlight-icon">
                                <i class="bi bi-heart-pulse"></i>
                            </div>
                            <h4>Electrocardiograma</h4>
                            <p>Este examen permite evaluar el ritmo y la frecuencia 
                                cardíaca, así como detectar alteraciones en la conducción 
                                eléctrica, arritmias u otros problemas cardíacos, siendo 
                                una herramienta fundamental para el diagnóstico y 
                                seguimiento de diversas enfermedades del corazón.
                            </p>
                        </div>
                    </div>

                    <div class="col-lg-4" data-aos="fade-up" data-aos-delay="200">
                        <div class="department-highlight">
                            <div class="highlight-icon">
                                <i class="bi bi-person-lines-fill"></i>
                            </div>
                            <h4>Consulta Médica</h4>
                            <p>Evaluación integral y personalizada por especialistas 
                                en Medicina Interna, enfocada en el diagnóstico, 
                                tratamiento y prevención de enfermedades en adultos.
                            </p>
                        </div>
                    </div>

                    <div class="col-lg-4" data-aos="fade-up" data-aos-delay="300">
                        <div class="department-highlight">
                            <div class="highlight-icon">
                                <i class="bi bi-activity"></i>
                            </div>
                            <h4>Holter</h4>
                            <p>examen médico no invasivo que registra de manera continua 
                                la actividad eléctrica del corazón durante 24 a 48 horas. 
                                Permite detectar arritmias, variaciones del ritmo cardíaco 
                                y otros problemas que podrían no presentarse durante un 
                                electrocardiograma convencional
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        
        <!-- tercera sección -->
        <section id="find-a-doctor" class="find-a-doctor section">
            <div class="container section-title" data-aos="fade-up">
                <h2>Dotor Disponible</h2>
                <div class="container" data-aos="fade-up" data-aos-delay="100" style="text-align: left;">
                    <div class="doctors-grid" data-aos="fade-up" data-aos-delay="300">
                        <div class="doctor-profile" data-aos="zoom-in" data-aos-delay="100">
                            <div class="profile-header">
                                <div class="doctor-avatar">
                                <img src="assets/img/bg/DraJennifer.png" alt="foto de la dra" class="img-fluid">
                                <div class="status-indicator available"></div>
                            </div>
                            
                            <div class="doctor-details">
                                <h4>Dra. Jennifer Reyes</h4>
                                <span class="specialty-tag">Médico Cirujano Y General</span>
                                <span class="specialty-tag">Especialista en Médicina Interna</span>
                                <div class="experience-info">
                                    <i class="bi bi-award"></i>
                                    <span>14+ Años de experiencia</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- cuarta sección -->
        <section id="find-contact" class="find-contact section">
            <div class="container section-title" data-aos="fade-up">
                <h2>Estamos Ubicados</h2>
                <div class="row mt-5 p-4 bg-light shadow-sm rounded">
                    <div class="col-md-5">
                        <hr>
                        <p><strong>Dirección:</strong></p>
                        <p>Del Laboratorio Divino Niño, media cuadra al Oeste.<br>
                        Diriá-Granada, Nicaragua.</p>
                        <br>
                        <p><strong>Horarios:</strong></p>
                        <ul class="list-unstyled">
                            <li><i class="far fa-clock"></i> Lunes a Viernes: 01:00 PM - 06:00 PM</li>
                            <li><i class="far fa-clock"></i> Domingo: 08:00 AM - 12:00 PM</li>
                        </ul>
                        <a href="https://wa.me/87922112" class="btn btn-outline-success">
                            <i class="fab fa-whatsapp"></i> Para mayor Información
                        </a>
                    </div>
                    
                    <div class="col-md-7">
                        <div class="embed-responsive embed-responsive-16by9 border rounded">
                            <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d927.782753026813!2d-86.0541354!3d11.8832674!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x8f740f0065c58f83%3A0xe604aa96c6c43ac4!2sFarmacia%20y%20Consultorio%20El%20Buen%20Pastor!5e1!3m2!1ses-419!2sni!4v1768268829513!5m2!1ses-419!2sni" 
                                width="100%" height="300" style="border:0;" allowfullscreen="" loading="lazy">
                            </iframe>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- Scroll Top -->
    <a href="#!" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center">
        <i class="bi bi-arrow-up-short"></i>
    </a>

    <!-- Preloader -->
    <div id="preloader"></div>

    <!-- Vendor JS Files -->
    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/vendor/php-email-form/validate.js"></script>
    <script src="assets/vendor/aos/aos.js"></script>
    <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
    <script src="assets/vendor/purecounter/purecounter_vanilla.js"></script>
    <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>

    <!-- Main JS File -->
    <script src="assets/js/main.js"></script>

    <script src="{{ asset('lib/wow/wow.min.js') }}"></script>
    <script src="{{ asset('lib/easing/easing.min.js') }}"></script>
    <script src="{{ asset('js/main.js') }}"></script>

</body>
</html>
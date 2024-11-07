<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'Social App' )</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/principal.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
</head>

    <div class="container-fluid">
        <div class="row">

            <header>
                <div class="navbar">
                    <div class="navbar-left" style="height: 45px;">
                        
                        <input type="text" placeholder="Search">
                        <img src="/css/imgen/Designer.png" alt="" width="55" height="55">
                    </div>
                    <div class="navbar-center">
                        <a href="/feed" class="nav-icon active"><i class="fas fa-home"></i></a>
                        <a href="/videos" class="nav-icon"><i class="fas fa-tv"></i></a>
                        <a href="/amigos" class="nav-icon"><i class="fas fa-users"></i></a>
                        
                    </div>
                    <div class="navbar-right">
                        <a href="#" class="nav-icon"><i class="fas fa-bell"></i></a>
                        <a href="#" class="nav-icon"><i class="fas fa-envelope"></i></a>
                        <a href="/Usuario" class="nav-icon"><i class="fas fa-user-circle"></i>  {{ session('usuario_nombre', 'Usuario') }}</a>
                    </div>
                </div>
            </header>

            <div class="col-md-3 sidebar">
                <h5>Home</h5>
                <ul class="nav flex-column">
                    <li class="nav-item"><a href="/Usuario" class="nav-link"><i class="fas fa-user"></i>  {{ session('usuario_nombre', 'Usuario') }}</a></li>
                    <li class="nav-item"><a href="/videos" class="nav-link"><i class="fas fa-video"></i> Videos</a></li>
                    <li class="nav-item"><a href="/eventos" class="nav-link"><i class="fas fa-calendar-alt"></i> Eventos</a></li>
                    <li class="nav-item"><a href="/guardado" class="nav-link"><i class="fas fa-bookmark"></i> Guardado</a></li>
                    <li class="nav-item"><a href="/favoritos" class="nav-link"><i class="fas fa-star"></i> Favoritos</a></li>
                    <li class="nav-item"><a href="/recuerdos" class="nav-link"><i class="fas fa-clock"></i> Recuerdos</a></li>
                    <li class="nav-item"><a href="/ayuda-y-soporte" class="nav-link"><i class="fas fa-question-circle"></i> Ayuda y Soporte</a></li>
                    <li class="nav-item"><a href="/configuracion-y-privacidad" class="nav-link"><i class="fas fa-cog"></i> Configuración y Privacidad</a></li>
                </ul>
            </div>

            <div class="col-md-6 main-feed">
                @yield('content')
            </div>

            <div class="col-md-4 right-sidebar">
                <h5>Sugerencias</h5>
                <div class="card mb-3" style="width: 100%;height: 28%;">
                    <div class="card-body">
                        <h6>Grupos</h6>
                        <p>Ahora puedes encontrar y comunicarte con tu comunidad</p>
                        <button class="btn btn-primary"> Encuntra tus grupos </button>
                    </div>
                </div>
                <h5>Contactos</h5>
                <ul class="list-unstyled">
                    <li class="contact-item d-flex align-items-center mb-2">
                        <img src="https://via.placeholder.com/40" class="rounded-circle me-2" alt="Dennis Han">
                        <span>Dennis Han</span>
                    </li>
                    <li class="contact-item d-flex align-items-center mb-2">
                        <img src="https://via.placeholder.com/40" class="rounded-circle me-2" alt="Eric Jones">
                        <span>Eric Jones</span>
                    </li>
                    <li class="contact-item d-flex align-items-center mb-2">
                        <img src="https://via.placeholder.com/40" class="rounded-circle me-2" alt="Cynthia Lopez">
                        <span>Cynthia Lopez</span>
                    </li>
                    <li class="contact-item d-flex align-items-center mb-2">
                        <img src="https://via.placeholder.com/40" class="rounded-circle me-2" alt="Anna Becklund">
                        <span>Anna Becklund</span>
                    </li>
                    <li class="contact-item d-flex align-items-center mb-2">
                        <img src="https://via.placeholder.com/40" class="rounded-circle me-2" alt="Aiden Brown">
                        <span>Aiden Brown</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</html>
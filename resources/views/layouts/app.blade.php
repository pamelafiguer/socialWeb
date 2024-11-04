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
                    <div class="navbar-left">
                        <a href="#"><i class=""></i></a>
                        <input type="text" placeholder="Search">
                    </div>
                    <div class="navbar-center">
                        <a href="#" class="nav-icon active"><i class="fas fa-home"></i></a>
                        <a href="#" class="nav-icon"><i class="fas fa-tv"></i></a>
                        <a href="#" class="nav-icon"><i class="fas fa-store"></i></a>
                        <a href="#" class="nav-icon"><i class="fas fa-users"></i></a>
                        <a href="#" class="nav-icon"><i class="fas fa-gamepad"></i></a>
                    </div>
                    <div class="navbar-right">
                        <a href="#" class="nav-icon"><i class="fas fa-bell"></i></a>
                        <a href="#" class="nav-icon"><i class="fas fa-envelope"></i></a>
                        <a href="#" class="nav-icon"><i class="fas fa-user-circle"></i></a>
                    </div>
                </div>
            </header>

            <div class="col-md-3 sidebar">
                <h5>Home</h5>
                <ul class="nav flex-column">
                    <li class="nav-item"><a href="#" class="nav-link">Perfil de Usuario</a></li>
                    <li class="nav-item"><a href="#" class="nav-link">Videos</a></li>
                    <li class="nav-item"><a href="#" class="nav-link">Eventos</a></li>
                    <li class="nav-item"><a href="#" class="nav-link">Guardado</a></li>
                    <li class="nav-item"><a href="#" class="nav-link">Gaming</a></li>
                    <li class="nav-item"><a href="#" class="nav-link">Favoritos</a></li>
                    <li class="nav-item"><a href="#" class="nav-link">Recuerdos</a></li>
                    <li class="nav-item"><a href="#" class="nav-link">Ayuda y Soporte</a></li>
                    <li class="nav-item"><a href="#" class="nav-link">Configuracion y Privacidad</a></li>
                </ul>
            </div>

            <div class="col-md-6 main-feed">
                @yield('content')
            </div>

            <div class="col-md-4 right-sidebar">
                <h5>Sugerencias</h5>
                <div class="card mb-3">
                    <div class="card-body">
                        <h6>Grupos</h6>
                        <p>Ahora puedes encontrar y comunicarte con tu comunidad</p>
                        <button class="btn btn-primary"> Encuntra tus grupos </button>
                    </div>
                </div>
                <h5>Contactos</h5>
                <ul class="list-unstyled">
                    <li>Dennis Han</li>
                    <li>Eric Jones</li>
                    <li>Cynthia Lopez</li>
                    <li>Anna Becklund</li>
                    <li>Aiden Brown</li>
                </ul>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</html>
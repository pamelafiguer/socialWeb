
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'App' )</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/amigos.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

    <div class="container-fluid">
        <div class="row">

            <header>
                <div class="navbar">
                    <div class="navbar-left" style="height: 45px;">
                        
                        <input type="text" placeholder="Search">
                        <img src="/css/imgen/111.PNG" alt="" width="55" height="55" style="vertical-align: middle;margin-top: -45px;margin-left: 25px;">
                    </div>
                    <div class="navbar-center">
                        <a href="/feed" class="nav-icon active"><i class="fas fa-home"></i></a>
                        <a href="/videos" class="nav-icon"><i class="fas fa-tv"></i></a>
                        <a href="/amigos" class="nav-icon"><i class="fas fa-users"></i></a>
                        
                    </div>
                    <div class="navbar-right">
                    
                        <a href="#" class="nav-icon" data-bs-toggle="modal" data-bs-target="#notificationsModal">
                            <i class="fas fa-bell"></i>
                            <span class="badge bg-danger" id="notificationCount">3</span>
                        </a>
                        <a href="#" class="nav-icon" data-bs-toggle="modal" data-bs-target="#messageModal">
                            <i class="fas fa-envelope"></i></a>
                        <a href="/Usuario" class="nav-icon">
                            <img src="{{ Auth::user()->foto_perfil ? asset('storage/public/' . Auth::user()->foto_perfil) : 'https://via.placeholder.com/100' }}"
                                        class="rounded-circle me-2" style="width: 30px;height: 30px;margin-top: 0.1px;/* border-radius: 100px; */;object-fit: cover;" >
                            {{ Auth::check() ? Auth::user()->nombre : 'nombre' }}</a>
                    </div>
                </div>
            </header>

            <div class="col-md-3 sidebar">
                <h5>Home</h5>
                <ul class="nav flex-column">
                    <li class="nav-item"><a href="/Usuario" class="nav-link"><i class="fas fa-user"></i>  {{ Auth::check() ? Auth::user()->nombre : 'nombre' }}</a></li>
                    <li class="nav-item"><a href="/Solicitudes" class="nav-link"><i class="fas fa-user-plus"></i> Solicitudes de amistad</a></li>
                    <li class="nav-item"><a href="/amigos" class="nav-link"><i class= "fas fa-users"></i> Todos los amigos</a></li>
                    <li class="nav-item"><a href="/EnviarSolicitudes" class="nav-link"><i class="fas fa-users"></i>  Enviar Solicitud</a></li>

                </ul>
            </div>

            <div class="col-md-6 main-feed">
                @yield('contenido')
            </div>

        </div>
    </div>

    
    <div class="modal fade" id="notificationsModal" tabindex="-1" aria-labelledby="notificationsModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="notificationsModalLabel">Notificaciones</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <ul class="list-group" id="notificationList">
                        
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
<script src="js/feed.js"></script>

</html>
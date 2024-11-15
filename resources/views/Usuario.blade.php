@extends('layouts.user')

@section('title', 'Perfil Usuario')

@section('contenido-user')
    <div class="container">
        <!-- Encabezado de la barra superior -->
        <header class="profile-header">
            <div class="cover-photo">
                <button class="btn btn-primary add-cover-photo">Agregar foto de portada</button>
            </div>
            <div class="profile-info d-flex align-items-center">
                <img src="https://via.placeholder.com/100" alt="Foto de perfil" class="profile-photo rounded-circle">
                <div class="ml-3">
                    <h1 class="profile-name"> {{ Auth::check() ? Auth::user()->nombre : 'nombre' }} </h1>
                    <button class="btn btn-primary btn-sm add-story"><i class="fas fa-plus-circle"></i> Agregar a historia</button>
                    <button class="btn btn-secondary btn-sm edit-profile"><i class="fas fa-edit"></i> Editar perfil</button>
                </div>
            </div>
            <nav class="profile-nav mt-3">
                <ul class="nav nav-pills">
                    <li class="nav-item"><a class="nav-link active" href="#">Publicaciones</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Información</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Amigos</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Fotos</a></li>
                </ul>
            </nav>
        </header>

        <!-- Sección de Detalles y Publicaciones -->
        <div class="row mt-4">
            <aside class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Detalles</h5>
                        <button class="btn btn-outline-primary btn-block">Agregar presentación</button>
                        <button class="btn btn-outline-secondary btn-block">Editar detalles</button>
                        <button class="btn btn-outline-success btn-block">Agregar destacados</button>
                    </div>
                </div>
            </aside>
            
            <section class="col-md-8">

                <div class="post card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5>Publicaciones</h5>
                        <div>
                            <button class="btn btn-light">Filtros</button>
                            <button class="btn btn-light">Administrar publicaciones</button>
                        </div>
                    </div>
                    <div class="card-body">
                        <p>No hay publicaciones recientes.</p>
                    </div>
                </div>
            </section>
        </div>
    </div>
@endsection

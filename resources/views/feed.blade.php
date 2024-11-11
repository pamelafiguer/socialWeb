@extends('layouts.app')

@section('title', 'Home')

@section('content')

    <!-- Sección de historias -->
    <div class="d-flex justify-content-between mb-3 align-items-center">
        <h5><i class="fas fa-book-open me-2"></i>Historias</h5>
        <a href="#" class="text-decoration-none">Ver todas</a>
    </div>

    <div class="d-flex overflow-auto mb-3">
        <!-- Tarjeta de historia (Añadir historia) -->
        <div class="story-card me-2 text-center">
            <img src="/css/imgen/Selfie.jpeg" alt="Avatar" class="rounded-circle" style="width: 60px; height: 60px;">
            <p class="small mt-2">Añade tu historia</p>
        </div>
        <!-- Otras historias (ejemplo) -->
        <div class="story-card me-2 text-center">
            <img src="/css/imgen/Grupoparque.jpeg" alt="Amigo" class="rounded-circle" style="width: 60px; height: 60px;">
            <p class="small mt-2">Nombre Amigo</p>
        </div>
        <!-- Más tarjetas de historia según sea necesario -->
    </div>

    <!-- Sección para añadir un post -->
    <div class="card mb-3">
        <div class="card-body">
            <div class="d-flex align-items-center">
                <img src="/css/imgen/Selfie.jpeg" alt="Usuario" class="rounded-circle me-2"
                    style="width: 40px; height: 40px;">
                <input type="text" class="form-control" data-bs-toggle="modal" data-bs-target="#publicacionesModal"
                    placeholder="¿Qué estás pensando?" style="border-radius: 20px;">
            </div>
            <div class="d-flex justify-content-between mt-2">
                <button class="btn btn-light m-2 d-flex align-items-center">
                    <i class="fas fa-camera-retro me-1"></i> Foto
                </button>
                <button class="btn btn-light d-flex align-items-center" style="margin-left: 15px;">
                    <i class="fas fa-video me-1"></i> Video
                </button>
            </div>
        </div>
    </div>

    <!-- Ejemplo de post -->
    <div class="card mb-3">
        <div class="card-body">
            <div class="d-flex align-items-center mb-2">
                <img src="/css/imgen/Selfie.jpeg" alt="Tom Russo" class="rounded-circle me-2"
                    style="width: 40px; height: 40px;">
                <h6 class="mb-0">Tom Russo</h6>
            </div>
            <p>Not having fun at all</p>
            <img src="/css/imgen/Grupoparque.jpeg" alt="Post Image" class="img-fluid rounded">
        </div>
    </div>

    <div id="feed">
        @foreach ($publicaciones as $publicacion)
            <div class="card mb-3">
                <div class="card-body">
                    <h6 class="mb-0">{{ session('usuario_nombre', 'Usuario') }}</h6>
                    <p>{{ $publicacion->contenido }}</p>
                    @if ($publicacion->imagen)
                        <img src="{{ asset('storage/' . $publicacion->imagen) }}" alt="Imagen de publicación"
                            class="img-fluid rounded">
                    @endif
                </div>
            </div>
        @endforeach

    </div>

    <!-- Modal para Crear Publicación -->
    <div class="modal fade" id="publicacionesModal" tabindex="-1" aria-labelledby="publicacionesModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="publicacionesModalLabel">Crear una Publicación</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('Nuevofeed') }}" id="postForm" method="POST" enctype="multipart/form-data">
                        @csrf <!-- Token de seguridad para Laravel -->
                        <div class="mb-3">
                            <textarea class="form-control" id="postContent" name="content" rows="3" placeholder="Escribe algo..."></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="postImage" class="form-label">Subir Imagen (opcional)</label>
                            <input class="form-control" type="file" id="postImage" name="image" accept="image/*">
                        </div>
                        <button type="submit" class="btn btn-primary">Publicar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>






@endsection

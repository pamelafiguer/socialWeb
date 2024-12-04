@extends('layouts.app')

@section('title', 'Home')

@section('content')

    <div class="d-flex justify-content-between mb-3 align-items-center">
        <h5><i class="fas fa-book-open me-2"></i>Historias</h5>
        <a href="#" class="text-decoration-none">Ver todas</a>
    </div>

    <div class="d-flex overflow-auto mb-3">
        <div class="story-card me-2 text-center">
            <img src="/css/imgen/Selfie.jpeg" alt="Avatar" class="rounded-circle" style="width: 60px; height: 60px;">
            <p class="small mt-2">Añade tu historia</p>
        </div>
        <div class="story-card me-2 text-center">
            <img src="/css/imgen/Grupoparque.jpeg" alt="Amigo" class="rounded-circle" style="width: 60px; height: 60px;">
            <p class="small mt-2">Nombre Amigo</p>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-body">
            <div class="d-flex align-items-center">
                <img src="/css/imgen/Selfie.jpeg" alt="Usuario" class="rounded-circle me-2"
                    style="width: 40px; height: 40px;">
                <input type="text" class="form-control" data-bs-toggle="modal" data-bs-target="#publicacionesModal"
                    placeholder="¿Qué estás pensando?" style="border-radius: 20px;">
            </div>
            <div class="d-flex justify-content-between mt-2">
                <button class="btn btn-light"><i class="fas fa-video"></i> Video en vivo</button>
                <button class="btn btn-light"><i class="fas fa-image"></i> Foto/Video</button>
                <button class="btn btn-light"><i class="fas fa-smile"></i> Acontecimiento importante</button>
            </div>
        </div>
    </div>

    <div id="feed">
        @foreach ($publicaciones as $publicacion)
            <div class="card mb-3">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2">
                        <img src="{{ $publicacion->foto_perfil ? asset('storage/public/' . $publicacion->foto_perfil) : 'https://via.placeholder.com/100' }}"
                            alt="{{ $publicacion->usuario_nombre }}" class="rounded-circle me-2"
                            style="width: 40px; height: 40px;">
                        <h6 class="mb-0">{{ $publicacion->usuario_nombre }}</h6>
                    </div>
                    <p>{{ $publicacion->contenido }}</p>
                    @if ($publicacion->imagen)
                        <img src="{{ asset('storage/public/' . $publicacion->imagen) }}" alt="Imagen de publicación"
                            class="img-fluid rounded mb-3">
                    @endif


                    <!-- Reacciones -->

                    <div class="d-flex justify-content-between">
                        <form action="{{ route('reaccionar', $publicacion->id_publicacion) }}" method="POST"
                            class="d-inline">
                            @csrf
                            <input type="hidden" name="tipo" value="me gusta">
                            <button type="submit" class="btn btn-link text-decoration-none">
                                <i class="fas fa-thumbs-up"></i> Me gusta
                                ({{ collect($reacciones)->where('id_publicacion', $publicacion->id_publicacion)->where('reaccion', 'me gusta')->count() }})
                            </button>
                        </form>
                        <form action="{{ route('reaccionar', $publicacion->id_publicacion) }}" method="POST"
                            class="d-inline">
                            @csrf
                            <input type="hidden" name="tipo" value="me encanta">
                            <button type="submit" class="btn btn-link text-decoration-none">
                                <i class="fas fa-heart"></i> Me encanta
                                ({{ collect($reacciones)->where('id_publicacion', $publicacion->id_publicacion)->where('reaccion', 'me encanta')->count() }})
                            </button>
                        </form>
                        <button class="btn btn-link text-decoration-none" data-bs-toggle="collapse"
                            data-bs-target="#comentarios-{{ $publicacion->id_publicacion }}">
                            <i class="fas fa-comment"></i> Comentar
                            ({{ collect($comentarios)->where('id_publicacion', $publicacion->id_publicacion)->count() }})
                        </button>
                    </div>


                    <!-- Comentarios -->
                    <div class="collapse mt-2" id="comentarios-{{ $publicacion->id_publicacion }}">
                        <form action="{{ route('comentar', $publicacion->id_publicacion) }}" method="POST">
                            @csrf
                            <div class="d-flex align-items-center mb-2">
                                <input type="text" name="contenido" class="form-control"
                                    placeholder="Escribe un comentario..." required>
                                <button type="submit" class="btn btn-primary ms-2">Enviar</button>
                            </div>
                        </form>
                        <!-- Aquí insertar los comentarios del procedimiento -->
                        @foreach ($comentarios[$publicacion->id_publicacion] ?? [] as $comentario)
                            <div class="d-flex align-items-center mb-2">
                                <img src="{{ $comentario->foto_perfil ? asset('storage/public/' . $comentario->foto_perfil) : 'https://via.placeholder.com/100' }}"
                                    class="rounded-circle me-2" style="width: 30px; height: 30px;">
                                <div>
                                    <h6 class="mb-0">{{ $comentario->usuario_nombre ?? 'Usuario desconocido' }}</h6>
                                    <p class="mb-0">{{ $comentario->contenido}}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Modal para nueva publicación -->
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
                        @csrf
                        <div class="mb-3">
                            <textarea class="form-control" id="postContent" name="content" rows="3" placeholder="Escribe algo..."></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="postImages" class="form-label">Subir Imágenes</label>
                            <input class="form-control" type="file" id="postImages" name="imagen" accept="image/*"
                                multiple>
                        </div>
                        <button type="submit" class="btn btn-primary">Publicar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

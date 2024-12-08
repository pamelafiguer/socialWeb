@extends('layouts.app')

@section('title', 'Home')

@section('content')
    @if (session('success'))
        <div class="alert alert-success mt-2">{{ session('success') }}</div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger mt-2">{{ session('error') }}</div>
    @endif

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
                        <!-- Botón "Me gusta" -->
                        <form action="{{ route('reaccionar', $publicacion->id_publicacion) }}" method="POST"
                            class="d-inline reaccion-form">
                            @csrf
                            <input type="hidden" name="tipo" value="me gusta">
                            <button type="submit"
                                class="btn btn-link text-decoration-none btn-reaccion {{ $publicacion->reaccion_usuario === 'me gusta' ? 'active' : '' }}"
                                data-reaccion="me gusta" data-id-publicacion="{{ $publicacion->id_publicacion }}">
                                <i class="fas fa-thumbs-up"></i> Me gusta
                            </button>
                        </form>

                        <!-- Botón "Me encanta" -->
                        <form action="{{ route('reaccionar', $publicacion->id_publicacion) }}" method="POST"
                            class="d-inline reaccion-form">
                            @csrf
                            <input type="hidden" name="tipo" value="me encanta">
                            <button type="submit"
                                class="btn btn-link text-decoration-none btn-reaccion {{ $publicacion->reaccion_usuario === 'me encanta' ? 'active' : '' }}"
                                data-reaccion="me encanta" data-id-publicacion="{{ $publicacion->id_publicacion }}">
                                <i class="fas fa-heart"></i> Me encanta
                            </button>
                        </form>
                        <button class="btn btn-link text-decoration-none" data-bs-toggle="collapse"
                            data-bs-target="#comentarios-{{ $publicacion->id_publicacion }}">
                            <i class="fas fa-comment"></i> Comentar

                        </button>
                    </div>
                    <div class="reacciones">
                        <a href="#" class="text-decoration-none" id="ver-reacciones"
                            data-id-publicacion="{{ $publicacion->id_publicacion }}">
                            {{ $publicacion->me_gusta + $publicacion->me_encanta }} Reacciones
                        </a>
                    </div>



                    <!-- Comentarios -->
                    <div class="collapse mt-2" id="comentarios-{{ $publicacion->id_publicacion }}">
                        <form action="{{ route('comentar', ['id_publicacion' => $publicacion->id_publicacion]) }}"
                            method="POST">
                            @csrf
                            <!-- Input oculto para el id_publicacion -->
                            <input type="hidden" name="id_publicacion" value="{{ $publicacion->id_publicacion }}">
                            <!-- Input oculto para el id_usuario -->
                            <input type="hidden" name="id_usuario" value="{{ auth()->id() }}">

                            <div class="d-flex align-items-center mb-2">
                                <!-- Input para el contenido del comentario -->
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
                                    <p class="mb-0">{{ $comentario->contenido }}</p>
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
                    <form action="{{ route('Nuevofeed') }}" id="postForm" method="POST"
                        enctype="multipart/form-data">
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

    <div id="modal-reacciones" class="modal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Usuarios que reaccionaron</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <ul id="usuarios-reacciones">
                        
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        jQuery(document).ready(function() {
            
            $(document).on('click', '#ver-reacciones', function(e) {
                e.preventDefault();

                
                const idPublicacion = $(this).data('id-publicacion');

                
                $.ajax({
                    url: '/reacciones/' +
                        idPublicacion, 
                    method: 'GET',
                    success: function(reacciones) {
                        
                        let html = '';
                        reacciones.forEach(function(usuario) {
                            html += `
                        <div class="d-flex align-items-center mb-2">
                            <img src="${usuario.foto_perfil ? '/storage/public/' + usuario.foto_perfil : 'https://via.placeholder.com/100'}" 
                                alt="${usuario.usuario_nombre}" class="rounded-circle me-2" style="width: 40px; height: 40px;margin-top: 10px;object-fit: cover;"">
                            <h6 class="mb-0">${usuario.usuario_nombre} reacciono con ${usuario.reaccion}</h6>
                        </div>
                    `;
                        });


                        
                        $('#usuarios-reacciones').html(html);

                        
                        $('#modal-reacciones').modal('show');
                    },
                    error: function() {
                        alert('No se pudieron obtener las reacciones.');
                    }
                });
            });
            $(document).on('click', '.close', function() {
                $('#modal-reacciones').modal('hide');
            });



        });
    </script>


@endsection

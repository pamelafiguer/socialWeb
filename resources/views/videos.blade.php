@extends('layouts.appi')

@section('title', 'friend Feed')

@section('contenido')
    
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
                <img src="/css/imgen/Selfie.jpeg"  alt="Usuario" class="rounded-circle me-2" style="width: 40px; height: 40px;">
                <input type="text" class="form-control" placeholder="¿Qué estás pensando?" style="border-radius: 20px;">
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
    
    
    <div class="card mb-3">
        <div class="card-body">
            <div class="d-flex align-items-center mb-2">
                <img src="/css/imgen/Selfie.jpeg"  alt="Tom Russo" class="rounded-circle me-2" style="width: 40px; height: 40px;">
                <h6 class="mb-0">Tom Russo</h6>
            </div>
            <p>Not having fun at all</p>
            <img src="/css/imgen/Grupoparque.jpeg"  alt="Post Image" class="img-fluid rounded">
        </div>
    </div>
@endsection

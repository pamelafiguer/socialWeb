@extends('layouts.app')

@section('tittle', 'Home Feed')

@section('content')
    <div class="d-flex justify-content-between mb-3">
        <h5>Historias</h5>
        <a href="#">See Ali</a>
    </div>
    <div class="d-flex overflow-auto mb-3">
        <div class="story-card me-2">
            <img src="" alt="" class="rounded-circle">
            <p>Añade tu historia</p>
        </div>
    </div>
    <div class="card mb-3">
        <div class="card-body">
            <input type="text" class="form-control" placeholder="Añadir post">
            <div class="d-flex mt-2">
                <button class="btn btn-light m-2">Photo</button>
                <button class="btn btn-light">Video</button>
            </div>
        </div>
    </div>
    <div class="card mb-3">
        <div class="card-body">
            <h6>Tom Russo</h6>
            <p>Not having fun at all </p>
            <img src="" alt="" class="img-fluid" alt="Post Image">
        </div>
    </div>
    
@endsection
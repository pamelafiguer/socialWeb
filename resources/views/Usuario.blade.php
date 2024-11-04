<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Perfil de Facebook</title>
  <link rel="stylesheet" href="perfil.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
  <div class="container">
    <!-- Encabezado de la barra superior -->
    <header class="top-bar">
      <input type="text" placeholder="Buscar en Facebook" class="search-bar">
      <div class="top-icons">
        <i class="fas fa-home"></i>
        <i class="fas fa-user-friends"></i>
        <i class="fas fa-tv"></i>
        <i class="fas fa-users"></i>
        <i class="fas fa-bell"></i>
        <i class="fas fa-caret-down"></i>
      </div>
    </header>
    
    <!-- Sección de Portada y Perfil -->
    <section class="profile-header">
      <div class="cover-photo">
        <button class="add-cover-photo">Agregar foto de portada</button>
      </div>
      <div class="profile-info">
        <img src="https://via.placeholder.com/100" alt="Foto de perfil" class="profile-photo">
        <h1 class="profile-name">Sofia Rosas</h1>
        <button class="add-story"><i class="fas fa-plus-circle"></i> Agregar a historia</button>
        <button class="edit-profile"><i class="fas fa-edit"></i> Editar perfil</button>
      </div>
      <nav class="profile-nav">
        <a href="#">Publicaciones</a>
        <a href="#">Información</a>
        <a href="#">Amigos</a>
        <a href="#">Fotos</a>
        <a href="#">Videos</a>
        <a href="#">Registros de visitas</a>
        <a href="#">Más</a>
      </nav>
    </section>
    
    <!-- Sección de Detalles y Publicaciones -->
    <main class="main-content">
      <aside class="sidebar">
        <h2>Detalles</h2>
        <button>Agregar presentación</button>
        <button>Editar detalles</button>
        <button>Agregar destacados</button>
      </aside>
      
      <section class="posts">
        <div class="new-post">
          <img src="https://via.placeholder.com/40" alt="Icono usuario">
          <input type="text" placeholder="¿Qué estás pensando?" class="post-input">
          <div class="post-options">
            <button><i class="fas fa-video"></i> Video en vivo</button>
            <button><i class="fas fa-image"></i> Foto/Video</button>
            <button><i class="fas fa-smile"></i> Acontecimiento importante</button>
          </div>
        </div>
        
        <div class="post">
          <h3>Publicaciones</h3>
          <button>Filtros</button>
          <button>Administrar publicaciones</button>
        </div>
      </section>
    </main>
  </div>
</body>
</html>

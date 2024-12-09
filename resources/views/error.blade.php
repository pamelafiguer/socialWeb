<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error</title>
</head>
<body>
    <h1>¡Oops! Algo salió mal.</h1>
    <p>{{ session('error') }}</p>
    <a href="{{ route('login') }}">Volver al inicio</a>
</body>
</html>

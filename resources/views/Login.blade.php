<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>
    <div class="background-image"></div>
    <div class="login-container">
        <h1>LOGIN</h1>
        <form action="{{ route('LoginForm') }}" method="POST">
            @csrf
            <p>How do I get started lorem ipsum dolor at?</p>

            <div class="input-group">
                <svg xmlns="http://www.w3.org/2000/svg" height="20" width="20" viewBox="0 0 24 24">
                    <path
                        d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" />
                </svg>
                <input type="email" name="email" id="email" placeholder="Correo">
            </div>

            <div class="input-group">
                <svg xmlns="http://www.w3.org/2000/svg" height="20" width="20" viewBox="0 0 24 24">
                    <path
                        d="M12 2a5 5 0 00-5 5v3H6c-1.1 0-2 .9-2 2v8c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2v-8c0-1.1-.9-2-2-2h-1V7a5 5 0 00-5-5zm0 2a3 3 0 013 3v3H9V7a3 3 0 013-3zm-1 10h2v4h-2v-4z" />
                </svg>
                <input type="password"  name="password" id="password" placeholder="Password">
            </div>

            <button class="login-btn">Login Now</button>

            <p class="social-login">Login with Others</p>

            <a href="#" class="social-btn google">
                <i class="fab fa-google"></i>
                Ingresa con Google
            </a>
            <a href="#" class="social-btn facebook">
                <img src="https://upload.wikimedia.org/wikipedia/commons/5/51/Facebook_f_logo_%282019%29.svg"
                    alt="Facebook Logo">
                Ingresa con Facebook
            </a>

            <a href="/Register">Si no tienes una cuenta Registrate aqui!</a>
        </form>
    </div>


    <div class="info-container">
        <div class="welcome-message">
            <p>Very good works are waiting for you Login Now!!!</p>
            <img src="/css/imgen/Selfie.jpeg" alt="Welcome Image">
        </div>
</body>

</html>

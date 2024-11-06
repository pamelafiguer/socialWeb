<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Pagina social</title>
    <link rel="stylesheet" href="css/estilos.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

</head>

<body>
    <div class="background-image"></div>
    <div class="container">

    <div class="form-container">
            <h2>REGISTER</h2>
            <p>Sign up to get started with our services</p>
        <form action="{{ route('RegisterForm') }}" method="POST">

                @csrf
            
            
                <div class="input-group">
                    <label for="name" style="vertical-align: inherit;font-size: small;">Nombres: 
                        <input type="text" name="Nombres" id="name" style="width: 160%;">
                    </label>
                </div>
                <div class="input-group">
                    <label for="firstname" style="vertical-align: inherit;font-size: small;">Apellidos:
                        <input type="text" name="Apellidos" id="lastname" style="width: 160%;">
                    </label>
                </div>

                <div class="input-group">
                    <label for="day">Dia:
                        
                        <select class="custom-select-1" name="birthday_day" id="dia">
                            <?php
        
                                    for ($i = 1; $i <= 31; $i++) {
                                        echo "<option value='$i'>$i</option>";
                                    }
                            ?>
                        </select>
                    </label>
                    <label for="month">Mes:
                        
                        <select class="custom-select-2"  name="birthday_month" id="mes">
                            <?php
        
                                for ($i = 1; $i <= 12; $i++) {
            
                                $nombreMes = date("F", mktime(0, 0, 0, $i, 1));
                                echo "<option value='$i'>$nombreMes</option>";
                                }
                            ?>
                        </select>
                    </label>
                    
                    <label for="Year">Año:
                        
                        <select class="custom-select-3"  name="bithday_year" id="year" style="width: 120%;">
                            <?php
        
                                for ($year = 2024; $year >= 1905; $year--) {
                                echo "<option value='$year'>$year</option>";
                                }
                            ?>
                        </select>
                    </label>
                    
                </div>
                <div class="input-group">Género:
                    <div class="generos" style="margin-left: 30px;">
                        <label for="genero">Mujer
                            <input type="radio" name="sex" id="sex" style="margin: 10px;">
                        </label>
                        <label for="genero">Hombre
                            <input type="radio" name="sex" id="sex" style="margin: 10px;">
                        </label>
                        <label for="genero">Personalizado
                            <input type="radio" name="sex" id="sex" style="margin: 10px;">
                        </label>
                    </div>
                    
                </div>
                <div class="input-group">
                    <label for="email">
                        <input type="email" name="email" id="correo" placeholder="Ingresa tu correo" style="width: 220%;">
                    </label>
                </div>
                <div class="input-group">
                    <label for="password">
                        <input type="password" name="password" id="password" placeholder="Contraseña Nueva" style="width: 220%;">
                    </label>
                </div>
                <div class="input-group">
                    <label for="button">
                        <button> Registrarte </button>
                    </label>
                        <br>
                        <br>
                        

                    <a href="/">¿Ya tienes una cuenta? </a>
                </div>
            
                
            </div>
            <div class="info-container">
                <p>Join us and start exploring new opportunities!<br>Sign up now to begin your journey!</p>
                <img src="/css/imgen/Grupoparque.jpeg" alt="Welcome Image">
        </div>
            </div>
        
        
        </form>
    </div>
</div>

</body>

</html>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login Tokenautas</title>
</head>
<body>

<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (isset($_SESSION['usuario_logueado']) && $_SESSION['usuario_logueado'] === true) {
    // Usuario logueado, insertamos el código JS para redirección
    echo "<script type='text/javascript'>
        document.addEventListener('DOMContentLoaded', function() {
            var anchoPantalla = window.innerWidth;
            if(anchoPantalla < 768) {
                // Redireccionar a las vistas para celulares
                window.location.href = '/vistas_celular/dash/dashboardVistaCelular.php';
            } else {
                // Redireccionar a las vistas para tablets/computadoras
                window.location.href = '/vistas/dashboard.php';
            }
        });
    </script>";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <!-- Aquí iría el resto de tu cabecera -->
</head>
<body>
    <!-- El resto de tu HTML va aquí -->
</body>
</html>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!--=============== REMIXICONS ===============-->
    <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet">

    <!--=============== CSS ===============-->
 
    <title>Inicio de sesion Tokenautas</title>
    <link rel="icon" href="../cohete06.png" type="image/png">
</head>
<body>
    <div class="container" style="transform: scale(0.8);">
        <div class="login__content" style="margin-top: -30px;">
            <img src="../loginTokenautas.webp" style="background-size: cover;" alt="login image" class="login__img">

            <form action="../Controladores/LoginControlador.php" method="post" class="login__form">
                <div>
                    <h1 class="login__title">
                        <span style="color: #1c0e2a;">Iniciar Sesion</span> 
                    </h1>
                    <p class="login__description">
                      Por favor ingresa tu usuario y contraseña
                    </p>
                </div>
                
                <div>
                    <div class="login__inputs">
                        <div>
                            <label for="input-email" class="login__label">Correo</label>
                            <input  type="texto" name="username" placeholder="Ingresa tu correo" required="true" class="login__input" id="input-email">
                        </div>

                        <div>
                            <label for="input-pass" class="login__label">Password</label>

                            <div class="login__box">
                                <input type="password" placeholder="Ingresa tu contraseña" name="password" required="true"  class="login__input" id="input-pass">
                                <i class="ri-eye-off-line login__eye" id="input-icon"></i>
                            </div>
                        </div>
                    </div>

                    <div class="login__check">
                        <input type="checkbox" class="login__check-input" id="input-check">
                        <label for="input-check" class="login__check-label">Remember me</label>
                    </div>
                </div>

                <div>
                    <div class="login__buttons">
                        <button type="submit" name="Login" value="Iniciar Sesión" class="login__button">Ingresar</button>
                        <!-- Cambio aquí: reemplazo el botón de Sign Up por un enlace -->
                 
                    </div>
                    <input type="hidden" id="anchoPantalla" name="anchoPantalla" value="">

                    <a href="./registro.php" class="login__forgot" style="color: #1c0e2a;">No tienes cuenta? Registrate Aqui!</a>
                </div>
            </form>
        </div>
    </div>

    <!--=============== MAIN JS ===============-->
    <script>
        /*=============== SHOW HIDDEN - PASSWORD ===============*/
const showHiddenPass = (inputPass, inputIcon) =>{
   const input = document.getElementById(inputPass),
         iconEye = document.getElementById(inputIcon)
         
   iconEye.addEventListener('click', () =>{
       // Change password to text
       if(input.type === 'password'){
           // Switch to text
           input.type = 'text'

           // Add icon
           iconEye.classList.add('ri-eye-line')
           // Remove icon
           iconEye.classList.remove('ri-eye-off-line')
       }else{
           // Change to password
           input.type = 'password'

           // Remove icon
           iconEye.classList.remove('ri-eye-line')
           // Add icon
           iconEye.classList.add('ri-eye-off-line')
       }
   })
}

showHiddenPass('input-pass','input-icon')
    </script>


<script>
    // Capturar el ancho de la pantalla del dispositivo y actualizar el campo oculto anchoPantalla
    document.getElementById('anchoPantalla').value = window.innerWidth;
</script>

    <style>
        @charset "UTF-8";
/*=============== GOOGLE FONTS ===============*/
@import url("https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap");
/*=============== VARIABLES CSS ===============*/
:root {
  /*========== Colors ==========*/
  /*Color mode HSL(hue, saturation, lightness)*/
  --first-color: hsl(244, 75%, 57%);
  --second-color: hsl(249, 64%, 47%);
  --title-color: hsl(244, 12%, 12%);
  --text-color: hsl(244, 4%, 36%);
  --body-color: hsl(208, 97%, 85%);
  /*========== Font and typography ==========*/
  /*.5rem = 8px | 1rem = 16px ...*/
  --body-font: "Poppins", sans-serif;
  --h2-font-size: 1.25rem;
  --small-font-size: .813rem;
  --smaller-font-size: .75rem;
  /*========== Font weight ==========*/
  --font-medium: 500;
  --font-semi-bold: 600;
}
@media screen and (min-width: 1024px) {
  :root {
    --h2-font-size: 1.75rem;
    --normal-font-size: 1rem;
    --small-font-size: .875rem;
    --smaller-font-size: .813rem;
  }
}

/*=============== BASE ===============*/
* {
  box-sizing: border-box;
  padding: 0;
  margin: 0;
}



input,
button {
  font-family: var(--body-font);
  border: none;
  outline: none;
}

img {
  max-width: 100%;
  height: auto;
}




/*=============== LOGIN FORM ===============*/
.login__content, .login__form, .login__inputs {
  display: grid;
}
.login__content {
  position: relative;
  height: 100vh;
  align-items: center;
  padding-bottom: 100px;

  
}
.login__img {
  position: absolute;
  width: 100%;
  height: 100%;
  object-fit: cover;
  object-position: center;
}
.login__form {
  position: relative;
  background-color: hsla(244, 16%, 92%, 0.6);
  border: 2px solid hsla(244, 16%, 92%, 0.75);
  margin-inline: 1.5rem;
  row-gap: 1.25rem;
  backdrop-filter: blur(20px);
  padding: 2rem;
  border-radius: 1rem;
}
.login__title {
  color: #1c0e2a;
  font-size: var(--h2-font-size);
  margin-bottom: 0.5rem;
}
.login__title span {
  color: var(--first-color);
}
.login__description {
  font-size: var(--small-font-size);
}
.login__inputs {
  row-gap: 0.75rem;
  margin-bottom: 0.5rem;
}
.login__label {
  display: block;
  color: var(--title-color);
  font-size: var(--small-font-size);
  font-weight: var(--font-semi-bold);
  margin-bottom: 0.25rem;
}
.login__input {
  width: 100%;
  padding: 14px 12px;
  border-radius: 6px;
  border: 2px solid var(--text-color);
  background-color: hsla(244, 16%, 92%, 0.6);
  color: var(--title-color);
  font-size: var(--smaller-font-size);
  font-weight: var(--font-medium);
  transition: border 0.4s;
}
.login__input::placeholder {
  color: var(--text-color);
}
.login__input:focus, .login__input:valid {
  border: 2px solid var(--first-color);
}
.login__box {
  position: relative;
}
.login__box .login__input {
  padding-right: 36px;
}
.login__eye {
  width: max-content;
  height: max-content;
  position: absolute;
  right: 0.75rem;
  top: 0;
  bottom: 0;
  margin: auto 0;
  font-size: 1.25rem;
  cursor: pointer;
}
.login__check {
  display: flex;
  column-gap: 0.5rem;
  align-items: center;
}
.login__check-input {
  appearance: none;
  width: 16px;
  height: 16px;
  border: 2px solid var(--text-color);
  background-color: hsla(244, 16%, 92%, 0.2);
  border-radius: 0.25rem;
}
.login__check-input:checked {
  background: var(--first-color);
}
.login__check-input:checked::before {
  content: "✔";
  display: block;
  color: #fff;
  font-size: 0.75rem;
  transform: translate(1.5px, -2.5px);
}
.login__check-label {
  font-size: var(--small-font-size);
}
.login__buttons {
  display: flex;
  column-gap: 0.75rem;
}
.login__button {
  width: 100%;
  padding: 14px 2rem;
  border-radius: 6px;
  background: hsl(270, 50%, 11%) !important;
  color: #fff;
  font-size: var(--small-font-size);
  font-weight: var(--font-semi-bold);
  box-shadow: 0 6px 24px hsla(244, 75%, 48%, 0.5);
  margin-bottom: 1rem;
  cursor: pointer;
}
.login__button-ghost {
  background: hsla(244, 16%, 92%, 0.6);
  border: 2px solid var(--first-color);
  color: var(--first-color);
  box-shadow: none;
}
.login__forgot {
  font-size: var(--smaller-font-size);
  font-weight: var(--font-semi-bold);
  color: var(--first-color);
  text-decoration: none;
}

/*=============== BREAKPOINTS ===============*/
/* For small devices */
@media screen and (max-width: 360px) {
  .login__buttons {
    flex-direction: column;
  }
}
/* For medium devices */
@media screen and (min-width: 576px) {
  .login__form {
    width: 450px;
    justify-self: center;
  }
}
/* For large devices */
@media screen and (min-width: 1064px) {
  .container {
    height: 100vh;
    display: grid;
    place-items: center;
  }
  .login__content {
    width: 1024px;
    height: 600px;
  }
  .login__img {
    border-radius: 3.5rem;
    box-shadow: 0 24px 48px hsla(244, 75%, 36%, 0.45);
  }
  .login__form {
    justify-self: flex-end;
    margin-right: 4.5rem;
  }
}
@media screen and (min-width: 1200px) {
  .login__content {
    height: 700px;
  }
  .login__form {
    row-gap: 2rem;
    padding: 3rem;
    border-radius: 1.25rem;
    border: 2.5px solid hsla(244, 16%, 92%, 0.75);
  }
  .login__description, .login__label, .login__button {
    font-size: var(--normal-font-size);
  }
  .login__inputs {
    row-gap: 1.25rem;
    margin-bottom: 0.75rem;
  }
  .login__input {
    border: 2.5px solid var(--text-color);
    padding: 1rem;
    font-size: var(--small-font-size);
  }
  .login__input:focus, .login__input:valid {
    border: 2.5px solid var(--first-color);
  }
  .login__button {
    padding-block: 1rem;
    margin-bottom: 1.25rem;
  }
  .login__button-ghost {
    border: 2.5px solid var(--first-color);
  }
}
    </style>
</body>
</html>



































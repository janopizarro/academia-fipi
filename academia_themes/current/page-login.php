<?php 
get_header("auth");
?>

<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/login.css">

<!-- Page content -->
<div class="page-content">

<!-- Main content -->
<div class="content-wrapper">

    <!-- Content area -->
    <div class="content d-flex justify-content-center align-items-center">

        <!-- Login form -->
        <form class="login-form" id="form_login">
            <div class="card mb-0">
                <div class="card-body">
                    <div class="text-center mb-3">
                        <img src="<?php echo get_template_directory_uri(); ?>/images/logo.png" alt="" width="160">
                        <h5 class="mb-0">Inicio de sesión</h5>
                        <span class="d-block text-muted">Ingresa con tu email y clave</span>
                    </div>

                    <div class="form-group form-group-feedback form-group-feedback-left">
                        <input type="email" name="email" class="form-control" placeholder="Email">
                        <div class="form-control-feedback">
                            <i class="icon-user text-muted"></i>
                        </div>
                    </div>

                    <div class="form-group form-group-feedback form-group-feedback-left">
                        <input type="password" name="password" class="form-control" placeholder="Clave">
                        <div class="form-control-feedback">
                            <i class="icon-lock2 text-muted"></i>
                        </div>
                    </div>

                    <div class="form-group">
                        <button type="button" id="entrar" class="btn btn-primary btn-block">ENTRAR <i class="icon-circle-right2 ml-2"></i></button>
                    </div>

                    <div id="mensaje" class="hide"></div>

                    <div class="text-center">
                        <a href="<?php echo home_url(); ?>/wp-login.php?action=lostpassword">¿Olvidaste tu clave?</a>
                    </div>
                </div>
            </div>
        </form>
        <!-- /login form -->

    </div>
    <!-- /content area -->

<?php 
get_footer("auth");
?>

<script>
let enviar = document.getElementById("entrar");
let mensaje = document.getElementById("mensaje");

entrar.addEventListener("click", function(){

    mensaje.style.display = "none";

    const data = new FormData(document.getElementById("form_login"));
    fetch('<?php echo get_template_directory_uri(); ?>/page-login--fetch.php', {
        method: 'POST',
        body: data
    })
    .then(function(response) {

        if(response.ok) {
            return response.text()
        } else {
            throw "Error en la llamada Ajax";
        }

    })
    .then(function(texto) {

        let res = JSON.parse(texto);

        mensaje.style.display = "block";
        mensaje.innerHTML = res.html;

        if(res.status){
            setTimeout(function () {
                window.location.href= '<?php echo home_url(); ?>';
            },3000); // 5 seconds
        }

    })
    .catch(function(err) {
        console.log(err);
    });


});

</script>
<?php 
get_header("auth");
?>

<style>
body{
	background-image: url("<?php echo get_template_directory_uri(); ?>/images/back-login.jpg");
	background-size: cover;
}
</style>

<!-- Page content -->
<div class="page-content">

<!-- Main content -->
<div class="content-wrapper">

    <!-- Content area -->
    <div class="content d-flex justify-content-center align-items-center">

        <!-- Login form -->
        <form class="login-form" action="index.html">
            <div class="card mb-0">
                <div class="card-body">
                    <div class="text-center mb-3">
                        <img src="<?php echo get_template_directory_uri(); ?>/images/logo.png" alt="" width="160">
                        <h5 class="mb-0">Regístro</h5>
                        <span class="d-block text-muted">Ingresa con tu email y clave</span>
                    </div>

                    <div class="form-group form-group-feedback form-group-feedback-left">
                        <input type="text" class="form-control" placeholder="Nombre">
                    </div>

					<div class="form-group form-group-feedback form-group-feedback-left">
                        <input type="text" class="form-control" placeholder="Apellido">
                    </div>

					<div class="form-group form-group-feedback form-group-feedback-left">
                        <input type="text" class="form-control" placeholder="Email">
                    </div>

					<div class="form-group form-group-feedback form-group-feedback-left">
                        <input type="tel" class="form-control" placeholder="Teléfono">
                    </div>

                    <div class="form-group form-group-feedback form-group-feedback-left">
                        <input type="password" class="form-control" placeholder="Clave">
                        <div class="form-control-feedback">
                            <i class="icon-lock2 text-muted"></i>
                        </div>
                    </div>

					<div class="form-group form-group-feedback form-group-feedback-left">
                        <input type="password" class="form-control" placeholder="Confirmar Clave">
                        <div class="form-control-feedback">
                            <i class="icon-lock2 text-muted"></i>
                        </div>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary btn-block">COMPLETAR REGÍSTRO <i class="icon-circle-right2 ml-2"></i></button>
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
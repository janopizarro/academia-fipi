<div class="pagar-con-flow">

    <strong>COMPRAR CURSO</strong>

    <?php if(!isset($_SESSION["user_fipi"])) { ?>

        <p style="padding-bottom: 20px;">
            <small>Para comprar el curso debes ingresar la siguiente información:</small>
        </p>

        <form id="form-flow" method="post" action="<?php echo get_template_directory_uri(); ?>/flow/webpay--process.php">

            <input type="hidden" name="id_curso" id="id_curso" value="<?php echo get_the_ID(); ?>">
            <input type="hidden" name="curso" id="curso" value="<?php echo the_title(); ?>">
            <input type="hidden" name="monto" id="monto" value="<?php echo get_post_meta( get_the_ID(), 'curso_monto', true ); ?>">

            <div style="display:flex; padding-bottom: 20px;">

                <input type="text" name="nombre" id="nombre" placeholder="Ingresa tu nombre">
                <input type="text" name="apellido" id="apellido" placeholder="Ingresa tu apellido">

            </div>

            <div style="display:flex; padding-bottom: 20px;">

                <input type="email" name="email" id="email" placeholder="Ingresa tu email">
                <input type="tel" name="telefono" id="telefono" placeholder="Ingresa tu teléfono">

            </div>

            <div style="display:flex; padding-bottom: 20px;">

                <input type="text" name="rut" id="rut" placeholder="Ej. 11111111-1">
                <select name="region" id="regiones"></select>

            </div>

            <div style="display:flex; padding-bottom: 20px;">

                <select name="comuna" id="comunas"></select>
                <input type="text" name="dir" id="dir" placeholder="Ingresa tu dirección">

            </div>

            <?php 
            global $wp;
            ?>

            <input type="hidden" id="return" name="return" value="<?php echo home_url($wp->request).'/'; ?>">

            <button type="button" id="pagar">IR A PAGAR</button>

        </form>

    <?php } else { ?>

        <form id="form-flow" method="post" action="<?php echo get_template_directory_uri(); ?>/flow/webpay--process.php">

            <input type="hidden" name="id_curso" id="id_curso" value="<?php echo get_the_ID(); ?>">
            <input type="hidden" name="curso" id="curso" value="<?php echo the_title(); ?>">
            <input type="hidden" name="monto" id="monto" value="<?php echo get_post_meta( get_the_ID(), 'curso_monto', true ); ?>">

            <input type="hidden" name="nombre" id="nombre" value="<?php echo getDataSession("nombre"); ?>" readonly>
            <input type="hidden" name="apellido" id="apellido" value="<?php echo get_user_meta(getDataSession("id"), 'user_apellido' , true ); ?>" readonly>

            <input type="hidden" name="email" id="email" value="<?php echo getDataSession("email"); ?>" readonly>
            <input type="hidden" name="telefono" id="telefono" value="<?php echo get_user_meta(getDataSession("id"), 'user_telefono' , true ); ?>" readonly>

            <input type="hidden" name="rut" id="rut" value="<?php echo get_user_meta(getDataSession("id"), 'user_rut' , true ); ?>">
            <input type="hidden" name="region" id="regiones" value="<?php echo get_user_meta(getDataSession("id"), 'user_comuna' , true ); ?>">
            <input type="hidden" name="comuna" id="comunas" value="<?php echo get_user_meta(getDataSession("id"), 'user_region' , true ); ?>">
            <input type="hidden" name="dir" id="dir" value="<?php echo get_user_meta(getDataSession("id"), 'user_direccion' , true ); ?>">

            <?php 
            global $wp;
            ?>

            <input type="hidden" id="return" name="return" value="<?php echo home_url($wp->request).'/'; ?>">

            <button type="button" id="pagar">IR A PAGAR</button>

            </form>

    <?php } ?>

    <p><small><img src="<?php echo get_template_directory_uri(); ?>/images/logo-flow.svg" alt="" width="65"> Serás direccionado a una plataforma segura.</small></p>

</div>
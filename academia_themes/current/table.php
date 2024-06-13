<?php
// funciones
// include('funciones.php');

function orderDate($date){
    $date = explode("-",$date);
    $date = $date[2]."-".$date[1]."-".$date[0];
    return $date;
}

// Avance alumnos
add_action('admin_menu', 'register_avance_alumnos_page');

function register_avance_alumnos_page() {
  add_submenu_page( 'users.php', 'Avance alumnos', 'Avance alumnos', 'manage_options', 'avance_alumnos', 'avance_alumnos_page_callback' ); 
}

function avance_alumnos_page_callback() {

    $lib = '
    
    <link rel="stylesheet" href="//cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.6.2/css/buttons.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="//cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <script src="https://cdn.datatables.net/buttons/1.6.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.flash.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.print.min.js"></script>
    
    
    <script>
    jQuery(function($){
        $("#envios_twilio").DataTable({
            responsive: true,
            dom: "Bfrtip",
            buttons: [
                "copy", "csv", "excel", "pdf", "print"
            ],
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.10.21/i18n/Spanish.json"
            }
        });
    });
    </script>

    ';

    echo $lib;

	echo '<div class="wrap"><div id="icon-tools" class="icon32"></div>';
    ?>    

        <style>
        .notice{ display:none; }
        td, th, .dataTables_info, .dataTables_filter, .paging_simple_numbers, .dt-buttons{
            font-size: 12px;
        }
        .btn{
            font-size: 9px;
            padding: 2px 10px;
            background: orange;
            border: orange;
            color: #121212;
        }
        </style>

        <table id="envios_twilio" class="table table-striped table-bordered" style="width:100%">
        <thead>
            <tr>
                <th align="left">Email alumno</th>
                <th align="left">Curso</th>
                <th align="left">Introducción</th>
                <th align="left">Unidad 01</th>
                <th align="left">Unidad 02</th>
                <th align="left">Unidad 03</th>
                <th align="left">Unidad 04</th>
            </tr>
        </thead>
        <tbody>

        <?php
        global $wpdb;
        $tableName = $wpdb->prefix . "estado_curso";
        $avances = $wpdb->get_results( " SELECT * FROM $tableName " );
        foreach($avances as $avance){

        $user_info = get_userdata($avance->id_user);
        $user_name = @$user_info->display_name;
        $user_email = @$user_info->user_email;
        ?>
 
            <tr>
                <td><?php echo $user_email; ?></td>
                <td><?php echo get_the_title($avance->id_curso); ?></td>
                <td><?php if($avance->unidad_intro > 0){ ?> <img src='<?php echo get_template_directory_uri() ?>/images/ok.png' style='width: 25px; position: relative; top: -1px; margin-left: 5px;'> <?php } else { ?> <img src='<?php echo get_template_directory_uri() ?>/images/wait.png' style='width: 42px; position: relative; top: -8px; margin-left: 0px;'> <?php } ?></td>
                <td><?php if($avance->unidad_1 > 0){ ?> <img src='<?php echo get_template_directory_uri() ?>/images/ok.png' style='width: 25px; position: relative; top: -1px; margin-left: 5px;'> <button type="button" class="btn btn-sm btn-primary verPreguntas" data-unidad="1" data-user="<?php echo $avance->id_user; ?>" data-curso="<?php echo $avance->id_curso; ?>" data-toggle="modal" data-target=".modalPreguntas">VER RESPUESTAS</button> <?php } else { ?> <img src='<?php echo get_template_directory_uri() ?>/images/wait.png' style='width: 42px; position: relative; top: -8px; margin-left: 0px;'> <?php } ?></td>
                <td><?php if($avance->unidad_2 > 0){ ?> <img src='<?php echo get_template_directory_uri() ?>/images/ok.png' style='width: 25px; position: relative; top: -1px; margin-left: 5px;'> <button type="button" class="btn btn-sm btn-primary verPreguntas" data-unidad="2" data-user="<?php echo $avance->id_user; ?>" data-curso="<?php echo $avance->id_curso; ?>" data-toggle="modal" data-target=".modalPreguntas">VER RESPUESTAS</button> <?php } else { ?> <img src='<?php echo get_template_directory_uri() ?>/images/wait.png' style='width: 42px; position: relative; top: -8px; margin-left: 0px;'> <?php } ?></td>
                <td><?php if($avance->unidad_3 > 0){ ?> <img src='<?php echo get_template_directory_uri() ?>/images/ok.png' style='width: 25px; position: relative; top: -1px; margin-left: 5px;'> <button type="button" class="btn btn-sm btn-primary verPreguntas" data-unidad="3" data-user="<?php echo $avance->id_user; ?>" data-curso="<?php echo $avance->id_curso; ?>" data-toggle="modal" data-target=".modalPreguntas">VER RESPUESTAS</button> <?php } else { ?> <img src='<?php echo get_template_directory_uri() ?>/images/wait.png' style='width: 42px; position: relative; top: -8px; margin-left: 0px;'> <?php } ?></td>
                <td><?php if($avance->unidad_4 > 0){ ?> <img src='<?php echo get_template_directory_uri() ?>/images/ok.png' style='width: 25px; position: relative; top: -1px; margin-left: 5px;'> <button type="button" class="btn btn-sm btn-primary verPreguntas" data-unidad="4" data-user="<?php echo $avance->id_user; ?>" data-curso="<?php echo $avance->id_curso; ?>" data-toggle="modal" data-target=".modalPreguntas">VER RESPUESTAS</button> <?php } else { ?> <img src='<?php echo get_template_directory_uri() ?>/images/wait.png' style='width: 42px; position: relative; top: -8px; margin-left: 0px;'> <?php } ?></td>
            </tr>

        <?php } ?>

        </tbody>
        <tfoot>
            <tr>
                <th align="left">Email alumno</th>
                <th align="left">Curso</th>
                <th align="left">Introducción</th>
                <th align="left">Unidad 01</th>
                <th align="left">Unidad 02</th>
                <th align="left">Unidad 03</th>
                <th align="left">Unidad 04</th>
            </tr>
        </tfoot>
    </table>

    <!-- Modal -->
    <div class="modal fade modalPreguntas" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Preguntas & Respuestas</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <img src="<?php echo get_template_directory_uri(); ?>/images/circles.svg" id="loading_" style="display:none; width: 30px; margin: 0 auto 0 auto; position: absolute; left: 0; right: 0;">
            <div class="contenido"></div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        </div>
        </div>
    </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.min.js" integrity="sha384-+YQ4JLhjyBLPDQt//I+STsc9iw4uQqACwlvpslubQzn4u2UU2UFM80nGisd026JF" crossorigin="anonymous"></script>

    <script>
    const verPreguntas = document.querySelectorAll(".verPreguntas");
    let loading_ = document.getElementById("loading_");

    for (const ver of verPreguntas) {

        ver.addEventListener('click', function(event) {

            loading_.style.display = "block";

            let unidad = this.getAttribute('data-unidad');
            let user = this.getAttribute('data-user');
            let curso = this.getAttribute('data-curso');

            const data = new FormData();
            data.append('unidad', unidad);
            data.append('user', user);
            data.append('curso', curso);

            fetch('<?php echo get_template_directory_uri(); ?>/table--respuestas.php', {
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
                document.querySelector(".contenido").innerHTML = texto;
                loading_.style.display = "none";
            })
            .catch(function(err) {
                console.log(err);
            });

        });

    }
    </script>

<?php
	echo '</div>';
}
?>
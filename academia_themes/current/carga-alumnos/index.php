<?php
// Carga de Alumnos
add_action('admin_menu', 'register_carga_alumnos_page');

function register_carga_alumnos_page() {
  add_submenu_page( 'users.php', 'Carga de Alumnos', 'Carga de Alumnos', 'manage_options', 'carga_alumnos', 'carga_alumnos_page_callback' ); 
}

function carga_alumnos_page_callback() {
    ?>    

        <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/carga-alumnos/index.css">
        <script src="<?php echo get_template_directory_uri(); ?>/carga-alumnos/sweetalert2.all.min.js"></script>
        <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/carga-alumnos/sweetalert2.css">

        <style>
        .notice, .update-nag, .updated{ display:none; }
        td, th, .dataTables_info, .dataTables_filter, .paging_simple_numbers, .dt-buttons{
            font-size: 12px;
        }
        .msg_xlsx{ display: block; padding: 10px; background: #c6efff; border: 1px solid #63d8e4; margin-top: 20px; margin-bottom: -20px; color: #31aeaf; }
        </style>

        <form action="" id="formulario" method="post" enctype="multipart/form-data" class="formulario">

            <h1>Carga de Alumnos Masivamente</h1>

            <label>Excel Carga de Alumnos:</label>
            <input type="file" id="carga_alumnos" name="carga_alumnos" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">

            <span class="msg_xlsx">Acá puedes descargar un archivo ejemplo <a href="<?php echo get_template_directory_uri(); ?>/carga-alumnos/carga-alumnos.xlsx" download>carga-alumnos.xlsx</a>. Recuerda que el nombre del usuario debe ser único.</span>

            <br>
            <br>

            <label for="idCurso">Seleccionar Curso</label>
            <select id="idCurso" name="idCurso">
                <option value="">Selecciona una curso</option>
                <?php 
                $argpCursos = array(  
                    'post_type' => 'curso',
                    'post_status' => 'publish',
                    'posts_per_page' => -1,
                    'order' => 'ASC'
                );

                $loopCursos = new WP_Query( $argpCursos );

                while ( $loopCursos->have_posts() ) : $loopCursos->the_post();
                ?>
    
                    <option value="<?php echo get_the_ID(); ?>"><?php echo the_title(); ?></option>
    
                <?php 
                endwhile;
                wp_reset_postdata();
                ?>
            </select>

            <label for="fechaInicioAcceso">Seleccionar Fecha Inicio Acceso</label>
            <input type="date" name="fechaInicioAcceso" id="fechaInicioAcceso" required="" placeholder="Seleccionar fecha inicio">

            <label for="fechaTerminoAcceso">Seleccionar Fecha Termino Acceso</label>
            <input type="date" name="fechaTerminoAcceso" id="fechaTerminoAcceso" required="" placeholder="Seleccionar fecha termino">

            <button type="button" id="procesar">Procesar Excel</button>

            <div id="resultado_temp"></div>

        </form>

        <script>
        document.getElementById("procesar").addEventListener("click", function(){

            var input = document.getElementById('carga_alumnos');

            if(!input.files[0]){

                Swal.fire({
                    title: '',
                    text: '¡Por favor selecciona un archivo para continuar!',
                    icon: 'warning',
                    confirmButtonText: 'ok'
                });

                return false;

            }

            if(document.getElementById("idCurso").value == ""){

                Swal.fire({
                    title: '',
                    text: '¡Por favor selecciona una curso para continuar!',
                    icon: 'warning',
                    confirmButtonText: 'ok'
                });

                return false;

            }

            if(document.getElementById("fechaInicioAcceso").value == "" || document.getElementById("fechaInicioAcceso").value == ""){

                Swal.fire({
                    title: '',
                    text: '¡Por favor selecciona una fecha de inicio y termino de acceso para continuar!',
                    icon: 'warning',
                    confirmButtonText: 'ok'
                });

                return false;

            }

            document.getElementById("procesar").textContent = "Procesando...";
            document.getElementById("procesar").disabled = true;

            var data = new FormData();
            data.append('carga_alumnos', input.files[0]);
            data.append('idCurso', document.getElementById("idCurso").value);
            data.append('nombreCurso', document.getElementById('idCurso').options[document.getElementById('idCurso').selectedIndex].text);
            data.append('fechaInicioAcceso', document.getElementById("fechaInicioAcceso").value);
            data.append('fechaTerminoAcceso', document.getElementById("fechaTerminoAcceso").value);

            var myHeaders = new Headers();
    		myHeaders.append("Accept", "application/json; charset=utf-8");

            fetch('<?php echo get_template_directory_uri(); ?>/carga-alumnos/index--fetch.php', {
                method: 'POST',
                body: data,
                headers: myHeaders
            })
            .then(response => response.text())
		    .then(result => {

                // solo para debuguear
                document.getElementById("resultado_temp").innerHTML = result;

                var res = JSON.parse(result);
                
                var modals = [];

                res.map(function(item){

                    modals.push({
                        title: item.title, 
                        text: item.text, 
                        icon: item.type, 
                        confirmButtonText: 'ok' 
                    });

                });

                swal.queue(modals);

                document.getElementById("procesar").textContent = "Procesar Excel";
                document.getElementById("procesar").disabled = false;

            })
            .catch(error => console.log('error', error));

        });
        </script>

        <!-- TABLA COLABORADORES --> 
        <!-- <link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri(); ?>/flow/css/jquery.dataTables.min.css">
        <script src="<?php echo get_template_directory_uri(); ?>/flow/js/jquery.dataTables.js"></script>
        <script src="https://cdn.datatables.net/buttons/1.6.0/css/buttons.dataTables.min.css"></script>
        <link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri(); ?>/flow/css/tabla--flow.css">

        <script src="https://cdn.datatables.net/buttons/1.6.4/js/dataTables.buttons.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/1.6.4/js/buttons.flash.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
        <script src="https://cdn.datatables.net/buttons/1.6.4/js/buttons.html5.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/1.6.4/js/buttons.print.min.js"></script>

        <script>
        jQuery(function($){
            $('#carga_alumnos_table').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ]
            });
        } );
        </script>

        <style>
        .notice, .update-nag{display:none}
        </style>

        <h1>Colabodores & Invitados</h1>

        <table class="table table-striped" id="carga_alumnos_table">
            <thead>
                <tr>
                    <th scope="col">ID GRUPO</th>
                    <th scope="col">Tipo</th>
                    <th scope="col">Invitación nº</th>
                    <th scope="col">Empresa</th>
                    <th scope="col">Plan</th>
                    <th scope="col">Nombre</th>
                    <th scope="col">Email</th>
                    <th scope="col">Fecha · Hora Creado</th>
                    <th scope="col">Estado</th>
                </tr>
            </thead>
            <tbody>

                <?php 
                // $users = get_users(array(
                //     'meta_key' => 'usuario_tipo',
                //     'meta_value' => 'colaborador',                      
                // ));

                // $y = 0;

                // foreach($users as $user){ $y++;
                ?>
                
                <tr>

                    <td><?php echo $y; ?></td>
                    <td>Colaborador</td>
                    <td>- -</td>
                    <td><strong><?php echo get_the_title(get_user_meta($user->ID, 'id_empresa')[0]); ?></strong></td>
                    <td><strong><?php echo get_the_title(get_post_meta( get_the_ID(get_user_meta($user->ID, 'id_empresa')[0]), 'carga_alumnos__sel_plan', true )); ?></strong></td>
                    <td><strong><?php echo $user->display_name; ?></strong></td>
                    <td><strong><?php echo $user->user_email; ?></strong></td>
                    <td><strong><?php echo date("d-m-Y h:i:s", strtotime($user->user_registered)); ?></strong></td>
                    <td>- -</td>

                </tr>

                <?php 

                    // obtener cantidad de invitados
                //     $idEmpresa = get_user_meta($user->ID, 'id_empresa')[0];
                //     $idPlan = get_post_meta( $idEmpresa, 'carga_alumnos__sel_plan', true );
                //     $cantInvitados = get_post_meta( $idPlan, 'carga_alumnos__limite_plan', true );
                //     $idColaborador = obtenerIdColaborador($user->ID);

                //     for($i = 0; $i < $cantInvitados; $i++) {
                //         $x = $i+1;

                //         global $wpdb;
                //         $query_tmp  = $wpdb->get_results( " SELECT * FROM `wpgq_carga_alumnos_invitados_temp` WHERE `id_empresa` = '$idEmpresa' AND `id_colaborador` = '$idColaborador' AND `n_invitacion` = $x limit 0,1 " );
                //         if(count($query_tmp) > 0){
                //             $status_tmp = true;
                //         } else {
                //             $status_tmp = false;
                //         }

                //         global $wpdb;
                //         $query  = $wpdb->get_results( " SELECT * FROM `wpgq_carga_alumnos_invitados` WHERE `id_empresa` = '$idEmpresa' AND `id_colaborador` = '$idColaborador' AND `n_invitacion` = $x limit 0,1 " );
                        
                //         if(count($query) > 0){
                //             $status_inv = true;
                //         } else {
                //             $status_inv = false;
                //         }


                //         if($status_inv === false && $status_tmp === true){
                //             // esperando
                //             $estado = "<img src='".get_template_directory_uri()."/carga_alumnos/esperando.png' style='width: 15px; position: relative; top: 3px; margin-right: 4px;'> Esperando";
                //             foreach($query_tmp as $res){
                //                 $nombre = $res->nombre;
                //                 $email = $res->email;                            
                //             }
                //         } elseif ($status_inv === true && $status_tmp === false) {
                //             // aceptada
                //             $estado = "<img src='".get_template_directory_uri()."/carga_alumnos/ok.png' style='width: 18px; position: relative; top: 4px; margin-right: 4px;'> Aceptada";
                //             foreach($query as $res){
                //                 $nombre = $res->nombre;
                //                 $email = $res->email;    
                //                 $wp_user = $res->wp_user;  
                //                 $user_registered = date("d-m-Y h:i:s", strtotime(user_registered_inv($wp_user)));             
                //             }
                //         } else {
                //             $estado = "- -";
                //             $nombre = "- -";
                //             $email = "- -";
                //             $user_registered = "- -";
                //         }

                //         // echo $status_inv;
                    

                //         echo "
                        
                //             <tr>

                //                 <td>".$y."</td>
                //                 <td>Invitado</td>
                //                 <td>".$x."</td>
                //                 <td style='color: #dedede'>".get_the_title(get_user_meta($user->ID, 'id_empresa')[0])."</td>
                //                 <td style='color: #dedede'>".get_the_title(get_post_meta( get_the_ID(get_user_meta($user->ID, 'id_empresa')[0]), 'carga_alumnos__sel_plan', true ))."</td>
                //                 <td>".$nombre."</td>
                //                 <td>".$email."</td>
                //                 <td>".$user_registered."</td>
                //                 <td>".$estado."</td>
            
                //             </tr>
                        
                //         ";

                //     } 

                // }
                ?>

                
            </tbody>
        </table> -->

        <!-- END TABLA COLABORADORES -->
 
<?php
}
?>
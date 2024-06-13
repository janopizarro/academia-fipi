<?php
// Data de Alumnos
add_action('admin_menu', 'register_data_alumnos_page');

function register_data_alumnos_page() {
  add_submenu_page( 'users.php', 'Data de Alumnos', 'Data de Alumnos', 'manage_options', 'data_alumnos', 'data_alumnos_page_callback' ); 
}

function data_alumnos_page_callback() {
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

        <link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri(); ?>/data-alumnos/css/jquery.dataTables.min.css">
        <script src="<?php echo get_template_directory_uri(); ?>/data-alumnos/js/jquery.dataTables.js"></script>
        <script src="https://cdn.datatables.net/buttons/1.6.0/css/buttons.dataTables.min.css"></script>
        <link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri(); ?>/data-alumnos/css/tabla--flow.css">

        <script src="https://cdn.datatables.net/buttons/1.6.4/js/dataTables.buttons.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/1.6.4/js/buttons.flash.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
        <script src="https://cdn.datatables.net/buttons/1.6.4/js/buttons.html5.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/1.6.4/js/buttons.print.min.js"></script>

        <script>
        jQuery(function($){
            $('#data_alumnos_table').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ]
            });
        } );
        </script>

        <style>
        .notice, .update-nag{display:none}
        .dataTables_wrapper{
            max-width: 90%;
            width: 100%;
        }
        table thead tr {
            background: #a175b7;
        }
        </style>

        <h1>Data Usuarios Academia FIPI</h1>

        <table class="table table-striped" id="data_alumnos_table">
            <thead>
                <tr>
                    <th scope="col">id</th>
                    <th scope="col">Nombres</th>
                    <th scope="col">Apellidos</th>
                    <th scope="col">Rut</th>
                    <th scope="col">Email</th>
                    <th scope="col">Teléfono #01</th>
                    <th scope="col">Teléfono #02</th>
                    <th scope="col">Región</th>
                    <th scope="col">Comuna</th>
                    <th scope="col">Dirección</th>
                </tr>
            </thead>
            <tbody>

                <?php 
                $args = array(
                    'role'    => 'alumno',
                    'orderby' => 'user_nicename',
                    'order'   => 'ASC'
                );
                $users = get_users( $args );

                $y = 0;

                foreach($users as $user){
                ?>
                
                <tr>

                    <td><?php echo $user->ID; ?></td>
                    <td><?php echo get_user_by( 'id', $user->ID )->first_name; ?></td>
                    <td><?php echo get_user_by( 'id', $user->ID )->last_name; ?></td>
                    <td><?php echo get_user_meta( $user->ID, 'user_rut' , true ); ?></td>
                    <td><?php echo get_user_by( 'id', $user->ID )->user_email; ?></td>
                    <td><?php echo get_user_meta( $user->ID, 'user_telefono' , true ); ?></td>
                    <td><?php if(get_user_meta( $user->ID, 'user_telefono_02' , true )){ echo get_user_meta( $user->ID, 'user_telefono_02' , true ); }else{echo"- -";} ?></td>
                    <td><?php echo get_user_meta( $user->ID, 'user_region' , true ); ?></td>
                    <td><?php echo get_user_meta( $user->ID, 'user_comuna' , true ); ?></td>
                    <td><?php echo get_user_meta( $user->ID, 'user_direccion' , true ); ?></td>

                </tr>

                <?php
                }
                ?>

                
            </tbody>
        </table>

 
<?php
}
?>
<?php
// Ex Alumnos Fipi Certificados
add_action('admin_menu', 'register_ex_alumnos_fipi_certificados_page');

function register_ex_alumnos_fipi_certificados_page() {
  add_submenu_page( 'users.php', 'Ex Alumnos Fipi Certificados', 'Ex Alumnos Fipi Certificados (en desarrollo)', 'manage_options', 'ex_alumnos_fipi_certificados', 'ex_alumnos_fipi_certificados_page_callback' ); 
}

function ex_alumnos_fipi_certificados_page_callback() { ?>    

    <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/features/ex-alumnos-certificados.css?var=<?php echo date("Y-m-d-i:s")?>">

    <style>
    .notice, .update-nag, .updated{ display:none; }
    td, th, .dataTables_info, .dataTables_filter, .paging_simple_numbers, .dt-buttons{
        font-size: 12px;
    }
    .msg_xlsx{ display: block; padding: 10px; background: #c6efff; border: 1px solid #63d8e4; margin-top: 20px; margin-bottom: -20px; color: #31aeaf; }
    </style>

    <h1>Ex Alumnos Fipi Certificados - Academia FIPI</h1>

    <?php 

    $idCurso = @$_POST["curso"];

    $taxonomies = get_post_taxonomies($idCurso);

    $type = "";

    // fipid_ex_ficha
    // fipid_ex_notas
    // fipid_ex_persona

    function getDataExCourse($id) {
        global $wpdb;
        $tableName = $wpdb->prefix . "ex_ficha";

        $query = $wpdb->get_results( " SELECT * FROM $tableName WHERE `id_curso` = $id " );

        return $query[0];
    }

    function getDataUser($id) {
        global $wpdb;
        $tableName = $wpdb->prefix . "ex_persona";

        $query = $wpdb->get_results( " SELECT * FROM $tableName WHERE `id_per_persona` = $id " );

        return $query[0];
    }

    ?>

    <form method="post" action="">

        <!-- seleccionar curso a revisar -->
        <select name="curso" id="curso">
            <option value="0">Selecciona un curso</option>
            <?php 
            global $wpdb;
            $tableName = $wpdb->prefix . "ex_ficha";

            $query = $wpdb->get_results( " SELECT * FROM $tableName " );

            foreach($query as $res){
            ?>

                <option <?php if($idCurso == $res->id_curso){echo "selected";} ?> value="<?php echo $res->id_curso ?>"><?php echo $res->alias ?> - Fecha: (<?php echo $res->f_inicio ?> al <?php echo $res->f_termino ?>)</option>
            
            <?php 
            }
            ?>
        </select>

        <button>BUSCAR EX ALUMNOS</button>

    </form>

    <?php if($idCurso){ ?>
    
    <h1 style="font-size: 19px;color: #116d9e;margin-top: 30px;margin-bottom: 20px;max-width: 800px;line-height: 30px;">Curso: <?php echo getDataExCourse($idCurso)->alias." (".getDataExCourse($idCurso)->f_inicio." al ".getDataExCourse($idCurso)->f_termino.")"; ?></h1>

    <?php } ?>

    <div id="loading" style="display: none; width: 100%; text-align: center; padding-top: 37px;"><img src="<?php echo get_template_directory_uri() ?>/features/img/gears-spinner.svg" width="40" /></div>

    <!-- resultados -->
    <div id="resultados" class="resultados">

        <div class="items-cabecera">
            <p>Personales</p>
            <p>Nota obtenida</p>
        </div> 
    
        <?php
        global $wpdb;
        $tableName = $wpdb->prefix . "ex_notas";

        $query = $wpdb->get_results( " SELECT * FROM $tableName WHERE `id_curso` = '".$idCurso."' " );

        if(count($query) === 0){
            echo "<p style='padding: 12px;background: #fbfbcb;margin: 12px;border: 1px solid orange;color: #845601;'>No se encontró nada relacionado con el curso <strong>".getDataExCourse($idCurso)->alias." - (".getDataExCourse($idCurso)->f_inicio." al ".getDataExCourse($idCurso)->f_termino.")</strong>...</p>";
        }

        foreach($query as $res){

        $user_name = "";
        
        $fechaVuelta = "";

        $onlyTime = "";

        $dataAlumno = getDataUser($res->id_alumno);

        $nombreApellido = $dataAlumno->Nombre1." ".$dataAlumno->Nombre2." ".$dataAlumno->Apellido1." ".$dataAlumno->Apellido2;
        ?>

            <!-- data-date="<?php echo $fechaVuelta; ?>" -->

            <div class="item-resultado" data-name="<?php if($nombreApellido){ echo $nombreApellido; } else { echo "sin-nombre"; } ?>">
                <div class="personal">
                    <p><?php echo $nombreApellido; ?></p>
                    <small>RUT: <?php echo $dataAlumno->RUT; ?></small>
                    <strong><?php echo $dataAlumno->eMAIL; ?></strong>
                </div>

                <div class="personal">
                    <p><strong><?php echo $res->nota; ?></strong></p>
                    <small>Descripción: <?php echo $res->descrip; ?></small>
                    <small>Nota aprobatoria: <?php echo getDataExCourse($idCurso)->nota_aprob; ?></small>
                </div>

                <?php 

                if($dataAlumno->Nombre1 && $res->nota !== "0.0"){

                    $hoursExploid = explode(" ",get_post_meta( $idCurso, 'curso_horas', true ));

                    $removeComillas = str_replace('"','',getDataExCourse($idCurso)->alias);

                    echo '
                    
                    <form method="get" action="'.get_template_directory_uri().'/features/informe-pdf-prev/index.php">
                        <input type="hidden" name="nombre" value="'.$nombreApellido.'" />
                        <input type="hidden" name="nombre_curso" value="'.$removeComillas.'" />
                        <input type="hidden" name="tipo" value="'.$type.'" />
                        <input type="hidden" name="fecha_termino" value="'.$type.'" />
                        <input type="hidden" name="horas" value="'.getDataExCourse($idCurso)->durac_hrs.'" />
                        <div class="generate" id="generate">
                            <button type="submit">Generar PDF<img src="'.get_template_directory_uri().'/features/img/pdf.svg" width="20" /></button>
                            <small>ACADEMIA, FIPI</small>
                        </div>
                    </form>

                    <form method="get" action="'.get_template_directory_uri().'/features/informe-pdf-prev/index-b.php">
                        <input type="hidden" name="nombre" value="'.$nombreApellido.'" />
                        <input type="hidden" name="nombre_curso" value="'.$removeComillas.'" />
                        <input type="hidden" name="tipo" value="'.$type.'" />
                        <input type="hidden" name="fecha_termino" value="'.$type.'" />
                        <input type="hidden" name="horas" value="'.getDataExCourse($idCurso)->durac_hrs.'" />
                        <div class="generate" id="generate">
                            <button type="submit">Generar PDF<img src="'.get_template_directory_uri().'/features/img/pdf.svg" width="20" /></button>
                            <small>IDEAS</small>
                        </div>
                    </form>

                    <form method="get" action="'.get_template_directory_uri().'/features/informe-pdf-prev/index-c.php">
                        <input type="hidden" name="nombre" value="'.$nombreApellido.'" />
                        <input type="hidden" name="nombre_curso" value="'.$removeComillas.'" />
                        <input type="hidden" name="tipo" value="'.$type.'" />
                        <input type="hidden" name="fecha_termino" value="'.$type.'" />
                        <input type="hidden" name="horas" value="'.getDataExCourse($idCurso)->durac_hrs.'" />
                        <div class="generate" id="generate">
                            <button type="submit">Generar PDF<img src="'.get_template_directory_uri().'/features/img/pdf.svg" width="20" /></button>
                            <small>ACADEMIA</small>
                        </div>
                    </form>

                    <form method="get" action="'.get_template_directory_uri().'/features/informe-pdf-prev/index-d.php">
                        <input type="hidden" name="nombre" value="'.$nombreApellido.'" />
                        <input type="hidden" name="nombre_curso" value="'.$removeComillas.'" />
                        <input type="hidden" name="tipo" value="'.$type.'" />
                        <input type="hidden" name="fecha_termino" value="'.$type.'" />
                        <input type="hidden" name="horas" value="'.getDataExCourse($idCurso)->durac_hrs.'" />
                        <div class="generate" id="generate">
                            <button type="submit">Generar PDF<img src="'.get_template_directory_uri().'/features/img/pdf.svg" width="20" /></button>
                            <small>IDEAS, ACADEMIA, SIMAQ</small>
                        </div>
                    </form>
                    
                    ';
                
                }
                ?>

            </div>
            

        <!-- -->
        
    <?php 
    }   
    ?>
        </div>

<?php } ?>
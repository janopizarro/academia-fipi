<?php
// Resumen Alumnos
add_action('admin_menu', 'register_resumen_alumnos_page');

function register_resumen_alumnos_page() {
  add_submenu_page( 'users.php', 'Resumen Alumnos', 'Resumen Alumnos', 'manage_options', 'resumen_alumnos', 'resumen_alumnos_page_callback' ); 
}


function resumen_alumnos_page_callback() { ?>    

    <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/features/resumen-alumnos.css?var=<?php echo date("Y-m-d-i:s")?>">

    <style>
    .notice, .update-nag, .updated{ display:none; }
    td, th, .dataTables_info, .dataTables_filter, .paging_simple_numbers, .dt-buttons{
        font-size: 12px;
    }
    .msg_xlsx{ display: block; padding: 10px; background: #c6efff; border: 1px solid #63d8e4; margin-top: 20px; margin-bottom: -20px; color: #31aeaf; }
    .updatingStatus{display:none}
    .okStatus{display:none}
    .errorStatus{display:none}
    </style>

    <h1>Resumen Alumnos - Academia FIPI</h1>

    <?php 
    $idCurso = @$_POST["curso"];

    $typeCourse = "";

    $post_id = $idCurso;
    $taxonomy = 'categoria'; // o cualquier otra taxonomía
    $terms = get_the_terms($post_id, $taxonomy);

    if (!is_wp_error($terms) && !empty($terms)) {
        foreach ($terms as $term) {
            $typeCourse = $term->name;
        }
    }

    function mesEnPalabras($numeroMes) {
        switch ($numeroMes) {
            case 1:
                return "Enero";
            case 2:
                return "Febrero";
            case 3:
                return "Marzo";
            case 4:
                return "Abril";
            case 5:
                return "Mayo";
            case 6:
                return "Junio";
            case 7:
                return "Julio";
            case 8:
                return "Agosto";
            case 9:
                return "Septiembre";
            case 10:
                return "Octubre";
            case 11:
                return "Noviembre";
            case 12:
                return "Diciembre";
            default:
                return "Mes inválido";
        }
    }
    ?>

    <form method="post" action="">

        <!-- seleccionar curso a revisar -->
        <select name="curso" id="curso">
            <option value="0">Selecciona un curso</option>
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

                <option <?php if($idCurso == get_the_ID()){echo "selected";} ?> value="<?php echo get_the_ID(); ?>"><?php echo the_title(); ?></option>
            
            <?php 
            endwhile;
            wp_reset_postdata();
            ?>
        </select>

        <button>BUSCAR ALUMNOS</button>

    </form>

    <?php 
    function getStatus_($ID, $index, $id_user, $type){
        global $wpdb;
        $tableName = $wpdb->prefix . "unidades_dinamico";

        if($type != 2){
            $query = $wpdb->get_results( " SELECT * FROM $tableName WHERE `id_curso` = $ID AND `id_user` = $id_user AND `tipo` = $type AND `status` = 1 " );
        } else {
            $query = $wpdb->get_results( " SELECT * FROM $tableName WHERE `id_curso` = $ID AND `n_unidad` = $index AND `id_user` = $id_user AND `tipo` = $type AND `status` = 1 " );
        }

        if(count($query) > 0){
            return true;
        } else {
            return false;
        }
    }

    function getStatusShowCert($ID, $id_user){
        global $wpdb;
        $tableName = $wpdb->prefix . "unidades_dinamico";

        $query = $wpdb->get_results( " SELECT * FROM $tableName WHERE `id_curso` = $ID AND `id_user` = $id_user AND `tipo` = 3 AND `status` = 1 " );

        return $query[0];
    }

    global $wpdb;
    $tableName = $wpdb->prefix . "unidades_dinamico";
    $avances = $wpdb->get_results( " SELECT * FROM $tableName WHERE id_curso = $idCurso GROUP BY id_user " );

    $existCount = array();
    $finish_ = array();
    $finishedForm_ = array();
    $finishedMiddle_ = array();

    foreach($avances as $avance){
        $user_info = get_userdata($avance->id_user);
        $user_name = @$user_info->display_name;
        $id_user = $avance->id_user;


        if($user_name){
            $existCount[] = 1;

            if(getStatus_($avance->id_curso, null, $id_user, 3)){
                $finish_[] = 1;
            }

            $tableName_ = $wpdb->prefix . "encuesta_satisfaccion";

            $query = $wpdb->get_results( " SELECT * FROM $tableName_ WHERE `id_curso` = $avance->id_curso AND `id_user` = $id_user " );

            if(count($query) > 0){

                $current_step = $query[0]->current_step;
                $total_steps = $query[0]->total_steps;

                if($current_step == $total_steps){
                    $finishedForm_[] = 1;
                } else {
                    $finishedMiddle_[] = 1;
                }

            }

        }

    }

?>

    <?php if($idCurso){ ?>
    
    <h1 style="font-size: 19px;color: #116d9e;margin-top: 30px;margin-bottom: 20px;max-width: 800px;line-height: 30px;">Curso: <?php echo get_the_title($idCurso); ?></h1>

    <div class="info">
        <p>Este curso tiene <strong><?php echo count($avances); ?></strong> alumn@os de l@s cuales <strong><?php echo count($existCount); ?></strong> aún están vigentes en la academía.</p>    
        <p><strong><?php echo count($finish_); ?></strong> han finalizado todas las unidades, <strong><?php echo count($finishedMiddle_); ?></strong> inició la encuesta y no la completó y <strong><?php echo count($finishedForm_); ?></strong> la terminó</p>
    </div>

    <?php } ?>

    <?php 
    $dateRegistered = [];

    foreach($avances as $avance){

        $user_info = get_userdata($avance->id_user);
        $user_registered = @$user_info->user_registered;

        $onlyDate = explode(" ",$user_registered)[0];

        if($onlyDate !== ""){
            $dateRegistered[] = $onlyDate;                
        }

    }
    ?>

    <?php
    if($idCurso){
    ?>
    <script>
    // document.addEventListener("DOMContentLoaded", function() {
    //     // Obtener referencia al select y a los elementos
    //     const selectorFecha = document.getElementById('date_registered');
    //     const selectorTipo = document.getElementById('tipo_alumno');

    //     const elementos = document.querySelectorAll('.item-resultado');
    //     const loading = document.getElementById('loading');
    //     const resultados = document.getElementById('resultados');
    //     const campoBusqueda = document.getElementById('search');

    //     // Función para filtrar por data-date
    //     function filtrarPorFecha(fecha) {
    //         elementos.forEach(elemento => {
    //             const fechaElemento = elemento.getAttribute('data-date');
    //             if (fecha === 'all' || fechaElemento === fecha) {
    //                 elemento.style.display = 'flex'; // Mostrar elemento
    //             } else {
    //                 elemento.style.display = 'none'; // Ocultar elemento
    //             }
    //         });
    //     }

    //     // Función para filtrar por data-name
    //     function filtrarPorNombre(fechaSeleccionada, texto) {
    //         let encontrado = false; // Variable para rastrear si se encontraron resultados
    //         elementos.forEach(elemento => {
    //             const nombreApellido = elemento.getAttribute('data-name');
    //             const fechaElemento = elemento.getAttribute('data-date'); // Obtener la fecha del elemento
    //             const nombreSinAcentos = quitarAcentos(nombreApellido).toLowerCase();
    //             const textoSinAcentos = quitarAcentos(texto).toLowerCase();
    //             if ((nombreApellido && texto && nombreSinAcentos.includes(textoSinAcentos)) || texto === '') {
    //                 if (fechaSeleccionada === 'all' || fechaElemento === fechaSeleccionada) {
    //                     elemento.style.display = 'flex'; // Mostrar elemento
    //                     encontrado = true; // Se encontró al menos un resultado
    //                 } else {
    //                     elemento.style.display = 'none'; // Ocultar elemento
    //                 }
    //             } else {
    //                 elemento.style.display = 'none'; // Ocultar elemento
    //             }
    //         });

    //     }

    //     function filtrarElementos(fecha, texto) {
    //         // Mostrar el elemento de carga
    //         loading.style.display = 'block';
    //         resultados.style.display = 'none';

    //         filtrarPorFecha(fecha);
    //         if (texto !== "") {
    //             filtrarPorNombre(fecha, texto); // Pasar fechaSeleccionada como parámetro
    //         }

    //         // Ocultar el elemento de carga después de un breve retraso (simulando una operación asincrónica)
    //         setTimeout(function() {
    //             loading.style.display = 'none';
    //             resultados.style.display = 'block';
    //         }, 1000); // Ajusta el valor del tiempo según sea necesario
    //     }

    //     // Asociar la función filtrarElementos al evento onchange del select
    //     selectorFecha.addEventListener('change', function() {
    //         campoBusqueda.value = '';
    //         const fechaSeleccionada = selectorFecha.value;
    //         filtrarElementos(fechaSeleccionada, campoBusqueda.value.trim());
    //     });

    //     selectorTipo.addEventListener('change', function() {
    //         campoBusqueda.value = '';
    //         const tipoSeleccionado = selectorTipo.value;
    //         filtrarElementos(fechaSeleccionada, campoBusqueda.value.trim());
    //     });

    //     campoBusqueda.addEventListener('keyup', function() {
    //         const fechaSeleccionada = selectorFecha.value;
    //         const textoBusqueda = campoBusqueda.value.trim();
    //         selectorFecha.value = 'all';
    //         filtrarElementos(fechaSeleccionada, textoBusqueda);
    //     });
    // });

    // function quitarAcentos(texto) {
    //     return texto.normalize("NFD").replace(/[\u0300-\u036f]/g, "");
    // }

    document.addEventListener("DOMContentLoaded", function() {
        // Obtener referencia a los select y a los elementos
        const selectorFecha = document.getElementById('date_registered');
        const selectorTipo = document.getElementById('tipo_alumno');

        const elementos = document.querySelectorAll('.item-resultado');
        const loading = document.getElementById('loading');
        const resultados = document.getElementById('resultados');
        const campoBusqueda = document.getElementById('search');

        // Función para filtrar por data-date y data-type
        function filtrarElementos(fecha, tipo, texto) {
            // Mostrar el elemento de carga
            loading.style.display = 'block';
            resultados.style.display = 'none';

            let encontrado = false; // Variable para rastrear si se encontraron resultados

            elementos.forEach(elemento => {
                const fechaElemento = elemento.getAttribute('data-date');
                const tipoElemento = elemento.getAttribute('data-type');
                const nombreApellido = elemento.getAttribute('data-name');
                const nombreSinAcentos = quitarAcentos(nombreApellido).toLowerCase();
                const textoSinAcentos = quitarAcentos(texto).toLowerCase();

                const cumpleFecha = (fecha === 'all' || fechaElemento === fecha);
                const cumpleTipo = (tipo === 'all' || tipoElemento === tipo);
                const cumpleNombre = (texto === '' || nombreSinAcentos.includes(textoSinAcentos));

                if (cumpleFecha && cumpleTipo && cumpleNombre) {
                    elemento.style.display = 'flex'; // Mostrar elemento
                    encontrado = true; // Se encontró al menos un resultado
                } else {
                    elemento.style.display = 'none'; // Ocultar elemento
                }
            });

            // Ocultar el elemento de carga después de un breve retraso (simulando una operación asincrónica)
            setTimeout(function() {
                loading.style.display = 'none';
                resultados.style.display = 'block';
            }, 1000); // Ajusta el valor del tiempo según sea necesario
        }

        // Asociar la función filtrarElementos al evento onchange de los select
        selectorFecha.addEventListener('change', function() {
            campoBusqueda.value = '';
            const fechaSeleccionada = selectorFecha.value;
            const tipoSeleccionado = selectorTipo.value;
            filtrarElementos(fechaSeleccionada, tipoSeleccionado, campoBusqueda.value.trim());
        });

        selectorTipo.addEventListener('change', function() {
            campoBusqueda.value = '';
            const fechaSeleccionada = selectorFecha.value;
            const tipoSeleccionado = selectorTipo.value;
            filtrarElementos(fechaSeleccionada, tipoSeleccionado, campoBusqueda.value.trim());
        });

        campoBusqueda.addEventListener('keyup', function() {
            const fechaSeleccionada = selectorFecha.value;
            const tipoSeleccionado = selectorTipo.value;
            const textoBusqueda = campoBusqueda.value.trim();
            filtrarElementos(fechaSeleccionada, tipoSeleccionado, textoBusqueda);
        });
    });

    // Función para quitar acentos
    function quitarAcentos(texto) {
        return texto.normalize("NFD").replace(/[\u0300-\u036f]/g, "");
    }

    document.addEventListener('DOMContentLoaded', (event) => {
        // Selecciona todos los checkboxes con la clase 'item-checkbox'
        const checkboxes = document.querySelectorAll('.item-checkbox');
        const resultado = document.getElementById('resultadoCheckbox');
        const resContainer = document.getElementById('resultadosCheckContainer');
        
        // Añade el evento 'change' a cada checkbox para que actualice el conteo inmediatamente
        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', contarCheckboxes);
        });
        
        // Función para contar los checkboxes seleccionados
        function contarCheckboxes() {
            // Filtra los checkboxes para contar solo los que están seleccionados
            const seleccionados = Array.from(checkboxes).filter(checkbox => checkbox.checked);
            
            // Muestra el resultado en el elemento con id 'resultado'
            resultado.innerText = `Alumnos seleccionados para generar PDF: ${seleccionados.length}`;
            
            // Muestra u oculta el contenedor según el número de checkboxes seleccionados
            if (seleccionados.length > 0) {
                resultado.style.display = 'block';
                resContainer.style.display = 'block';
            } else {
                resultado.style.display = 'none';
                resContainer.style.display = 'none';
            }
        }

        // Llama a la función contarCheckboxes inicialmente para establecer el estado correcto al cargar la página
        contarCheckboxes();
    });

    document.addEventListener("DOMContentLoaded", function() {
        // Obtenemos todos los botones con la clase 'generate'
        const generateButtons = document.querySelectorAll('.generate');

        // Recorremos cada botón y le agregamos un event listener
        generateButtons.forEach(function(button) {
            button.addEventListener('click', function() {
                // Obtenemos el valor del atributo data-type
                const dataType = button.getAttribute('data-type');

                // Obtenemos todos los elementos li
                const items = document.querySelectorAll('#resultados .item-resultado');

                const selectedData = [];

                // Recorremos cada item
                items.forEach(function(item) {
                    const checkbox = item.querySelector('input[name="itemCheckbox"]');

                    // Verificamos si el checkbox del item está seleccionado
                    if (checkbox.checked) {
                        const nombre = item.querySelector('input[name="nombre[]"]').value;
                        const curso = item.querySelector('input[name="curso[]"]').value;
                        const tipo = item.querySelector('input[name="tipo[]"]').value;
                        const horas = item.querySelector('input[name="horas[]"]').value;
                        const mes = item.querySelector('input[name="mes[]"]').value;
                        const ano = item.querySelector('input[name="ano[]"]').value;
                        
                        const itemData = {
                            nombre: nombre,
                            curso: curso,
                            tipo: tipo,
                            horas: horas,
                            mes: mes,
                            ano: ano,
                        };

                        selectedData.push(itemData);
                    }
                });

                // Creamos un formulario oculto para enviar los datos
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '<?php echo get_template_directory_uri(); ?>/features/informe-pdf/process.php';

                // Añadimos el dataType al formulario
                const dataTypeInput = document.createElement('input');
                dataTypeInput.type = 'hidden';
                dataTypeInput.name = 'dataType';
                dataTypeInput.value = dataType;
                form.appendChild(dataTypeInput);

                // Añadimos los items seleccionados al formulario
                selectedData.forEach((item, index) => {
                    for (const key in item) {
                        if (item.hasOwnProperty(key)) {
                            const hiddenField = document.createElement('input');
                            hiddenField.type = 'hidden';
                            hiddenField.name = `items[${index}][${key}]`;
                            hiddenField.value = item[key];
                            form.appendChild(hiddenField);
                        }
                    }
                });

                // Añadimos el formulario al body y lo enviamos
                document.body.appendChild(form);
                form.submit();
            });
        });
    });

    document.addEventListener('DOMContentLoaded', () => {
        // Selecciona todos los contenedores de grupos de select e input hidden
        const containers = document.querySelectorAll('.mostrar-certificado');

        // Itera sobre cada contenedor
        containers.forEach(container => {
            // Encuentra el select e input hidden dentro de cada contenedor
            const selectElement = container.querySelector('select');
            const idShowCertElement = container.querySelector('input[name="idShowCert"]');
            const loading = container.querySelector('.updatingStatus');
            const messageOk = container.querySelector('.okStatus');
            const messageError = container.querySelector('.errorStatus');

            // Añade un event listener al select dentro de este contenedor
            selectElement.addEventListener('change', () => {

                loading.style.display = "inline-block";
                messageOk.style.display = "none";
                messageError.style.display = "none";

                // Obtiene el valor seleccionado y el valor del input hidden
                const selectedOption = selectElement.value;
                const idShowCert = idShowCertElement.value;

                // Crea un objeto con los datos a enviar
                const data = {
                    mostrarCertificado: selectedOption,
                    idShowCert: idShowCert
                };

                // Envía los datos mediante Fetch
                fetch('<?php echo get_template_directory_uri(); ?>/features/resumen-alumnos-update-show-cert.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(data)
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.text();
                })
                .then(data => {
                    const dataParsed = JSON.parse(data);

                    if(dataParsed.status === "ok"){
                        loading.style.display = "none";
                        messageOk.style.display = "inline-block";
                    } else {
                        loading.style.display = "none";
                        messageError.style.display = "inline-block";
                        selectElement.value = selectElement.options[selectedOption === "SI" ? 0 : 1].value;    
                    }
                })
                .catch(error => {
                    loading.style.display = "none";
                    messageError.style.display = "inline-block";
                    selectElement.value = selectElement.options[selectedOption === "SI" ? 0 : 1].value;
                });
            });
        });
    });
    </script>


    <div style="padding-top: 20px; border-top: 1px solid #121212; margin-top: 20px;">
        <label for="">Fecha de creación en la Academia: </label>
        <select name="date_registered" id="date_registered">
            <option value="all">- - -</option>
            <?php 
            foreach(array_unique($dateRegistered) as $dateR){
            $fechaVuelta = date("d-m-Y", strtotime($dateR));
            ?>
            <option value="<?php echo $fechaVuelta; ?>"><?php echo $fechaVuelta; ?></option>
            <?php 
            }
            ?>
        </select>
        <input type="text" name="search" id="search" autocomplete="one-time-code" placeholder="Busca por nombre">
        <select name="tipo_alumno" id="tipo_alumno">
            <option value="all">Tipo Alumno</option>
            <option value="activo">Alumnos activos</option>
            <option value="inactivo">Alumnos inactivos</option>
            <option value="habilitado-certificado">Alumnos Habilitado para Certificado</option>
        </select>
    </div>

    <div style="padding: 10px;background: #f9f2c5;color: #9d690a;border: 1px solid #dedede;max-width: 430px;margin-top: 30px;" id="resultadosCheckContainer">
        <strong id="resultadoCheckbox" style="margin-bottom: 10px; text-align: center;">...</strong>

        <div class="buttons" style="display: flex;align-items: center;justify-content: center;">
            <div class="generate" data-type="a">
                <button type="submit">Generar PDF<img src="<?php echo get_template_directory_uri(); ?>/features/img/pdf.svg" width="20" /></button>
                <small>ACADEMIA, FIPI</small>
            </div>

            <div class="generate" data-type="b">
                <button type="submit">Generar PDF<img src="<?php echo get_template_directory_uri(); ?>/features/img/pdf.svg" width="20" /></button>
                <small>IDEAS</small>
            </div>

            <div class="generate" data-type="c">
                <button type="submit">Generar PDF<img src="<?php echo get_template_directory_uri(); ?>/features/img/pdf.svg" width="20" /></button>
                <small>ACADEMIA</small>
            </div>

            <div class="generate" data-type="d">
                <button type="submit">Generar PDF<img src="<?php echo get_template_directory_uri(); ?>/features/img/pdf.svg" width="20" /></button>
                <small>ACADEMIA, FIPI, SIMAQ</small>
            </div>
        </div>

        <!-- <button id="checkButton" type="button">Check Items</button> -->

    </div>

    <div id="loading" style="display: none; width: 100%; text-align: center; padding-top: 37px;"><img src="<?php echo get_template_directory_uri() ?>/features/img/gears-spinner.svg" width="40" /></div>

    <!-- resultados -->
    <div id="resultados" class="resultados">

        <div class="items-cabecera">
            <p title="Fecha ingreso sistema FIPI">Fecha Ing.</p>
            <p>Personales</p>
            <p>Intro</p>
            <p>Unidades</p>
            <p>Finalizado</p>
            <p>Encuesta</p>
            <p>Mostrar certificado a alumno/a</p>
        </div> 
    
        <?php
        foreach($avances as $avance){

            $user_info = get_userdata($avance->id_user);
            $id_user = $avance->id_user;
            $user_name = @$user_info->display_name;
            $user_email = @$user_info->user_email;
            $user_registered = @$user_info->user_registered;

            $idAvance = $avance->id;

            $exist = true;

            if(!$user_name){
                $exist = false;
            }

            $intro = false;

            if(getStatus_($avance->id_curso, null, $id_user, 1)){
                $intro = true;
            }

            $onlyDate = explode(" ",$user_registered)[0];
            @$onlyTime = explode(" ",$user_registered)[1];
            $fechaVuelta = date("d-m-Y", strtotime($onlyDate));

            $tipo_alumno = "";

            $finish = false;

            if(getStatus_($avance->id_curso, null, $id_user, 3)){
                $finish = true;
            }

            if ($exist && $finish) {
                $tipo_alumno = "habilitado-certificado";
            } elseif ($exist) {
                $tipo_alumno = "activo";
            } else {
                $tipo_alumno = "inactivo";
            }

            ?>

            <?php 
            // if($exist){
            ?>
            <div class="item-resultado" data-date="<?php echo $fechaVuelta; ?>" data-type="<?php echo $tipo_alumno; ?>" data-name="<?php if($user_name){ echo $user_name; } else { echo "sin-nombre"; } ?>">
                <?php 
                if($exist && $finish){

                $hoursExploid = explode(" ",get_post_meta( $idCurso, 'curso_horas', true ));
                $cursoFecha = explode("-",get_post_meta( $idCurso, 'curso_fecha', true ));

                $month = mesEnPalabras($cursoFecha[1]);
                $year = $cursoFecha[2];
                ?>
                <input type="checkbox" class="item-checkbox" name="itemCheckbox" id="" style="margin-top: 10px; margin-left: -15px;">

                <input type="hidden" name="nombre[]" id="" value="<?php echo $user_name; ?>">
                <input type="hidden" name="curso[]" id="" value="<?php echo get_the_title($idCurso); ?>">
                <input type="hidden" name="tipo[]" id="" value="<?php echo $typeCourse; ?>">
                <input type="hidden" name="horas[]" id="" value="<?php echo $hoursExploid[0]; ?>">
                <input type="hidden" name="mes[]" id="" value="<?php echo $month; ?>">
                <input type="hidden" name="ano[]" id="" value="<?php echo $year; ?>">

                <?php 
                } else {
                ?>
                <input type="checkbox" class="item-checkbox" name="itemCheckbox" id="" style="display:none;margin-top: 10px; margin-left: -15px;">
                <?php
                }
                ?>
                <div class="user_registered">
                    <small><?php echo $fechaVuelta." - ".$onlyTime; ?></small>
                    <input type="hidden" name="id" value="<?php echo $idAvance; ?>">
                </div>
                <div class="personal">
                    <?php 
                    if($exist){
                        echo '                        
                            <p>'.$user_name.'</p>
                            <strong>'.$user_email.'</strong>
                        ';
                    } else {
                        echo "<small><img src='".get_template_directory_uri()."/features/img/warning.svg' width='20' /> Alumn@ #".$avance->id_user." ya no existe en academia fipi</small>";
                    }
                    ?>
                </div>
                <div class="intro">
                    <?php 
                    if($exist){

                    ?>
                    <ul>
                        <li><img src="<?php echo get_template_directory_uri(); ?>/features/img/<?php if($intro){ echo "ready"; } else { echo "pending"; } ?>.svg" width="20" /></li>
                    </ul>
                    <?php
                    }
                    ?>
                </div>
                <div class="status">
                    <?php 
                    if($exist){

                    $units = getUnitsCourse($avance->id_curso);
                    ?>
                    <ul>

                        <?php 

                        $x = 0;

                        foreach($units as $res){ $x++;
                                                
                            if(getStatus_($avance->id_curso, $x, $id_user, 2)){
                                echo '<li><img src="'.get_template_directory_uri().'/features/img/ready.svg" width="20" /></li>';
                            } else {
                                echo '<li><img src="'.get_template_directory_uri().'/features/img/pending.svg" width="20" /></li>';
                            }

                        }

                        ?>
                        
                    </ul>
                    <?php 
                    }
                    ?>
                </div>
                <div class="finish">
                    <?php 
                    if($exist){

                    $finish = false;

                    if(getStatus_($avance->id_curso, null, $id_user, 3)){
                        $finish = true;
                    }

                    ?>
                    <ul>
                        <li><img src="<?php echo get_template_directory_uri(); ?>/features/img/<?php if($finish){ echo "ready"; } else { echo "pending"; } ?>.svg" width="20" /></li>
                    </ul>
                    <?php
                    }
                    ?>
                </div>
                <div class="form">
                    <?php 
                    if($exist){

                    global $wpdb;
                    $tableName = $wpdb->prefix . "encuesta_satisfaccion";

                    $query = $wpdb->get_results( " SELECT * FROM $tableName WHERE `id_curso` = $avance->id_curso AND `id_user` = $id_user " );

                    $finishedForm = false;
                    $finishedMiddle = false;

                    if(count($query) > 0){

                        $current_step = $query[0]->current_step;
                        $total_steps = $query[0]->total_steps;

                        if($current_step == $total_steps){
                            $finishedForm = true;
                        } else {
                            $finishedMiddle = true;
                        }

                    }

                    ?>
                    <ul>
                        <?php 
                        if($finishedForm){
                            echo '<li><img src="'.get_template_directory_uri().'/features/img/ready.svg" width="20" /></li>';
                        } else if ($finishedMiddle){
                            echo '<li><img src="'.get_template_directory_uri().'/features/img/middle.svg" width="20" /></li>';
                        } else {
                            echo '<li><img src="'.get_template_directory_uri().'/features/img/pending.svg" width="20" /></li>';
                        }
                        ?>
                    </ul>
                    <?php 
                    }
                    ?>
                    
                </div>

                <?php 
                // if($exist && $finish){

                //     $hoursExploid = explode(" ",get_post_meta( $idCurso, 'curso_horas', true ));

                //     echo '
                    
                //     <form method="get" action="'.get_template_directory_uri().'/features/informe-pdf/index.php">
                //         <input type="hidden" name="nombre" value="'.$user_name.'" />
                //         <input type="hidden" name="nombre_curso" value="'.get_the_title($idCurso).'" />
                //         <input type="hidden" name="tipo" value="'.$type.'" />
                //         <input type="hidden" name="horas" value="'.$hoursExploid[0].'" />
                //         <div class="generate" id="generate">
                //             <button type="submit">Generar PDF<img src="'.get_template_directory_uri().'/features/img/pdf.svg" width="20" /></button>
                //             <small>ACADEMIA, FIPI</small>
                //         </div>
                //     </form>

                //     <form method="get" action="'.get_template_directory_uri().'/features/informe-pdf/index-b.php">
                //         <input type="hidden" name="nombre" value="'.$user_name.'" />
                //         <input type="hidden" name="nombre_curso" value="'.get_the_title($idCurso).'" />
                //         <input type="hidden" name="tipo" value="'.$type.'" />
                //         <input type="hidden" name="horas" value="'.$hoursExploid[0].'" />
                //         <div class="generate" id="generate">
                //             <button type="submit">Generar PDF<img src="'.get_template_directory_uri().'/features/img/pdf.svg" width="20" /></button>
                //             <small>IDEAS</small>
                //         </div>
                //     </form>

                //     <form method="get" action="'.get_template_directory_uri().'/features/informe-pdf/index-c.php">
                //         <input type="hidden" name="nombre" value="'.$user_name.'" />
                //         <input type="hidden" name="nombre_curso" value="'.get_the_title($idCurso).'" />
                //         <input type="hidden" name="tipo" value="'.$type.'" />
                //         <input type="hidden" name="horas" value="'.$hoursExploid[0].'" />
                //         <div class="generate" id="generate">
                //             <button type="submit">Generar PDF<img src="'.get_template_directory_uri().'/features/img/pdf.svg" width="20" /></button>
                //             <small>ACADEMIA</small>
                //         </div>
                //     </form>

                //     <form method="get" action="'.get_template_directory_uri().'/features/informe-pdf/index-d.php">
                //         <input type="hidden" name="nombre" value="'.$user_name.'" />
                //         <input type="hidden" name="nombre_curso" value="'.get_the_title($idCurso).'" />
                //         <input type="hidden" name="tipo" value="'.$type.'" />
                //         <input type="hidden" name="horas" value="'.$hoursExploid[0].'" />
                //         <div class="generate" id="generate">
                //             <button type="submit">Generar PDF<img src="'.get_template_directory_uri().'/features/img/pdf.svg" width="20" /></button>
                //             <small>IDEAS, ACADEMIA, SIMAQ</small>
                //         </div>
                //     </form>
                    
                //     ';
                // }

                $mostrarCertificadoStatus = "disabled";

                if($exist && $finish){
                    $mostrarCertificadoStatus = "";
                }

                $statusActualShowCert = getStatusShowCert($idCurso, $id_user);

                // print_r($statusActualShowCert);

                $optionsShowCert = array("NO","SI");
                ?>

                <div class="mostrar-certificado">
                    <input type="hidden" name="idShowCert" value="<?php echo $statusActualShowCert->id; ?>" />
                    <select name="mostrarCertificao" id="mostrarCertificado" <?php echo $mostrarCertificadoStatus; ?>>
                        <?php 
                        foreach($optionsShowCert as $option){

                            $isSelected = "";

                            if($statusActualShowCert->show_cert === $option){
                                $isSelected = "selected";
                            }

                            echo "<option ".$isSelected." value='".$option."'>".$option."</option>";
                        }
                        ?>
                    </select>
                    <small class="updatingStatus"><img src="<?php echo get_template_directory_uri(); ?>/features/img/gears-spinner.svg" style="width: 12px;top: 2px;margin-right: 5px;margin-left: 5px;position: relative;" />Actualizando estado</small>
                    <small class="okStatus"><img src="<?php echo get_template_directory_uri(); ?>/features/img/ok-svgrepo-com.svg" style="width: 12px;top: 2px;margin-right: 5px;margin-left: 5px;position: relative;" />Estado Actualizado</small>
                    <small class="errorStatus"><img src="<?php echo get_template_directory_uri(); ?>/features/img/error-svgrepo-com.svg" style="width: 12px;top: 2px;margin-right: 5px;margin-left: 5px;position: relative;" />Error, el estado no se guardo!</small>
                </div>

            </div>
            <?php 
            // }
            ?>

        <?php 
        }
        ?>

        <!-- -->
        
    </div>

    <?php 
    }   
    ?>
    
<?php } ?>
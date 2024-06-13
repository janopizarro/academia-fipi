<div class="card">
    <div class="card-header" id="h_unidad_encuesta">
        <h5 class="mb-0">
            <button class="btn btn-link unidad_encuesta" data-toggle="collapse" data-target="#unidad_encuesta"
                aria-expanded="false" aria-controls="unidad_encuesta">
                Encuesta de evaluación
            </button>
        </h5>
    </div>
    <div id="unidad_encuesta" class="collapse" aria-labelledby="h_unidad_encuesta" data-parent="#accordion">

        <style>
        .encuesta_campo{
        display: flex;
        align-items: center;
        }
        .encuesta_campo label{
        padding-top: 4px;
        margin: 0px;
        margin-left: 5px;
        }
        .encuesta_titulo{
        margin-top: 30px;
        display: flex;
        align-items: center;
        }
        .encuesta_titulo h3{
        font-size: 17px;
        }
        .encuesta_grupo_titulo{
        margin-top: 25px;
        margin-bottom: 30px;
        }
        .encuesta_grupo_titulo h4{
        font-size: 19px;
        color: #919191;
        font-weight: 400;
        margin: 0px;
        padding: 0px;
        margin-bottom: 12px;
        border-left: 3px solid #35a2f7;
        border-radius: 0px;
        padding-left: 5px;
        }
        .encuesta_grupo_titulo small{
        font-size: 15px;
        color: #6b6b6b;
        font-weight: 500;
        }
        .enviar_respuestas{
        padding: 6px 15px;
        line-height: 20px;
        font-size: 13px !important;
        font-weight: 600;
        margin: 0;
        border: none;
        border-radius: 23px;
        color: white !important;
        background: purple;
        margin-top: 20px;
        margin-bottom: 20px;
        }
        .campo_requerido{
        color: red
        }
        .campos_mensaje{
        padding: 4px 10px;
        background: #def0ff;
        color: #3d7999;
        border: 1px solid #a1d4f5;
        font-size: 14px;
        font-weight: 300;
        }
        .campos_mensaje span{
        color: red;
        }
        </style>

        <style>
        .loading_steps{
            margin-bottom: 10px;
        }
        .loading_steps p{
            margin-right: 10px;
            margin-bottom: 0px;
        }
        .loading_steps img{
            width: 16px;
        }

        .finish_steps{
            text-align: center;
            padding-top: 35px;
        }
        .finish_steps p{
            font-size: 20px;
            color: #949494;
            line-height: 32px;
        }
        .finish_steps p strong{
            color: purple;
        }
        .finish_steps img{
            width: 65px;
            margin-bottom: 20px;
        }
        </style>

        <div id='finish_steps' class='finish_steps' style='display:none'><img src='<?php echo get_template_directory_uri(); ?>/images/finish.png' /><p>Encuesta finalizada, muchas gracias por completar el curso <strong><?php echo get_the_title(); ?></strong></p></div>
        <div id='loading_steps' class='loading_steps' style='display:none'><p>Cargando preguntas...</p><img src='<?php echo get_template_directory_uri(); ?>/images/loading.svg' /></div>

        <div class="card-body forms-container">

            <?php 

            $taxonomyData = $term_obj_list = get_the_terms($post->ID, 'categoria');

            $category = $taxonomyData[0]->slug;

            $type = "";
            $type_ = "";

            if($category == "cursos-en-vivo" || $category == "cursos-a-tu-ritmo"){

                if($category == "cursos-en-vivo"){
                    $type = "sincronico";
                    $type_ = "sincronico";
                }

                if($category == "cursos-a-tu-ritmo"){
                    $type = "asincronica";  
                    $type_ = "asinconica";
                }

                // verificar en que paso de la encuesta está 
                $tableName = $wpdb->prefix . "encuesta_satisfaccion";
                $query = $wpdb->get_results(" SELECT * FROM $tableName WHERE `id_user` = $id_alumno AND `id_curso` = $id_curso AND `type` = '$type' ");

                $status = false;

                if(count($query) > 0){

                    $status = $query[0]->current_step == $query[0]->total_steps;

                    if($status){
                        echo "<script>document.getElementById('finish_steps').style.display = 'block';</script>";
                    } else {
                        $current = $query[0]->current_step + 1;
                    }

                } else {
                    $current = 1;
                    $status = false;
                }

                $grupos_preguntas = [];
                $grupos_actuales = get_categories('taxonomy=categoria_encuesta_'.$type_.'&type=encuesta_'.$type.'');

                if(count($grupos_actuales) > 0){

                    foreach ($grupos_actuales as $grupo) {
                        $grupos_preguntas[] = array("id_grupo" => $grupo->term_id, "titulo_grupo" => $grupo->name, "slug_grupo" => $grupo->slug, "descripcion_grupo" => $grupo->description);
                    }
                                        
                    $s = 0;
                    foreach ($grupos_preguntas as $res){ $s++;

                        if($s === 1 && !$status){
                            echo "
                                <div class='campos_mensaje'>
                                    <strong>Los campos marcados con <span>*</span> son obligatorios.</strong>
                                </div>";
                        }

                        $visible = $current == $s ? "display:block" : "display:none";

                        if(!$status){

                            echo "<form id='form_0".$s."' style='".$visible."'>";
                        
                            echo "
                                <div class='encuesta_grupo_titulo'>
                                    <h4>".$res["titulo_grupo"]."</h4>
                                    <small>".$res["descripcion_grupo"]."</small>
                                </div>";

                            $loop = obtenerPreguntas($res["id_grupo"], $type, $type_);

                            while ( $loop->have_posts() ) : $loop->the_post(); $l++;

                                $esRequerida = get_post_meta( get_the_ID(), 'encuesta_'.$type.'_pregunta_obligatoria', true ) === "si" ? "required" : "";
                                $tipoCampo = get_post_meta( get_the_ID(), 'encuesta_'.$type.'_pregunta_tipo_campo', true );
                                $tieneAlternativas = count(get_post_meta( get_the_ID(), 'encuesta_'.$type.'_alternativas', true )) > 1 ? 'si' : 'no';

                                $asterisco = get_post_meta( get_the_ID(), 'encuesta_'.$type.'_pregunta_obligatoria', true ) === "si" ? "<strong class='campo_requerido'>*</strong>" : "";

                                echo "<div class='encuesta_titulo'><h3>".get_the_title()." ".$asterisco."</h3></div>";

                                if($tieneAlternativas === "si"){

                                    // iterar las alernativas
                                    $i = 0;
                                    foreach(get_post_meta( get_the_ID(), 'encuesta_'.$type.'_alternativas', true ) as $res){ $i++;
                                        $alternativa = $res['encuesta_'.$type.'_alternativa'];
                                        $requiereTexto = $res['encuesta_'.$type.'_alternativa_texto'];

                                        $firstRequired = "";

                                        if($requiereTexto === "si"){
                                            $inputAuxiliar = "class='txt_campo_otro'";
                                        }

                                        echo "

                                            <div class='encuesta_campo'>
                                        
                                                <input type='hidden' name='preg_".get_the_ID()."' value='".get_the_title()."' />
                                                <input type='radio' value='".$alternativa."' name='resp_".get_the_ID()."' ".$inputAuxiliar." id='alt_".get_the_ID()."_".$i."' ".$esRequerida." />
                                                <label for='alt_".get_the_ID()."_".$i."'>".$alternativa."</label>
                                            
                                            </div>

                                        ";

                                        if($requiereTexto === "si"){
                                            echo "<input type='text' name='txt_campo_otro_".get_the_ID()."' placeholder='Campo requerido si la opción (".$alternativa.") está activa' />";                        
                                        }

                                    }

                                } else {
                                    echo "<input type='hidden' name='preg_".get_the_ID()."' value='".get_the_title()."' />";

                                    if($tipoCampo === "input"){
                                        echo "<input type='text' name='resp_".get_the_ID()."' placeholder='Por favor completa este campo' ".$esRequerida." />";
                                    } elseif ($tipoCampo === "textarea"){
                                        echo "<textarea name='resp_".get_the_ID()."' placeholder='Por favor completa este campo' ".$esRequerida."></textarea>";
                                    } else {
                                        echo "<input type='text' name='resp_".get_the_ID()."' placeholder='Por favor completa este campo' ".$esRequerida." />";
                                    }


                                }

                            endwhile;
                            wp_reset_postdata();

                            echo "
                    
                                <input type='hidden' name='id_curso' value='".$id_course."' />
                                <input type='hidden' name='id_user' value='".$id_user."' />
                                <input type='hidden' name='type' value='".$type."' />
                        
                            ";

                            echo "<button type='button' class='enviar_respuestas' id='enviar_respuestas_".$s."' onclick='verifyForm(this)'>Continuar</button>";
                            
                        echo "</form>";

                        }

                    }


                } else {

                    echo "no hay preguntas...";

                }
                
            }
            ?>

        </div>
    </div>
</div>

<?php
// verificar en que paso de la encuesta está 
// $tableName = $wpdb->prefix . "encuesta_satisfaccion";
// $query = $wpdb->get_results(" SELECT * FROM $tableName WHERE `id_user` = $id_alumno AND `id_curso` = $id_curso AND `type` = 1 ");

// $current = $query[0]->current;
?>

<script>
const formsContainer = document.querySelector(".forms-container");
const formsQty = [...document.querySelectorAll(".forms-container form")].length;
const loadingSteps = document.getElementById("loading_steps");
const finishSteps = document.getElementById("finish_steps");

const url = '<?php echo get_template_directory_uri() ?>';

function verifyForm(form){


    
    try {
    
        const id_form = form.parentNode.id;
        const currentForm = document.getElementById(id_form);
        const nextForm = document.getElementById(id_form).nextSibling;

        const id_current_form = form.parentNode.id.replace('form_0', '');

        if( currentForm.checkValidity() ){

            if(nextForm.nodeName === "FORM"){

                loadingSteps.style.display = "flex";

            }

            const data = new FormData(document.getElementById(id_form));
            data.append('current', form.parentNode.id);
            data.append('total', formsQty);

            document.getElementById("enviar_respuestas_" + id_current_form).disabled = true;

            fetch(url + '/includes/encuesta-save.php', {
                method: 'POST',
                body: data
            })
            .then(function(response) {
                if(response.ok) {
                    return response.text()
                } else {
                    throw 'Error en la llamada Ajax';
                }

            })
            .then(function(res) {

                respuesta = JSON.parse(res);

                let status = respuesta.status;

                if(status){
                    if(nextForm.nodeName === "FORM"){

                    currentForm.style.display = "none";
                    nextForm.style.display = "block";

                    } else {

                    finishSteps.style.display = "block";
                    formsContainer.remove();

                    }
                } else {

                    alert("Ocurrio un error, por favor recarga la pagina e intenta nuevamente, muchas gracias.");

                }
                
                loadingSteps.style.display = "none";

            })
            .catch(function(err) {
                console.log(err);
            });     

        } else {

            alert("Por favor completa todos los campos requeridos para continuar");

        }

        // let validInput = true;
        // let validRadio = true;

        // const radiosArray = [];

        // [...document.querySelectorAll(`#${form.parentNode.id} input[type="radio"]`)].map(field => {

        //     radiosArray.push(field.name);

        //     if(field.classList.contains('txt_campo_otro') && field.checked){
                
        //         field.parentNode.nextElementSibling.required = "true";

        //     } else {

        //         field.parentNode.nextElementSibling.required = "";
        //         field.parentNode.nextElementSibling.value = "";
                
        //     }

        // });

        // const radiosArrayUnique = [...new Set(radiosArray)];

        // radiosArrayUnique.map(e => {

        //     [...document.querySelectorAll(`[name="${e}"]`)].some(({ checked }) => checked) ? validRadio = true : validRadio = false

        // });

        // [...document.querySelectorAll(`#${form.parentNode.id} input[type="text"]`)].map(field => {

        //     if(field.required){

        //         if(field.value === "") validInput = false

        //     }

        // });

        // if(validInput && validRadio){

        //     if(current < formsQty){

                // const data = new FormData(document.getElementById(id_form));
                // data.append('current', form.parentNode.id);
                // data.append('total', formsQty);

                // fetch(url + '/includes/encuesta-save.php', {
                //     method: 'POST',
                //     body: data
                // })
                // .then(function(response) {
                //     if(response.ok) {
                //         return response.text()
                //     } else {
                //         throw 'Error en la llamada Ajax';
                //     }

                // })
                // .then(function(texto) {
                //     console.log(texto);
                // })
                // .catch(function(err) {
                //     console.log(err);
                // });                

        //     } else {

        //         alert("GRACIAS POR RESPONDER LA ENCUESTA!");

        //     }

        // } else {

        //     alert("Por favor completa los campos marcados con (*) para continuar.");

        // }

    } catch (error) {
        console.log('error',error)
    }
    
}    
</script>

<?php 
include('../../../../wp-load.php');

if($_POST){

    $start = $_POST["start"];
    
    ?>
        
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

    <div class="card-body">

    <?php 

    // $type = "asinconica";
    $type = "sincronico";

    $id_alumno = $_POST["id_alumno"];
    $id_curso = $_POST["id_curso"];

    // verificar en que paso de la encuesta está 
    $tableName = $wpdb->prefix . "encuesta_satisfaccion";
    $query = $wpdb->get_results(" SELECT * FROM $tableName WHERE `id_user` = $id_alumno AND `id_curso` = $id_curso AND `type` = 1 ");

    if(count($query) > 0){

        $grupos_preguntas = [];
        $grupos_actuales = get_categories('taxonomy=categoria_encuesta_'.$type.'&type=encuesta_'.$type.'');

        if(count($grupos_actuales) > 0){

            foreach ($grupos_actuales as $grupo) {
                $grupos_preguntas[] = array("id_grupo" => $grupo->term_id, "titulo_grupo" => $grupo->name, "slug_grupo" => $grupo->slug, "descripcion_grupo" => $grupo->description);
            }

            usort($grupos_preguntas, function($a, $b) {
                return $a['id_grupo'] - $b['id_grupo'];
            });
            
            $s = 0;
            foreach ($grupos_preguntas as $res){ $s++;

                if($s === 1){
                    echo "
                        <div class='campos_mensaje'>
                            <strong>Los campos marcados con <span>*</span> son obligatorios.</strong>
                        </div>";
                }

                echo "<form id='form_0".$s."' style='display:none'>";
            
                    echo "
                        <div class='encuesta_grupo_titulo'>
                            <h4>".$res["titulo_grupo"]."</h4>
                            <small>".$res["descripcion_grupo"]."</small>
                        </div>";

                    $loop = obtenerPreguntas($res["id_grupo"], $type);

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
                        <input type='hidden' name='id_alumno' value='".$id_user."' />
                        <input type='hidden' name='type' value='".$type."' />
                
                    ";

                    echo "<button type='button' class='enviar_respuestas'>Continuar</button>";
                    
                echo "</form>";

            }


        } else {

            echo "no hay preguntas...";

        }

    }

}
?>
<!-- enviar_respuestas // TODO! -->
<script>

const url = '<?php echo get_template_directory_uri() ?>';

function verifyForm(form){
    
    try {
    
        const id_form = form.parentNode.id;

        let validInput = true;
        let validRadio = true;

        const radiosArray = [];

        [...document.querySelectorAll(`#${form.parentNode.id} input[type="radio"]`)].map(field => {

            radiosArray.push(field.name);

            if(field.classList.contains('txt_campo_otro') && field.checked){
                
                field.parentNode.nextElementSibling.required = "true";

            } else {

                field.parentNode.nextElementSibling.required = "";
                field.parentNode.nextElementSibling.value = "";
                
            }

        });

        const radiosArrayUnique = [...new Set(radiosArray)];

        radiosArrayUnique.map(e => {

            [...document.querySelectorAll(`[name="${e}"]`)].some(({ checked }) => checked) ? validRadio = true : validRadio = false

        });

        [...document.querySelectorAll(`#${form.parentNode.id} input[type="text"]`)].map(field => {

            if(field.required){

                if(field.value === "") validInput = false

            }

        });

        if(validInput && validRadio){

            const data = new FormData(document.getElementById(id_form));

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
            .then(function(texto) {
                console.log(texto);
            })
            .catch(function(err) {
                console.log(err);
            });

        } else {

            alert("Por favor completa los campos marcados con (*) para continuar.");

        }

    } catch (error) {
        console.log('error',error)
    }
    
}    
</script>
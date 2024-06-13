<?php 
include('../../../wp-load.php');
?>

<form id="form_etp_01">
							
<?php 
$html = '';
$entries = get_post_meta( $_POST["curso"], 'presencial_preguntas', true );
if(!empty($entries)){
    $x = 0;
    foreach ($entries as $key => $entry ) { $x++;

        if ($entry['presencial_pregunta']) {
            $pregunta = esc_html( $entry['presencial_pregunta'] );

            $alternativas = $entry['presencial_alternativa'];
            ?>

                <ul class="pregunta pregunta_<?php echo $x; ?>">
                    <li><span><?php echo $pregunta ?></span></li>
                    <ul>
                        <?php 
                        $i = 0;
                        foreach($alternativas as $alt){ $i++;
                            if($entry['presencial_alternativa_correcta'] === $alt){
                                echo '<input type="hidden" name="alt_c[]" value="alternativa_'.$i.'_'.$x.'">';
                                echo '<input type="hidden" name="alt_etp_01_c[]" value="'.$entry['presencial_alternativa_correcta'].'|alternativa_'.$i.'_'.$x.'">';
                            }
                            echo '<li class="alternativa_'.$i.'_'.$x.'"><input type="checkbox" class="alernativa_preg_'.$x.'" name="alt_sel[]" value="'.$alt.'|alternativa_'.$i.'_'.$x.'" style="width: 20px; box-shadow: none; height: 15px;" id="sel_'.$x.'_'.$i.'"><label for="sel_'.$x.'_'.$i.'">'.$alt.'</label></li>';
                        }
                        ?>
                    </ul>
                </ul>

                <input type="hidden" name="preg_etp_01[]" value="<?php echo $pregunta; ?>">
                <input type="hidden" name="preg_n[]" value="<?php echo $x; ?>">
                

        <?php
        }
    }

    echo '<input type="hidden" name="cant_preg" value="'.count($entries).'">';

}
?>

<img src="<?php echo get_template_directory_uri(); ?>/cargando.gif" id="cargando_1" style="display:none; width: 20px; margin-bottom: 20px;">

<button class="button" type="button" id="finalizar_etapa_01" data-id="1">Finalizar</button>

</form>
<?php 
if(get_post_meta( get_the_ID(), 'bibliografia_titulo', true )){
?>

<div class="dashboard-list-box invoices" style="border-top: 10px solid #B0358A; margin-bottom: 20px;">
    <h4>
        <img src="<?php echo get_template_directory_uri(); ?>/images/bibliografia.png" style='width: 33px;'> 
        Resumen bibliografía del curso
        <br/><br/>
        <p>Aquí puedes descargar el resumen de todos los textos obligatorios y complementarios que forman parte de este curso.</p>
    </h4>
    
        <?php 
        $docs = get_post_meta(get_the_ID(), 'bibliografia_descargable', true);
            
        if($docs && count($docs) > 0){

            $html_biografia = '<li style="    list-style: none;
            display: block;
            padding: 10px;
            background: #FFFFFF;">';

            foreach($docs as $doc){
                $html_biografia .= '<a href="'.$doc.'" target="_blank" style="margin: 14px;" rel="noopener noreferrer"><img style="width:22px;" src="'.get_template_directory_uri().'/images/doc.png" /></a>';
            }

            $html_biografia .= '</li>';

            echo $html_biografia;

        }
        ?>

</div>

<?php 
}
?>
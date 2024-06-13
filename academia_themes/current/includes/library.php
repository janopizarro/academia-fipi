<?php 
if(haveLibrary( $id_curso )){
?>

<div class="dashboard-list-box invoices" style="border-top: 10px solid #B0358A;">
    <h4>
        <img src="<?php echo get_template_directory_uri(); ?>/images/biblioteca.png" style='width: 33px;'> 
        <a href="<?php the_permalink(haveLibrary($id_curso)[0]->ID); ?>" target="_blank" rel="nofollow noopener noreferrer">Biblioteca de Curso</a>
        <br/><br/>
        <p>Aquí puedes revisar la biblioteca del curso.</p>
    </h4>
</div>

<?php 
}
?>
<?php 
session_start();

// se verifica si la fecha de termino ya se cumplió
// $fechaTermino = get_post_meta( get_the_ID(), 'curso_fecha_termino', true );

// setlocale(LC_TIME, 'es_ES', 'Spanish_Spain', 'Spanish'); 
// $date = $date = str_replace("/","-",get_post_meta( get_the_ID(), 'curso_fecha_termino', true ));

// $date_now = new DateTime();
// $date2    = new DateTime($fechaTermino);
// $date2->modify('+1 day');

// if($date_now > $date2){ 

// 	echo "<script>alert('Curso finalizó el ".$date."');</script>"; redirect(100,'login'); 

// } else {

	/* * functions single course * */
	require_once dirname( __FILE__ ) . '/single-course--functions.php';

    get_header();
	?>


	<!-- Dashboard -->
	<div id="dashboard">

        <style>
        .button_course{
            background: orange;
            padding: 4px 15px;
            border-radius: 23px;
            color: #FFFFFF;
            font-size: 16px;
            display: table;
            margin-top: 20px;
        }
        </style>

		<?php 
		/* * side * */
		require_once dirname( __FILE__ ) . '/includes/side.php';
		?>

		<div class="dashboard-content" style="height: 100%;">

			<!-- Titlebar -->
			<div id="titlebar">
				<div class="row">
					<div class="col-md-12">
						<?php 
                        echo '<h2 style="width:100%">Biblioteca de curso: '.get_the_title(get_post_meta( get_the_ID(), 'biblioteca_curso', true )).'</h2>';
                        // echo '<strong><a class="button_course" href="'.get_the_permalink(get_post_meta( get_the_ID(), 'biblioteca_curso', true )).'">Regresar al curso</a></strong>';
                        ?>
					</div>
				</div>
			</div>

			<div class="row">
				
				<div class="col-lg-6 col-md-12">
					<div class="dashboard-list-box invoices margin-top-20">

                        <div class="dashboard-list-box invoices margin-top-20">
    
                            <ul>
    
                                <?php
                                // BIBLIOTECA_CURSO
                                $arr = get_post_meta( get_the_ID(), 'biblioteca_archivos', true );

                                $files = array();

                                if(count(haveFilesUnitCourse( get_the_ID() )) > 0){
                                    // SE UNEN LOS ARCHIVOS DE LAS UNIDADES DINÁMICAS Y LOS ARCHIVOS EXTRAS DE LA BIBLIOTECA
                                    $filesGroup = array_merge($arr,haveFilesUnitCourse( get_the_ID() ));
                                } else {
                                    $filesGroup = $arr;
                                }

                                foreach($filesGroup as $res){
                                ?>

                                    <li style="padding-left: 30px;">
                                        <strong>
                                            <a style="font-size: 16px;" href="<?php echo $res; ?>" rel="nofollow noopener noreferrer" target="_blank">
                                                <img src="<?php echo get_template_directory_uri(); ?>/images/doc.png" style="width: 23px;">
                                                <?php 
                                                $var = str_replace(".pdf","",$res); 
                                                $del = substr($var, 0, strrpos( $var, '/'));
                                                $str = str_replace($del."/","",$var);
                                                $str = str_replace(array("-","_"),array(" "," "),$str);
                                                echo ucwords($str);
                                                ?>

                                            </a>
                                        </strong> 
                                    </li>

                                <?php 
                                }
                                ?>

                                <?php
                                $arr = get_post_meta( get_the_ID(), 'biblioteca_links', true );

                                if(count(haveLinksUnitCourse( get_the_ID() )) > 0){
                                    // SE UNEN LOS ARCHIVOS DE LAS UNIDADES DINÁMICAS Y LOS ARCHIVOS EXTRAS DE LA BIBLIOTECA
                                    $linksGroup = array_merge($arr,haveLinksUnitCourse( get_the_ID() ));
                                } else {
                                    $linksGroup = $arr;
                                }

                                foreach($linksGroup as $res){
                                ?>

                                    <li style="padding-left: 30px;">
                                        <strong>
                                            <a style="font-size: 16px;" href="<?php echo $res; ?>" rel="nofollow noopener noreferrer" target="_blank">
                                                <img src="<?php echo get_template_directory_uri(); ?>/images/doc_apoyo.png" style="width: 23px;">
                                                <?php 
                                                if((strpos($res, '.pdf') !== false)){
                                                    $var = str_replace(".pdf","",$res); 
                                                    $del = substr($var, 0, strrpos( $var, '/'));
                                                    $str = str_replace($del."/","",$var);
                                                    $str = str_replace(array("-","_"),array(" "," "),$str);
                                                    echo ucwords($str);
                                                } else {
                                                    echo $res;
                                                }
                                                ?>

                                            </a>
                                        </strong> 
                                    </li>

                                <?php 
                                }
                                ?>

                            </ul>

                        </div>

					</div>
				</div> 
				


	<?php 
	get_footer();
	?>

<?php 
// }
?>
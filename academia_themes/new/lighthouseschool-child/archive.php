<?php
/**
 * The template to display all single pages
 *
 * @package WordPress
 * @subpackage LIGHTHOUSESCHOOL
 * @since LIGHTHOUSESCHOOL 1.0
 */

session_start();
get_header();

/* * * * * * */
$id_alumno = getDataSession("id");
$email_alumno = getDataSession("email");

$fecha_actual = date("Y-m-d");
/* * * * * * */

get_header();

	?>

    <link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri(); ?>/css/bootstrap.min.css" crossorigin="anonymous">

    <style>
    .row-cols-3 > * {
      margin-bottom: 15px;
    }
    .row-cols-3 [class^="col-"] {
      padding-right: 5px;
      padding-left: 5px;
    }
    </style>

<style>
    /* Estilos generales */
    .container-new {
      /* display: grid;
      grid-template-columns: 1fr 3fr;
      gap: 20px; */
        display: flex;
        padding: 20px;
    }
    .sidebar-new {
      background-color: #f8f9fa;
      padding: 20px;
    min-width: 300px;
    margin-right: 20px;
    }
    .content-new {
        display: flex;
        flex-wrap: wrap;
        transition: all 0.4s;
    }
    .item-new {
        background-color: #f0f0f0;
        padding: 0px;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 0 6px #00000017;
    }

    .item-new .item-new--inset{
        padding: 15px;
    }

    .item-new .item-new--inset h5{
        text-transform: capitalize;
        color: #2A649D;
        font-size: 20px !important;
    }

    .item-new .dashboard-stat{
        height: auto;
        overflow: hidden;
        position: relative;
    }

    .item-new .dashboard-stat img{
        width: 100%;
        zoom: 2;
    }

    @media (max-width: 768px) {
      .container-new {
        display: block;
        padding: 0px;
        padding-top: 30px;
      }
      .sidebar-new{
        margin: 0px;
        min-width: 100%;
      }
      .cableItem{
        max-width: 100% !important;
        margin: 0px !important;
        margin: 25px 0px !important;
      }
    }
  </style>

    <div>

        <div class="container-new">

            <div class="sidebar-new">

                <div id="matchCount" style="text-align: left;padding: 5px 10px;background: #fffffc;margin-bottom: 14px;color: #2A649D;font-size: 12px;"></div>

                <h3>¿Qué buscas?</h3>

                <?php echo do_shortcode( '[filtros_cursos_fipi]' ); ?>

                <!-- <div class="sidebar-new--widget">
                    <input type="search" placeholder="Buscar por nombre" class="search-course-input" />
                </div>
                
                <div class="sidebar-new--widget">

                    <h3>Categoría:</h3>

                    <ul>
                        <li><input type="checkbox" id="vehicle1" name="vehicle1" value="Bike" /> Especialización</li>
                        <li><input type="checkbox" id="vehicle1" name="vehicle1" value="Bike" /> Intervención</li>
                        <li><input type="checkbox" id="vehicle1" name="vehicle1" value="Bike" /> Evaluación</li>
                        <li><input type="checkbox" id="vehicle1" name="vehicle1" value="Bike" /> Fundamentos Teóricos</li>
                        <li><input type="checkbox" id="vehicle1" name="vehicle1" value="Bike" /> Enfoques Transversales</li>
                    </ul>
                </div>

                <div class="sidebar-new--widget">

                    <h3>Tipo:</h3>

                    <ul>
                        <li><input type="checkbox" id="vehicle1" name="vehicle1" value="Bike" /> Programa en Vivo</li>
                        <li><input type="checkbox" id="vehicle1" name="vehicle1" value="Bike" /> Programa con Sesiones Grabadas</li>
                    </ul>
                </div>

                <div class="sidebar-new--widget">

                    <h3>Tags</h3>

                    <nav class="widget-tags">
                        <a href="#">Educación</a>
                        <a href="#">Teoría</a>
                        <a href="#">Fipi de apaña</a>
                        <a href="#">Salud mental</a>
                        <a href="#">Amistad</a>
                        <a href="#">Enseñanza</a>
                    </nav>

                </div> -->

            </div>

            <!-- Contenido principal -->
            <div class="content-new category">

                <div id="notFound"></div>

                <?php 
                $queried_object = get_queried_object();
                $term_id = $queried_object->term_id;
                $term_name = $queried_object->name;
                
                $args = array(  
                    'posts_per_page' => -1,
                    'order' => 'DESC',
                    'post_status' => 'publish',
                    'post_type' => 'curso'
                    // 'tax_query' => array(
                    //     array(
                    //     'taxonomy' => 'categoria',
                    //     'field' => 'term_id',
                    //     'terms' => $term_id
                    //     ),
                    // ),
                );

                $loop = new WP_Query( $args );

                // si encuentra post imprime el card
                if($loop->post_count > 0){

                    while ( $loop->have_posts() ) : $loop->the_post();

                    $id_curso = $loop->post->ID;

                    $toFilter = [];

                    $asd__ = get_the_terms($id_curso, 'categoria');
                    $asd = get_the_terms($id_curso, 'modalidad_cursos');
                    $asd_ = get_the_terms($id_curso, 'tags_cursos');

                    if($asd__){
                        foreach($asd__ as $re){
                            $toFilter[] = $re->slug;
                        }    
                    }

                    $modalidd_ = [];

                    if($asd){
                        foreach($asd as $re){
                            $toFilter[] = $re->slug;
                            $modalidd_[] = $re->name;
                        }    
                    }

                    if($asd_){
                        foreach($asd_ as $re_){
                            $toFilter[] = $re_->slug;
                        }
                    }

                    $string = implode(", ", $toFilter);
                    ?>

                    <div class="item-new cableItem" data-title="<?php echo get_the_title($id_curso); ?>" data-target="<?php echo $string; ?>">
                        <!-- <h4>Columna 1</h4>
                        <p>Contenido de la columna 1...</p> -->
                        <?php include 'includes/card.php'; ?>
                    </div>

                    <?php 
                    endwhile;
                    wp_reset_postdata();

                } else {

                echo "<p class='working-course'>Estámos trabajando en nuevos contenidos</p>";

                }
                ?>

                
            </div>

        </div>

    </div>

    <!--<div class="container-fluid">
        <div class="row">
            <div class="col-md-3">
                <div class="filtros-cursos">
                    <h3>Filtrar</h3>
                </div>
            </div>
            <div class="col-md-9">

                <div class="row g-3 row-cols-3">
                <!-- <div class="dashboard-content">

                    <div class="row row--new cards--fipi"> -->

                        <?php 
                        // $queried_object = get_queried_object();
                        // $term_id = $queried_object->term_id;
                        // $term_name = $queried_object->name;
                        
                        // $args = array(  
                        //     'posts_per_page' => -1,
                        //     'order' => 'DESC',
                        //     'post_status' => 'publish',
                        //     'tax_query' => array(
                        //         array(
                        //         'taxonomy' => 'categoria',
                        //         'field' => 'term_id',
                        //         'terms' => $term_id
                        //         ),
                        //     ),
                        // );

                        // $loop = new WP_Query( $args );

                        // // si encuentra post imprime el card
                        // if($loop->post_count > 0){

                        //     while ( $loop->have_posts() ) : $loop->the_post();

                        //     $id_curso = $loop->post->ID;
                            
                        //     include 'includes/card.php';

                        //     endwhile;
                        //     wp_reset_postdata();

                        // } else {

                        //     echo "<p class='working-course'>Estámos trabajando en nuevos contenidos</p>";

                        // }
                        ?>

                    <!-- </div> -->

                <!--</div>

            </div>
            
        </div>

    </div>-->

	<?php

get_footer();
?>
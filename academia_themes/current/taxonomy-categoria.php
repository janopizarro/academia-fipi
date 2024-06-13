<?php 
session_start();
get_header();

/* * * * * * */
$id_alumno = getDataSession("id");
$email_alumno = getDataSession("email");

$fecha_actual = date("Y-m-d");
/* * * * * * */
?>

<!-- Dashboard -->
<div id="dashboard">

    <?php 
    /*
     * card * */

    require_once dirname( __FILE__ ) . '/includes/side.php';

    /*
     * card * */
    ?>

    <div class="dashboard-content">

        <div class="row row--new">

            <?php 
            // obtener info de la taxonomia actual
            $queried_object = get_queried_object();
            $term_id = $queried_object->term_id;
            $term_name = $queried_object->name;
            ?>

            <h4 class="title-row"><?php echo $term_name; ?></h4>

            <?php
            // argumentos para la query
            $args = array(  
				'posts_per_page' => -1,
				'order' => 'DESC',
                'post_status' => 'publish',
                'tax_query' => array(
                    array(
                    'taxonomy' => 'categoria',
                    'field' => 'term_id',
                    'terms' => $term_id
                    ),
                ),
			);

			$loop = new WP_Query( $args );

            // si encuentra post imprime el card
            if($loop->post_count > 0){

                while ( $loop->have_posts() ) : $loop->the_post();

                $id_curso = $loop->post->ID;
                
                include 'includes/card.php';

                endwhile;
                wp_reset_postdata();

            } else {

                echo "<p class='working-course'>Estámos trabajando en nuevos contenidos</p>";

            }
			?>


        </div>

    </div>

    <?php 
get_footer();
?>
<?php
// funciones
// include('funciones.php');

// function orderDate($date){
//     $date = explode("-",$date);
//     $date = $date[2]."-".$date[1]."-".$date[0];
//     return $date;
// }

// Encuesta Sincronica Resultados
add_action('admin_menu', 'register_encuesta_sincronica_grafico_resultados_page');

function register_encuesta_sincronica_grafico_resultados_page() {
  add_submenu_page( 'edit.php?post_type=encuesta_sincronico', 'Resultados (Gráfico) Todos los años', 'Resultados (Gráfico) Todos los años', 'manage_options', 'encuesta_sincronica_resultados_grafico', 'encuesta_sincronica_resultados_grafico_page_callback' ); 
}

function encuesta_sincronica_resultados_grafico_page_callback() {

    echo '<div class="wrap"><div id="icon-tools" class="icon32"></div>';
    ?>    

        <style>
        .notice{ display:none; }
        td, th, .dataTables_info, .dataTables_filter, .paging_simple_numbers, .dt-buttons{
            font-size: 12px;
        }
        .btn{
            font-size: 9px;
            padding: 2px 10px;
            background: orange;
            border: orange;
            color: #121212;
        }
        </style>

        <div>
            <h3>Resultados</h3>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/chart.js@3.8.0/dist/chart.min.js"></script>
	    <!-- <script src="https://www.chartjs.org/samples/latest/utils.js"></script> -->

    	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>

        <div style="display: flex; flex-wrap: wrap;">

        <style>
        h2{
            flex: 0 0 95%;
            display: flex;
            background: #a0a0a0;
            padding: 10px;
            color: #FFFFFF;
            text-shadow: 0px 0px 4px #343434;
            font-size: 21px !important;
            border-radius: 40px;
            padding-left: 19px;
        }
        h3{
            font-size: 20px !important;
            font-weight: bold;
            color: #855ba2;
        }
        .grupo_grafico{
            flex: 0 0 100%;
            display: flex;
            flex-wrap: wrap;
        }
        .grafico_{
            flex: 0 0 33%;
        }
        </style>

        <?php 
        // require_once dirname( __FILE__ ) . '/encuesta-sincronica-table/chart-01.php';
        // require_once dirname( __FILE__ ) . '/encuesta-sincronica-table/chart-02.php';
        require_once dirname( __FILE__ ) . '/encuesta-sincronica-table/chart-03.php';
        // require_once dirname( __FILE__ ) . '/encuesta-sincronica-table/table.php';
        ?>

        </div>

        <script>
        // window.onload = function(){
            <?php
            // $x = 0;
            // while($x < 2) { $x++;
    
            //     echo '
                
            //         var ctx'.$x.' = document.getElementById("chart-area'.$x.'").getContext("2d");
            //         var ctx0'.$x.' = new Chart(ctx'.$x.',config0'.$x.');
                    
            //     ';
    
            // }
            ?>
        // };
        </script>

<?php
	echo '</div>';
}
?>
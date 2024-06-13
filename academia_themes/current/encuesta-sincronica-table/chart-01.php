<h5>Cursos con encuesta completa</h5>

<?php 
$x_ = 1;
?>

<div id="canvas-holder-0<?php echo $x_; ?>" style="max-width:400px;">
    <canvas id="chart-area<?php echo $x_; ?>"></canvas>
</div>

<?php
global $wpdb;
$tableName = $wpdb->prefix . "encuesta_satisfaccion";
$data = $wpdb->get_results( " SELECT * FROM $tableName WHERE type = 'sincronico' " );

$cursos = array();

foreach($data as $res){
    $cursos[] = $res->id_curso;
}
?>

<script>
    var config0<?php echo $x_ ?> = {
        type: 'pie',
        data: {
            datasets: [{
                data: [
                    <?php 
                    foreach(array_count_values($cursos) as $x => $key){
                        echo $key.",";
                    }
                    ?>
                ],
                backgroundColor: 'orange',
                label: 'Dataset <?php echo $x; ?>'
            }],
            labels: [
                <?php 
                foreach(array_count_values($cursos) as $x => $key){
                    echo "'".get_the_title($x)."',";
                }
                ?>
            ],
        },
        options: {
            responsive: true,
            plugins: {
                title: {
                    display: true,
                    text: 'Chart Title',
                }
            }
        }
    };

</script>
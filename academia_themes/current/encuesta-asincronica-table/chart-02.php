<h5>Alumnos con encuesta completa</h5>

<?php 
$x_ = 2;
?>

<div id="canvas-holder-0<?php echo $x_; ?>" style="max-width:400px;">
    <canvas id="chart-area<?php echo $x_; ?>"></canvas>
</div>

<?php
global $wpdb;
$tableName = $wpdb->prefix . "encuesta_satisfaccion";
$data = $wpdb->get_results( " SELECT * FROM $tableName WHERE type = 'sincronico' " );

$alumnos = array();

foreach($data as $res){
    $alumnos[] = $res->id_user;
}
?>

<script>
    var config0<?php echo $x_ ?> = {
        type: 'pie',
        data: {
            datasets: [{
                data: [
                    <?php 
                    foreach(array_count_values($alumnos) as $x => $key){
                        echo $key.",";
                    }
                    ?>
                ],
                backgroundColor: 'green',
                label: 'Dataset <?php echo $x; ?>'
            }],
            labels: [
                <?php 
                foreach(array_count_values($alumnos) as $x => $key){
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
<?php 
$unicos = array_unique($respuestasEdad);

?>

<?php 
$total = 0;
foreach($unicos as $res){
    $num = getAlgo($respuestasEdad, $res);
    $total += $num;
}

$nums = "";
foreach($unicos as $res){
    $num = getAlgo($respuestasEdad, $res);
    $porcentaje = ($num / $total) * 100;
    $nums .= round($porcentaje).",";
}

?>


<div style="width: 500px; height:500px; text-align:center;">
    <strong>TOTAL RESPUESTAS (100%): <strong style="color:red;"><?php echo $total; ?></strong></strong>
    <canvas id="myChart2"></canvas>
</div>


<script>
document.addEventListener('DOMContentLoaded', function() {


    
            var ctx1 = document.getElementById('myChart2').getContext('2d');

            var data1 = {
                labels: [
                    <?php 
                    foreach($unicos as $res){
                        echo '"Rango Edad: '.$res.'",';
                    }
                    ?>
                ],
                datasets: [{
                    axis: 'y',
                    label: 'Porcentaje',
                    data: [
                        <?php echo $nums; ?>
                    ],
                    fill: false,
                    backgroundColor: ['#D24545','#86A7FC','#A78295','#525CEB','#80BCBD','#B80000','#FB8B24','#C21292','#0766AD','#ED5AB3','#D24545','#86A7FC','#A78295','#525CEB','#80BCBD','#B80000','#FB8B24','#C21292','#0766AD','#ED5AB3','#D24545','#86A7FC','#A78295','#525CEB','#80BCBD','#B80000','#FB8B24','#C21292','#0766AD','#ED5AB3','#D24545','#86A7FC','#A78295','#525CEB','#80BCBD','#B80000','#FB8B24','#C21292','#0766AD','#ED5AB3','#D24545','#86A7FC','#A78295','#525CEB','#80BCBD','#B80000','#FB8B24','#C21292','#0766AD','#ED5AB3','#D24545','#86A7FC','#A78295','#525CEB','#80BCBD','#B80000','#FB8B24','#C21292','#0766AD','#ED5AB3'],
                    borderColor: ['#D24545','#86A7FC','#A78295','#525CEB','#80BCBD','#B80000','#FB8B24','#C21292','#0766AD','#ED5AB3','#D24545','#86A7FC','#A78295','#525CEB','#80BCBD','#B80000','#FB8B24','#C21292','#0766AD','#ED5AB3','#D24545','#86A7FC','#A78295','#525CEB','#80BCBD','#B80000','#FB8B24','#C21292','#0766AD','#ED5AB3','#D24545','#86A7FC','#A78295','#525CEB','#80BCBD','#B80000','#FB8B24','#C21292','#0766AD','#ED5AB3','#D24545','#86A7FC','#A78295','#525CEB','#80BCBD','#B80000','#FB8B24','#C21292','#0766AD','#ED5AB3','#D24545','#86A7FC','#A78295','#525CEB','#80BCBD','#B80000','#FB8B24','#C21292','#0766AD','#ED5AB3'],
                    borderWidth: 1
                }]
            };

            var char1 = new Chart(ctx1, {
                type: 'doughnut',
                data: data1,
                options: {
                    cutout: 0,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'right'
                        },
                        labels: {
                            render: 'percentage',
                            precision: 2,
                            showZero: true,
                            fontSize: 12,
                            fontColor: '#121212',
                            fontStyle: 'normal',
                            fontFamily: "'Helvetica Neue', 'Helvetica', 'Arial', sans-serif",
                            textShadow: true,
                            shadowBlur: 10,
                            shadowOffsetX: -5,
                            shadowOffsetY: 5,
                            shadowColor: 'rgba(255,0,0,0.75)',
                            arc: true,
                            position: 'default',
                            overlap: true,
                            showActualPercentages: true,
                            images: [
                                {
                                    src: 'image.png',
                                    width: 16,
                                    height: 16
                                }
                            ],
                            outsidePadding: 4,
                            textMargin: 4
                        }
                    }
                }
            });
        });

        
</script>
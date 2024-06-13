<?php 

// load wordpress
require_once($_SERVER['DOCUMENT_ROOT'].'/wp-load.php');

// process.php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dataType = $_POST['dataType'];
    $items = $_POST['items'];

    echo '<div id="back" style="padding: 20px;margin-bottom: 40px;border-bottom: 2px dashed #bc24a7;"><a href="#" style="padding: 12px 26px;font-size: 17px;background: #bc24a7;border-radius: 45px;display: inline-block;color: #FFFFFF;text-decoration: none;font-family: monospace;">REGRESAR AL RESUMEN DE ALUMNOS</a></div>';
    
    if (is_array($items)) {
        // foreach ($items as $item) {
        //     echo "Nombre: " . htmlspecialchars($item['nombre']) . "<br>";
        //     echo "Curso: " . htmlspecialchars($item['curso']) . "<br>";
        //     echo "Tipo: " . htmlspecialchars($item['tipo']) . "<br>";
        //     echo "Horas: " . htmlspecialchars($item['horas']) . "<br>";
        //     echo "Mes: " . htmlspecialchars($item['mes']) . "<br>";
        //     echo "Año: " . htmlspecialchars($item['ano']) . "<br><br>";
        // }

        $i = 0;

        foreach($items as $res){ $i++;
    
            $name = $texto_formateado = htmlspecialchars(ucwords(strtolower($res["nombre"])));
            $nameCourse = htmlspecialchars($res["curso"]);
            $type = str_replace(["Cursos en vivo","Cursos a tu ritmo"],["Sincrónico","Asincrónico"],htmlspecialchars($res["tipo"]));
            $hours = htmlspecialchars($res["horas"]);
            $month = htmlspecialchars($res["mes"]);
            $year = htmlspecialchars($res["ano"]);
            
            $html = '
            
            <!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta http-equiv="X-UA-Compatible" content="IE=edge">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <link rel="preconnect" href="https://fonts.googleapis.com">
                <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
                <link href="https://fonts.googleapis.com/css2?family=Arbutus+Slab&family=Montserrat:wght@600&family=Raleway:wght@400;500&display=swap" rel="stylesheet">
                <link href="https://fonts.cdnfonts.com/css/brotherhood-script" rel="stylesheet">
                <title>Document</title>
                <style>
                html,body {width: 100%;height: 100%;font: 32px/1.4;letter-spacing: 0;}
                #curved2 {position: absolute;width: 89px; width: 89px;}
                @media print {
                    .printSpecial {
                        top: 20px !important;
                    }
                  }
                </style>
            </head>
            <body>
                <div id="pdf_'.$i.'" style="position:relative; max-width: 1100px; height: 600px; border-top: 25px solid #2C68A5; border-bottom: 25px solid #2C68A5;">
                    <div style="width: 15px;display: flex;justify-content: space-between;margin: 30px; margin-top: 0px; margin-bottom: 0px;">
                        <div style="width:1.5px; height: 600px; background-color: #FAE14D"></div>
                        <div style="width:1.5px; height: 600px; background-color: #BC2871"></div>
                    </div>
                    <div style="height: 15px;width: 100%;justify-content: space-between;margin: 30px;margin-top: 0px;margin-bottom: 0px;top: 29px;position: absolute;left: -29px;">
                        <div style="width:100%; height: 1.5px; background-color: #FAE14D; margin-bottom: 14px;"></div>
                        <div style="width:100%; height: 1.5px; background-color: #BC2871"></div>';

                        if($dataType === "a"){
                            $html .= '<div style="width: auto; padding-right: 35px;height: auto;position: absolute;right: 30px;top: -21px;display: flex;">
                                        <img src="ideas-para-la-infancia.jpg" style="height:70px;" />
                                        <img src="academia-fipi.jpg" style="height:70px;" />
                                      </div>';
                        }

                        if($dataType === "b"){
                            $html .= '<div style="width: auto; padding-right: 35px;height: auto;position: absolute;right: 30px;top: -21px;display: flex;">
                                        <img src="ideas-para-la-infancia.jpg" style="height:70px;" />
                                    </div>';
                        }

                        if($dataType === "c"){
                            $html .= ' <div style="width: auto; padding-right: 35px;height: auto;position: absolute;right: 30px;top: -21px;display: flex;">
                                            <img src="academia-fipi.jpg" style="height:70px;" />
                                        </div>';
                        }

                        if($dataType === "d"){
                            $html .= '<div style="width: auto; padding-right: 35px;height: auto;position: absolute;right: 30px;top: -21px;display: flex;">
                                        <img src="ideas-para-la-infancia.jpg" style="height:70px;" />
                                        <img src="academia-fipi.jpg" style="height:70px;" />
                                        <img src="simaq.png" style="height:70px;" />
                                    </div>';
                        }


                    $html .= '</div>
                    <div style="position: absolute;width: 15px;display: flex;justify-content: space-between;margin: 30px;margin-top: 0px;margin-bottom: 0px;top: 0px;right: 0px;">
                        <div style="width:1.5px; height: 600px; background-color: #FAE14D"></div>
                        <div style="width:1.5px; height: 600px; background-color: #BC2871"></div>
                    </div>
                    <div style="text-align: center;position: absolute;left: 0;right: 0;top: 0;bottom: 0;margin: auto;height: fit-content; padding-top: 80px;">
                        <h1 style="color: #2C68A5; font-family:\'Arbutus Slab\',serif; font-size:60px; margin:0px; font-weight: 200; letter-spacing: 2px;">CERTIFICADO</h1>
                        <p style="color: #2C68A5; font-family:\'Raleway\',serif; font-size:23px; margin:0px; margin-bottom: 35px; letter-spacing: 4px;">DE APROBACIÓN</p>
                        <p style="color: #2C68A5; font-family:\'Raleway\',serif; font-size:14px; margin: 0px;">SE ENTREGA EL PRESENTE CERTIFICADO A:</p>
                        <h2 style="color: #2C68A5; font-family:\'Brotherhood Script\',serif; margin-bottom:0px; font-size: 72px; font-weight: 300; margin-top: 30px;">'.$name.'</h2>
                        <div style="max-width: 600px;height: 1.5px;background-color: #BC2871;margin: 0 auto 0 auto;position: relative;top: -5px;z-index: -1;">
                            <p style="color: #2C68A5; font-family:\'Raleway\',serif; font-weight: bold; font-size: 14px; margin:0px; padding-top:30px;">Fundación Ideas para la Infancia, certiﬁca que ha concluido exitosamente el <span style="color: #BC2871;">'.$nameCourse.'</span> en  Modalidad <span style="color: #BC2871;">'.$type.'.</span></p>
                            <p style="color: #2C68A5; font-family:\'Raleway\',serif; font-weight: bold; font-size: 14px; margin:0px; padding-top:0px;">'.$month.' '.$year.'</p>
                        </div>
                        <div style="display: flex;align-items: center;padding-top: 95px;justify-content: space-between;max-width: 800px;margin: 0 auto 0 auto;width: 100%;left: 0;right: 0;">
                            <div style="text-align:center;">
                                <img src="firma-a.png" style="height:100px;" />
                                <div style="width: 220px;height: 1.5px;background-color: #BC2871;position: relative;top: -24px;"></div>
                                <p style="font-size:11px; font-family: \'Montserrat\', sans-serif; color: #BC2871; font-weight: bold;  margin: 0px; margin-top: -17px;">María Paz Badilla Budinich</p>
                                <p style="font-size:7px; font-family: \'Montserrat\', sans-serif; color: #2C68A5;">Directora Ecutiva<br/> Fundación Ideas para la Infancia</p>
                            </div>
                            <div style="position: relative;width: 89px;height: 89px; top: 35px; font: 10px/1.4 \'Raleway\', serif;letter-spacing: 0;color: #BC2871;display: flex;align-items: center;justify-content: center;">
                                <img src="clock.png" class="printSpecial" style="width: 40px;position: absolute;top: -50px;bottom: 0;margin-top: auto;margin-bottom: auto;" />
                                <div id="curved2">DURACIÓN '.$hours.' HORAS CRONOLOGICAS · </div>
                            </div>
                            <div style="text-align:center;">
                                <img src="firma-b.png" style="height:100px;" />
                                <div style="width: 220px;height: 1.5px;background-color: #BC2871;position: relative;top: -24px;"></div>
                                <p style="font-size:11px; font-family: \'Montserrat\', sans-serif; color: #BC2871; font-weight: bold;  margin: 0px; margin-top: -17px;">Magdalena Muñoz Quinteros</p>
                                <p style="font-size:7px; font-family: \'Montserrat\', sans-serif; color: #2C68A5;">Directora de Innovación y Estudios<br/> Fundación Ideas para la Infancia</p>
                            </div>
                        </div>
                    </div>
                </div>
                <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
                <script src="https://cdnjs.cloudflare.com/ajax/libs/lettering.js/0.6.1/jquery.lettering.min.js"></script>
            </body>
            </html>
            
            ';
            
            echo $html;
                
            $texto_minusculas = strtolower($res["nombre"]);
            $texto_formateado = str_replace(' ', '-', $texto_minusculas);
    
            date_default_timezone_set('America/Santiago');
            $date = date('d-m-Y');
    
            echo '<div style="padding: 20px;margin-bottom: 40px;border-bottom: 2px dashed #5c5757;"><a href="#" style="padding: 12px 26px;font-size: 17px;background: #00b59e;border-radius: 45px;display: inline-block;color: #FFFFFF;text-decoration: none;font-family: monospace;" onclick="generarPDF(\'pdf_' . $i . '\', \'' . $texto_formateado . '\', \'' . $date . '\')">Descargar PDF de '.$res["nombre"].'</a></div>';
    
            ?>

            <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.8.0/html2pdf.bundle.min.js"></script>
            <script src="index.js"></script>

            <script>
            function generarPDF(id, nombre, fecha) {
            var element = document.getElementById(id);
            
            var opciones = {
                margin: 10,
                filename: `${nombre}-${fecha}.pdf`,
                image: { type: 'jpeg', quality: 0.98 },
                html2canvas: { scale: 3 },
                jsPDF: { unit: 'mm', format: 'a4', orientation: 'landscape' } // Aquí se establece la orientación
            };
            
            html2pdf(element, opciones);
            }

            document.getElementById("back").addEventListener("click", () => {
                history.back()
            });
            </script>

            <?php
        }

    } else {
        echo "No se recibieron items.";
    }
} else {
    echo "Método de solicitud no soportado.";
}
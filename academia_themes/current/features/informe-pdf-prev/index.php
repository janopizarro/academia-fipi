<?php 

if($_GET){

    $name = $texto_formateado = ucwords(strtolower($_GET["nombre"]));;
    $nameCourse = $_GET["nombre_curso"];
    $type = $_GET["tipo"];
    $hours = $_GET["horas"];
    
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
        <div id="pdf" style="position:relative; max-width: 1100px; height: 600px; border-top: 25px solid #2C68A5; border-bottom: 25px solid #2C68A5;">
            <div style="width: 15px;display: flex;justify-content: space-between;margin: 30px; margin-top: 0px; margin-bottom: 0px;">
                <div style="width:1.5px; height: 600px; background-color: #FAE14D"></div>
                <div style="width:1.5px; height: 600px; background-color: #BC2871"></div>
            </div>
            <div style="height: 15px;width: 100%;justify-content: space-between;margin: 30px;margin-top: 0px;margin-bottom: 0px;top: 29px;position: absolute;left: -29px;">
                <div style="width:100%; height: 1.5px; background-color: #FAE14D; margin-bottom: 14px;"></div>
                <div style="width:100%; height: 1.5px; background-color: #BC2871"></div>
                <div style="width: auto; padding-right: 35px;height: auto;position: absolute;right: 30px;top: -21px;display: flex;">
                    <img src="ideas-para-la-infancia.jpg" style="height:70px;" />
                    <img src="academia-fipi.jpg" style="height:70px;" />
                </div>
            </div>
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
    
    echo "<p id='status'>GENERANDO... ESPERE POR FAVOR...</p>";
    echo "<button id='back' style='display:none'>REGRESAR</button>";

    ?>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.8.0/html2pdf.bundle.min.js"></script>
    <script src="index.js"></script>

    <?php 
    date_default_timezone_set('America/Santiago');
    $date = date('d-m-Y');

    $texto_minusculas = strtolower($name);
    $texto_formateado = str_replace(' ', '-', $texto_minusculas);
    ?>
        
    <script>
    function generarPDF() {
      var element = document.getElementById('pdf');
      
      var opciones = {
        margin: 10,
        filename: '<?php echo $texto_formateado."-".$date; ?>.pdf',
        image: { type: 'jpeg', quality: 0.98 },
        html2canvas: { scale: 3 },
        jsPDF: { unit: 'mm', format: 'a4', orientation: 'landscape' } // Aquí se establece la orientación
      };
    
      html2pdf(element, opciones);
    }

    setTimeout(() => {
        generarPDF();  
        document.getElementById("status").innerText = "PDF GENERADO, PRESIONA EL BOTÓN PARA REGRESAR";
        document.getElementById("back").style.display = "block";
    }, 1500);

    document.getElementById("back").addEventListener("click", () => {
        history.back()
    });
    </script>

<?php } ?>

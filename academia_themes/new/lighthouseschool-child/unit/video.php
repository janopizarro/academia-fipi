<?php 
// load wordpress
include('../../../../wp-load.php');

// error_reporting(E_ALL);
// error_reporting(-1);
// ini_set('error_reporting', E_ALL);

// post send
if($_POST){

    $id_user = $_POST["id_user"];
    $id_course = $_POST["id_curso"];
    $nextUnit = $_POST["nextUnit"];
    $id_unidad = $_POST["id_unidad"];

    // obtener todas las unidades
    $unidades = getUnitsCourse($id_course);

    if($nextUnit === ""){
    
        if(get_post_meta( $id_course, 'curso_intro_video_iframe', true )){

            preg_match('/src="([^"]+)"/', get_post_meta( $id_unidad, 'curso_intro_video_iframe', true), $match);
            $url = $match[1];
        
            $js = "    
                <script>
                let videoPlayer = document.getElementById('videoPlayer');
                let url_string = '".$url."';
                let adsURL = url_string+'&api=1&player_id=video&title=0&amp;byline=0&amp;portrait=0&amp;color=c9ff23;autoplay=1';
                videoPlayer.src = adsURL;
                </script>
            ";
        
            // $id_video = str_replace("https://vimeo.com/","",get_post_meta( $id_unidad, 'curso_intro_video', true )); 						
            // $iframe = '<iframe src="https://player.vimeo.com/video/'.$id_video.'" width="640" height="360" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe>';

            echo json_encode(array("video" => $js));
    
        } else {
    
            echo json_encode(array("error" => "houston"));
    
        }

    } else {
    
        if(get_post_meta( $nextUnit, 'unidad_resena_video_iframe', true )){

            // $id_video = str_replace("https://vimeo.com/","",get_post_meta( $nextUnit, 'unidad_resena_video', true )); 						
            // $iframe = '<iframe src="https://player.vimeo.com/video/'.$id_video.'?api=1&player_id=video&title=0&amp;byline=0&amp;portrait=0&amp;color=c9ff23;autoplay=1" width="640" height="360" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe>';

            preg_match('/src="([^"]+)"/', get_post_meta( $nextUnit, 'unidad_resena_video_iframe', true), $match);
            $url = $match[1];
        
            $js = "    
                <script>
                let videoPlayer = document.getElementById('videoPlayer');
                let url_string = '".$url."';
                let adsURL = url_string+'&api=1&player_id=video&title=0&amp;byline=0&amp;portrait=0&amp;color=c9ff23;autoplay=1';
                videoPlayer.src = adsURL;
                </script>
            ";

            echo json_encode(array("video" => $js));
    
        } else {
    
            echo json_encode(array("error" => "houston"));
    
        }

    }

}


?>
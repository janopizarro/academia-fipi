<?php 
// load wordpress
include('../../../../../wp-load.php');

// error_reporting(E_ALL);
// error_reporting(-1);
// ini_set('error_reporting', E_ALL);

// post send
if($_POST){

    $userId = $_POST["userId"];
    $courseId = $_POST["courseId"];
    $nextUnit = $_POST["nextUnit"];

    if($nextUnit == 5){
        $urlVideo = str_replace("https://vimeo.com/","",get_post_meta( $courseId, 'curso_intro_video', true )); 						

        $iframe = '<iframe src="https://player.vimeo.com/video/'.$urlVideo.'" width="640" height="360" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe>';
    
        if(get_post_meta( $courseId, 'curso_intro_video', true )){
    
            echo json_encode(array("video" => $iframe));
    
        } else {
    
            echo json_encode(array("error" => "houston"));
    
        }

    } else {

        $urlVideo = str_replace("https://vimeo.com/","",get_post_meta( $courseId, 'curso_resena_0'.$nextUnit.'_video', true )); 
        
        $cleanUrl = explode("/", $urlVideo);

        // $iframe = '<iframe src="https://player.vimeo.com/video/'.$cleanUrl[0].'" width="640" height="360" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe>';
        $iframe = '<iframe src="https://player.vimeo.com/video/'.$urlVideo.'" width="640" height="360" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe>';
    
        if(get_post_meta( $courseId, 'curso_resena_0'.$nextUnit.'_video', true )){
    
            echo json_encode(array("video" => $iframe));
    
        } else {
    
            echo json_encode(array("error" => "houston"));
    
        }

    }



}


?>
<?php
$id_alumno = getDataSession("id");
?>
		</div>

	</div>
	<!-- Content / End -->


</div>
<!-- Dashboard / End -->

<footer>
	<div class="wrappfooter">
		<div class="container">
			<div class="columnas">
				<div class="col">
					<img src="<?php echo get_template_directory_uri(); ?>/images/fipi-footer.svg" width="150" alt="">
					<a href="mailto:asistencia@ideasparalainfancia.com" class="enlace">
						asistencia@ideasparalainfancia.com
					</a>
				</div>
				<div class="col">
					<nav>
						<li>
							<a href="#">
								<img src="<?php echo get_template_directory_uri(); ?>/images/face_inst.svg" alt="">
							</a>
							<strong>Ideasparalainfancia</strong>
						</li>
						<li>
							<a href="#">
								<img src="<?php echo get_template_directory_uri(); ?>/images/twitter.svg" alt="">
							</a>
							<strong>IdeasInfancia</strong>
						</li>
					</nav>
				</div>
			</div>
		</div>
	</div>
</footer>


</div>
<!-- Wrapper / End -->


<!-- Scripts
================================================== -->
<script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
<!--<script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/scripts/jquery-3.4.1.min.js"></script>-->
<script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/scripts/jquery-migrate-3.1.0.min.js"></script>
<script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/scripts/mmenu.min.js"></script>
<script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/scripts/chosen.min.js"></script>
<script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/scripts/slick.min.js"></script>
<script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/scripts/rangeslider.min.js"></script>
<script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/scripts/magnific-popup.min.js"></script>
<script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/scripts/waypoints.min.js"></script>
<script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/scripts/counterup.min.js"></script>
<script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/scripts/jquery-ui.min.js"></script>
<script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/scripts/tooltips.min.js"></script>
<script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/scripts/custom.js"></script>
<script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/slick/slick.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

<script>
$('.slide').slick({
	arrows: true,
	infinite: true,
	slidesToShow: 1,
	slidesToScroll: 1,
	dots: true,
	autoplay: true,
	autoplaySpeed: 7000,
});
</script>

<?php

global $wp_query;
$post_id = $wp_query->post->ID;

if($id_alumno && $post_id === 1){
	// se verifica si se dispone del id usuario y estamos en la pagina de inicio
	
	if(verificarTutorial($id_alumno, 1)){

		$modal = '<style>.modal { z-index: 2000 } .modal iframe { height: 395px !important; } .modal .modal-dialog { max-width: 680px; }</style>';
		
		$modal .= '

			<script>
			jQuery(document).ready(function($) {
				$("#modalTutorial").modal("show");

				$("#modalTutorial").on("hidden.bs.modal", function () {
					var $this = $(this);
					var vidsrc_frame = $this.find("iframe");
					vidsrc_frame.attr("src", "");
				});
			});
			</script>

			<!-- Modal -->
			<div class="modal fade" id="modalTutorial" tabindex="-1" role="dialog"
				aria-labelledby="modalTutorialLabel" aria-hidden="true">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="modalTutorialLabel" style=" font-size: 20px;">Bienvenid@ a Academia FIPI</h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<div class="modal-body">
							<p>A continuación dejamos un tutorial de como funciona la plataforma, si la finalizar el tutorial mantienes dudas, nos puedes contactar.</p>
							<iframe title="vimeo-player" src="https://player.vimeo.com/video/708841575?h=376305450f&autoplay=1&loop=1&autopause=0" width="640" height="334" frameborder="0" allow="autoplay" allowfullscreen></iframe>							
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-secondary" data-dismiss="modal"
								style="padding: 3px 10px; font-size: 17px;">Cerrar</button>
						</div>
					</div>
				</div>
			</div>	
			<!-- End Modal -->
		
		';

		echo $modal;

	}
}
?>

</body>
</html>
<?php 
session_start();
verificarSesion();

get_header();
?>

<!-- Dashboard -->
<div id="dashboard">

	<!-- Navigation
	================================================== -->

	<!-- Responsive Navigation Trigger -->
	<a href="#" class="dashboard-responsive-nav-trigger"><i class="fa fa-reorder"></i> Dashboard Navigation</a>

	<div class="dashboard-nav">
		<div class="dashboard-nav-inner">

            <ul data-submenu-title="FIPI">
				<li class="active"><a href="<?php echo home_url(); ?>/cursos-presenciales"><i class="sl sl-icon-user"></i> Cursos Presenciales</a></li>
				<li><a href="<?php echo home_url(); ?>/cursos-online"><i class="sl sl-icon-cursor"></i> Cursos Online</a></li>
				<!-- <li><a href="dashboard-bookings.html"><i class="fa fa-calendar-check-o"></i> Bookings</a></li>
				<li><a href="dashboard-wallet.html"><i class="sl sl-icon-wallet"></i> Wallet</a></li> -->
			</ul>
			
			<!-- <ul data-submenu-title="Listings">
				<li><a><i class="sl sl-icon-layers"></i> My Listings</a>
					<ul>
						<li><a href="dashboard-my-listings.html">Active <span class="nav-tag green">6</span></a></li>
						<li><a href="dashboard-my-listings.html">Pending <span class="nav-tag yellow">1</span></a></li>
						<li><a href="dashboard-my-listings.html">Expired <span class="nav-tag red">2</span></a></li>
					</ul>	
				</li>
				<li><a href="dashboard-reviews.html"><i class="sl sl-icon-star"></i> Reviews</a></li>
				<li><a href="dashboard-bookmarks.html"><i class="sl sl-icon-heart"></i> Bookmarks</a></li>
				<li><a href="dashboard-add-listing.html"><i class="sl sl-icon-plus"></i> Add Listing</a></li>
			</ul>	

			<ul data-submenu-title="Account">
				<li><a href="dashboard-my-profile.html"><i class="sl sl-icon-user"></i> My Profile</a></li>
				<li><a href="index.html"><i class="sl sl-icon-power"></i> Logout</a></li>
			</ul> -->
			
		</div>
	</div>
	<!-- Navigation / End -->


	<!-- Content
	================================================== -->
	<div class="dashboard-content">

		<!-- Titlebar -->
		<div id="titlebar">
			<div class="row">
				<div class="col-md-12">
					<h2>Hola, <?php echo getDataSession("nombre"); ?>!</h2>
				</div>
			</div>
		</div>

		<div class="row">
			
			<div class="col-lg-6 col-md-12">
				<div class="dashboard-list-box with-icons margin-top-20">
					<h4 style="font-size: 26px;">Curso demo</h4>

					<img src="https://images.pexels.com/photos/4145153/pexels-photo-4145153.jpeg?auto=compress&cs=tinysrgb&dpr=2&h=750&w=1260" alt="">

				</div>

				<!-- reseñas --> 
				<div class="dashboard-list-box invoices margin-top-20">
					<h4>Reseñas del curso <i class="im im-icon-File-HorizontalText" style="position: relative; top: 3px;"></i></h4>
					<ul>
						
						<li>
							<strong>Etapa 01</strong>
							<p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Eum molestiae amet necessitatibus, quo quos illum laudantium magni modi fuga? Consequuntur repellat dolores obcaecati perspiciatis esse corrupti unde porro assumenda dolorum!</p>
						</li>
						
						<li>
							<strong>Etapa 02</strong>
							<p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Eum molestiae amet necessitatibus, quo quos illum laudantium magni modi fuga? Consequuntur repellat dolores obcaecati perspiciatis esse corrupti unde porro assumenda dolorum!</p>
						</li>

						<li>
							<strong>Etapa 03</strong>
							<p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Eum molestiae amet necessitatibus, quo quos illum laudantium magni modi fuga? Consequuntur repellat dolores obcaecati perspiciatis esse corrupti unde porro assumenda dolorum!</p>
						</li>

						<li>
							<strong>Etapa 04</strong>
							<p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Eum molestiae amet necessitatibus, quo quos illum laudantium magni modi fuga? Consequuntur repellat dolores obcaecati perspiciatis esse corrupti unde porro assumenda dolorum!</p>
						</li>

					</ul>
				</div>
				<!-- end reseñas --> 

			</div>
			<div class="col-lg-6 col-md-12">
				<div class="dashboard-list-box invoices with-icons margin-top-20">
					<h4>Avance curso <i class="sl sl-icon-check" style="position: relative; top: 3px;"></i></h4>
					<ul>
						
						<li><i class="list-box-icon fa fa-video-camera"></i>
							<strong>Etapa 01</strong>
							<ul>
								<li>Preguntas: 6</li>
							</ul>
							<div class="buttons-to-right">
								<a href="dashboard-invoice.html" class="button gray completado-href">COMPLETADO</a>
							</div>
						</li>
						
						<li><i class="list-box-icon fa fa-video-camera"></i>
							<strong>Etapa 02</strong>

							<ul class="pregunta">
								<li><span>PREGUNTA</span></li>
								<ul>
									<li><input type="radio"><span>ALTERNATIVA</span></li>
									<li><input type="radio"><span>ALTERNATIVA</span></li>
									<li><input type="radio"><span>ALTERNATIVA</span></li>
									<li><input type="radio"><span>ALTERNATIVA</span></li>
								</ul>
							</ul>

							<ul class="pregunta">
								<li><span>PREGUNTA</span></li>
								<ul>
									<li><input type="radio"><span>ALTERNATIVA</span></li>
									<li><input type="radio"><span>ALTERNATIVA</span></li>
									<li><input type="radio"><span>ALTERNATIVA</span></li>
								</ul>
							</ul>

							<ul class="pregunta">
								<li><span>PREGUNTA</span></li>
								<ul>
									<li><input type="radio"><span>ALTERNATIVA</span></li>
									<li><input type="radio"><span>ALTERNATIVA</span></li>
								</ul>
							</ul>

							<a href="#" class="button">Finalizar</a>

						</li>

						<li class="course-disabled"><i class="list-box-icon fa fa-video-camera"></i>
							<strong>Etapa 03</strong>
							<ul>
								<li>Preguntas: 8</li>
							</ul>
							<div class="buttons-to-right">
								<a href="dashboard-invoice.html" class="button gray">REALIZAR</a>
							</div>
						</li>

						<li class="course-disabled"><i class="list-box-icon fa fa-video-camera"></i>
							<strong>Etapa 04</strong>
							<ul>
								<li>Preguntas: 12</li>
							</ul>
							<div class="buttons-to-right">
								<a href="dashboard-invoice.html" class="button gray">REALIZAR</a>
							</div>
						</li>

					</ul>
				</div>
			</div> 


<?php 
get_footer();
?>
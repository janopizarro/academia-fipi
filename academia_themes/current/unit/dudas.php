<!-- Form -->
<div class="col-lg-6 col-md-12">
				<div class="dashboard-list-box margin-top-0">
					<h4 class="gray">Formulario de contacto</h4>
					<div class="dashboard-list-box-static">

						<form action="" id="form">
						
							<div class="my-profile">
								<label class="margin-top-0">Nombre</label>
								<input type="text" name="nombre" id="nombre" required><br/>

								<label class="margin-top-0">Email</label>
								<input type="email" name="email" id="email" required><br/>

								<label class="margin-top-0">Teléfono (opcional)</label>
								<input type="tel" name="telefono" id="telefono"><br/>

								<label class="margin-top-0">Curso</label>
								<select name="curso" id="curso" required>
									<option value="">Selecciona</option>
									<?php
									$args = array('numberposts' => -1, 'post_type' => 'curso');
									
									if(count(get_posts($args)) > 0){
										foreach(get_posts($args) as $res){
											echo "<option value='".$res->post_title."'>".$res->post_title."</option>";
										}
									}
									?>
								</select><br/>

								<label class="margin-top-0">Duda/Comentario</label>
								<textarea id="duda_comentario" name="duda_comentario" placeholder="Dejanos acá tus dudas o comentarios.."></textarea>

								<div class="status_message">...</div>

								<button class="button margin-top-15" type="button" id="enviar">Enviar Formulario</button>
							</div>
						
						</form>
					
					</div>
				</div>
			</div>

			<style>
			.status_message p{padding: 7px;font-size: 13px;border-radius: 5px;}
			.warning{background: #d4c516;color: #6f4800;}
			.error{background: #d41616;color: #ffd0d0;}
			.info{background: #45c8e6;color: #0f5469;}
			.success{background: #a1bf42;color: #2d4a00;}
			</style>

			<script>
			function ValidateEmail(email){
				if (/^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/.test(email)){
					return (true)
				} else {
					return (false)
				}
			}

			document.getElementById("enviar").addEventListener("click", function(){

				let nombre = document.getElementById("nombre");
				let email = document.getElementById("email");
				let curso = document.getElementById("curso");
				let duda_comentario = document.getElementById("duda_comentario");

				if(nombre.value === ""){
					alert("Por favor completa el campo nombre para continuar");
					return false;
				}

				if(email.value === ""){
					alert("Por favor completa el campo email para continuar");
					return false;
				}

				if(!ValidateEmail(email.value)){
					alert("Por favor ingresa un email válido para continuar");
					return false;
				}

				if(curso.value === ""){
					alert("Por favor selecciona un curso para continuar");
					return false;
				}

				if(duda_comentario.value === ""){
					alert("Por favor ingresa una duda/comentario para continuar");
					return false;
				}

				const url = '<?php echo get_template_directory_uri(); ?>';

				const data = new FormData(document.getElementById('form'));
				fetch(url+'/page-contacto--ajax.php', {
					method: 'POST',
					body: data
				})
				.then(function(response) {
					if(response.ok) {
						return response.text()
					} else {
						document.querySelector(".status_message").innerHTML = "<p class='error'>Problema en la llamada ajax!</p>";
					}
				})
				.then(function(res) {

					respuesta = JSON.parse(res);

					let status = respuesta.status;
					let message = respuesta.message;
					let class_message = respuesta.class_message;

					document.querySelector(".status_message").innerHTML = "<p class='"+class_message+"'>"+message+"</p>";

				})
				.catch(function(err) {
					console.log(err);
				});

			});
			</script>
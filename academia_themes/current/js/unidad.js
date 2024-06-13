const finalizarUnidad = document.querySelectorAll(".finalizar_unidad");
let statusUnit = new Object();
let statusUnitQuestions = new Object();

let idUser = document.querySelector(".idUser").value;
let idCurso = document.querySelector(".idCurso").value;
let template = document.querySelector(".template").value;

for (const finalizarBtn of finalizarUnidad) {
  // finalizarBtn.style.opacity = "0.3";
  // finalizarBtn.disabled = true;
  // finalizarBtn.style.pointerEvents = "none";

  // se verifica si el video terminó para poder finalizar la unidad
  let iframe = document.getElementById("videoPlayer");
  let player = new Vimeo.Player(iframe);

  player.on("ended", function () {
    finalizarBtn.style.opacity = "1";
    finalizarBtn.disabled = false;
    finalizarBtn.style.pointerEvents = "auto";
    player.element.src = player.element.src;

    setTimeout(function () {
      player.pause();
    }, 400);
  });

  finalizarBtn.addEventListener("click", function (event) {
    // se verifica si se seleccionó al menos una de las alternativas
    let cant = this.getAttribute("data-cant");
    let id = this.getAttribute("data-id");

    // se verifica el estado de las respuestas minimas
    for (i = 0; i < cant; i++) {
      let c = i + 1;
      let grupo_unidad = document.querySelectorAll(
        ".grupo_unidad_" + id + "_" + c + ":checked"
      );
      let helperGroup = document.querySelector(
        ".helper_grupo_unidad_" + id + "_" + c + ""
      );
      let cantMinima = document.querySelector(
        ".cantMinima_unidad_" + id + "_" + c + ""
      ).value;

      if (grupo_unidad.length < cantMinima) {
        helperGroup.style.display = "inline-block";
        helperGroup.textContent = `Debes seleccionar al menos ${cantMinima} alternativa(s) para continuar`;
        // statusUnit = false;
        statusUnit["pregunta_" + c] = false;
      } else {
        helperGroup.style.display = "none";
        helperGroup.textContent = ``;
        // statusUnit = true;
        statusUnit["pregunta_" + c] = true;
      }
    }

    // se verifica el estado resultante de las respuestas minimas
    if (verificarEstado(statusUnit)) {
      // se genera el FormData con la info del formulario
      const data = new FormData(document.getElementById("form_etp_0" + id));

      // se adiciona data del curso y usuario
      data.append("id_user", idUser);
      data.append("id_curso", idCurso);
      data.append("unidad", id);
      data.append("cant", cant);

      let file = template + "/includes/unidades/unidad--verify.php";

      fetch(file, {
        method: "POST",
        body: data,
      })
        .then(function (response) {
          if (response.ok) {
            return response.text();
          } else {
            throw "Error en la llamada Ajax";
          }
        })
        .then(function (res) {
          let json = JSON.parse(res);

          // console.log(json);

          json.map(function (pregunta) {
            if (pregunta.estado === "incorrecta") {
              document
                .querySelector("." + pregunta.class_question)
                .classList.add("warning_question");
            }

            if (pregunta.estado === "correcta") {
              document
                .querySelector("." + pregunta.class_question)
                .classList.add("ok_question");

              // disabled all radio buttons
              disabledCheckbox(pregunta.class_question);
            }

            if (
              document
                .querySelector("." + pregunta.class_question)
                .classList.contains("warning_question") &&
              pregunta.estado === "incorrecta" &&
              pregunta.intento === "intentos-agotados"
            ) {
              document
                .querySelector("." + pregunta.class_question)
                .classList.remove("warning_question");
              document
                .querySelector("." + pregunta.class_question)
                .classList.add("error_question");

              // disabled all radio buttons
              disabledCheckbox(pregunta.class_question);
            }

            if (
              document
                .querySelector("." + pregunta.class_question)
                .classList.contains("warning_question") &&
              pregunta.estado === "correcta"
            ) {
              document
                .querySelector("." + pregunta.class_question)
                .classList.remove("warning_question");
              document
                .querySelector("." + pregunta.class_question)
                .classList.add("ok_question");

              // disabled all radio buttons
              disabledCheckbox(pregunta.class_question);
            }

            // show message!
            if (pregunta.message != "") {
              document
                .querySelector("." + pregunta.class_question)
                .getElementsByClassName("helper_grupo")[0].style.display =
                "inline-block";
              document
                .querySelector("." + pregunta.class_question)
                .getElementsByClassName("helper_grupo")[0].textContent =
                pregunta.message;
            }

            // verificar si se puede avanzar a la siguiente unidad
            if (
              (pregunta.estado === "incorrecta" &&
                pregunta.intento === "intentos-agotados") ||
              (pregunta.estado === "correcta" && pregunta.intento == "1") ||
              (pregunta.estado === "correcta" && pregunta.intento == "2") ||
              (pregunta.estado === "correcta" &&
                pregunta.intento == "intentos-agotados")
            ) {
              statusUnitQuestions[
                "pregunta_" + pregunta.question_number
              ] = true;
            } else {
              statusUnitQuestions[
                "pregunta_" + pregunta.question_number
              ] = false;
            }
          });

          // console.log(statusUnitQuestions);

          if (verificarEstado(statusUnitQuestions)) {
            // se esperan 3 segundos para continuar
            document.getElementById("load_unit_0" + id).style.display = "block";

            setTimeout(function () {
              document.querySelector(".finalizar_unidad_" + id).remove();

              document.getElementById("load_unit_0" + id).style.display =
                "none";
              // se elimina la clase 'show' de la unidad terminada
              document.getElementById("unidad_0" + id).classList.remove("show");

              // se elimina el disabled y se añade la clase 'show' a la siguiente unidad
              let nextUnit = parseInt(id) + 1;

              for (var item of document.querySelectorAll(".resena_")) {
                item.classList.remove("resena_activa");
                item.style.display = "none";
              }

              if (nextUnit < 5) {
                document
                  .getElementById("resena_0" + nextUnit)
                  .classList.add("resena_activa");
                document.querySelector(
                  "[data-target='#unidad_0" + nextUnit + "']"
                ).disabled = false;
                document
                  .getElementById("unidad_0" + nextUnit + "")
                  .classList.add("show");
              }

              const data = new FormData();
              data.append("userId", idUser);
              data.append("courseId", idCurso);
              data.append("unit", id);
              data.append("nextUnit", nextUnit);

              // actualizar estado de unidad en bbdd
              fetch(template + "/includes/unidades/unidad--status.php", {
                method: "POST",
                body: data,
              })
                .then(function (response_1) {
                  if (response_1.ok) {
                    return response_1.text();
                  } else {
                    throw "Error en la llamada Ajax";
                  }
                })
                .then((result_1) => {
                  /*let json = JSON.parse(result_1); console.log("res 1: "+json);*/
                })
                .catch(function (err) {
                  console.log("error unidad--status");
                });

              // actualizar porcentaje de estado
              fetch(template + "/includes/unidades/unidad--porcentaje.php", {
                method: "POST",
                body: data,
              })
                .then(function (response_2) {
                  if (response_2.ok) {
                    return response_2.text();
                  } else {
                    throw "Error en la llamada Ajax";
                  }
                })
                .then((result_2) => {
                  let json = JSON.parse(result_2);
                  document.getElementById("porcentajeVal").textContent =
                    json + "%";
                })
                .catch(function (err) {
                  console.log("error unidad--porcentaje");
                });

              // update video
              fetch(template + "/includes/unidades/unidad--video.php", {
                method: "POST",
                body: data,
              })
                .then(function (response_3) {
                  if (response_3.ok) {
                    return response_3.text();
                  } else {
                    throw "Error en la llamada Ajax";
                  }
                })
                .then((result_3) => {
                  let json = JSON.parse(result_3);
                  document.getElementById("loadVideo").innerHTML = json.video;
                })
                .catch(function (err) {
                  console.log("error unidad--video");
                });

              // finalizarBtn.style.opacity = "1";
              // finalizarBtn.disabled = false;

              // preguntar si el id siguiente es 5 para finalizar el curso y enviar correo electronico a admin
              if (nextUnit == 5) {
                document.getElementById("load_finish").style.display = "block";

                // show finish
                fetch(template + "/includes/unidades/unidad--f-course.php", {
                  method: "POST",
                  body: data,
                })
                  .then(function (response_4) {
                    if (response_4.ok) {
                      return response_4.text();
                    } else {
                      throw "Error en la llamada Ajax";
                    }
                  })
                  .then((result_4) => {
                    document.getElementById("f_course").innerHTML = result_4;
                    document.getElementById("load_finish").style.display =
                      "none";
                  })
                  .catch(function (err) {
                    console.log("error unidad--f-course");
                  });

                Swal.fire({
                  title: "",
                  text: "",
                  imageUrl: template + "/images/felicitaciones-02.png",
                  imageWidth: 700,
                  imageHeight: 200,
                  imageAlt: "Custom image",
                  confirmButtonText: "Genial",
                });

                setTimeout(function () {
                  location.reload();
                }, 3000);
              }
            }, 2000);
          }
        })
        .catch(function (err) {
          console.log(err);
        });
    }
  });
}

/*
 * Verificar estado de alternativa(s) seleccionada(s) minimas para avanzar con validación * */

function verificarEstado(object) {
  if (Object.keys(object).every((name) => object[name])) {
    return true;
  } else {
    return false;
  }
}

/*
 * disabled checkbox * */

function disabledCheckbox(clase) {
  let allElements = document
    .querySelector("." + clase)
    .nextElementSibling.getElementsByTagName("input");

  if (allElements) {
    for (var i = 0, l = allElements.length; i < l; ++i) {
      allElements[i].style.pointerEvents = "none";
      allElements[i].style.opacity = ".3";
    }
  }
}

// function sendFetch(file, userId, courseId, unit){

//     const data = new FormData();
//     data.append('userId', userId);
//     data.append('courseId', courseId);
//     data.append('unit', unit);
//     fetch(template+'/includes/unidades/'+file+'.php', { method: 'POST', body: data })
//     .then(function(response) { if(!response.ok) { throw "Error en la llamada Ajax"; } })
//     .then(function(res) { return res })
//     .catch(function(err) { return err; });

// }

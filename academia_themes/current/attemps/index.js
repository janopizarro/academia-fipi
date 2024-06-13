const attempsVideo = document.querySelectorAll(".attempVideo");
for (const attemp of attempsVideo) {
  attemp.addEventListener("click", function (event) {
    document.getElementById("attempLoading").style.display = "block";
    $("#attempVideo").html("");

    const ids = this.getAttribute("data-ids").split("-");
    const unit_id = ids[0];
    const course_id = ids[1];
    const user_id = ids[2];

    const type = this.getAttribute("data-type");

    const n_unidad = this.getAttribute("data-n-unidad");

    $("#modalVideoAttemp").modal("show");

    if (type === "1") {
      $("#attempUnit").text("0" + unit_id);
    } else {
      $("#attempUnit").text("0" + n_unidad);
    }

    const dataAttemp = new FormData();

    dataAttemp.append("unit_id", unit_id);
    dataAttemp.append("course_id", course_id);
    dataAttemp.append("user_id", user_id);
    dataAttemp.append("path_absolute", urlBase);

    dataAttemp.append("n_unidad", n_unidad);

    dataAttemp.append("type", type);

    function htmlDecode(input) {
      var e = document.createElement("div");
      e.innerHTML = input;
      return e.childNodes.length === 0 ? "" : e.childNodes[0].nodeValue;
    }

    // ajax
    fetch(urlBase + "/attemps/index.php", {
      method: "post",
      body: dataAttemp,
    })
      .then((response) => {
        if (response.status == 200) {
          return response.text();
        } else {
          throw "Respuesta incorrecta del servidor";
        }
      })
      .then((responseText) => {
        let response = JSON.parse(responseText);
        const errorAttemp = response["errorAttemp"];
        const video = htmlDecode(response["video"]);
        const n_intento_max = 6;
        const n_intento = response["n_intento"];
        const n_intento_remain = n_intento_max - n_intento;
        const n_intento_wait = 5 - n_intento;
        // const errorAttemp = htmlDecode(response['errorAttemp']);
        console.log(errorAttemp);
        if (n_intento_remain === 0 || n_intento_remain === -1) {
          $("#attempVideo").html(errorAttemp);
          $("#attempsRemain").text("");
        } else {
          $("#attempVideo").html(video);
          if (n_intento_wait === 0) {
            $("#attempsRemain").html(
              "<strong>Este es el último intento para visualizar el video de esta unidad.</strong>"
            );
          } else {
            $("#attempsRemain").html(
              "<strong>Te quedan <span color='green'>" +
                n_intento_wait +
                "</span> intentos para visualizar el video de esta unidad</strong>"
            );
          }
        }
        document.getElementById("attempLoading").style.display = "none";
      })
      .catch((err) => {
        console.log(err);
      });
    // end ajax
  });
}

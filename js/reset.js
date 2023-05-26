$(document).ready(function () {
  var img = new Image();
  img.src = "http://vacunas.panamasolidario.gob.pa/vacucheck/img/bg.jpg";

  var int = setInterval(function () {
    if (img.complete) {
      clearInterval(int);
      document.getElementsByTagName("body")[0].style.backgroundImage =
        "url(" + img.src + ")";
    }
  }, 50);

  $("#btn-check").click(() => {
    reset();
  });
});

const reset = async () => {
  let operationNumber = $("#operation-number").val();
  let email = $("#email").val();

  $("#operation-number").removeClass(
    "animate__animated animate__shakeX placeholder-red-200 border-red-300"
  );
  $("#email").removeClass(
    "animate__animated animate__shakeX placeholder-red-200 border-red-300"
  );

  if (!operationNumber) {
    Swal.fire({
      html:
        "<div style='text-align:left'><b>Ingrese el número de aviso de operación.</b></div>",
      icon: "error",
      confirmButtonText: "Aceptar",
      position: "top",
    });
    $("#operation-number").addClass(
      "animate__animated animate__shakeX placeholder-red-200 border-red-300"
    );
    return;
  }

  if (!validateEmail(email)) {
    Swal.fire({
      html:
        "<div style='text-align:left'><b>Ingrese una dirección de correo electrónico valida.</b></div>",
      icon: "error",
      confirmButtonText: "Aceptar",
      position: "top",
    });
    $("#email").addClass(
      "animate__animated animate__shakeX placeholder-red-200 border-red-300"
    );
    return;
  }

  await grecaptcha.ready(async () => {
    let siteKey = "6LeiVcYaAAAAAJdD1cpDx2jP8PSGokBgnG9hKDob";
    let action = "resetPassword";
    grecaptcha.execute(siteKey, { action: action }).then(function (token) {
      $("#_token").val(token);

      let fileCheck = "checkUserForResetPass.php";
      let operationNumber = $("#operation-number").val();
      let email = $("#email").val();
      let _token = $("#_token").val();

      $("#loader").removeClass("hidden");
      $("#lbl-reset").addClass("hidden");

      $.ajax({
        url: `./${fileCheck}`,
        method: "POST",
        data: {
          operationNumber,
          email,
          _token,
          action,
        },
        success: (res) => {
          let jsonObj = res;

          finishLoad();

          if (jsonObj[0] == "DOES_NOT_EXISTS") {
            let message = `El aviso de operación o email no se encuentran registrados.`;
            Swal.fire({
              html: message,
              icon: "warning",
              confirmButtonText: "Aceptar",
              position: "top",
            });
          } else if (jsonObj[0] == "EMAIL-SENT") {
            let message = `Se ha enviado un email a la dirección: <b>${email}</b>, por favor siga los pasos descritos en el email para poder restablecer su contraseña.`;
            Swal.fire({
              html: message,
              icon: "success",
              confirmButtonText: "Aceptar",
              position: "top",
            });
            setTimeout(function () {
              window.location.reload();
            }, 9000);
          } else if (jsonObj[0] == "EMAIL-NOT-SENT") {
            let message = `Ha ocurrido un error al momento de enviar el correo de confirmación. <br />Intente nuevamente.`;
            Swal.fire({
              html: message,
              icon: "info",
              confirmButtonText: "Aceptar",
              position: "top",
            });
          }
        },
        complete: () => {
          finishLoad();
        },
        error: () => {
          finishLoad();
        },
      });
    });
  });
};

const validateEmail = (mail) => {
  if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(mail)) {
    return true;
  }
  return false;
};

const finishLoad = () => {
  $("#loader").addClass("hidden");
  $("#lbl-reset").removeClass("hidden");
};

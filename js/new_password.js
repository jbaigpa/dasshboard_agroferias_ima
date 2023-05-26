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
  let password = $("#password").val();
  let repeatPassword = $("#repeat-password").val();

  if (password != repeatPassword) {
    Swal.fire({
      html:
        "<div style='text-align:left'><b>Las contraseñas no coinciden.</b></div>",
      icon: "error",
      confirmButtonText: "Aceptar",
      position: "top",
    });
    return;
  }

  if (password.length < 8) {
    Swal.fire({
      html:
        "<div style='text-align:left'><b>La contraseña debe poseer un mínimo de 8 caracteres.</b></div>",
      icon: "error",
      confirmButtonText: "Aceptar",
      position: "top",
    });
    return;
  }

  if (!password.match(/[A-Z]/)) {
    Swal.fire({
      html:
        "<div style='text-align:left'><b>La contraseña debe poseer mínimo una letra en mayúscula.</b></div>",
      icon: "error",
      confirmButtonText: "Aceptar",
      position: "top",
    });
    return;
  }

  if (!password.match(/\d/)) {
    Swal.fire({
      html:
        "<div style='text-align:left'><b>La contraseña debe poseer mínimo un número.</b></div>",
      icon: "error",
      confirmButtonText: "Aceptar",
      position: "top",
    });
    return;
  }

  await grecaptcha.ready(async () => {
    let siteKey = "6LeiVcYaAAAAAJdD1cpDx2jP8PSGokBgnG9hKDob";
    let action = "newPassword";

    grecaptcha.execute(siteKey, { action: action }).then(function (token) {
      $("#loader").removeClass("hidden");
      $("#lbl-reset").addClass("hidden");

      $("#_token").val(token);
      let _token = $("#_token").val();

      let fileCheck = "updatePassword.php";

      $.ajax({
        type: "post",
        url: `./${fileCheck}`,
        data: {
          _token,
          action,
          password,
        },
        success: (res) => {
          let jsonObj = res;

          if (jsonObj[0] == "TIME_EXPIRED") {
            window.location.href =
              "https://vacunas.panamasolidario.gob.pa/vacucheck";
            return;
          } else if (jsonObj[0] == "UPDATED") {
            let message = `Contraseña actualizada.`;
            Swal.fire({
              html: message,
              icon: "success",
              confirmButtonText: "Aceptar",
              position: "top",
            });
            setTimeout(function () {
              window.location.href =
              "https://vacunas.panamasolidario.gob.pa/vacucheck";
            return;
            }, 3000);
          } else if (jsonObj[0] == "ERROR") {
            let message = `Ha ocurrido un error al momento de actualizar la contraseña.`;
            Swal.fire({
              html: message,
              icon: "error",
              confirmButtonText: "Aceptar",
              position: "top",
            });
            setTimeout(function () {
              window.location.reload();
            }, 3000);
          }

          finishLoad();
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

const finishLoad = () => {
  $("#loader").addClass("hidden");
  $("#lbl-reset").removeClass("hidden");
};

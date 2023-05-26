$(document).ready(function () {
  $("#telefono").keydown(function () {
    let telefono = $("#telefono").val();

    if (!telefono.startsWith("6")) {
      $("#telefono").mask("000-0000", {
        placeholder: "###-####",
        selectOnFocus: true,
      });
    } else {
      $("#telefono").mask("0000-0000", {
        placeholder: "####-####",
        selectOnFocus: true,
      });
    }
  });
  $("#btn-edit-user").click((e) => {
    e.preventDefault();
    editUser();
  });
});

const editUser = async () => {
  let nombre = $("#nombrefull").val();
  let password = $("#password").val();
  let confirmarPassword = $("#password-2").val();
  let fuente = $("#fuente").val();
  let telefono = $("#telefono").val();
  let provincia = $("#provincia").val();
  let email = $("#email").val();
  let cedulaResponsable = $("#cedulaResponsable").val();
  let nivel = $("#nivel").val();
  let appsPanavacNormal = $("#apps-panavac-normal").prop("checked") ? 1 : 0;
  let appsPanavacXt = $("#apps-panavac-xt").prop("checked") ? 1 : 0;
  let appsVacucheck = $("#apps-vacucheck").prop("checked") ? 1 : 0;

  let id = $("#id").val();

  if (!nombre) {
    Swal.fire({
      html: "Ingrese el nombre completo.",
      icon: "error",
      confirmButtonText: "Aceptar",
      position: "top",
    });
    return;
  }

  if (password) {
    if (!confirmarPassword) {
      Swal.fire({
        html: "Confirme el password.",
        icon: "error",
        confirmButtonText: "Aceptar",
        position: "top",
      });
      return;
    }

    if (password != confirmarPassword) {
      Swal.fire({
        html: "Las contraseñas no coinciden.",
        icon: "error",
        confirmButtonText: "Aceptar",
        position: "top",
      });
      return;
    }

    if (!password.match(/[A-Z]/)) {
      Swal.fire({
        html: "La contraseña debe poseer mínimo una letra en mayúscula.",
        icon: "error",
        confirmButtonText: "Aceptar",
        position: "top",
      });
      return;
    }

    if (!password.match(/\d/)) {
      Swal.fire({
        html: "La contraseña debe poseer mínimo un número.",
        icon: "error",
        confirmButtonText: "Aceptar",
        position: "top",
      });
      return;
    }
  }

  if (!fuente) {
    Swal.fire({
      html: "Ingrese la fuente.",
      icon: "error",
      confirmButtonText: "Aceptar",
      position: "top",
    });
    return;
  }
  if (!telefono) {
    Swal.fire({
      html: "Ingrese el teléfono.",
      icon: "error",
      confirmButtonText: "Aceptar",
      position: "top",
    });
    return;
  }
  if (!provincia) {
    Swal.fire({
      html: "Seleccione la provincia.",
      icon: "error",
      confirmButtonText: "Aceptar",
      position: "top",
    });
    return;
  }

  if (!validateCedula(cedulaResponsable).isValid) {
    Swal.fire({
      html: "La cédula del responsable posee un formato invalido",
      icon: "error",
      confirmButtonText: "Aceptar",
      position: "top",
    });
    return;
  }

  if (!nivel) {
    Swal.fire({
      html: "Seleccione el nivel del usuario.",
      icon: "error",
      confirmButtonText: "Aceptar",
      position: "top",
    });
    return;
  }
  
  let siteKey = "6LeiVcYaAAAAAJdD1cpDx2jP8PSGokBgnG9hKDob";
  let action = "editUser";

  grecaptcha.execute(siteKey, { action: action }).then(function (_token) {
    $.ajax({
      type: "post",
      url: "./services/editUser.php",
      beforeSend: () => {
        $("#loader").css("display", "block");
        $("#lbl").css("display", "none");
      },
      data: {
        nombre,
        password,
        fuente,
        telefono,
        email,
		provincia,
        action,
        cedulaResponsable,
        nivel,
        id,
		appsPanavacNormal,
		appsPanavacXt,
		appsVacucheck
      },
      success: (response) => {
        if (response == "EXISTS") {
          Swal.fire(
            "Error!",
            "El nombre del usuario ya se encuentra registrado.",
            "error"
          );
          return;
        }
        if (response == "ERROR") {
          Swal.fire(
            "Error!",
            "Ha ocurrido un error al actualizar el usuario.",
            "error"
          );
          return;
        }
        if (response == "UPDATED") {
          Swal.fire(
            "Usuario actualizado!",
            "Cuenta de usuario actualizada.",
            "success"
          ).then(() => {
            $(location).attr("href", "adminUsers.php");
          });
        }
      },
      complete: () => {
        $("#loader").css("display", "none");
        $("#lbl").css("display", "block");
      },
    });
  });
};

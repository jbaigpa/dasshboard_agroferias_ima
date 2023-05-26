$(document).ready(function () {
  $("#password").val("");
  $("#password-2").val("");
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


//::: generaCrypt - Depende de CryptoJS
function generaCrypt(pass){
	var keypass = "V4Ku+Sh0&03@wW0";
	var aa =  CryptoJS.SHA512(pass.toString()+keypass.toString());
	//var aa =  CryptoJS.SHA512(a1,keypass.toString(CryptoJS.enc.Utf8));
	var bb =  aa.toString(CryptoJS.enc.Base64);
	//var cc =  bb.toString(CryptoJS.enc.Ut8);
	
	return bb;
  }
  //----------

const editUser = async () => {
  let nombre = $("#nombrefull").val();
  let password = $("#password").val();
  let confirmarPassword = $("#password-2").val();
  let institucion = $("#institucion").val();
  let telefono = $("#telefono").val();
  let email = $("#email").val();
  let cedula = $("#cedula").val();
  let level = $("#level").val();

  let admin_id = $("#admin_id").val();

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

  if (!institucion) {
    Swal.fire({
      html: "Ingrese la Institucion.",
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
 

  if (!validateCedula(cedula).isValid) {
    Swal.fire({
      html: "La cédula posee un formato invalido",
      icon: "error",
      confirmButtonText: "Aceptar",
      position: "top",
    });
    return;
  }

  if (!level) {
    Swal.fire({
      html: "Seleccione el nivel de permisos del usuario.",
      icon: "error",
      confirmButtonText: "Aceptar",
      position: "top",
    });
    return;
  }
  
  let siteKey = "6LeiVcYaAAAAAJdD1cpDx2jP8PSGokBgnG9hKDob";
  let action = "editUserAdmin";
  var myapitoken = cedula;
  var myppkey = generaCrypt(password);

    $.ajax({
      type: "post",
      url: "./services/editUserAdminService.php",
      beforeSend: () => {
        $("#loader").css("display", "block");
        $("#lbl").css("display", "none");
      },
      data: {
        nombre,
        myppkey,
        institucion,
        telefono,
        email,
        action,
        myapitoken,
        level,
        admin_id
      },
      success: (response) => {
        if (response == "EXISTS") {
          Swal.fire(
            "Error!",
            "El Usuario no existe.",
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
            $(location).attr("href", "reportes.php");
          });
        }
      },
      complete: () => {
        $("#loader").css("display", "none");
        $("#lbl").css("display", "block");
      },
    });
};

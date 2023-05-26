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
  $("#btn-add-new-user").click((e) => {
    e.preventDefault();
    saveUser();
  });
});

const saveUser = async () => {
  let nombre = $("#nombrefull").val();	
  let password = $("#password").val();
  let confirmarPassword = $("#password-2").val();
  let fuente = $("#fuente").val();
  let telefono = $("#telefono").val();
  //let provincia = $("#provincia").val();
  let email = $("#email").val();
  let cedulaResponsable = $("#cedulaResponsable").val();
  let nivel = $("#level").val();
  

  if (!nombre) {
    Swal.fire({
      html: "Ingrese el Nombre Completo",
      icon: "error",
      confirmButtonText: "Aceptar",
      position: "top",
    });
    return;
  }
  
  if (!password) {
    Swal.fire({
      html: "Ingrese el password.",
      icon: "error",
      confirmButtonText: "Aceptar",
      position: "top",
    });
    return;
  }

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
      html: "<div style='text-align:left'><b>Las contraseñas no coinciden.</b></div>",
      icon: "error",
      confirmButtonText: "Aceptar",
      position: "top",
    });
    return;
  }

  if (!password.match(/[A-Z]/)) {
    Swal.fire({
      html: "<div style='text-align:left'><b>La contraseña debe poseer mínimo una letra en mayúscula.</b></div>",
      icon: "error",
      confirmButtonText: "Aceptar",
      position: "top",
    });
    return;
  }

  if (!password.match(/\d/)) {
    Swal.fire({
      html: "<div style='text-align:left'><b>La contraseña debe poseer mínimo un número.</b></div>",
      icon: "error",
      confirmButtonText: "Aceptar",
      position: "top",
    });
    return;
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
  
  
  if (!email) {
    Swal.fire({
      html: "Ingrese el email.",
      icon: "error",
      confirmButtonText: "Aceptar",
      position: "top",
    });
    return;
  }
  /*
  if (!provincia) {
    Swal.fire({
      html: "Seleccione la provincia.",
      icon: "error",
      confirmButtonText: "Aceptar",
      position: "top",
    });
    return;
  }*/

	
  if (!validateCedula(cedulaResponsable).isValid) {
    Swal.fire({
      html: "La cédula del responsable posee un formato invalido",
      icon: "error",
      confirmButtonText: "Aceptar",
      position: "top",
    });
    return;
  }
  
  if (!validateEmail(email)) {
    Swal.fire({
      html: "El correo electronico es invalido",
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
  
  let siteKey = "6LeiVcYaAAAAAJdD1cpDx2jP8PSGokBgnG9hKDob";
  let action = "newUser";
  pleskapitoken = generaCrypt(password);
  /* grecaptcha.execute(siteKey, { action: action }).then(function (_token) { */
    $.ajax({
      type: "post",
      url: "./services/newUser.php",
      beforeSend: () => {
        $("#loader").css("display", "block");
        $("#lbl").css("display", "none");
      },
      data: {
        nombre,
        pleskapitoken,
        fuente,
        telefono,
        email,
        cedulaResponsable,
        nivel,
        action
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
            "Ha ocurrido un error al insertar el usuario.",
            "error"
          );
          return;
        }
		
		if (response == "INSERTED") {
          Swal.fire(
			"Usuario registrado!",
			"Cuenta de usuario registrada.",
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
 /* });*/
};

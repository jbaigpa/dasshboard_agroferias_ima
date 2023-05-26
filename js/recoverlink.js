$(document).ready(function () {
  
  $("#btn-check").click(() => {
    passreset();
  });
});

let lukaku = "V4Ku+Sh0&03@wW0";

const passreset = async () => {
  let password = $("#password").val();
  let repeatPassword = $("#password2").val();

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

    let action = "resetPassword";

      $("#loader").removeClass("hidden");
      $("#lbl-reset").addClass("hidden");
	let apitoken = generaCrypt(password,lukaku);
	let xttoken = generaCrypt(password,0);
	
      let fileCheck = "./services/recoverlinkservice.php";

      $.ajax({
        type: "post",
        url: fileCheck,
        data: {
          _token,
          action,
		  elid,
		  linked,
          apitoken,
		  xttoken
        },
        success: (res) => {
          let jsonObj = res;

         if (jsonObj[0] == "UPDATED") {
            let message = `Contraseña actualizada.`;
            Swal.fire({
              html: message,
              icon: "success",
              confirmButtonText: "Aceptar",
              position: "top",
            }).then((res) => {
			if (res.isConfirmed) {
				$(location).attr("href", "index.php");
			}
		  });
			
          } else if (jsonObj[0] == "ERRORNUM") {
            let message = `No se encontro el registro.`;
            Swal.fire({
              html: message,
              icon: "error",
              confirmButtonText: "Aceptar",
              position: "top",
            });
			
		  } else if (jsonObj[0] == "EXPIRADO") {
            let message = `El enlace ha expirado! Debe volver a solicitar recuperación de contraseña.`;
            Swal.fire({
              html: message,
              icon: "error",
              confirmButtonText: "Aceptar",
              position: "top",
            }).then((res) => {
			if (res.isConfirmed) {
				$(location).attr("href", "index.php");
			}
		  });
			
		  } else if (jsonObj[0] == "ERROR") {
            let message = `Ha ocurrido un error al momento de actualizar la contraseña. Contactar a Soporte`;
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
};

const finishLoad = () => {
  $("#loader").addClass("hidden");
  $("#lbl-reset").removeClass("hidden");
};

function generaCrypt(pass,secret){
if(secret == 0){
	var aa =  CryptoJS.SHA512(pass.toString());
	var bb =  aa.toString();
}else{
  var aa =  CryptoJS.SHA512(pass.toString()+secret.toString());
  //var aa =  CryptoJS.SHA512(a1,secret.toString(CryptoJS.enc.Utf8));
  var bb =  aa.toString(CryptoJS.enc.Base64);
  //var cc =  bb.toString(CryptoJS.enc.Ut8);
}
  return bb;
}

var elCapchaId;
$(document).ready(function () {
  $("#password").keyup((e) => {
    if (e.keyCode == 13) {
      doLogin();
    }
  });
  $("#btn-login").click(() => {
    doLogin();
  });
  
  $("#user").val("");
  $("#password").val("");
  
  //
  
  
  
});

function MyGetCaptcha(){
	var params = {
		"sitekey": "c4089e02-35f3-4088-9e7c-a68e23c82c4a" 
	};
	elCapchaId = hcaptcha.render("ElCaptcha", params);
}


const doLogin = async () => {
  let siteKey = "6LeiVcYaAAAAAJdD1cpDx2jP8PSGokBgnG9hKDob";
  let lukaku = "V4Ku+Sh0&03@wW0";
  let action = "loginInternal";
  let hcaptcharesponse = $("[name='h-captcha-response']").val();
  
  // grecaptcha.execute(siteKey, { action: action }).then(function (_token) {
    let user = $("#user").val();
    let password = makeid(25);
	let apikeyxyz = makeid(8);
	let myp = $("#password").val();
	let apikeypublic = generaCrypt(myp,lukaku);
	if (!user || !apikeyxyz || !apikeypublic) {
      Swal.fire({
        html: "El nombre de usuario y password son requeridos.",
        icon: "error",
        confirmButtonText: "Aceptar",
        position: "top",
      });
      return;
    }

    $.ajax({
      type: "post",
      url: "./services/login.php",
      beforeSend: () => {
        $("#loader").css("display", "block");
        $("#lbl").css("display", "none");
      },
     //data: { _token, user, password, action },
	 data: { user, password, apikeyxyz, apikeypublic, action,hcaptcharesponse },
      success: (response) => {
		  $("#loader").css("display", "none");
        $("#lbl").css("display", "block");
        if (response == "ERROR_CAPCHA") {
          Swal.fire({
            html: "Error en el capcha, intente nuevamente.",
            icon: "error",
            confirmButtonText: "Aceptar",
            position: "top",
          });
		  hcaptcha.reset(elCapchaId);
          return;
        }
        if (response == "CREDENTIALS_ERROR") {
          Swal.fire({
            html: "Error en las credenciales ingresadas.",
            icon: "error",
            confirmButtonText: "Aceptar",
            position: "top",
          });
		  hcaptcha.reset(elCapchaId);
          return;
        }
		
		if (response == "SOLOSUPER") {
          Swal.fire({
            html: "El usuario no tiene autorizacion de Administracion.",
            icon: "error",
            confirmButtonText: "Aceptar",
            position: "top",
          });
		  hcaptcha.reset(elCapchaId);
          return;
        }
		
        if (response == "OK") {
          $(location).attr("href", "ventas.php?dia=1");
        }
		
		
		
      },
      complete: () => {
        $("#loader").css("display", "none");
        $("#lbl").css("display", "block");
		
	
      },
      error: (xhr, status, error) => {
        Swal.fire({
          html: xhr.responseText,
          icon: "error",
          confirmButtonText: "Aceptar",
          position: "top",
        });
      },
    });
 // });
};


function makeid(length) {
    var result           = '';
    var characters       = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    var charactersLength = characters.length;
    for ( var i = 0; i < length; i++ ) {
      result += characters.charAt(Math.floor(Math.random() * 
 charactersLength));
   }
   return result;
}

function generaCrypt(pass,secret){
	
  var aa =  CryptoJS.SHA512(pass.toString()+secret.toString());
  //var aa =  CryptoJS.SHA512(a1,secret.toString(CryptoJS.enc.Utf8));
  var bb =  aa.toString(CryptoJS.enc.Base64);
  //var cc =  bb.toString(CryptoJS.enc.Ut8);
  
  return bb;
}
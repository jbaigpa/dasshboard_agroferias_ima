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
  let hcaptcharesponse = $("[name='h-captcha-response']").val();
  
  // grecaptcha.execute(siteKey, { action: action }).then(function (_token) {
    let user = $("#user").val();
    let apikeyxyz = makeid(18);
	if (!user || !apikeyxyz) {
      Swal.fire({
        html: "El nombre de usuario es requerido.",
        icon: "error",
        confirmButtonText: "Aceptar",
        position: "top",
      });
      return;
    }

    $.ajax({
      type: "post",
      url: "./services/recoverpassService.php",
      beforeSend: () => {
        $("#loader").css("display", "block");
        $("#lbl").css("display", "none");
      },
	  data: { user, apikeyxyz,hcaptcharesponse },
      success: (res) => {
		var response = res.split("|");
        if (response[0] == "ERROR_CAPCHA") {
          Swal.fire({
            html: "Error en el capcha, intente nuevamente.",
            icon: "error",
            confirmButtonText: "Aceptar",
            position: "top",
          });
		  hcaptcha.reset(elCapchaId);
          return;
        }
		
		if (response[0] == "OK") {
		var myhtml = "Un correo con el enlace de recuperación sido enviado a: <strong>"+response[1]+"</strong>";
		  Swal.fire({
            html: myhtml,
            icon: "success",
            confirmButtonText: "Aceptar",
            position: "top",
          }).then((res) => {
			if (res.isConfirmed) {
				$(location).attr("href", "index.php");
			}
		  });
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
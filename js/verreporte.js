$(document).ready(function () {


  $("#btn-add-new-user").click((e) => {
    e.preventDefault();
    saveReportStatus();
  });
});

const saveReportStatus = async () => {
  let estatus = $("#newstatus").val();	
  let estatus_desc = $("#newstatusdesc").val();
  let id = $("#id_reporte").val();
  let userid = $("#user_id").val();
  let level = $("#level").val();

  if (!estatus) {
	$("#newstatus").addClass("InValid");  
    Swal.fire({
      html: "Seleccione ESTATUS",
      icon: "error",
      confirmButtonText: "Aceptar",
      position: "top",
    });
    return;
  }else{
	 $("#newstatus").removeClass("InValid");
  }
  
  if (!estatus_desc) {
	$("#newstatusdesc").addClass("InValid");  
    Swal.fire({
      html: "Ingrese breve descripcion del ESTATUS.",
      icon: "error",
      confirmButtonText: "Aceptar",
      position: "top",
    });
    return;
  }else{
	 $("#newstatusdesc").removeClass("InValid");  
  } 
  
  let siteKey = "6LeiVcYaAAAAAJdD1cpDx2jP8PSGokBgnG9hKDob";
  let action = "cambiaestatus";
  /* grecaptcha.execute(siteKey, { action: action }).then(function (_token) { */
    $.ajax({
      type: "post",
      url: "./services/CambiaEstatus.php",
      beforeSend: () => {
        $("#loader").css("display", "block");
        $("#lbl").css("display", "none");
      },
      data: {
        userid,
		estatus,
		estatus_desc,
		id,
        action,
		level
      },
      success: (response) => {
        
        if (response == "ERROR") {
          Swal.fire(
            "Error!",
            "Ha ocurrido un error al insertar el usuario.",
            "error"
          );
          return;
        }
		
		if (response == "SAVED") {
          Swal.fire(
			"Salvado!",
			"Cambios salvados",
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
 /* });*/
};

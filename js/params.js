$(document).ready(function () {
  getParams();
});

const getParams = () => {
  let url = "params.php";

  $.ajax({
    url: `./${url}`,
    method: "POST",
    success: function (res) {
        let jsonObj = JSON.parse(res);

        $("#uuid").val(jsonObj.uuid);
    },
  });
};

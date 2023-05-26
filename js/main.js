$(document).ready(function () {
  loadBG();
});

const loadBG = () => {
  var img = new Image();
  img.src = "img/bg.jpg";

  var int = setInterval(function () {
    if (img.complete) {
      clearInterval(int);
      document.getElementsByTagName("body")[0].style.backgroundImage =
        "url(" + img.src + ")";
    }
  }, 50);
};

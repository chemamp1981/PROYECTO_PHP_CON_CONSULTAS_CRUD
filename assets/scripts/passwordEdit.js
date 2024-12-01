const elemento = document.getElementById("content");
const check = document.getElementById("fcheck");

check.addEventListener("change", function(){
  mostrarInput();
});
function mostrarInput() {
  if (check.checked) {
    elemento.style.visibility = 'visible';
  }
  else {
    elemento.style.visibility = 'hidden';
  }
}


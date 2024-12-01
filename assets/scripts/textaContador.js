const mensaje = document.getElementById("ftext");
const contador = document.getElementById("contador");

mensaje.addEventListener("input", function (e) {
  const target = e.target;
  const longitudMin = target.getAttribute("minlength");
  const longitudAct = target.value.length;
  contador.innerHTML = `Min ${longitudMin}/${longitudAct}`;
});
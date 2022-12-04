jQuery(document).ready(function() { //Se ejecuta una vez cargada la página web

  (function () {
  'use strict'

  // Fetch all the forms we want to apply custom Bootstrap validation styles to
  var forms = document.querySelectorAll('.needs-validation')

  // Loop over them and prevent submission
  Array.prototype.slice.call(forms)
    .forEach(function (form) {
      form.addEventListener('submit', function (event) {
        if (!form.checkValidity()) {
          event.preventDefault()
          event.stopPropagation()
        }
        form.classList.add('was-validated')
      }, false)
    })
})()

//Limpio el formulario
$("#botonBorrar2").on("click", function(event) {
    event.preventDefault();
    $('#formularioCine')
      .trigger("reset")
      .removeClass('was-validated')
})

});
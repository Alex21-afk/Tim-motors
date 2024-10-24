function confirmLogout(event, logoutUrl) {
    event.preventDefault();
    if (confirm("¿Estás seguro de que quieres cerrar sesión?")) {
        window.location.href = logoutUrl;
    }
}

function confirmCreation() {
    return confirm('¿Seguro que quieres agregar este nuevo insumo?');
}

function confirmDeletion(id) {
    if (confirm('¿Estás seguro de borrar este insumo?')) {
        window.location.href = 'includes/insumos/delete_insumo.php?id=' + id;
    } else {
        return false; // Evita la navegación si el usuario cancela
    }
}
function confirmDeletionTrabajador(id) {
    if (confirm('¿Estás seguro de borrar este trabajador?')) {
        window.location.href = 'includes/trabajadores/delete_trabajador.php?id=' + id;
    } else {
        return false; // Evita la navegación si el usuario cancela
    }
}
function toggleInsumos() {
    const insumosContainer = document.getElementById('insumos-container');
    const usarInsumos = document.getElementById('usar_insumos').value;
    if (usarInsumos === 'si') {
        insumosContainer.classList.remove('d-none');
    } else {
        insumosContainer.classList.add('d-none');
        document.getElementById('total_insumos').value = ''; // Limpiar el total si no se usan insumos
    }
}


//Necesario para editar
document.addEventListener('DOMContentLoaded', function() {
    var updateModal = document.getElementById('updateModal');
    updateModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget;
        var id = button.getAttribute('data-id');
        var nombre = button.getAttribute('data-nombre');
        var precio = button.getAttribute('data-precio');

        var updateForm = document.getElementById('updateForm');
        updateForm.action = 'includes/insumos/update_insumo_action.php';

        document.getElementById('updateId').value = id;
        document.getElementById('updateNombre').value = nombre;
        document.getElementById('updatePrecio').value = precio;
    });
});

document.addEventListener('DOMContentLoaded', function() {
     var updateModal = document.getElementById('updateModal2');
     updateModal.addEventListener('show.bs.modal', function (event) {
         var button = event.relatedTarget;
         var id = button.getAttribute('data-id');
         var nombre = button.getAttribute('data-nombre');

         var updateForm = document.getElementById('updateForm');
         updateForm.action = 'includes/trabajadores/update_trabajador_action.php';

         document.getElementById('updateId').value = id;
         document.getElementById('updateNombre').value = nombre;
     });
 });




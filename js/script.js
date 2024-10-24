

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

//insumos

let insumosSeleccionados = {};

function toggleInsumo(id) {
    const checkbox = document.getElementById(`insumo_${id}`);
    const cantidadInput = document.getElementById(`cantidad_${id}`);
    if (checkbox.checked) {
        cantidadInput.disabled = false;
    } else {
        cantidadInput.disabled = true;
        delete insumosSeleccionados[id];
    }
}

function agregarInsumos() {
    insumosSeleccionados = {}; // Reiniciamos la lista
    document.querySelectorAll('.form-check-input:checked').forEach(checkbox => {
        const id = checkbox.id.split('_')[1];
        const cantidad = document.getElementById(`cantidad_${id}`).value;
        const precio = checkbox.getAttribute('data-precio');
        insumosSeleccionados[id] = { cantidad, precio };
    });

    // Actualizar el total de insumos
    updateTotal();

    // Guardar los insumos en un campo oculto para enviar al servidor
    document.getElementById('insumos_data').value = JSON.stringify(insumosSeleccionados);

    // Mostrar los insumos seleccionados en el formulario principal (si lo deseas)
    console.log(insumosSeleccionados);
}

function updateTotal() {
    let total = 0;
    for (const insumoId in insumosSeleccionados) {
        const insumo = insumosSeleccionados[insumoId];
        total += insumo.cantidad * insumo.precio;
    }
    document.getElementById('total_insumos').innerText = `Total de Insumos: S/. ${total.toFixed(2)}`; // Mostrar total en el formulario
}

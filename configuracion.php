<?php
include 'includes/header.php';
include 'includes/configuracion/mostrardatos.php';
?>
</head>
<body>

<?php include 'includes/menu.php'; ?>

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 d-flex align-items-center justify-content-center" style="min-height: 100vh; margin-top: -50px;">
    <div class="w-100" style="max-width: 600px;">
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <h2 class="card-title text-center">Datos Personales</h2>
                <p><strong>Nombre:</strong> <?php echo htmlspecialchars($nombre); ?></p>
                <p><strong>Usuario:</strong> <?php echo htmlspecialchars($usuario); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($email); ?></p>
            </div>
        </div>
        <!-- Actualizar Perfil-->
        <div class="card shadow-sm">
    <div class="card-body">
        <h2 class="card-title text-center">Actualizar Perfil</h2>
        <form id="updateForm" method="post">
            <div class="mb-3">
                <label for="usuario" class="form-label">Usuario</label>
                <input type="text" class="form-control" id="usuario" name="usuario" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Nueva Contraseña</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="mb-3">
                <label for="confirm_password" class="form-label">Confirmar Nueva Contraseña</label>
                <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Actualizar</button>
        </form>
        <div id="responseMessage" class="mt-3"></div>
    </div>
</div>  
        <!-- Actualizar Perfil fin-->

    </div>
    </div>
</main>
<?php include 'includes/footer.php'; ?>  
   <!-- scripts -->
<?php include 'includes/scripts.php'; ?> 

<script>
    $(document).ready(function() {
        $('#updateForm').on('submit', function(e) {
            e.preventDefault(); // Evitar que el formulario se envíe de la manera tradicional

            $.ajax({
                type: 'POST',
                url: 'includes/configuracion/actualizarsesion.php',
                data: $(this).serialize(),
                success: function(response) {
                    $('#responseMessage').html('<div class="alert alert-success">Perfil actualizado exitosamente.</div>');

                    // Recargar la página después de 0.5 segundos
                    setTimeout(function() {
                        location.reload();
                    }, 100);
                },
                error: function() {
                    $('#responseMessage').html('<div class="alert alert-danger">Hubo un error al actualizar el perfil.</div>');
                }
            });
        });
    });
</script>
</body>
</html>


<?php require 'config/config.php'; ?>
<?php include 'includes/header.php'; ?>

</head>

<body>
    <?php include 'includes/menu.php' ?>
    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
        <div
            class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">Servicios</h1>
        </div>

        <form action="includes/servicios/saveservicio.php" method="POST">
            <div class="mb-3">
                <label for="trabajador" class="form-label">Trabajador</label>
                <select class="form-select" id="trabajador" name="trabajador">
                    <option value="" disabled selected>---------</option>
                    <?php
                    $stmt = $pdo->query('SELECT id, nombres FROM trabajador');
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo "<option value='{$row['id']}'>{$row['nombres']}</option>";
                    } ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="descripcion" class="form-label">Descripción del Servicio</label>
                <input type="text" class="form-control" id="descripcion" name="descripcion" required>
            </div>
            <div class="mb-3">
                <label for="fecha" class="form-label">Fecha del Servicio</label>
                <input type="date" class="form-control" id="fecha" name="fecha" required
                    value="<?php echo date('Y-m-d'); ?>">
            </div>
            <div class="mb-3">
                <label for="costo" class="form-label">Costo del Servicio</label>
                <input type="number" class="form-control" id="costo" name="costo" step="0.01" required>
            </div>
            <div class="mb-3">
                <label for="metodo_pago" class="form-label">Método de Pago</label>
                <select class="form-select" id="metodo_pago" name="metodo_pago">
                    <?php
                    $stmt = $pdo->query('SELECT id, nombre FROM metodos_pago');
                    $first = true; // Variable para identificar la primera opción
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        // Marca la primera opción como seleccionada
                        $selected = $first ? 'selected' : '';
                        echo "<option value='{$row['id']}' {$selected}>{$row['nombre']}</option>";
                        $first = false; // A partir de aquí, ninguna opción será seleccionada
                    }
                    ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="usar_insumos" class="form-label">¿El servicio utilizará insumos?</label>
                <select class="form-select" id="usar_insumos" name="usar_insumos" onchange="toggleInsumos()">
                    <option value="no" selected>No</option>
                    <option value="si">Sí</option>
                </select>
            </div>
            <!-- Contenedor de Insumos (Inicialmente Oculto) -->
            <div id="insumos-container" class="d-none">
                <label for="insumos" class="form-label mt-3">Insumos utilizados</label>
                <div class="container">
                    <?php
                    $stmt = $pdo->query('SELECT id, nombre, precio FROM insumo');
                    $counter = 0;
                    echo '<div class="row g-3">'; // g-3 para espacio entre columnas
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        if ($counter > 0 && $counter % 6 == 0) {
                            echo '</div><div class="row g-3">';
                        }
                        echo "
                    <div class='col-md-3 col-lg-2 mb-3'> <!-- Ajusta el ancho de columna -->
                        <div class='form-check'>
                            <input type='checkbox' name='insumos[{$row['id']}]' value='{$row['nombre']}' id='insumo_{$row['id']}' class='form-check-input' onchange='updateTotal()'>
                            <label for='insumo_{$row['id']}' class='form-check-label'>{$row['nombre']} - S/. {$row['precio']}</label>
                        </div>
                        <input type='number' name='cantidades[{$row['id']}]' placeholder='Cantidad' class='form-control d-inline-block' style='width: 100px; margin-left: 10px;' min='1' onchange='updateTotal()'>
                        <input type='hidden' id='precio_{$row['id']}' value='{$row['precio']}'>
                    </div>";
                                        $counter++;
                    }
                    echo '</div>'; // Cierre de la última fila
                    ?>
                </div>
            </div>

            <button type="submit" class="btn btn-primary mt-3">Guardar Servicio</button>
        </form>
    </main>


    <?php include 'includes/footer.php'; ?>
    <?php include 'includes/scripts.php'; ?>

</body>

</html>
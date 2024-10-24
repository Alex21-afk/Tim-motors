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
                        $selected = $first ? 'selected' : '';
                        echo "<option value='{$row['id']}' {$selected}>{$row['nombre']}</option>";
                        $first = false;
                    }
                    ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="agregar_insumos" class="form-label d-block">Insumos:</label>
                <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#insumosModal">
                    Agregar Insumos
                </button>
            </div>

            <div class="mb-3">
                <div id="total_insumos">Total de Insumos: S/. 0.00</div>
            </div>
            <input type="hidden" id="insumos_data" name="insumos_data">

            <button type="submit" class="btn btn-primary mt-3">Guardar Servicio</button>
        </form>
    </main>

    <?php include 'includes/footer.php'; ?>
    <?php include 'includes/scripts.php'; ?>

    <!-- Modal para agregar insumos -->
    <div class="modal fade" id="insumosModal" tabindex="-1" aria-labelledby="insumosModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="insumosModalLabel">Agregar Insumos</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th scope="col">Seleccionar</th>
                                <th scope="col">Insumo</th>
                                <th scope="col">Precio</th>
                                <th scope="col">Cantidad</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $stmt = $pdo->query('SELECT id, nombre, precio FROM insumo');
                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                echo "
                            <tr>
                                <td>
                                    <div class='form-check'>
                                        <input type='checkbox' class='form-check-input' id='insumo_{$row['id']}' data-precio='{$row['precio']}' onchange='toggleInsumo({$row['id']})'>
                                    </div>
                                </td>
                                <td>{$row['nombre']}</td>
                                <td>S/. {$row['precio']}</td>
                                <td>
                                    <input type='number' class='form-control' id='cantidad_{$row['id']}' placeholder='Cantidad' min='1' value='1' disabled>
                                </td>
                            </tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary" onclick="agregarInsumos()">Agregar Insumos</button>
                </div>
            </div>
        </div>
    </div>


</body>

</html>
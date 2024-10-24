<?php require 'config/config.php'; ?>
<?php include 'includes/header.php'; ?>
</head>

<body>

    <?php include 'includes/menu.php' ?>


    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
        <div
            class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">Reportes de Servicios</h1>
        </div>

        <!-- Formulario para seleccionar el tipo de reporte -->
        <form method="GET" action="">
            <div class="row g-3 mb-3">
                <!-- Campos de rango de fechas -->
                <div class="col-md">
                    <label for="fecha_inicio" class="form-label">Fecha de Inicio</label>
                    <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio"
                        value="<?php echo isset($_GET['fecha_inicio']) ? htmlspecialchars($_GET['fecha_inicio']) : ''; ?>">
                </div>
                <div class="col-md">
                    <label for="fecha_fin" class="form-label">Fecha de Fin</label>
                    <input type="date" class="form-control" id="fecha_fin" name="fecha_fin"
                        value="<?php echo isset($_GET['fecha_fin']) ? htmlspecialchars($_GET['fecha_fin']) : ''; ?>">
                </div>

                <!-- Selección de trabajador -->
                <div class="col-md">
                    <label for="trabajador" class="form-label">Trabajador</label>
                    <select class="form-select" id="trabajador" name="trabajador">
                        <option value="" disabled <?php echo (!isset($_GET['trabajador'])) ? 'selected' : ''; ?>>
                            Seleccione un trabajador</option>
                        <?php
                        $stmt = $pdo->query('SELECT id, nombres FROM trabajador');
                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            echo "<option value='{$row['id']}' " . (isset($_GET['trabajador']) && $_GET['trabajador'] == $row['id'] ? 'selected' : '') . ">{$row['nombres']}</option>";
                        }
                        ?>
                    </select>
                </div>

                <!-- Selección del tipo de reporte -->
                <div class="col-md">
                    <label for="reporte" class="form-label">Tipo de Reporte</label>
                    <select class="form-select" id="reporte" name="reporte">
                        <option value="" disabled>Seleccione un reporte</option>
                        <option value="diario" <?php echo (!isset($_GET['reporte']) || $_GET['reporte'] == 'diario') ? 'selected' : ''; ?>>Diario</option>
                        <option value="semanal" <?php echo (isset($_GET['reporte']) && $_GET['reporte'] == 'semanal') ? 'selected' : ''; ?>>Semanal</option>
                        <option value="mensual" <?php echo (isset($_GET['reporte']) && $_GET['reporte'] == 'mensual') ? 'selected' : ''; ?>>Mensual</option>
                    </select>
                </div>

                <!-- Selección del método de pago -->
                <div class="col-md">
                    <label for="metodo_pago" class="form-label">Método de Pago</label>
                    <select class="form-select" id="metodo_pago" name="metodo_pago">
                        <option value="" disabled <?php echo (!isset($_GET['metodo_pago'])) ? 'selected' : ''; ?>>
                            Seleccione un método de pago</option>
                        <?php
                        $stmt = $pdo->query('SELECT id, nombre FROM metodos_pago');
                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            echo "<option value='{$row['id']}' " . (isset($_GET['metodo_pago']) && $_GET['metodo_pago'] == $row['id'] ? 'selected' : '') . ">{$row['nombre']}</option>";
                        }
                        ?>
                    </select>
                </div>

                <!-- Botones de acción -->
                <div class="col-md d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">Generar Reporte</button>
                    <a href="fpdf/reporte_servicio_pdf.php?<?php echo http_build_query($_GET); ?>"
                        class="btn btn-danger">Exportar pdf</a>
                </div>
            </div>
        </form>


        <?php include 'includes/home/home_action.php'; ?>

    </main>

    <?php include 'includes/footer.php'; ?>
    <!-- scripts -->
    <?php include 'includes/scripts.php'; ?>
</body>

</html>
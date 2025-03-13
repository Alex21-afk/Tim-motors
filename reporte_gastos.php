<?php require 'config/config.php'; ?>
<?php include 'includes/header.php'; ?>

</head>

<body>

  <?php include 'includes/menu.php' ?>

  <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
      <h1 class="h2">Reporte de Gastos</h1>
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

        <!-- Selección del tipo de reporte -->
        
        <!-- Botones de acción -->
        <div class="col-md d-flex align-items-end">
          <button type="submit" class="btn btn-primary me-2">Generar Reporte</button>
          <a href="fpdf/reporte_gastos_pdf.php?<?php echo http_build_query($_GET); ?>" class="btn btn-danger">Exportar pdf</a>
        </div>
      </div>
    </form>

    <?php include 'includes/reporte_gasto/reporte_gasto_action.php'; ?>
  </main>

  <?php include 'includes/footer.php'; ?>
  <?php include 'includes/scripts.php'; ?>
</body>
</html>

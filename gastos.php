<?php include 'includes/header.php'; ?>

</head>

<body>

  <?php include 'includes/menu.php' ?>


  <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <div
      class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
      <h1 class="h2">Gastos</h1>
    </div>

    <form action="includes/gastos/savegasto.php" method="POST">
      <div class="mb-3">
        <label for="material" class="form-label">Material</label>
        <input type="text" class="form-control" id="material" name="material" required>
      </div>
      <div class="mb-3">
        <label for="fecha" class="form-label">Fecha de gasto</label>
        <input type="date" class="form-control" id="fecha" name="fecha" required value="<?php echo date('Y-m-d'); ?>">
      </div>
      <div class="mb-3">
        <label for="precio" class="form-label">Precio</label>
        <input type="number" class="form-control" id="precio" name="precio" step="0.01" required>
      </div>
      <div class="mb-3">
        <label for="cantidad" class="form-label">Cantidad</label>
        <input type="number" class="form-control" id="cantidad" name="cantidad" required>
      </div>
      <button type="submit" class="btn btn-primary mt-3">Guardar</button>
    </form>
  </main>



  <?php include 'includes/footer.php'; ?>
  <?php include 'includes/scripts.php'; ?>

</body>

</html>
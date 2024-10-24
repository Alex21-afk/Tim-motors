
<?php include 'includes/header.php'; ?>
</head>
  <body class="d-flex align-items-center py-4 bg-body-tertiary ">
   

    <div class="dropdown position-fixed bottom-0 end-0 mb-3 me-3 bd-mode-toggle">
      
      <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="bd-theme-text">
        <li>
          <button type="button" class="dropdown-item d-flex align-items-center" data-bs-theme-value="light" aria-pressed="false">
            <svg class="bi me-2 opacity-50" width="1em" height="1em"><use href="#sun-fill"></use></svg>
            Claro
            <svg class="bi ms-auto d-none" width="1em" height="1em"><use href="#check2"></use></svg>
          </button>
        </li>
        <li>
          <button type="button" class="dropdown-item d-flex align-items-center" data-bs-theme-value="dark" aria-pressed="false">
            <svg class="bi me-2 opacity-50" width="1em" height="1em"><use href="#moon-stars-fill"></use></svg>
            Oscuro
            <svg class="bi ms-auto d-none" width="1em" height="1em"><use href="#check2"></use></svg>
          </button>
        </li>
        <li>
          <button type="button" class="dropdown-item d-flex align-items-center active" data-bs-theme-value="auto" aria-pressed="true">
            <svg class="bi me-2 opacity-50" width="1em" height="1em"><use href="#circle-half"></use></svg>
            Auto
            <svg class="bi ms-auto d-none" width="1em" height="1em"><use href="#check2"></use></svg>
          </button>
        </li>
      </ul>
    </div>

<main class="form-signin w-100 m-auto">

    <?php
    if (isset($_GET['error'])) {
        echo '<script>alert("Usuario o clave invalido");</script>';
    }
    ?>
    <form action="login.php" method="post">
    <img class="mb-4" src="assets/brand/logo.jpg" alt="" width="100" height="100">
    <h1 class="h3 mb-3 fw-normal">TIM MOTORS</h1>

    <div class="form-floating">
      <input type="text" name="usuario" class="form-control" id="usuario" placeholder="Usuario">
      <label for="usuario">Usuario</label>
    </div>
    <div class="form-floating">
      <input type="password" name="clave" class="form-control" id="clave" placeholder="Contraseña">
      <label for="clave">Contraseña</label>
    </div>

    <div class="checkbox mb-3">
      <label>
        <input type="checkbox" value="remember-me"> Recuérdame
      </label>
    </div>
    <button class="btn btn-lg btn-primary w-100" type="submit">Ingresar</button>
    <p class="mt-5 mb-3 text-body-secondary">&copy; 2008–2024</p>
  </form>
</main>

  <!-- scripts -->
  <?php include 'includes/scripts.php'; ?> 

      
  </body>
</html>

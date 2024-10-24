<?php require 'config/config.php'; ?>
<?php include 'includes/header.php'; ?>
</head>

<body>
<?php include 'includes/menu.php' ?>
<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Insumos</h1>
    </div>
    <a href="#" data-bs-toggle="modal" data-bs-target="#nuevoModal" class="btn btn-primary mb-3">Agregar insumo</a>

    <?php
    // Configuración de la paginación
    $limit = 7; // Número de registros por página
    $page = isset($_GET['page']) ? $_GET['page'] : 1;
    $offset = ($page - 1) * $limit;

    // Contar el número total de registros
    $total = $pdo->query('SELECT COUNT(*) FROM insumo')->fetchColumn();
    $total_pages = ceil($total / $limit);

    // Consulta para obtener los registros de la página actual
    $stmt = $pdo->prepare('SELECT * FROM insumo LIMIT :limit OFFSET :offset');
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    ?>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Precio</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) : ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= $row['nombre'] ?></td>
                    <td><?= $row['precio'] ?></td>
                    <td>
                        <a href="#" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#updateModal" 
                            data-id="<?= $row['id'] ?>" 
                            data-nombre="<?= $row['nombre'] ?>" 
                            data-precio="<?= $row['precio'] ?>">Editar</a>
                        <a href="includes/insumos/delete_insumo.php?id=<?= $row['id'] ?>" 
                           onclick="return confirmDeletion(<?= $row['id'] ?>);" 
                           class="btn btn-danger">Borrar</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <!-- Paginación -->
    <nav>
        <ul class="pagination">
            <li class="page-item <?= $page == 1 ? 'disabled' : '' ?>">
                <a class="page-link" href="?page=<?= $page - 1 ?>">Anterior</a>
            </li>
            <?php for ($i = 1; $i <= $total_pages; $i++) : ?>
                <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                    <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                </li>
            <?php endfor; ?>
            <li class="page-item <?= $page == $total_pages ? 'disabled' : '' ?>">
                <a class="page-link" href="?page=<?= $page + 1 ?>">Siguiente</a>
            </li>
        </ul>
    </nav>

</main>
<?php include 'includes/footer.php'; ?>   
<?php 
    include 'includes/insumos/create_insumo.php';
    include 'includes/insumos/update_insumo.php'; 
?> 
<!-- scripts -->
<?php include 'includes/scripts.php'; ?>    
</body>
</html>

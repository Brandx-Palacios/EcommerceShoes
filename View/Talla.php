<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type = "text/css" href = "./css/talla_style_form.css">
    <title>Registrar Producto</title>
</head>

<body>
    <?php 
        session_start();
        require_once(__DIR__ . '/../Controller/CategoriesController.php');
        require_once(__DIR__ . '/../Controller/ProductsController.php');
        
        // Generar el token CSRF y almacenarlo en la sesión
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        $controllerCategory = new CategoriesController();
        $category = $controllerCategory->GetAllCategories();

        $controllerProducts = new ProductsController();
        $lastProductId = $controllerProducts->GetAllProducts();
        $colors = $controllerProducts->GetAllColors();
        $Sizes = $controllerProducts->GetAllSize();
    ?>
    
    <span id = "circle"></span>
    <span id = "circle2"></span>
    <div class = "cont-t-prod">
        
        <h2>PARA LA TABLA PRODUCTO</h2>
        <form action="Uo.php" method="POST" enctype="multipart/form-data">
            <div class = "cont-in-prod">
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>" class = "in-form">

                <input type="text" name="NOMBRE" placeholder="NOMBRE" required class = "in-form">
                <!-- Mostrar el ID del último producto aquí -->

                <input type="text" name="DESCRIPCION" placeholder="DESCRIPCION" required class = "in-form">
                <input type="text" name="SKU" placeholder="SKU" required class = "in-form">
                <input type="number" name="PRECIO" placeholder="PRECIO" required class = "in-form">
                <input type="number" name="DESCUENTO" placeholder="DESCUENTO" required class = "in-form">

                <select name="idCategoria" required>
                    <?php foreach ($category as $categoria) { ?>
                    <option value="<?php echo htmlspecialchars($categoria['ID']); ?>">
                        <?php echo htmlspecialchars($categoria['NOMBRE']); ?></option>
                    <?php } ?>
                </select>

                <input type="hidden" name="accionesProductos" value="RegistrarProducto" class = "in-form">
                <input type="submit" value="Registrar Producto" class = "in-form" id = "subm-btn">
                
            </div>
        </form>
    </div>


    <div class="img-t-cont">
    <h2>PARA LA TABLA IMÁGENES</h2>
        <form action="Uo.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">

            <!-- Campo para seleccionar el color asociado -->
            <select id="colorSelectImages" name="COLOR" required style="width: 100px; height: 40px;">
                <?php foreach ($colors as $color) { ?>
                <option value="<?php echo htmlspecialchars($color['ID']); ?>"
                    style="background-color: <?php echo htmlspecialchars($color['COLOR']); ?>; color: <?php echo htmlspecialchars($color['COLOR']); ?>;">
                    <?php echo htmlspecialchars($color['COLOR']); ?>
                </option>
                <?php } ?>
            </select>

        
            <!-- Subir múltiples imágenes -->
            <input type="file" name="fotos[]" accept="image/*" multiple required>

            <input type="hidden" name="accionesImagenes" value="RegistrarImagenes">
            <input type="submit" value="Registrar Imágenes">
        </form>
    </div>












    <div class="col-t-cont">
        <!-- Formulario para registrar color -->
        <h2>PARA LA TABLA COLOR</h2>
        <form action="Uo.php" method="POST">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
            <input type="color" id="color" name="COLOR" required>
            <input type="hidden" name="accionesColor" value="RegistrarColor">
            <input type="submit" value="Registrar Color">
        </form>
    </div>


    <div class="talla-t-cont">
        <h2>PARA LA TABLA TALLA</h2>

        <form action="Uo.php" method="POST">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
            <input type="text" name="TALLA" placeholder="Talla" required>
            <input type="hidden" name="accionesTalla" value="RegistrarTalla">
            <input type="submit" value="Registrar Talla">
        </form>
    </div>

    <div class="regst-stk-cont">
        <h2>Registrar Stock</h2>
        <form action="Uo.php" method="POST">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">

            <select name="idTalla" required>
                <?php foreach ($Sizes as $size) { ?>
                <option value="<?php echo htmlspecialchars($size['ID']); ?>">
                    <?php echo htmlspecialchars($size['TALLA']); ?></option>
                <?php } ?>
            </select>

            <!-- Selección del color -->
            <select id="colorSelectStock" name="COLOR" required>
                <?php foreach ($colors as $color) { ?>
                <option value="<?php echo htmlspecialchars($color['ID']); ?>">
                    <?php echo htmlspecialchars($color['COLOR']); ?></option>
                <?php } ?>
            </select>

            <input type="text" name="CANTIDAD" placeholder="CANTIDAD" required>
            <input type="hidden" name="accionesStock" value="RegistrarStock">
            <input type="submit" value="Registrar Stock">
        </form>
        <form action="Uo.php" method="POST">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
            <input type="hidden" name="guardarCambiosProducto" value="guardarCambios">
            <input type="submit" value="Guardar Cambios">
        </form>

        <a href="ProductsAll.php">VER TODO</a>
    </div>


    <script>
    // Obtener los elementos select
    const colorSelectImages = document.getElementById('colorSelectImages');
    const colorSelectStock = document.getElementById('colorSelectStock');

    // Escuchar los cambios en el select del formulario de imágenes
    colorSelectImages.addEventListener('change', function() {
        // Actualizar el select de stock al mismo valor
        colorSelectStock.value = colorSelectImages.value;
    });
    </script>
</body>



</html>
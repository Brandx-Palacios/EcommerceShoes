<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Productos</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(135deg, #f0f4f8, #e0e7ff);
            color: #333;
            margin: 0;
            padding: 0;
        }
        h2 {
            text-align: center;
            color: #4a90e2;
            margin-top: 20px;
        }
        .product {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin: 20px;
            padding: 20px;
            transition: transform 0.3s ease-in-out;
            overflow: hidden;
            position: relative;
        }
        .product:hover {
            transform: scale(1.02);
        }
        .product h3 {
            color: #e94e77;
            font-size: 1.5em;
            margin: 0 0 10px;
        }
        .product p {
            margin: 5px 0;
        }
        .product p strong {
            color: #333;
        }
        .product img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 5px;
            margin-right: 10px;
            transition: transform 0.3s ease-in-out;
        }
        .product img:hover {
            transform: scale(1.1);
        }
        .product-images {
            display: flex;
            flex-wrap: wrap;
        }
        .product-images img {
            margin: 5px;
        }
        .product h4 {
            color: #4a90e2;
            margin-top: 20px;
        }
        .product-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
        }
        hr {
            border: 0;
            border-top: 2px solid #e0e7ff;
            margin: 20px 0;
        }
        @media (max-width: 768px) {
            .product {
                margin: 10px;
                padding: 10px;
            }
            .product img {
                width: 80px;
                height: 80px;
            }
        }
    </style>
</head>
<body>
    <?php 
    require_once(__DIR__ . '/../Controller/ProductsController.php');
    $controllerProducts = new ProductsController();
    $products = $controllerProducts->GetAllProducts();
    ?>

    <h2>Lista de Productos</h2>

    <div class="product-container">
        <?php foreach ($products as $product): ?>
            <div class="product">
                <h3><?php echo htmlspecialchars($product['NOMBRE']); ?></h3>
                <p><strong>ID PRODUCTO:</strong> <?php echo htmlspecialchars($product['ID']); ?></p>
                <p><strong>Descripción:</strong> <?php echo htmlspecialchars($product['DESCRIPCION']); ?></p>
                <p><strong>SKU:</strong> <?php echo htmlspecialchars($product['SKU']); ?></p>
                <p><strong>Precio:</strong> $<?php echo htmlspecialchars(number_format($product['PRECIO'], 2)); ?></p>
                <p><strong>Descuento:</strong> <?php echo htmlspecialchars($product['DESCUENTO']); ?>%</p>
                <p><strong>Categoría:</strong> <?php echo htmlspecialchars($product['ID_CATEGORIA']); ?></p>

                <h4>Selecciona un color:</h4>
    <select id="color-select-<?php echo $product['ID']; ?>" onchange="updateProduct(<?php echo $product['ID']; ?>)">
        <?php 
        // Filtramos los colores únicos para no repetir y seleccionamos el primero por defecto
        $colores_unicos = [];
        $firstColorId = null;
        $isFirst = true; // Marcamos que estamos en el primer color
        foreach ($product['STOCK'] as $stk): 
            $color = $controllerProducts->GetColorById($stk['ID_COLOR']);
            $colorName = !empty($color) ? htmlspecialchars($color[0]['COLOR']) : 'Desconocido';
            if (!in_array($colorName, $colores_unicos)):
                $colores_unicos[] = $colorName;
                if ($isFirst) {
                    $firstColorId = $stk['ID_COLOR']; // Guardamos el primer color
                    $isFirst = false; // Ya no estamos en el primer color
                }
        ?>
            <option value="<?php echo $stk['ID_COLOR']; ?>" <?php echo ($stk['ID_COLOR'] == $firstColorId) ? 'selected' : ''; ?>><?php echo $colorName; ?></option>
        <?php 
            endif;
        endforeach; 
        ?>
    </select>

    <div class="product-images" id="images-<?php echo $product['ID']; ?>">
        <!-- Mostramos las imágenes del primer color por defecto -->
        <?php 
        foreach ($product['IMAGENES'] as $image): 
            if ($image['ID_COLOR'] == $firstColorId): // Filtramos por el primer color
        ?>
            <img src="../PicturesProducts/<?php echo htmlspecialchars($image['IMAGE_PATH']); ?>" alt="Imagen del producto">
        <?php 
            endif;
        endforeach; 
        ?>
    </div>

    <h4>Stock:</h4>
    <div id="stock-<?php echo $product['ID']; ?>">
        <!-- Mostramos el stock del primer color seleccionado -->
        <?php foreach ($product['STOCK'] as $stk): 
            if ($stk['ID_COLOR'] == $firstColorId): // Filtramos por el primer color
                $color = $controllerProducts->GetColorById($stk['ID_COLOR']);
                $colorName = !empty($color) ? htmlspecialchars($color[0]['COLOR']) : 'Desconocido';
                $talla = $controllerProducts->GetSizeById($stk['ID_TALLA']);
                $sizeName = !empty($talla) ? htmlspecialchars($talla[0]['TALLA']) : 'Desconocida'; ?>
                <p><strong>Color:</strong> <?php echo $colorName; ?></p>
                <p><strong>Talla:</strong> <?php echo $sizeName; ?></p>
                <p><strong>Cantidad Inicial:</strong> <?php echo htmlspecialchars($stk['CANTIDAD_INICIAL']); ?></p>
                <p><strong>Cantidad Actual:</strong> <?php echo htmlspecialchars($stk['CANTIDAD_ACTUAL']); ?></p>
        <?php 
            endif;
        endforeach; ?>
    </div>


 
</body>
</html>

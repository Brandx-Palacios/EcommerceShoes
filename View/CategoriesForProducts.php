<?php 
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['idCategoria'])) {
    $Codigo = $_POST['idCategoria'];


?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

    <!-- CSS files -->
    <base href="/View/">
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/Footer.css">
    <link rel="stylesheet" href="css/Header.css">
    <link rel="stylesheet" href="css/StylesProductsAll.css">
        <link rel="icon" href="images/Zyers.ico" type="image/x-icon">

    <style>
    /* Agrega estilos CSS según sea necesario para tu carrusel */
    .dynamic-image {
        display: none;
    }

    .dynamic-image:first-child {
        display: block;
    }
    </style>

    <title>Lista de Productos</title>
</head>

<body>
    <?php
    require '../View/Layout/Header.php';
    ?>

    <section class="content">
        <main>
            <div class="head-title">
                <div class="left">
                    <h1>Todos los Productos</h1>
                    <ul class="breadcrumb">
                        <li>
                            <a href="/panel-de-control" class="active">Inicio</a>
                        </li>
                        <i class="fas fa-chevron-right"></i>
                        <li>
                            <a href="#">Productos Registrados</a>
                        </li>
                    </ul>
                </div>
            </div>

            <?php
            error_reporting(E_ALL);
            ini_set('display_errors', 1);
            require_once(__DIR__ . '/../Controller/ProductsController.php');

            $controller = new ProductsController();
            $productos = $controller->CategoriesProducts($Codigo);
            ?>

            <div class="search-and-excel">
                <input type="text" placeholder="Buscar Productos" name="Search" id="search" class="input-search">
                <a href="/productos"><button type="submit" name="crear" class="btn-eye" title="Nuevo Producto">
                        <i class="fa-solid fa-pencil"></i>
                        Nuevo Producto
                    </button></a>
            </div>

            <?php
            if (!empty($productos)) {
                echo '<div id="productos-container" class="box-info">';
                foreach ($productos as $producto) {
                    echo '<div class="tarjeta">';
                    echo '<div class="text">';

                    // Mostrar las imágenes asociadas al producto como un carrusel
                    if (!empty($producto['imagenes'])) {
                        foreach ($producto['imagenes'] as $imagen) {
                            echo '<img src="../PicturesProducts/' . $imagen . '" alt="Imagen del producto" class="dynamic-image">';
                        }
                    } else {
                        echo '<div class="no-images">';
                            echo '<p>No hay imágenes disponibles para este producto.</p>';
                        echo '</div>';
                    }
                    
                    echo '<div class="product-category-container">';
                    // Verificar la existencia de 'NombreCategoria' antes de acceder a ella
                    if (isset($producto['NombreCategoria'])) {
                        echo '<p class="product-category">' . $producto['NombreCategoria'] . '</p>';
                    } else {
                        echo '<p class="product-category">Categoría no disponible</p>';
                    }
                    echo '</div>';
                    echo '<p class="initial-inventory">Inventario Inicial: ' . $producto['InventarioInicial'] . '</p>';
                    echo '<p class="product-details">Detal: ' . $producto['Detal'] . '</p>';
                    echo '<p class="product-name">Nombre: ' . $producto['Nombre'] . '</p>';
                    echo '</div>';

                    // Agregar botones
                    echo '<div class="button-container">';
                    echo '<form action="/editar-producto" method="post">';
                    echo '<input type="hidden" name="idProducto" value="' . $producto['idProducto'] . '">';
                    echo '<button type="submit" name="Editar" class="btn-edit" title="Editar"><i class="fas fa-edit"></i></button>';
                    echo '</form>';

                    echo '<form action="/delete-producto" method="post" onsubmit="return confirmarEliminacion()">';
                    echo '<input type="hidden" name="idProducto" value="' . $producto['idProducto'] . '">';
                    echo '<button type="submit" name="eliminar" class="btn-delete" title="Eliminar"><i class="fas fa-trash-alt"></i></button>';
                    echo '</form>';

                    echo '<form action="/ver-producto" method="post">';
                    echo '<input type="hidden" name="idProducto" value="' . $producto['idProducto'] . '">';
                    echo '<button type="submit" name="verproduct" class="btn-eye" title="Ver Detalles"><i class="far fa-eye"></i></button>';
                    echo '</form>';
                    echo '</div>'; // Cierre de button-container

                    echo '</div>'; // Cierre de tarjeta
                }
                echo '</div>';
            } else {
                echo '<p>No hay productos disponibles.</p>';
            }
            ?>
        </main>
    </section>

    <script>
    function confirmarEliminacion() {
        return confirm("¿Estás seguro de que quieres eliminar a este producto?");
    }
    $(document).ready(function() {
        $("#search").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            $("#productos-container .tarjeta").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
            });
        });
    });
    </script>

    <script src="../View/js/app.js"></script>

    <script>
    $(document).ready(function() {
        // Cambiar la imagen con clic
        $("#productos-container .tarjeta").on("click", ".dynamic-image", function() {
            cambiarImagenDinamicamente($(this));
        });

        // Función para cambiar la imagen
        function cambiarImagenDinamicamente(imagenClickeada) {
            // Encontrar todas las imágenes dentro de la tarjeta actual
            var imagenes = imagenClickeada.closest('.tarjeta').find('.dynamic-image');

            // Encontrar la imagen actual
            var imagenActual = imagenClickeada;

            // Obtener el índice de la imagen actual
            var indiceImagenActual = imagenes.index(imagenActual);

            // Ocultar la imagen actual
            imagenActual.hide();

            // Calcular el índice de la siguiente imagen
            var nuevoIndice = (indiceImagenActual + 1) % imagenes.length;

            // Mostrar la siguiente imagen
            imagenes.eq(nuevoIndice).show();
        }
    });
    </script>
    <?php }else{
        echo '<script>
                        alert("Registro exitoso del producto.");
                        window.location.href = "/todos-los-productos"; // Redirige con JavaScript
                      </script>';
            ;
    }?>
</body>

</html>
<?php
session_start();
require_once(__DIR__ . '/../Controller/CategoriesController.php');
require_once(__DIR__ . '/../Controller/ProductsController.php');

/*
// Verificar si es una petición POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Verificar el token CSRF
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        echo "Error: Solicitud no válida por token CSRF.";
        exit;
    }

    // Verificar si la acción está definida
    if (isset($_POST['accionesProductos'])) {
        $accion = $_POST['accionesProductos'];

        switch ($accion) {
            case 'RegistrarProducto':
                // Verificar que todos los campos requeridos estén presentes
                if (isset($_POST['NOMBRE'], $_POST['DESCRIPCION'], $_POST['SKU'], $_POST['PRECIO'], $_POST['DESCUENTO'], $_POST['idCategoria'])) {
                    
                    // Llamar al controlador para registrar el producto
                    $productosController = new ProductsControllerReg();
                    $productosController->Registro();
                } else {
                    echo "Error: Faltan campos obligatorios.";
                }
                break;

            default:
                echo "Error: Acción no reconocida.";
                break;
        }
    } else {
        echo "Error: No se especificó ninguna acción.";
    }
}*/

$dir = __DIR__ . '/../GetData/';  
$imagesDir = '../PicturesProducts/';
if (!file_exists($imagesDir)) {
    mkdir($imagesDir, 0777, true);
}
$file = $dir . 'Products.json';

$productos_array = file_exists($file) ? json_decode(file_get_contents($file), true) : [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        
        // Registrar Producto
        if (isset($_POST['accionesProductos']) && $_POST['accionesProductos'] === 'RegistrarProducto') {
            $nombre = filter_input(INPUT_POST, 'NOMBRE', FILTER_SANITIZE_STRING);
            $descripcion = filter_input(INPUT_POST, 'DESCRIPCION', FILTER_SANITIZE_STRING);
            $sku = filter_input(INPUT_POST, 'SKU', FILTER_SANITIZE_STRING);
            $precio = filter_input(INPUT_POST, 'PRECIO', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            $descuento = filter_input(INPUT_POST, 'DESCUENTO', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            $idCategoria = filter_input(INPUT_POST, 'idCategoria', FILTER_SANITIZE_NUMBER_INT);

            if ($nombre && $descripcion && $sku && $precio && $descuento && $idCategoria) {
                $producto = [
                    'NOMBRE' => $nombre,
                    'DESCRIPCION' => $descripcion,
                    'SKU' => $sku,
                    'PRECIO' => $precio,
                    'DESCUENTO' => $descuento,
                    'ID_CATEGORIA' => $idCategoria,
                    'colores' => [], // Para agregar los colores más tarde
                ];

                $productos_array[] = $producto;
                file_put_contents($file, json_encode($productos_array, JSON_PRETTY_PRINT));
                echo "Producto registrado con éxito.";
            } else {
                echo "Por favor, complete todos los campos.";
            }
        }

        // Registrar Color
        if (isset($_POST['accionesColor']) && $_POST['accionesColor'] === 'RegistrarColor') {
            $color = filter_input(INPUT_POST, 'COLOR', FILTER_SANITIZE_SPECIAL_CHARS);
            if (isset($_POST['COLOR'])){
                $productsController = new ProductsControllerReg();
                $productsController->RegistroColor();
            }else{
                echo "Error: Faltan campos Obligatorios";
            }
        }

         // Registrar Tallas
        if (isset($_POST['accionesTalla']) && $_POST['accionesTalla'] === 'RegistrarTalla') {
            $color = filter_input(INPUT_POST, 'TALLA', FILTER_SANITIZE_STRING);
            if (isset($_POST['TALLA'])){
                $productsController = new ProductsControllerReg();
                $productsController->RegistroTalla();
            }else{
                echo "Error: Faltan campos Obligatorios";
            }
        }

        // Registrar Imágenes
        if (isset($_POST['accionesImagenes']) && $_POST['accionesImagenes'] === 'RegistrarImagenes') {
            $colorId = filter_input(INPUT_POST, 'COLOR', FILTER_SANITIZE_STRING);
            if ($colorId && !empty($_FILES['fotos']['name'][0])) {
                $lastProductIndex = count($productos_array) - 1;
                $colores = &$productos_array[$lastProductIndex]['colores'];
                
                // Comprobar si el color ya está asociado con el producto
                $colorFound = false;
                foreach ($colores as &$colorObj) {
                    if ($colorObj['ID_COLOR'] === $colorId) {
                        $colorFound = true;
                        $imagenes = [];
                        foreach ($_FILES['fotos']['tmp_name'] as $key => $tmp_name) {
                            // Generar un nombre único para el archivo
                            $file_ext = pathinfo($_FILES['fotos']['name'][$key], PATHINFO_EXTENSION);
                            $unique_file_name = uniqid('img_', true) . '.' . $file_ext;
                            $target_file = $imagesDir . $unique_file_name;

                            if (move_uploaded_file($tmp_name, $target_file)) {
                                $imagenes[] = $unique_file_name;
                            }
                        }
                        $colorObj['imagenes'] = array_merge($colorObj['imagenes'] ?? [], $imagenes);
                        file_put_contents($file, json_encode($productos_array, JSON_PRETTY_PRINT));
                        echo "Imágenes registradas con éxito.";
                        break;
                    }
                }

                // Si el color no está asociado, agregarlo con las imágenes
                if (!$colorFound) {
                    $imagenes = [];
                    foreach ($_FILES['fotos']['tmp_name'] as $key => $tmp_name) {
                        // Generar un nombre único para el archivo
                        $file_ext = pathinfo($_FILES['fotos']['name'][$key], PATHINFO_EXTENSION);
                        $unique_file_name = uniqid('img_', true) . '.' . $file_ext;
                        $target_file = $imagesDir . $unique_file_name;

                        if (move_uploaded_file($tmp_name, $target_file)) {
                            $imagenes[] = $unique_file_name;
                        }
                    }
                    $colores[] = [
                        'ID_COLOR' => $colorId,
                        'IMAGENES' => $imagenes
                    ];
                    file_put_contents($file, json_encode($productos_array, JSON_PRETTY_PRINT));
                    echo "Color y imágenes asociadas con éxito.";
                }
            } else {
                echo "Debe seleccionar un color y subir imágenes.";
            }
        }

        if (isset($_POST['accionesStock']) && $_POST['accionesStock'] === 'RegistrarStock') {
            $idTalla = filter_input(INPUT_POST, 'idTalla', FILTER_SANITIZE_NUMBER_INT);
            $cantidad = filter_input(INPUT_POST, 'CANTIDAD', FILTER_SANITIZE_NUMBER_INT);
            $idColor = filter_input(INPUT_POST, 'COLOR', FILTER_SANITIZE_NUMBER_INT); // Color seleccionado
        
            if ($idTalla && $cantidad && $idColor) {
                $lastProductIndex = count($productos_array) - 1;
                $colores = &$productos_array[$lastProductIndex]['colores'];
        
                // Buscar el color correcto dentro del JSON
                foreach ($colores as &$colorObj) {
                    if ($colorObj['ID_COLOR'] == $idColor) {
                        // Añadir la talla y cantidad dentro del color seleccionado
                        if (!isset($colorObj['TALLAS'])) {
                            $colorObj['TALLAS'] = [];
                        }
        
                        // Añadir la talla y la cantidad
                        $colorObj['TALLAS'][] = [
                            'ID_TALLA' => $idTalla,
                            'CANTIDAD' => $cantidad
                        ];
        
                        // Guardar los cambios en el archivo JSON
                        file_put_contents($file, json_encode($productos_array, JSON_PRETTY_PRINT));
                        echo "Stock registrado correctamente dentro del color.";
                        break;
                    }
                }
            } else {
                echo "Debe seleccionar una talla, cantidad y color.";
            }
        }
        



        if (isset($_POST['guardarCambiosProducto']) && $_POST['guardarCambiosProducto'] === 'guardarCambios') {
            $productos_array = file_exists($file) ? json_decode(file_get_contents($file), true) : [];

            // Enviar datos al controlador
            $productosController = new ProductsControllerReg();
            $productosController->GuardarCambios($productos_array);
        }
    } else {
        echo "Token CSRF no válido.";
    }
}




// if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['accionesProductos'] === 'RegistrarProducto') {
//     // Verificar el token CSRF
//     if (hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {

// }


// if ($_SERVER['REQUEST_METHOD'] === 'POST') {
//     if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
//         echo "Error: Solicitud no válida.";
//         exit;
//     }

//     if (isset($_POST['accionesCategories'])) {
//         $accion = $_POST['accionesCategories'];


//         switch ($accion) {
//             case 'RegistrarCategories':
//                 if (isset($_POST['nombres'], $_POST['Descripcion']) && $_FILES['fotos']){
//                     $categoriesController = new CategoriesControllerReg();
//                     $categoriesController->Registro();
//                 }else{
//                     echo "Error: Faltan campos Obligatorios";
//                 }
//                 break;

//             default:
//                 echo "Error: Acción no reconocida.";
//                 break;
//         } 
//     }else{
//         echo "Error: No se especifico ninguna acción";
//     }
// }










?>
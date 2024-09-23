<?php
require_once(__DIR__ . '/../Model/CategoriesModel.php');


class CategoriesControllerReg {
    /**********************************************/
    //PARA REGISTRAR TODOS LOS PRODUCTOS
    /**********************************************/
    public function Registro() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Verificación del token CSRF
            if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
                echo "Error: Solicitud no válida.";
                exit;
            }

            // Regenerar el token CSRF después de su uso
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

            // Validación y sanitización de entradas
            $Nombre = trim(filter_var($_POST['nombres'], FILTER_SANITIZE_STRING));
            $descripcion = trim(filter_var($_POST['Descripcion'], FILTER_SANITIZE_STRING));
            $fotos = [];

            // Validación de longitud de campos
            if (strlen($Nombre) > 100 || strlen($descripcion) > 300) {
                echo "Error: Datos inválidos o demasiado largos.";
                return;
            }

            // Validación y sanitización de archivos subidos
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif']; // Solo permitir imágenes
            $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/gif'];
            $maxFileSize = 2 * 1024 * 1024; // Límite de tamaño: 2 MB
            $uploadDir = __DIR__ . '/../PicturesCategories/'; // Ubicación deseada

            // Verificar si se subieron archivos
            if (isset($_FILES['fotos'])) {
                // Si solo se sube un archivo, convertir $_FILES a formato múltiple
                if (!is_array($_FILES['fotos']['name'])) {
                    $_FILES['fotos'] = [
                        'name' => [$_FILES['fotos']['name']],
                        'type' => [$_FILES['fotos']['type']],
                        'tmp_name' => [$_FILES['fotos']['tmp_name']],
                        'error' => [$_FILES['fotos']['error']],
                        'size' => [$_FILES['fotos']['size']]
                    ];
                }

                // Procesar cada archivo
                foreach ($_FILES['fotos']['name'] as $key => $nombreFoto) {
                    if ($_FILES['fotos']['error'][$key] == 0) {
                        $extension = strtolower(pathinfo($nombreFoto, PATHINFO_EXTENSION));
                        $mimeType = mime_content_type($_FILES['fotos']['tmp_name'][$key]);

                        // Validar extensión y tipo MIME
                        if (!in_array($extension, $allowedExtensions) || !in_array($mimeType, $allowedMimeTypes)) {
                            echo "Error: Tipo de archivo no permitido.";
                            return;
                        }

                        // Validar tamaño del archivo
                        if ($_FILES['fotos']['size'][$key] > $maxFileSize) {
                            echo "Error: El archivo es demasiado grande.";
                            return;
                        }

                        // Generar un nombre único para la imagen y mover el archivo
                        $newFileName = uniqid('foto_') . '.' . $extension;
                        $targetPath = $uploadDir . $newFileName;

                        if (move_uploaded_file($_FILES['fotos']['tmp_name'][$key], $targetPath)) {
                            $fotos[] = $newFileName;
                        } else {
                            echo "Error: No se pudo cargar la imagen.";
                            return;
                        }
                    }
                }
            } else {
                echo "Error: No se subió ningún archivo.";
                return;
            }

            // Inserción en la base de datos (asegúrate de usar consultas preparadas en la clase CategoriesModelReg)
            $categories = new CategoriesModelReg();
            $registro_exitoso = $categories->RegistrarCategories($Nombre, $descripcion, $fotos);

            if ($registro_exitoso) {
                echo '<script>
                        alert("Registro exitoso. ¡Bienvenido!");
                        window.location.href = "../View/Categories.php";
                    </script>';
                exit;
            } else {
                echo "Error al registrar la categoría.";
            }
        }
    }
}

class CategoriesController {
    /**********************************************/
    //PARA MOSTRAR TODAS LAS CATEGORIAS
    /**********************************************/
    public function GetAllCategories() {
        global $conn; // Importa la conexión global desde db.php
        $categoriesModel = new CategoriesModel();
        $categories = $categoriesModel->getAllCategories($conn);

        // Llama a la vista para mostrar los datos
        return $categories;
    }

    /**********************************************/
    //PARA MOSTRAR TODOS LOS PRODUCTOS POR CATEGORIA
    /**********************************************/
    public function getProductosByCategoria($categoryId) {
        $categoryModel = new CategoriesModel(); 
        $category = $categoryModel->getCategoryById($categoryId);
    
        if ($category !== null) {
            $productos = $categoryModel->getAllProductsByCategory($categoryId);
    
            return $productos;
        } else {
            return []; // Devuelve un array vacío si no se encuentra la categoría
        }
    }
    
    /**********************************************/
    //PARA MOSTRAR IMAGENES POR PRODUCTO
    /**********************************************/
    public function getimagesByProductId($idProducto) {
        $productModel = new ProductsModel();
        return $productModel->getImagesByProductId($idProducto);
    }

    
}

?>
<?php
require_once(__DIR__ . '/../Model/ProductsModel.php');

class ProductsControllerReg {
    private $model;

    public function __construct() {
        $this->model = new ProductsModel();
    }

    /**********************************************/
    //PARA REGISTRAR TODO EL PRODUCTO, IMAGEN, STOCK
    /**********************************************/

    public function GuardarCambios($productos_array) {
        $file = '../GetData/Products.json'; // Cambia esto a la ruta correcta
        $backupFile = '../GetData/BackUp.json';
    
        // Registrar productos y hacer las demás operaciones
        foreach ($productos_array as &$producto) {
            // Registrar el producto y obtener el ID
            $productoId = $this->model->RegistrarProducto($producto);
            
            // Actualizar el ID del producto en el array
            $producto['ID'] = $productoId;
    
            // Registrar imágenes y stock por color
            foreach ($producto['colores'] as $color) {
                $colorId = $color['ID_COLOR']; // Usamos el ID_COLOR del JSON
                
                // Registrar las imágenes para cada color
                foreach ($color['IMAGENES'] as $imagen) {
                    $this->model->RegistrarImagen($productoId, $colorId, $imagen);
                }
                
                // Registrar el stock asociado a cada color y talla
                if (isset($color['TALLAS'])) { // Verificar si el color tiene tallas asociadas
                    foreach ($color['TALLAS'] as $talla) {
                        $this->model->RegistrarStock($productoId, $colorId, $talla['ID_TALLA'], $talla['CANTIDAD']);
                    }
                }
            }
        }
    
        // Guardar el JSON actualizado con los IDs en el archivo principal
        file_put_contents($file, json_encode($productos_array, JSON_PRETTY_PRINT));
    
        // Leer el contenido actual del archivo de copia de seguridad
        if (file_exists($backupFile)) {
            $backupData = json_decode(file_get_contents($backupFile), true);
        } else {
            $backupData = [];
        }
    
        // Añadir los nuevos productos al contenido de la copia de seguridad
        $backupData = array_merge($backupData, $productos_array);
    
        // Guardar los datos actualizados en el archivo de copia de seguridad
        file_put_contents($backupFile, json_encode($backupData, JSON_PRETTY_PRINT));
    
        // Limpiar el archivo principal
        file_put_contents($file, '');
    
        echo "Datos guardados exitosamente.";
    }

    /**********************************************/
    //PARA REGISTRAR LOS COLORES
    /**********************************************/
    public function RegistroColor() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Verificación del token CSRF
            if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
                echo "Error: Solicitud no válida por token CSRF.";
                exit;
            }

            $COLOR = filter_input(INPUT_POST, 'COLOR', FILTER_SANITIZE_SPECIAL_CHARS);

            if ($COLOR !== false ) {
                // Llamar al modelo para registrar el producto
                $productoModel = new ProductsModel();
                $registroExitoso = $productoModel->registrarColor($COLOR);

                if ($registroExitoso) {
                    echo '<script>
                            alert("Registro exitoso del producto.");
                            window.location.href = "../View/Talla.php"; // Redirigir
                          </script>';
                } else {
                    echo "Error al registrar el producto.";
                }
            } else {
                echo "Error: Datos inválidos.";
            }
        }
    }

    /**********************************************/
    //PARA REGISTRAR LAS TALLAS
    /**********************************************/
    public function RegistroTalla() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Verificación del token CSRF
            if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
                echo "Error: Solicitud no válida por token CSRF.";
                exit;
            }

            $TALLA = filter_input(INPUT_POST, 'TALLA', FILTER_SANITIZE_STRING);

            if ($TALLA  !== false ) {
                // Llamar al modelo para registrar el producto
                $productoModel = new ProductsModel();
                $registroExitoso = $productoModel->registrarTalla( $TALLA);

                if ($registroExitoso) {
                    echo '<script>
                            alert("Registro exitoso del producto.");
                            window.location.href = "../View/Talla.php"; // Redirigir
                          </script>';
                } else {
                    echo "Error al registrar el producto.";
                }
            } else {
                echo "Error: Datos inválidos.";
            }
        }
    }
}

class ProductsController {

    /**********************************************/
    //PARA MOSTRAR TODO LOS PRODCUTOS
    /**********************************************/
    public function GetAllProducts() {
        // Obtener el ID del último producto desde el modelo
        $colorModel = new ProductsModel();
        $products = $colorModel->getAllProducts();
    
        // Retornar el ID del último producto
        return $products;
    }

    /**********************************************/
    //PARA MOSTRAR TODOS LOS COLORES
    /**********************************************/
    public function GetAllColors() {
        // Obtener el ID del último producto desde el modelo
        $colorModel = new ProductsModel();
        $colors = $colorModel->getAllColors();
    
        // Retornar el ID del último producto
        return $colors;
    }

    /**********************************************/
    //PARA MOSTRAR TODAS LAS TALLAS
    /**********************************************/
    public function GetAllSize() {
        // Obtener el ID del último producto desde el modelo
        $sizeModel = new ProductsModel();
        $sizes = $sizeModel->getAllSize();
    
        // Retornar el ID del último producto
        return $sizes;
    }

    /**********************************************/
    //PARA MOSTRAR LOS COLORES POR EL ID
    /**********************************************/
    public function GetColorById($id) {

        $productModel = new ProductsModel();
        $Color = $productModel->getColorsById($id);

        return $Color;
    }

    /**********************************************/
    //PARA MOSTRAR LA TALLA POR EL ID
    /**********************************************/
    public function GetSizeById($id) {

        $productModel = new ProductsModel();
        $Size = $productModel->getSizeById($id);

        return $Size;
    }

    /**********************************************/
    //PARA MOSTRAR INFORMACION DEL PRODUCTO POR EL COLOR
    /**********************************************/
    public function GetProductsByColor($colorid, $idpro) {

        $productModel = new ProductsModel();
        $product = $productModel->getProductsByColor($colorid, $idpro);

        return $product;
    }

    /**********************************************/
    //PARA MOSTRAR EL ULTIMO PRODUCTO
    /**********************************************/
    public function GetLastProducts() {
        // Obtener el ID del último producto desde el modelo
        $productModel = new ProductsModel();
        $lastProductId = $productModel->getLastProductId();
    
        // Retornar el ID del último producto
        return $lastProductId;
    }
    
    /**********************************************/
    //PARA MOSTRAR LOS PRODCUTOS POR CATEGORIA
    /**********************************************/
    public function CategoriesProducts($codigo) {

        $productModel = new ProductsModel();
        $product = $productModel->getAllLProductsByCategory($codigo);

        return $product;
    }
}
?>



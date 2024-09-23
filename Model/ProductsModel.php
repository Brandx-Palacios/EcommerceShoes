<?php
require_once(__DIR__ . '/../DataBase/DB.php');

class ProductsModel {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    /**********************************************/
    //PARA REGISTRAR EL PRODUCTO
    /**********************************************/

    public function RegistrarProducto($producto) {
        $conn = $this->db->getConnection(); // Obtener la conexión desde el objeto Database
        $sql = "INSERT INTO PRODUCTOS (NOMBRE, DESCRIPCION, SKU, PRECIO, DESCUENTO, ID_CATEGORIA) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssiii", $producto['NOMBRE'], $producto['DESCRIPCION'], $producto['SKU'], $producto['PRECIO'], $producto['DESCUENTO'], $producto['ID_CATEGORIA']);
        $stmt->execute();
        $productoId = $stmt->insert_id;
        $stmt->close();
    
        return $productoId;
    }
    
    /**********************************************/
    //PARA REGISTRAR LAS IMAGENES DEL PRODUCTO
    /**********************************************/

    public function RegistrarImagen($productoId, $colorId, $imagen) {
        $conn = $this->db->getConnection(); // Obtener la conexión desde el objeto Database
        $sql = "INSERT INTO IMAGENES_PRODUCTO (ID_PRODUCTO, ID_COLOR, IMAGEN_URL) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iis", $productoId, $colorId, $imagen);
        $stmt->execute();
        $stmt->close();
    }
    
    /**********************************************/
    //PARA REGISTRAR LAS CANTIDADES
    /**********************************************/
    public function RegistrarStock($productoId, $colorId, $tallaId, $cantidad) {
        $conn = $this->db->getConnection(); // Obtener la conexión desde el objeto Database
        $sql = "INSERT INTO STOCK_PRODUCTO (ID_PRODUCTO, ID_COLOR, ID_TALLA, CANTIDAD_INICIAL, CANTIDAD_ACTUAL) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iiiii", $productoId, $colorId, $tallaId, $cantidad, $cantidad);
        $stmt->execute();
        $stmt->close();
    }

    /**********************************************/
    //PARA REGISTRAR LOS COLORES
    /**********************************************/

    public function registrarColor($COLOR) {
        $conn = $this->db->getConnection();
        if (!$conn) {
            die("Error de conexión: " . mysqli_connect_error());
        }

        // Iniciar transacción
        mysqli_autocommit($conn, false);

        // Preparar y ejecutar la consulta
        $sqlProducto = "INSERT INTO colores_producto (COLOR) VALUES (?)";
        $stmtProducto = $conn->prepare($sqlProducto);
        $stmtProducto->bind_param("s", $COLOR);

        $resultProducto = $stmtProducto->execute();
        $stmtProducto->close();

        if ($resultProducto) {
            mysqli_commit($conn);
        } else {
            mysqli_rollback($conn);
        }

        $conn->close();
        return $resultProducto;
    }

    /**********************************************/
    //PARA REGISTRAR LAS TALLAS
    /**********************************************/

    public function registrarTalla($TALLA) {
        $conn = $this->db->getConnection();
        if (!$conn) {
            die("Error de conexión: " . mysqli_connect_error());
        }

        // Iniciar transacción
        mysqli_autocommit($conn, false);

        // Preparar y ejecutar la consulta
        $sqlProducto = "INSERT INTO tallas_producto (TALLA) VALUES (?)";
        $stmtProducto = $conn->prepare($sqlProducto);
        $stmtProducto->bind_param("s", $TALLA);

        $resultProducto = $stmtProducto->execute();
        $stmtProducto->close();

        if ($resultProducto) {
            mysqli_commit($conn);
        } else {
            mysqli_rollback($conn);
        }

        $conn->close();
        return $resultProducto;
    }

    /**********************************************/
    //PARA MOSTRAR TODOS LOS COLORES
    /**********************************************/
    public function getAllColors() {
        $db = new Database();
        $conn = $db->getConnection();
        $query = "SELECT * FROM colores_producto";
        
        $result = $conn->query($query);
        $colors = [];
    
        // Itera sobre todos los resultados
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $colors[] = $row;  
            }
        }
    
        return $colors;
    }

    /**********************************************/
    //PARA MOSTRAR TODAS LAS TALLAS
    /**********************************************/
    public function getAllSize() {
        $db = new Database();
        $conn = $db->getConnection();
        $query = "SELECT * FROM tallas_producto";
        
        $result = $conn->query($query);
        $talla = [];
    
        // Itera sobre todos los resultados
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $talla[] = $row;  
            }
        }
    
        return  $talla;
    }
    
    /**********************************************/
    //PARA MOSTRAR TODO LO DEL PRODUCTO, TALLA, IMAGENES, COLORES
    /**********************************************/
    public function getAllProducts(){
        // Consulta de Productos: Primero, obtienes todos los productos.
        // Consulta de Imágenes: Para cada producto, realizas una consulta separada para obtener las imágenes asociadas.
        // Consulta de Stock: Realizas otra consulta separada para obtener el stock del producto.
        // Organización de Datos: Añades las imágenes y el stock a la información del producto antes de agregarlo a la lista final.
        $db = new Database();
        $conn = $db->getConnection();
        
        // Consultar todos los productos
        $query = "SELECT * FROM PRODUCTOS";
        $result = $conn->query($query);
        $products = [];
    
        // Recorrer los resultados de productos
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $productId = $row['ID'];
                
                // Consultar las imágenes del producto
                $queryImages = "SELECT IMAGEN_URL FROM IMAGENES_PRODUCTO WHERE ID_PRODUCTO = ?";
                $stmtImages = $conn->prepare($queryImages);
                $stmtImages->bind_param("i", $productId);
                $stmtImages->execute();
                $resultImages = $stmtImages->get_result();
                
                $images = [];
                while ($imgRow = $resultImages->fetch_assoc()) {
                    $images[] = $imgRow['IMAGEN_URL'];
                }
                $stmtImages->close();
                
                // Consultar el stock del producto
                $queryStock = "SELECT ID_COLOR, ID_TALLA, CANTIDAD_INICIAL, CANTIDAD_ACTUAL FROM STOCK_PRODUCTO WHERE ID_PRODUCTO = ?";
                $stmtStock = $conn->prepare($queryStock);
                $stmtStock->bind_param("i", $productId);
                $stmtStock->execute();
                $resultStock = $stmtStock->get_result();
                
                $stock = [];
                while ($stkRow = $resultStock->fetch_assoc()) {
                    $stock[] = $stkRow;
                }
                $stmtStock->close();
                
                // Añadir la información de imágenes y stock al producto
                $row['IMAGENES'] = $images; // Asegúrate de usar la misma clave en el HTML
                $row['STOCK'] = $stock;
                
                $products[] = $row;
            }
            $result->free(); // Libera la memoria asociada con el conjunto de resultados
        }
        
        return $products;
    }

    /**********************************************/
    //PARA MOSTRAR EL COLOR POR EL ID
    /**********************************************/
    public function getColorsById($id) {
        $db = new Database();
        $conn = $db->getConnection();
        
        // Preparar la consulta para evitar inyección SQL
        $query = "SELECT * FROM colores_producto WHERE ID = ?";
        $stmt = $conn->prepare($query);
        
        // Enlazar el parámetro y ejecutar la consulta
        $stmt->bind_param("i", $id);
        $stmt->execute();
        
        // Obtener el resultado
        $result = $stmt->get_result();
        
        // Fetch todos los resultados
        $colors = [];
        while ($row = $result->fetch_assoc()) {
            $colors[] = $row;
        }
        
        // Cerrar la declaración y la conexión
        $stmt->close();
        
        return $colors;
    }

    /**********************************************/
    //PARA MOSTRAR LA TALLA POR EL ID
    /**********************************************/

    public function getSizeById($id) {
        $db = new Database();
        $conn = $db->getConnection();
        
        // Preparar la consulta para evitar inyección SQL
        $query = "SELECT TALLA FROM tallas_producto WHERE ID = ?";
        $stmt = $conn->prepare($query);
        
        // Enlazar el parámetro y ejecutar la consulta
        $stmt->bind_param("i", $id);
        $stmt->execute();
        
        // Obtener el resultado
        $result = $stmt->get_result();
        
        // Obtener el nombre de la talla (asumiendo que hay una fila)
        $tallaNombre = null;
        if ($row = $result->fetch_assoc()) {
            $tallaNombre = $row['TALLA']; // Obtener el nombre de la talla
        }
        
        // Cerrar la declaración y la conexión
        $stmt->close();
        
        return $tallaNombre; // Devolver el nombre de la talla como una cadena
    }
    
    /**********************************************/
    //PARA MOSTRAR LA INFORMACIÓN POR EL COLOR
    /**********************************************/
    public function getProductsByColor($colorId, $idpro) {
        // Inicializar la conexión a la base de datos
        $db = new Database();
        $conn = $db->getConnection();
        
        // Consultar las imágenes del producto para un color específico
        $queryImages = "SELECT IMAGEN_URL FROM IMAGENES_PRODUCTO WHERE ID_PRODUCTO = ? AND ID_COLOR = ?";
        $stmtImages = $conn->prepare($queryImages);
        $stmtImages->bind_param("ii", $idpro, $colorId);
        $stmtImages->execute();
        $resultImages = $stmtImages->get_result();
        
        $images = [];
        while ($imgRow = $resultImages->fetch_assoc()) {
            $images[] = $imgRow['IMAGEN_URL'];
        }
        $stmtImages->close();
        
        // Consultar el stock del producto para un color específico
        $queryStock = "SELECT ID_TALLA, CANTIDAD_INICIAL, CANTIDAD_ACTUAL FROM STOCK_PRODUCTO WHERE ID_PRODUCTO = ? AND ID_COLOR = ?";
        $stmtStock = $conn->prepare($queryStock);
        $stmtStock->bind_param("ii", $idpro, $colorId);
        $stmtStock->execute();
        $resultStock = $stmtStock->get_result();
        
        $stock = [];
        while ($stkRow = $resultStock->fetch_assoc()) {
            $stock[] = $stkRow;
        }
        $stmtStock->close();
        
        // Crear un array con la información del producto
        $productDetails = [
            'IMAGENES' => $images,
            'STOCK' => $stock
        ];
        
        // Cerrar la conexión
        $conn->close();
        
        return $productDetails;
    }
    
    /**********************************************/
    //PARA MOSTRAR TODOS LOS PRODUCTOS SIN IMAGEN
    /**********************************************/
    public function getProductDetails($idpro) {
        $db = new Database();
        $conn = $db->getConnection();
        
        $query = "SELECT * FROM productos WHERE ID = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $idpro);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $product = $result->fetch_assoc();
        
        $stmt->close();
        
        return $product;
    }

    /**********************************************/
    //PARA MOSTRAR LAS IMAGENES DEL PRODCUTO
    /**********************************************/
    public function getProductImages($idpro, $colorId) {
        $db = new Database();
        $conn = $db->getConnection();
        
        $query = "SELECT PATH FROM imagenes_producto WHERE ID_PRODUCTO = ? AND ID_COLOR = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ii", $idpro, $colorId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $images = [];
        while ($row = $result->fetch_assoc()) {
            $images[] = $row['PATH'];
        }
        
        $stmt->close();
        
        return $images;
    }
    
    /**********************************************/
    //PARA MOSTRAR LA CANTIDAD
    /**********************************************/
    public function getProductStock($idpro, $colorId) {
        $db = new Database();
        $conn = $db->getConnection();
        
        $query = "SELECT CANTIDAD FROM stock_producto WHERE ID_PRODUCTO = ? AND ID_COLOR = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ii", $idpro, $colorId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $stock = 0;
        if ($row = $result->fetch_assoc()) {
            $stock = $row['CANTIDAD'];
        }
        
        $stmt->close();
        
        return $stock;
    }
    
    /**********************************************/
    //PARA MOSTRAR TODO LO ANTERIOR, INFORMACIÓN DEL PRODUCTO
    /**********************************************/
    public function getProductInfo($idpro, $colorId) {
        $productDetails = $this->getProductDetails($idpro);
        $productImages = $this->getProductImages($idpro, $colorId);
        $productStock = $this->getProductStock($idpro, $colorId);
        
        $productInfo = [
            'ID' => $productDetails['ID'],
            'IMAGES' => $productImages,
            'CANTIDAD' => $productStock, 
            'TALLA' => $productStock
        ];
        
        return $productInfo;
    }
    
    


    
    
































































    // public function getLastProductId() {
    //     $db = new Database();
    //     $conn = $db->getConnection();
    //     $query = "SELECT ID FROM productos ORDER BY ID DESC LIMIT 1";
    
    //     $result = $conn->query($query);
    //     $lastProductId = null;
    
    //     if ($result && $row = $result->fetch_assoc()) {
    //         $lastProductId = $row['ID'];
    //     }
    
    //     return $lastProductId;
    // }
    

//     public function getAllLProductsByCategory($codigo) {
//         $db = new Database();
//         $conn = $db->getConnection();
//         $query = "SELECT productos.*, categorias.Nombre AS NombreCategoria 
//                   FROM productos 
//                   INNER JOIN categorias ON productos.idCategoria = $codigo";

//         $result = $conn->query($query);
    
//         $products = array();
//         while ($row = $result->fetch_assoc()) {
//             // Obtener las imágenes asociadas al producto
//             $imagenes = $this->getImagesByProductId($row['idProducto']);
//             $row['imagenes'] = $imagenes;

//             $products[] = $row;
//         }
    
//         return $products;
//     }

//     public function getImagesByProductId($idProducto) {
//         $conn = $this->db->getConnection();
//         $query = "SELECT RutaFoto FROM fotosproductos WHERE idProducto = ?";
//         $stmt = $conn->prepare($query);
//         $stmt->bind_param("i", $idProducto);
//         $stmt->execute();

//         $result = $stmt->get_result();

//         $imagenes = array();
//         while ($row = $result->fetch_assoc()) {
//             $imagenes[] = $row['RutaFoto'];
//         }

//         $stmt->close();

//         return $imagenes;
//     }
    

//     public function getProductsById($id) {
//         $db = new Database();
//         $conn = $db->getConnection();
    
//         // Utilizamos una sentencia preparada para evitar SQL injection
//         $query = "SELECT * FROM productos WHERE idProducto = ?";
    
//         // Preparamos la sentencia
//         $stmt = $conn->prepare($query);
    
//         // Bind de los parámetros
//         $stmt->bind_param("i", $id); // "i" indica que es un entero
    
//         // Ejecutamos la sentencia
//         $stmt->execute();
    
//         // Obtenemos el resultado
//         $result = $stmt->get_result();
    
//         // Verificamos si se encontró una categoría
//         if ($result->num_rows > 0) {
//             // Devolvemos el resultado
//             return $result->fetch_assoc();
//         } else {
//             // Si no se encontró ninguna categoría, devolvemos null o un valor indicativo
//             return null;
//         }
//     }
    
//     public function deleteProducts($idProducto) {
//         $db = new Database();
//         $conn = $db->getConnection();
//         // Obtener las imágenes asociadas al producto
//         $imagenes = $this->getImagesByProductId($idProducto);
    
//         // Eliminar las imágenes asociadas al producto
//         foreach ($imagenes as $imagen) {
//             $this->deleteImageFile($imagen);
//         }
    
//         $conn = $this->db->getConnection();
//         $idProducto = $conn->real_escape_string($idProducto);
//         $query = "DELETE FROM productos WHERE idProducto = $idProducto";
//         $result = $conn->query($query);
    
//         $conn->close();
    
//         return $result;
//     }
//     // Nueva función para eliminar una imagen específica asociada a un producto
//     public function deleteImage($idProducto, $imagen) {
//         $conn = $this->db->getConnection();
//         $idProducto = $conn->real_escape_string($idProducto);
//         $imagen = $conn->real_escape_string($imagen);
    
//         $query = "DELETE FROM fotosproductos WHERE idProducto = $idProducto AND RutaFoto = '$imagen'";
//         $result = $conn->query($query);
    
//         return $result;
//     }
    
//     public function deleteImageFile($imagePath) {
//     $imagePath = __DIR__ . '/../PicturesProducts/' . $imagePath; // Asegúrate de ajustar la ruta según tu estructura de archivos
//     if (file_exists($imagePath)) {
//         unlink($imagePath); // Eliminar el archivo
//     }
//     }

    


//     public function actualizarProduct($idProducto, $nombre, $descripcion, $sku, $precio, $descuento, $preciodes, $cuidados, $stock, $categoria, $Caracteristicas, $TiempoEntrega, $seccion, $Garantia, $pago, $fotos) {
//         $db = new Database();
//         $conn = $db->getConnection();
    
//         $idProducto = $conn->real_escape_string($idProducto);
//         $nombre = $conn->real_escape_string($nombre);
//         $descripcion = $conn->real_escape_string($descripcion);
//         $sku = $conn->real_escape_string($sku);
//         $precio = $conn->real_escape_string($precio);
//         $descuento = $conn->real_escape_string($descuento);
//         $preciodes = $conn->real_escape_string($preciodes);
//         $cuidados = $conn->real_escape_string($cuidados);
//         $stock = $conn->real_escape_string($stock);
//         $categoria = $conn->real_escape_string($categoria);
//         $Caracteristicas = $conn->real_escape_string($Caracteristicas);
//         $TiempoEntrega = $conn->real_escape_string($TiempoEntrega);
//         $seccion = $conn->real_escape_string($seccion);
//         $Garantia = $conn->real_escape_string($Garantia);
//         $pago = $conn->real_escape_string($pago);
    
//         // Actualizar el producto
//         $query = "UPDATE productos SET idCategoria = ?, Nombre = ?, DescripcionCorta = ?, SKU = ?, PorcentajeDescuento = ?, InventarioInicial = ?, Detal = ?, PrecioDescuento = ?, Garantia = ?, TipodePago = ?, Caracteristicas = ?, Cuidados = ?, TiempoEntrega = ?, Seccion = ? WHERE idProducto = ?";
//         $stmt = $conn->prepare($query);
//         $stmt->bind_param("isssiiiissssssi", $categoria, $nombre, $descripcion, $sku, $descuento, $stock, $precio, $preciodes, $Garantia, $pago, $Caracteristicas, $cuidados, $TiempoEntrega, $seccion, $idProducto);
//         $result = $stmt->execute();
    
//         if ($result) {
//             if (!empty($fotos)) {
//                 // Eliminar todas las fotos existentes si se proporcionan nuevas fotos
//                 $queryDelete = "DELETE FROM fotosproductos WHERE idProducto = ?";
//                 $stmtDelete = $conn->prepare($queryDelete);
//                 $stmtDelete->bind_param("i", $idProducto);
//                 $stmtDelete->execute();
//                 $stmtDelete->close();
    
//                 // Insertar las nuevas fotos
//                 $queryInsert = "INSERT INTO fotosproductos (RutaFoto, idProducto) VALUES (?, ?)";
//                 $stmtInsert = $conn->prepare($queryInsert);
//                 foreach ($fotos as $foto) {
//                     $stmtInsert->bind_param("si", $foto, $idProducto);
//                     $stmtInsert->execute();
//                 }
//                 $stmtInsert->close();
//             }
//         }
    
//         $stmt->close();
//         $conn->close();
    
//         return $result;
//     }
    
    
    
    
    
//     // En tu modelo ProductsModel.php
//     public function getAllProductsByCategory($idCategoria) {
//         $db = new Database();
//         $conn = $db->getConnection();
//         if (!$conn) {
//             die("Error de conexión: " . mysqli_connect_error());
//         }
//         /*$sql = "SELECT idProducto, Nombre, DescripcionCorta, SKU, PorcentajeDescuento, PrecioDetal, InventarioInicial
//         FROM productos
//         WHERE idCategoria = ?";*/
//         $sql = "SELECT *
//                 FROM productos
//                 WHERE idCategoria = ?";
//         $stmt = $conn->prepare($sql);
//         $stmt->bind_param("i", $idCategoria);
//         $stmt->execute();
    
//         if ($stmt->error) {
//             die("Error en la consulta: " . $stmt->error);
//         }
    
//         $result = $stmt->get_result();
    
//         $productos = array();
//         while ($row = $result->fetch_assoc()) {
//             $productos[] = $row;
//         }
    
//         $stmt->close();
//         $conn->close();
    
//         return $productos;
//     }
}

?>









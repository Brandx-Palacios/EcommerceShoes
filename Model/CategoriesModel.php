<?php
require_once(__DIR__ . '/../DataBase/DB.php');


class CategoriesModelReg {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    /**********************************************/
    //PARA REGISTRAR LA CATEGORIA
    /**********************************************/
    public function RegistrarCategories($Nombre, $descripcion, $fotos) {
        $Nombre = trim(filter_var($Nombre, FILTER_SANITIZE_STRING));
        $descripcion = trim(filter_var($descripcion, FILTER_SANITIZE_STRING));
        $conn = $this->db->getConnection();
        if (!$conn) {
            throw new Exception("Error de conexión: " . mysqli_connect_error());
        }
        $sql = "INSERT INTO categorias (NOMBRE, DESCRIPCION, IMAGEN) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);

        // Verifica si $foto es un array y conviértelo a cadena (por ejemplo, usando implode)
        if (is_array($fotos)) {
            $fotos = implode(",", $fotos); // Cambia ',' al delimitador apropiado para tu caso de uso
        }
        $stmt->bind_param("sss",$Nombre,  $descripcion, $fotos);

        $result = $stmt->execute();
        $stmt->close();
        $conn->close();

        return $result;
    }
}

class CategoriesModel {

    public function closeConnection() {
        if ($this->conn) {
            $this->conn->close();
        }
    }

    /**********************************************/
    //PARA MOSTRAR TODAS LAS CATEGORIAS
    /**********************************************/
    public function getAllCategories() {
        $db = new Database();
        $conn = $db->getConnection();
        $query = "SELECT * FROM categorias";
        $result = $conn->query($query);

        $categories = array();
        while ($row = $result->fetch_assoc()) {
            $categories[] = $row;
        }

        return $categories;
    }

    /**********************************************/
    //PARA MOSTRAR LAS CATEGORIAS POR ID
    /**********************************************/

    public function getCategoriesById($Cedula) {
        $db = new Database();
        $conn = $db->getConnection();
        
        $query = "SELECT * FROM categorias WHERE Propietario = ?";
        
        $stmt = $conn->prepare($query);
    
        // Un solo marcador de posición, por lo que un solo parámetro en bind_param
        $stmt->bind_param("i", $Cedula);
    
        $stmt->execute();
        $result = $stmt->get_result();
    
        $usuarios = array();
        while ($row = $result->fetch_assoc()) {
            $usuarios[] = $row;
        }
    
        return $usuarios;
    }

    /**********************************************/
    //PARA MOSTRAR LA CATEGOTIA POR ID
    /**********************************************/
    public function getCategoryById($id) {
        $db = new Database();
        $conn = $db->getConnection();
    
        // Utilizamos una sentencia preparada para evitar SQL injection
        $query = "SELECT * FROM categorias WHERE idCategoria = ?";
    
        // Preparamos la sentencia
        $stmt = $conn->prepare($query);
    
        // Bind de los parámetros
        $stmt->bind_param("i", $id); // "i" indica que es un entero
    
        // Ejecutamos la sentencia
        $stmt->execute();
    
        // Obtenemos el resultado
        $result = $stmt->get_result();
    
        // Verificamos si se encontró una categoría
        if ($result->num_rows > 0) {
            // Devolvemos el resultado
            return $result->fetch_assoc();
        } else {
            // Si no se encontró ninguna categoría, devolvemos null o un valor indicativo
            return null;
        }
    }
    
    /**********************************************/
    //PARA ELIMINAR LA CATEGORIA
    /**********************************************/

    public function deleteCategory($id) {
        $db = new Database();
        $conn = $db->getConnection();

        // Comienza una transacción
        $conn->begin_transaction();

        try {
            // Elimina las fotos asociadas a productos de la categoría
            $this->deleteCategoryPhotos($id, $conn);

            // Elimina los productos de la categoría
            $this->deleteCategoryProducts($id, $conn);

            // Elimina la categoría
            $this->deleteCategoryRow($id, $conn);

            // Confirma la transacción
            $conn->commit();
        } catch (Exception $e) {
            // Si hay algún error, realiza un rollback
            $conn->rollback();
            echo "Error: " . $e->getMessage();
        } finally {
            // Cierra la conexión
            $conn->close();
        }
    }

    /**********************************************/
    //PARA ELIMINAR LA FOTO DE LA CATEGORIA
    /**********************************************/
    private function deleteCategoryPhotos($id, $conn) {
        $query = "DELETE FROM fotosproductos WHERE idProducto IN (SELECT idProducto FROM productos WHERE idCategoria = $id)";
        $conn->query($query);
    }

    /**********************************************/
    //PARA ELIMINAR LOS PRODUCTOS DE LA CATEGORIA
    /**********************************************/
    private function deleteCategoryProducts($id, $conn) {
        $query = "DELETE FROM productos WHERE idCategoria = $id";
        $conn->query($query);
    }

    /**********************************************/
    //PARA ELIMINAR LA CATEGORIA EN ESPECIFICO
    /**********************************************/
    private function deleteCategoryRow($id, $conn) {
        $query = "DELETE FROM categorias WHERE idCategoria = $id";
        $conn->query($query);
    }

    /**********************************************/
    //PARA ACTUALUZAR LA CATEGORIA
    /**********************************************/
    public function actualizarCategory($Nombre, $idCategoria, $fotos) {
        // Asegúrate de tener una conexión a la base de datos configurada y almacenada en la variable $conn.
        $db = new Database();
        $conn = $db->getConnection();
        // Escapa los valores para evitar inyecciones SQL (usando sentencias preparadas es aún mejor).
        $idCategoria = $conn->real_escape_string($idCategoria);
        $Nombre = $conn->real_escape_string($Nombre);

        // Convertir el array de fotos a una cadena separada por comas
        $foto = implode(",", $fotos);
        // Preparar la sentencia SQL con marcadores de posición
        $query = "UPDATE categorias SET Nombre = ?, Foto = ? WHERE idCategoria = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssi", $Nombre,$foto, $idCategoria);


        // Ejecutar la sentencia preparada
        $result = $stmt->execute();

        // Cerrar la sentencia preparada
        $stmt->close();

        // Cerrar la conexión
        $conn->close();

        // Devolver el resultado de la operación
        return $result;
    }

    /**********************************************/
    //PARA MOSTRAR LOS PRODUCTOS POR CATEGORIA
    /**********************************************/
    public function getAllProductsByCategory($idCategoria) {
        $db = new Database();
        $conn = $db->getConnection();
        if (!$conn) {
            die("Error de conexión: " . mysqli_connect_error());
        }
    
        $sql = "SELECT *
                FROM productos
                WHERE idCategoria = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $idCategoria);
        $stmt->execute();
    
        if ($stmt->error) {
            die("Error en la consulta: " . $stmt->error);
        }
    
        $result = $stmt->get_result();
    
        $productos = array();
        while ($row = $result->fetch_assoc()) {
            $productos[] = $row;
        }
    
        $stmt->close();
        $conn->close();
    
        return $productos;
    }
    
}

?>
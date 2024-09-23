<?php require_once(__DIR__ . '/../Controller/ProductsController.php');

$productId = isset($_GET['productId']) ? intval($_GET['productId']) : 0;
$colorId = isset($_GET['colorId']) ? intval($_GET['colorId']) : 0;

$controllerProducts = new ProductsController();
$productDetails = $controllerProducts->GetProductsByColor($colorId, $productId);

$response = [];

// Imágenes
foreach ($productDetails['IMAGENES'] as $image) {
    $response['IMAGENES'][] = [
        'IMAGE_PATH' => $image
    ];
}

// Stock
foreach ($productDetails['STOCK'] as $stock) {
    // Obtener el nombre de la talla utilizando el método GetSizeById
    $tallaNombre = $controllerProducts->GetSizeById($stock['ID_TALLA']);
    
    
    $response['STOCK'][] = [
        'SIZE' => $tallaNombre, // Aquí utilizas el nombre en lugar del ID
        'INITIAL_QUANTITY' => $stock['CANTIDAD_INICIAL'],
        'CURRENT_QUANTITY' => $stock['CANTIDAD_ACTUAL']
    ];
}

header('Content-Type: application/json');
echo json_encode($response);
?>
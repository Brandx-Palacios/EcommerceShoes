<?php
require_once(__DIR__ . '/../../Model/CartModel.php');
require_once(__DIR__ . '/../../Controller/CompanyController.php');
require_once(__DIR__ . '/../../DataBase/DB.php');
require "./code128.php";	# Incluyendo librerias necesarias #
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['verPDF'])) {
	$Codigo = $_POST['Codigo'];
	
	// Obtén los datos del usuario a editar desde el modelo
	$ordersModel = new OrdersModel();
	$orderDetails = $ordersModel->getOrdersById($Codigo);

	$pdf = new PDF_Code128('P','mm','Letter');
	$pdf->SetMargins(17,17,17);
	$pdf->AddPage();
	// Crear una instancia de la clase Database
	$db = new Database();
	// Consulta SQL para obtener las imágenes del producto
	$sql = "SELECT * FROM empresa";
	$result = $db->getConnection()->query($sql); // Ejecutar la consulta
	$pdf->SetFont('Arial','',10);
	$pdf->Cell(150,7,iconv("UTF-8", "ISO-8859-1",""),0,0);
	$pdf->SetTextColor(97,97,97);
	// Establecer la zona horaria a Colombia
	date_default_timezone_set('America/Bogota');

	// Obtener la fecha y hora actual en el formato deseado (día/mes/año hora:minutos AM/PM)
	$currentDateTime = date("d/m/Y h:i A");

	// Mostrar la fecha y hora actual en el PDF
	$pdf->Cell(116, 7, iconv("UTF-8", "ISO-8859-1", $currentDateTime), 0, 0, 'L');
	$pdf->Ln(9);
	// Ruta de la carpeta que contiene las imágenes
	if ($result && $result->num_rows > 0) {
		while ($row = $result->fetch_assoc()) {
			$nombre = $row['Nombre'];
			$logo = $row['logo']; // Supongo que 'logo' es el nombre del campo en tu base de datos que contiene el nombre del archivo de la imagen

			// Construye la ruta completa de la imagen
			$rutaImagen = "../../PicturesCompany/" . $logo;

			# Logo de la empresa formato png #
			$pdf->Image($rutaImagen, 165, 25, 35, 35, 'PNG');
			# Encabezado y datos de la empresa #
			$pdf->SetFont('Arial','B',16);
			$pdf->SetTextColor(0,0,0);
			//$pdf->Cell(150,10,iconv("UTF-8", "ISO-8859-1",strtoupper($company['Nombre'])),0,0,'L');
			$pdf->Cell(35, 7, iconv("UTF-8", "ISO-8859-1", strtoupper($nombre)), 0, 0, 'L');

			$pdf->Ln(9);

			$pdf->SetFont('Arial','',10);
			$pdf->SetTextColor(39,39,51);
			$pdf->Cell(60,7,iconv("UTF-8", "ISO-8859-1","NIT: " .$row['Nit']),0,0,'L');

			$pdf->Ln(5);

			$pdf->Cell(60,7,iconv("UTF-8", "ISO-8859-1","Dirección: " .$row['Direccion']),0,0,'L');

			$pdf->Ln(5);

			$pdf->Cell(60,7,iconv("UTF-8", "ISO-8859-1","Celular: " .$row['Telefono']),0,0,'L');

			$pdf->Ln(5);

			$pdf->Cell(60,7,iconv("UTF-8", "ISO-8859-1","Email: " .$row['CorreoElectronico']),0,0,'L');
			
			$pdf->Ln(9);
		}
	}



	$pdf->SetFont('Arial','B',10);
	$pdf->SetTextColor(39,39,51);

	$numeroFactura = $orderDetails['Codigo'];

	// Construir el texto completo de la factura
	$textoFactura = "Factura Nro. " . $numeroFactura;

	// Mostrar el texto de la factura en el PDF
	$pdf->Cell(180, 7, iconv("UTF-8", "ISO-8859-1", strtoupper($textoFactura)), 0, 0, 'C');

	#pedido#
	$pdf->Ln(10);

	//DETALLES DEL CLIENTE
	$pdf->SetFont('Arial','',10);
	$pdf->SetTextColor(39,39,51);
	$pdf->Cell(15,7,iconv("UTF-8", "ISO-8859-1","Nombre:"),0,0);
	$pdf->SetTextColor(97,97,97);
	$pdf->Cell(60,7,iconv("UTF-8", "ISO-8859-1", $orderDetails['Nombre']), 0, 0, 'L');
	$pdf->SetTextColor(39,39,51);
	$pdf->Cell(15,7,iconv("UTF-8", "ISO-8859-1","Apellido:"),0,0);
	$pdf->SetTextColor(97,97,97);
	$pdf->Cell(60,7,iconv("UTF-8", "ISO-8859-1", $orderDetails['Apellidos']), 0, 0, 'L');
	$pdf->SetTextColor(39,39,51);
	$pdf->Cell(8,7,iconv("UTF-8", "ISO-8859-1","CC: "),0,0,'L');
	$pdf->SetTextColor(97,97,97);
	$pdf->Cell(60,7,iconv("UTF-8", "ISO-8859-1",$orderDetails['Cedula']),0,0,'L');
	$pdf->SetTextColor(39,39,51);

	$pdf->Ln(7);
	$pdf->Cell(7,7,iconv("UTF-8", "ISO-8859-1","Tel:"),0,0,'L');
	$pdf->SetTextColor(97,97,97);
	$pdf->Cell(72,7,iconv("UTF-8", "ISO-8859-1",$orderDetails['Celular']),0,0,'L');
	$pdf->SetTextColor(97,97,97);
	$pdf->Cell(7,7,iconv("UTF-8", "ISO-8859-1","Dir:"),0,0);
	$pdf->SetTextColor(97,97,97);
	$pdf->Cell(50,7,iconv("UTF-8", "ISO-8859-1",$orderDetails['Direccion']),0,0,'L');
	$pdf->SetTextColor(97,97,97);
	$pdf->Cell(13,7,iconv("UTF-8", "ISO-8859-1","Email:"),0,0);
	$pdf->SetTextColor(97,97,97);
	$pdf->Cell(60,7,iconv("UTF-8", "ISO-8859-1", $orderDetails['CorreoElectronico']), 0, 0, 'L');
	$pdf->SetTextColor(39,39,51);

	$pdf->Ln(9);

	# Tabla de productos #
	$pdf->SetFont('Arial','',8);
	$pdf->SetFillColor(211,211,211);
	$pdf->SetDrawColor(211,211,211);
	$pdf->SetTextColor(0,0,0); // Establecer color de texto a negro

	// Encabezados de la tabla
	//$pdf->Cell(20, 20, iconv("UTF-8", "ISO-8859-1", ""), 1, 0, 'C', true);
	$pdf->Cell(80, 12, iconv("UTF-8", "ISO-8859-1", "Nombre"), 1, 0, 'C', true);
	$pdf->Cell(20, 12, iconv("UTF-8", "ISO-8859-1", "SKU"), 1, 0, 'C', true);
	$pdf->Cell(20, 12, iconv("UTF-8", "ISO-8859-1", "Garantía"), 1, 0, 'C', true);
	$pdf->Cell(15, 12, iconv("UTF-8", "ISO-8859-1", "Cantidad"), 1, 0, 'C', true);
	$pdf->Cell(20, 12, iconv("UTF-8", "ISO-8859-1", "Descuento"), 1, 0, 'C', true);
	$pdf->Cell(30, 12, iconv("UTF-8", "ISO-8859-1", "Precio"), 1, 0, 'C', true);

	$pdf->Ln(); // Salto de línea después de encabezados

	$products = new OrdersModel();  
	$product = $products->getOrdersByProductId($Codigo); 
	$imagenModelo = new OrdersModel();         

	// Contenido de la tabla
	foreach ($product as $pro) {
		//$codigopro = $pro['Codigo'];
		//$imagenes = $imagenModelo->getImagesByProductId($codigopro);

		// Mostrar la primera imagen en la celda con tamaño fijo
		//if (!empty($imagenes)) {
			//$firstImage = $imagenes[0]; // Obtener la primera imagen
			//$imagePath = '../../PicturesOrders/' . $firstImage;

			// Insertar la imagen con tamaño específico (50x50 puntos)
			//$pdf->Cell(20, 20, $pdf->Image($imagePath, $pdf->GetX(), $pdf->GetY(), 20, 20), 1, 0, 'C', false);
		//} else {
			// Si no hay imagen, mostrar una celda vacía
			//$pdf->Cell(50, 20, '', 1, 0, 'C', false);
		//}

		// Resto de las columnas
		$pdf->Cell(80, 10, iconv("UTF-8", "ISO-8859-1", $pro['Nombre']), 'B', 0, 'C');
		$pdf->Cell(20, 10, iconv("UTF-8", "ISO-8859-1", $pro['SKU']), 'B', 0, 'C');
		$pdf->Cell(20, 10, iconv("UTF-8", "ISO-8859-1", $pro['Garantia']), 'B', 0, 'C');
		$pdf->Cell(15, 10, iconv("UTF-8", "ISO-8859-1", $pro['Cantidad']), 'B', 0, 'C');
		$pdf->Cell(20, 10, iconv("UTF-8", "ISO-8859-1", $pro['PorcentajeDescuento'].'%'), 'B', 0, 'C');
		$pdf->Cell(30, 10, iconv("UTF-8", "ISO-8859-1", "$ " . number_format($pro['PrecioDescuento'], 0)), 'B', 0, 'C');

		$pdf->Ln(); // Salto de línea después de cada fila
	}

	/*----------  Fin Detalles de la tabla  ----------*/


	
	$pdf->SetFont('Arial','B',9);
	


	$pdf->Ln(7);

	$pdf->Cell(100,7,iconv("UTF-8", "ISO-8859-1",''),'',0,'C');
	$pdf->Cell(15,7,iconv("UTF-8", "ISO-8859-1",''),'',0,'C');
	$pdf->Cell(44,7,iconv("UTF-8", "ISO-8859-1","SUBTOTAL"),'',0,'C');
	$pdf->Cell(23,7,iconv("UTF-8", "ISO-8859-1","$ " . number_format($orderDetails['SubTotal'], 0)),'',0,'C');


	$pdf->Ln(7);

	$pdf->Cell(100,7,iconv("UTF-8", "ISO-8859-1",''),'',0,'C');
	$pdf->Cell(15,7,iconv("UTF-8", "ISO-8859-1",''),'',0,'C');
	$pdf->Cell(44,7,iconv("UTF-8", "ISO-8859-1","ENVÍO"),'',0,'C');
	$pdf->Cell(23,7,iconv("UTF-8", "ISO-8859-1","$ " . number_format($orderDetails['PrecioEnvio'], 0)),'',0,'C');

	$pdf->Ln(7);
	$pdf->Cell(100,7,iconv("UTF-8", "ISO-8859-1",''),'',0,'C');
	$pdf->Cell(15,7,iconv("UTF-8", "ISO-8859-1",''),'',0,'C');


	$pdf->Cell(44,7,iconv("UTF-8", "ISO-8859-1","TOTAL"),'T',0,'C');
	$pdf->Cell(23,7,iconv("UTF-8", "ISO-8859-1","$ " . number_format($orderDetails['TotalCompra'], 0)),'T',0,'C');

	$pdf->Ln(7);
	$pdf->Ln(12);

	$pdf->SetFont('Arial','',9);

	
	$pdf->SetTextColor(39,39,51);
	$pdf->Ln(70);
	$pdf->MultiCell(0,9,iconv("UTF-8", "ISO-8859-1","*** Precios de productos incluyen impuestos. Para poder realizar un reclamo o devolución debe de presentar esta factura ***"),0,'C',false);

	# Nombre del archivo PDF #
	$numeroFactura = $orderDetails['Codigo'];

	// Construir el texto completo de la factura
	$textoFactura = "Factura Nro. " . $numeroFactura;
	$pdf->Output("I","Factura",true);

	
}
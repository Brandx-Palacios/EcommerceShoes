<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Formulario de Productos</title>

    <!-- Agrega los estilos CSS del segundo código -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <base href="/View/">
    <link rel="stylesheet" href="css/Footer.css">
    <link rel="stylesheet" href="css/Header.css">
    <link rel="stylesheet" href="css/StyleImageSelector.css">
    <link rel="stylesheet" href="css/StylesProducts.css">
        <link rel="icon" href="images/Zyers.ico" type="image/x-icon">

    <!-- Agrega esta referencia en el encabezado de tu HTML -->
    <!-- Agrega estas líneas en el encabezado de tu HTML -->
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css" />
    <link rel="stylesheet" type="text/css"
        href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css" />
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
</head>

<body>
    <?php
        require 'Layout/Header.php';
    ?>

    <section class="content">
        <main>
            <div class="head-title">
                <div class="left">
                    <h1>Registrar Producto</h1>
                    <ul class="breadcrumb">
                        <li>
                            <a href="/panel-de-control" class="active">Inicio</a>
                        </li>
                        <i class="fas fa-chevron-right"></i>
                        <li>
                            <a>Registrar Producto</a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="table-data">
                <div class="order">
                    <div class="head">
                        <h3>Tú Producto</h3>
                    </div>
                    <center>
                        <!-- Contenedor para previsualización de imágenes del formulario de producto -->
                        <div id="image-preview-form">
                            <div class="container">
                                <div class="img_container">
                                    <img src="../Pictures/producto.png" alt="" class="main_img">
                                </div>

                                <div class="thumbnail_container">
                                    <!-- Contenedor de miniaturas -->
                                </div>
                            </div>
                        </div>
                    </center>

                </div>

                <div class="todo">
                    <div class="head">
                        <h3>Información</h3>
                    </div>
                    <form class="form" action="Uo.php" method="POST" enctype="multipart/form-data">
                        <div class="list-container">
                            <div class="column">
                                <ul class="todo-list">
                                    <li class="not-completed">
                                        <p>
                                            <input type="hidden" name="idProducto" value="">
                                            <input type="hidden" name="idCategoria" value="">
                                            <label for="nombre">Nombre del Producto:</label>
                                            <input type="text" name="nombre" required>
                                        </p>
                                    </li>
                                    <li class="not-completed">
                                        <p>
                                            <label for="descripcion">Descripción Corta:</label>
                                            <textarea name="descripcion" rows="2"></textarea>
                                        </p>
                                    </li>
                                    <li class="not-completed">
                                        <p>
                                            <label for="sku">SKU:</label>
                                            <input type="text" name="sku" required>
                                        </p>
                                    </li>
                                    <li class="not-completed">
                                        <p>
                                            <label for="precio">Precio:</label>
                                            <input type="number" id="precio" name="precio" step="0.01" min="0" oninput="calcularDescuento()" required>
                                        </p>
                                    </li>
                                    <li class="not-completed">
                                        <p>
                                            <label for="descuento">Descuento del producto (%):</label>
                                            <input type="number" id="descuento" name="descuento" step="0.01" min="0" value="0" oninput="calcularDescuento()">
                                        </p>
                                    </li>
                                    <li class="not-completed">
                                        <p>
                                            <label for="precioDesc">Precio con Descuento:</label>
                                            <input readonly id="precioDesc" type="number" name="precioDesc" step="0.01" min="0">
                                            
                                        </p>
                                    </li>
                                    <script>
                                        function calcularDescuento() {
                                            var precioOriginal = parseFloat(document.getElementById("precio").value) || 0;
                                            var porcentajeDescuento = parseFloat(document.getElementById("descuento").value) || 0;
                                        
                                            // Calculamos el monto del descuento
                                            let descuento = (precioOriginal * porcentajeDescuento) / 100;
                                        
                                            // Calculamos el precio con descuento
                                            let precioConDescuento = precioOriginal - descuento;
                                        
                                            // Mostramos el resultado en el campo de precio con descuento
                                            document.getElementById("precioDesc").value = precioConDescuento.toFixed(0); // Redondear a 2 decimales
                                        }
                                    </script>
                                    
                                    <li class="not-completed">
                                        <p>
                                            
                                            <label for="cuidados">Cuidados del Producto:</label>
                                            <input type="text" name="cuidados">
                                        </p>
                                    </li>

                                </ul>
                            </div>

                            <div class="column">
                                <ul class="todo-list">
                                    <li class="not-completed">
                                        <p>
                                            <label for="inventario">Stock:</label>
                                            <input type="number" name="inventario" min="0" required>
                                        </p>
                                    </li>
                                    <li class="not-completed">
                                        <p>
                                            <label for="garantia">Garantia:</label>
                                            <input type="text" name="garantia" required>
                                        </p>
                                    </li>
                                    <li class="not-completed">
                                        <p>
                                            <label for="tipo_servicio">Categoría:</label>
                                            <select name="idCategoria" required>
                                                <?php
                                                    require_once(__DIR__ . '/../DataBase/DB.php');
                                                    require_once(__DIR__ . '/../Model/CategoriesModel.php');

                                                    // Obtén la conexión a la base de datos
                                                    $db = new Database();
                                                    $conn = $db->getConnection();

                                                    // Obtén las categorías desde tu modelo de categorías
                                                    $categoriesModel = new CategoriesModel();
                                                    $categorias = $categoriesModel->getAllCategories($conn); // Ajusta esto según tu implementación

                                                    // Itera sobre las categorías y crea las opciones del menú desplegable
                                                    foreach ($categorias as $categoria) {
                                                        echo '<option value="' . $categoria['idCategoria'] . '">' . $categoria['Nombre'] . '</option>';
                                                    }

                                                    // Cierra la conexión después de usarla
                                                    $conn->close();
                                                ?>
                                            </select>
                                        </p>
                                    </li>
                                    
                                    <li class="not-completed">
                                        <p>
                                            <label for="pago">Tipo de Pago:</label>
                                            <select id="pago" name="pago" required>
                                                <option value="Pagos Contraentrega">Pagos en contraentrega</option>
                                                <option value="Pagos por: (Nequi, Daviplata, Bancolombia, Lulo Bank)">Pagos por: (Nequi, Daviplata, Bancolombia, Lulo Bank)</option>
                                            </select>

                                        </p>

                                    </li>
                                    <li class="not-completed">
                                        <p>
                                           
                                            <label for="caracteristicas">Caracteristicas del Producto:</label>
                                            <input type="text" name="caracteristicas">
                                        </p>
                                    </li>
                                    <li class="not-completed">
                                        <p>
                                            
                                            <label for="tiempoentrega">Tiempo de entrega:</label>
                                            <select id="tiempoentrega" name="tiempoentrega" required>
                                                <option value="1 día hábil">1 día hábil</option>
                                                <option value="2 días hábiles">2 días hábiles</option>
                                                <option value="3 días hábiles">3 días hábiles</option>
                                                <option value="4 días hábiles">4 días hábiles</option>
                                                <option value="5 días hábiles">5 días hábiles</option>
                                                <option value="6 días hábiles">6 días hábiles</option>
                                                <option value="7 días hábiles">7 días hábiles</option>
                                                <option value="Más de 7 días hábiles">Más de 7 días hábiles</option>
                                                <option value="1 mes">1 mes</option>
                                                <option value="2 meses">2 meses</option>
                                                <option value="3 meses">3 meses</option>
                                                <option value="4 meses">4 meses</option>
                                                <option value="5 meses">5 meses</option>
                                                <option value="6 meses">6 meses</option>
                                                <option value="Más de 6 meses">Más de 6 meses</option>
                                            </select>

                                        </p>
                                    </li>
                                    <li class="not-completed">
                                        <p>
                                            
                                        <label for="secciones">¿Quieres que este producto se vea en la pagina principal?:</label>
                                                <select id="opciones" onchange="toggleOptions()">
                                                    <option value="si">Sí</option>
                                                    <option value="no" selected>No</option>
                                                </select>

                                                <div id="sectionSelect" style="display: none;">
                                                    <h3>Seleccione la sección:</h3>
                                                    <select id="seccionSelect" onchange="toggleLinkVisibility()" name="secciones">
                                                    <option value="">Escojer Sección</option>
                                                    <option value="Seccion1">Sección 1</option>
                                                    <option value="Seccion2">Sección 2</option>
                                                </select>
                                                </div>
                                        </p>
                                    </li>
                                    <li class="not-completed">
                                        <p>
                                            <label for="fotos">Imágenes:</label>
                                            <input type="file" name="fotos[]" accept="image/*" multiple
                                                onchange="previewImages(event, '#image-preview-form')">
                                        </p>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <input type="hidden" name="accionesProductos" value="registrarProducto">
                        <input type="submit" value="Registrar Producto" name="registrarProducto"
                            class="btn-register-user">
                    </form>

                    <form action="/todos-los-productos">
                        <input type="submit" value="Ver todos los productos registrados" name=""
                            class="btn-register-user">
                    </form>
                </div>
            </div>
        </main>
    </section>
    <!-- Script para previsualización de imágenes del formulario de producto -->
    <script>
            function toggleOptions() {
                var selectValue = document.getElementById("opciones").value;
                var sectionSelect = document.getElementById("sectionSelect");
                var linkParaSection1 = document.getElementById("linkParaSection1");

                if (selectValue === "si") {
                    sectionSelect.style.display = "block";
                } else {
                    sectionSelect.style.display = "none";
                    linkParaSection1.style.display = "none"; // Ocultar el enlace cuando No está seleccionado
                }
            }

            function toggleLinkVisibility() {
                var sectionValue = document.getElementById("seccionSelect").value;
                var linkParaSection1 = document.getElementById("linkParaSection1");
                var linkParaSection2 = document.getElementById("linkParaSection2");

                // Ocultar todas las imágenes al cambiar de sección
                linkParaSection1.style.display = "none";
                linkParaSection2.style.display = "none";

                // Mostrar la imagen correspondiente a la sección seleccionada
                if (sectionValue === "1") {
                    linkParaSection1.style.display = "block";
                } else if (sectionValue === "2") {
                    linkParaSection2.style.display = "block";
                }
            }

            // Función para ampliar la imagen
            function expandImage(imageId) {
                var image = document.getElementById(imageId);
                // Cambiar el tamaño de la imagen al doble
                image.style.width = "200%";
                image.style.height = "200%";
                // Hacer clic en la imagen para reducir su tamaño
                image.setAttribute("onclick", "reduceImage('" + imageId + "')");
            }

            // Función para reducir la imagen al tamaño original
            function reduceImage(imageId) {
                var image = document.getElementById(imageId);
                // Restablecer el tamaño original de la imagen
                image.style.width = "";
                image.style.height = "";
                // Hacer clic en la imagen para ampliarla
                image.setAttribute("onclick", "expandImage('" + imageId + "')");
            }
            </script>
    <script>
    function previewImages(event, querySelector) {
        const input = event.target;
        const imagePreviewContainer = document.querySelector(querySelector);

        if (!input.files || input.files.length === 0) {
            imagePreviewContainer.innerHTML = ''; // Limpiar el contenedor de previsualización
            return;
        }

        imagePreviewContainer.innerHTML = ''; // Limpiar el contenedor de previsualización

        for (let i = 0; i < input.files.length; i++) {
            const file = input.files[i];
            const objectURL = URL.createObjectURL(file);

            const img = document.createElement('img');
            img.src = objectURL;

            imagePreviewContainer.appendChild(img);
        }
    }
    </script>

    <script>
    function previewImages(event, previewContainer) {
        var files = event.target.files;
        var thumbnailContainer = $(previewContainer).find('.thumbnail_container');
        var imgContainer = $(previewContainer).find('.img_container');
        thumbnailContainer.empty(); // Limpiar miniaturas anteriores

        for (var i = 0; i < files.length; i++) {
            var reader = new FileReader();

            reader.onload = function(e) {
                var thumbnail = $('<img>').attr('src', e.target.result).addClass('thumbnail');
                thumbnail.click(function() {
                    imgContainer.find('.main_img').attr('src', e.target.result);
                    thumbnailContainer.find('.thumbnail').removeClass('active');
                    thumbnail.addClass('active');
                });
                thumbnailContainer.append(thumbnail);

                if (i === 0) {
                    // Mostrar la primera imagen como imagen principal
                    imgContainer.find('.main_img').attr('src', e.target.result);
                    thumbnail.addClass('active');
                }
            };

            reader.readAsDataURL(files[i]);
        }
    }
    </script>
    <script src="js/app.js"></script>

</body>

</html>
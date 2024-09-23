<!-- HTML -->

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <!-- CSS files -->

    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/Footer.css">
    <link rel="stylesheet" href="css/Header.css">
    <link rel="stylesheet" href="css/StylesCategories.css">
    <link rel="icon" href="images/Zyers.ico" type="image/x-icon">

    <title>Categorías</title>
</head>

<body>

    <?php
    require '../View/Layout/Header.php';
    session_start();
    // Generar el token CSRF y almacenarlo en la sesión
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    ?>

    <section class="content">
        <main>
        <div class="head-title">
                <div class="left">
                    <h1>Categorias</h1>
                    <ul class="breadcrumb">
                        <li>
                            <a href="/panel-de-control" class="active">Inicio</a>
                        </li>
                        <i class="fas fa-chevron-right"></i>
                        <li>
                            <a>Categorias</a>
                        </li>
                    </ul>
                </div>
            </div>
            <section class="categories-section">
                <form class="form" action="Uo.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">

                    <label>
                        <input type="text" placeholder="Nombre" name="nombres" id="nombres" class="input-register"
                            required>
                    </label>
                    <label>
                        <input type="text" placeholder="Descripcion" name="Descripcion" id="nombres" class="input-register"
                            required>
                    </label>
                    <label for="foto">
                        <input onchange="previewImage(event, '#imgPreview')" type="file" name="fotos" class="foto"
                            id="add-new-photo">
                        <center><img id="imgPreview" class="imgPreview"></center>
                    </label>

                    <input type="hidden" name="accionesCategories" value="RegistrarCategories">
                    <input type="submit" value="Registrar" name="RegistrarCategories" class="btn-register-user">
                </form>

                
            </section>
        </main>
    </section>

    <script src="../View/js/app.js"></script>
    <style>
        .warning-icon {
            color: #ff8c00; /* Cambia el color según tus preferencias */
            margin-right: 5px;
        }
    </style>
    <script>
    function previewImage(event, querySelector) {
        const input = event.target;
        $imgPreview = document.querySelector(querySelector);

        if (!input.files.length) return

        file = input.files[0];
        objectURL = URL.createObjectURL(file);

        $imgPreview.src = objectURL;
    }


    function confirmarEliminacion()  {
        // Icono de advertencia Unicode: ⚠
        var warningIcon = "\u26A0";
        return confirm(warningIcon + " ¿Estás seguro de que quieres eliminar a esta categoría? Recuerda que si tienes productos en esta categoria, se eliminaran todos los productos asociados a esta categoria");
    }

    




    const cards = document.querySelectorAll('.category-card');

    cards.forEach(card => {
        card.addEventListener('mouseenter', () => {
            card.style.transform = 'translateY(-5px)';
        });

        card.addEventListener('mouseleave', () => {
            card.style.transform = 'translateY(0)';
        });
    });
    </script>
        <script>
        $(document).ready(function () {
            // Función de búsqueda en tiempo real
            $("#search").on("keyup", function () {
                var value = $(this).val().toLowerCase();
                $("#categories-container .categories").filter(function () {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });
        });
    </script>

</body>

</html>
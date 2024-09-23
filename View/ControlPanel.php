<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <title>Panel de Administrador | E-Comerce</title>

    <!--font awesome-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

    <!--css file-->

    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/Footer.css">
    <link rel="stylesheet" href="css/Header.css">
    <link rel="icon" href="images/Zyers.ico" type="image/x-icon">

    <style>
        @keyframes parpadeo {
            0% {
                color: gray;
            }

            50% {
                color: red;
            }

            100% {
                color: gray;
            }
        }

        .fa-lightbulb {
            animation: parpadeo 1.5s infinite alternate;
        }

        @keyframes girar-lenta {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        .fa-clock-hour {
            animation: girar-lenta 100s linear infinite;
        }

        @keyframes pulso {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.1);
            }

            100% {
                transform: scale(1);
            }
        }

        .fa-calendar-check {
            animation: pulso 3s ease-in-out infinite;
        }
    </style>
</head>

<body>
    <?php
    require 'Layout/Header.php';
    ?>

    <section class="content">
        <main>
            <div class="head-title">
                <div class="left">
                    <h1>Inicio</h1>
                    <ul class="breadcrumb">
                        <i class="fas fa-chevron-right"></i>
                        <li>
                            <a href="Orders.php" class="active">Actualizaciones</a>
                        </li>
                    </ul>
                </div>

                <!--
                    <a href="#" class="download-btn">
                        <i class="fas fa-cloud-download-alt"></i>
                        <span class="text">Download Report</span>
                    </a>
                -->
            </div>

            <div class="box-info">
                <li>
                    <i class="fas fa-lightbulb" style="background-color: transparent;"></i>
                    <span class="text">
                        <h3 id="contadorPedidos">
                           
                        </h3>
                        <p>Nuevos Pedidos</p>
                    </span>
                </li>
                <li>
                    <i class="fas fa-clock fa-clock-hour" style="color: #FFC700; background-color: transparent;"></i>
                    <span class="text">
                        <h3>
                          
                        </h3>
                        <p>Pedidos Pendientes</p>
                    </span>
                </li>
                <li>
                    <i class="fas fa-calendar-check" style="color: #41B06E; background-color: transparent;"></i>
                    <span class="text">
                        <h3>
                            
                        </h3>
                        <p>Pedidos Pagados</p>
                    </span>
                </li>
            </div>

            <div class="table-data">
                <div class="order">
                    <div class="head">
                        <h3>Estado de los últimos pedidos</h3>
                    </div>

                    <table class="usuarios">
                        <thead>
                            <tr class="table-header">
                                <th>Nombre</th>
                                <th>Apellido</th>
                                <th>Ciudad</th>
                                <th>Total</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                   
                               
                        </tbody>
                    </table>
                </div>

                <div class="todo">
                    <div class="head">
                        <h3>Todos</h3>
                    </div>

                    <ul class="todo-list">
                       
                    </ul>
                </div>

            </div>
        </main>
    </section>



    <script src="js/app.js"></script>
    <script src="https://kit.fontawesome.com/your-font-awesome-kit.js" crossorigin="anonymous"></script>
</body>

</html>

<section class="content">
    <nav>
        <form action="#">
            <div class="form-input"></div>
        </form>

        <input type="checkbox" hidden id="switch-mode" />
        <label for="switch-mode" class="switch-mode"></label>
        <a href="/perfil" class="profile">
            <img src="../../Pictures/<?php echo $usuario['Foto']; ?>" alt="" />
        </a>
    </nav>
</section>

<section class="sidebar">
    <a href="/panel-de-control" class="logo">
        <i class="fab fa-slack"></i>
    </a>

    <ul class="side-menu top">

        <li>
            <a href="../View/Categories.php" class="nav-link">
                <i class="fa-solid fa-icons"></i>
                <span class="text">Categorias</span>
            </a>
        </li>
        <li>
            <a href="../Products.php" class="nav-link">
                <i class="fas fa-message"></i>
                <span class="text">Productos</span>
            </a>
        </li>


    </ul>




    <ul class="side-menu">
        <li>
            <?php
                require 'Footer.php';
            ?>
        </li>
    </ul>

</section>

   



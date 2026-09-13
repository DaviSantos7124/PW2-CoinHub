<?php 

$paginaAtual = basename($_SERVER["PHP_SELF"]); 

?> 

<!DOCTYPE html> 
<html lang="pt-BR"> 

<head> 
    <meta charset="UTF-8"> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 

    <title>CoinHub</title> 

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <div class="layout">
        <aside class="sidebar">
            <div class="sidebar-logo">
                <i class="bi bi-coin"></i>
                <span>
                    Coin<span>Hub</span>
                </span>
            </div>

            <nav class="menu">
                <a href="index.php" class="<?= $paginaAtual === "index.php" ? "ativo" : "" ?>">
                    <i class="bi bi-house-fill"></i>
                    Início
                </a>

                <a href="produtos.php" class="<?= in_array($paginaAtual, ["produtos.php", "adicionar.php", "editar.php", "excluir.php"]) ? "ativo" : "" ?>">
                    <i class="bi bi-box-seam-fill"></i>
                    Produtos
                </a>

                <a href="compras.php" class="<?= in_array($paginaAtual, ["compras.php", "adicionar_compra.php", "editar_compra.php", "excluir_compra.php"]) ? "ativo" : "" ?>">
                    <i class="bi bi-bag-fill"></i>
                    Compras
                </a>

                <a href="vendas.php" class="<?= in_array($paginaAtual, ["vendas.php", "adicionar_venda.php", "editar_venda.php", "excluir_venda.php"]) ? "ativo" : "" ?>">
                    <i class="bi bi-cart-fill"></i>
                    Vendas
                </a>

                <div class="menu-relatorios">
                    <span>
                        <i class="bi bi-bar-chart-fill"></i>
                        Relatórios
                    </span>

                    <a href="relatorios.php?tipo=mais">
                        <i class="bi bi-graph-up-arrow"></i>
                        Mais vendidos
                    </a>

                    <a href="relatorios.php?tipo=menos">
                        <i class="bi bi-graph-down-arrow"></i>
                        Menos vendidos
                    </a>
                </div>

                <a href="logout.php">
                    <i class="bi bi-box-arrow-right"></i>
                    Sair
                </a>
            </nav>
        </aside>

        <section class="area-principal">
            <header class="topbar">
                <div></div>
                <div class="usuario">
                    <i class="bi bi-person-fill"></i>
                    <?= htmlspecialchars($_SESSION["usuario"] ?? "admin") ?>
                </div>
            </header>
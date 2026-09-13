<?php

require_once "includes/conexao.php";

$totalProdutos = 0;
$totalEstoque = 0;
$baixoEstoque = 0;
$semEstoque = 0;

// Total de produtos
$sql = "SELECT COUNT(*) AS total FROM tb_produtos";
$resultado = $conexao->query($sql);

if ($resultado) {
    $dados = $resultado->fetch_assoc();
    $totalProdutos = $dados["total"];
}

// Total de itens em estoque
$sql = "SELECT COALESCE(SUM(qt_estoque), 0) AS total FROM tb_produtos";
$resultado = $conexao->query($sql);

if ($resultado) {
    $dados = $resultado->fetch_assoc();
    $totalEstoque = $dados["total"];
}

// Produtos com estoque baixo
$sql = "SELECT COUNT(*) AS total FROM tb_produtos WHERE qt_estoque BETWEEN 1 AND 5";
$resultado = $conexao->query($sql);

if ($resultado) {
    $dados = $resultado->fetch_assoc();
    $baixoEstoque = $dados["total"];
}

// Produtos sem estoque
$sql = "SELECT COUNT(*) AS total FROM tb_produtos WHERE qt_estoque = 0";
$resultado = $conexao->query($sql);

if ($resultado) {
    $dados = $resultado->fetch_assoc();
}

include "includes/header.php";

?>

<main class="container">

<section class="hero">

    <div class="hero-text">

        <span class="tag">Sistema de estoque</span>

        <h1>
            Gerencie suas moedas
            <span>virtuais.</span>
        </h1>

        <p>
            Controle seus produtos, acompanhe o estoque
            e mantenha sua loja organizada.
        </p>

        <a href="produtos.php" class="btn btn-azul">
            <i class="bi bi-box-seam"></i>
            Ver produtos
        </a>

    </div>

    <div class="hero-icon">

        <i class="bi bi-coin"></i>

        <div class="mini-coins">
            <i class="bi bi-coin"></i>
            <i class="bi bi-coin"></i>
            <i class="bi bi-coin"></i>
        </div>

    </div>

</section>


<section class="dashboard">

    <div class="card">

        <div class="card-icon azul">
            <i class="bi bi-box-seam"></i>
        </div>

        <div>
            <span>Total de produtos</span>
            <strong><?= $totalProdutos ?></strong>
        </div>

    </div>


    <div class="card">

        <div class="card-icon amarelo">
            <i class="bi bi-boxes"></i>
        </div>

        <div>
            <span>Itens em estoque</span>
            <strong><?= $totalEstoque ?></strong>
        </div>

    </div>


    <div class="card">

        <div class="card-icon amarelo">
            <i class="bi bi-exclamation-triangle"></i>
        </div>

        <div>
            <span>Estoque baixo</span>
            <strong><?= $baixoEstoque ?></strong>
        </div>

    </div>


    <div class="card">

        <div class="card-icon vermelho">
            <i class="bi bi-x-circle"></i>
        </div>

        <div>
            <span>Sem estoque</span>
            <strong><?= $semEstoque ?></strong>
        </div>

    </div>

</section>


<section class="beneficios">

    <div class="beneficio">

        <i class="bi bi-lightning-charge-fill"></i>

        <div>
            <h3>Rápido</h3>
            <p>
                Gerencie seus produtos de forma simples.
            </p>
        </div>

    </div>


    <div class="beneficio">

        <i class="bi bi-shield-lock-fill"></i>

        <div>
            <h3>Organizado</h3>
            <p>
                Tenha controle sobre seu estoque.
            </p>
        </div>

    </div>


    <div class="beneficio">

        <i class="bi bi-controller"></i>

        <div>
            <h3>Para gamers</h3>
            <p>
                Produtos de diferentes jogos em um só lugar.
            </p>
        </div>

    </div>

</section>

</main>

<?php include "includes/footer.php"; ?>

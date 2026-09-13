<?php

$mensagem = "";
$erro = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $nome = trim($_POST["nome"] ?? "");
    $jogo = trim($_POST["jogo"] ?? "");
    $preco = $_POST["preco"] ?? "";
    $estoque = $_POST["estoque"] ?? "";

    if ($nome === "" || $jogo === "" || $preco === "" || $estoque === "") {

        $erro = "Preencha todos os campos.";
    } elseif (!is_numeric($preco) || $preco < 0) {

        $erro = "Digite um preço válido.";
    } elseif (!is_numeric($estoque) || $estoque < 0) {

        $erro = "Digite uma quantidade válida.";
    } else {

        /*
        FUTURO BANCO DE DADOS

        Aqui será colocado o INSERT no MySQL.

        Exemplo:

        INSERT INTO produtos
        (nome, jogo, preco, estoque)
        VALUES
        (:nome, :jogo, :preco, :estoque);
        */

        $mensagem = "Produto cadastrado com sucesso.";
    }
}

include "includes/header.php";

?>

<main class="container">
    <div class="pagina-topo">
        <div>
            <span class="tag"> Estoque </span>
            <h1>Adicionar produto</h1>
            <p> Cadastre uma nova moeda virtual. </p>
        </div>
    </div>


    <section class="form-container">
        <?php if ($mensagem): ?>
            <div class="mensagem sucesso">
                <i class="bi bi-check-circle"></i>
                <?= htmlspecialchars($mensagem) ?>
            </div>
        <?php endif; ?>

        <?php if ($erro): ?>
            <div class="mensagem erro">
                <i class="bi bi-exclamation-circle"></i>
                <?= htmlspecialchars($erro) ?>
            </div>
        <?php endif; ?>

        <form method="POST">
            <div class="campo">
                <label for="nome"> Nome do produto </label>
                <input type="text" id="nome" name="nome" placeholder="Ex: 400 Robux" required>
            </div>

            <div class="campo">
                <label for="jogo"> Jogo </label>
                <input type="text" id="jogo" name="jogo" placeholder="Ex: Roblox" required>
            </div>

            <div class="form-linha">
                <div class="campo">
                    <label for="preco"> Preço </label>
                    <input type="number" id="preco" name="preco" step="0.01" min="0" placeholder="19.90" required>
                </div>

                <div class="campo">
                    <label for="estoque"> Quantidade </label>
                    <input type="number" id="estoque" name="estoque" min="0" placeholder="25" required>
                </div>
            </div>

            <div class="form-acoes">
                <a href="produtos.php" class="btn btn-cancelar">
                    Cancelar
                </a>

                <button type="submit" class="btn btn-amarelo">
                    <i class="bi bi-plus-lg"></i>
                    Adicionar produto
                </button>
            </div>
            
        </form>

    </section>

</main>

<?php include "includes/footer.php"; ?>
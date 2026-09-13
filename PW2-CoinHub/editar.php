<?php

require_once "includes/conexao.php";

$id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);

$mensagem = "";
$erro = "";
$produto = null;


// =====================================================
// BUSCAR PRODUTO
// =====================================================

if (!$id) {

    $erro = "Produto não encontrado.";

} else {

    $sql = "SELECT
                cd_produto,
                nm_produto,
                nm_jogo,
                vl_preco,
                qt_estoque
            FROM tb_produtos
            WHERE cd_produto = ?";

    $stmt = $conexao->prepare($sql);

    if ($stmt) {

        $stmt->bind_param("i", $id);
        $stmt->execute();

        $resultado = $stmt->get_result();

        if ($resultado->num_rows === 1) {

            $produto = $resultado->fetch_assoc();

        } else {

            $erro = "Produto não encontrado.";
        }

        $stmt->close();

    } else {

        $erro = "Erro ao consultar o produto.";
    }
}


// =====================================================
// ATUALIZAR PRODUTO
// =====================================================

if ($produto && $_SERVER["REQUEST_METHOD"] === "POST") {

    $nome = trim($_POST["nome"] ?? "");
    $jogo = trim($_POST["jogo"] ?? "");
    $preco = $_POST["preco"] ?? "";
    $estoque = $_POST["estoque"] ?? "";


    if (
        $nome === "" ||
        $jogo === "" ||
        $preco === "" ||
        $estoque === ""
    ) {

        $erro = "Preencha todos os campos.";

    } elseif (!is_numeric($preco) || $preco < 0) {

        $erro = "Digite um preço válido.";

    } elseif (
        !is_numeric($estoque) ||
        $estoque < 0 ||
        floor($estoque) != $estoque
    ) {

        $erro = "Digite uma quantidade válida.";

    } else {

        $preco = (float) $preco;
        $estoque = (int) $estoque;


        $sql = "UPDATE tb_produtos
                SET
                    nm_produto = ?,
                    nm_jogo = ?,
                    vl_preco = ?,
                    qt_estoque = ?
                WHERE cd_produto = ?";

        $stmt = $conexao->prepare($sql);

        if ($stmt) {

            $stmt->bind_param(
                "ssdii",
                $nome,
                $jogo,
                $preco,
                $estoque,
                $id
            );

            if ($stmt->execute()) {

                $mensagem = "Produto atualizado com sucesso.";

                // Atualiza os dados exibidos no formulário
                $produto["nm_produto"] = $nome;
                $produto["nm_jogo"] = $jogo;
                $produto["vl_preco"] = $preco;
                $produto["qt_estoque"] = $estoque;

            } else {

                $erro = "Erro ao atualizar o produto.";
            }

            $stmt->close();

        } else {

            $erro = "Erro ao preparar a atualização do produto.";
        }
    }
}


include "includes/header.php";

?>

<main class="container">

    <div class="pagina-topo">

        <div>

            <span class="tag">
                Estoque
            </span>

            <h1>
                Editar produto
            </h1>

            <p>
                Altere as informações do produto.
            </p>

        </div>

    </div>


    <?php if ($erro && !$produto): ?>

        <div class="mensagem erro">

            <i class="bi bi-exclamation-circle"></i>

            <?= htmlspecialchars($erro) ?>

        </div>


        <a
            href="produtos.php"
            class="btn btn-azul"
        >
            Voltar para produtos
        </a>


    <?php elseif ($produto): ?>

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

                    <label for="nome">
                        Nome do produto
                    </label>

                    <input
                        type="text"
                        id="nome"
                        name="nome"
                        value="<?= htmlspecialchars($produto["nm_produto"]) ?>"
                        required
                    >

                </div>


                <div class="campo">

                    <label for="jogo">
                        Jogo
                    </label>

                    <input
                        type="text"
                        id="jogo"
                        name="jogo"
                        value="<?= htmlspecialchars($produto["nm_jogo"]) ?>"
                        required
                    >

                </div>


                <div class="form-linha">

                    <div class="campo">

                        <label for="preco">
                            Preço
                        </label>

                        <input
                            type="number"
                            id="preco"
                            name="preco"
                            step="0.01"
                            min="0"
                            value="<?= htmlspecialchars($produto["vl_preco"]) ?>"
                            required
                        >

                    </div>


                    <div class="campo">

                        <label for="estoque">
                            Quantidade
                        </label>

                        <input
                            type="number"
                            id="estoque"
                            name="estoque"
                            min="0"
                            value="<?= htmlspecialchars($produto["qt_estoque"]) ?>"
                            required
                        >

                    </div>

                </div>


                <div class="form-acoes">

                    <a
                        href="produtos.php"
                        class="btn btn-cancelar"
                    >
                        Cancelar
                    </a>


                    <button
                        type="submit"
                        class="btn btn-amarelo"
                    >

                        <i class="bi bi-check-lg"></i>

                        Salvar alterações

                    </button>

                </div>


            </form>

        </section>

    <?php endif; ?>

</main>


<?php include "includes/footer.php"; ?>
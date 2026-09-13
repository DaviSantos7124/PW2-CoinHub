<?php

require_once "includes/conexao.php";

$mensagem = "";
$erro = "";

$acao = $_GET["acao"] ?? "";
$idEditar = $_GET["id"] ?? "";

/*
|--------------------------------------------------------------------------
| CADASTRAR PRODUTO
|--------------------------------------------------------------------------
*/

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $acaoPost = $_POST["acao"] ?? "";

    if ($acaoPost === "adicionar") {

        $jogo = trim($_POST["jogo"] ?? "");
        $pacote = trim($_POST["pacote"] ?? "");
        $preco = $_POST["preco"] ?? "";
        $estoque = $_POST["estoque"] ?? "";

        if ($jogo === "" || $pacote === "" || $preco === "" || $estoque === "") {

            $erro = "Preencha todos os campos.";

        } elseif (!is_numeric($preco) || $preco < 0) {

            $erro = "Digite um preço válido.";

        } elseif (!is_numeric($estoque) || $estoque < 0 || floor($estoque) != $estoque) {

            $erro = "Digite uma quantidade válida.";

        } else {

            $sql = "INSERT INTO tb_produtos
                    (nm_produto, nm_jogo, vl_preco, qt_estoque)
                    VALUES (?, ?, ?, ?)";

            $stmt = $conexao->prepare($sql);

            if ($stmt) {

                $preco = (float) $preco;
                $estoque = (int) $estoque;

                $stmt->bind_param(
                    "ssdi",
                    $pacote,
                    $jogo,
                    $preco,
                    $estoque
                );

                if ($stmt->execute()) {

                    $mensagem = "Produto cadastrado com sucesso.";
                    $acao = "";

                } else {

                    $erro = "Erro ao cadastrar o produto.";
                }

                $stmt->close();

            } else {

                $erro = "Erro ao preparar o cadastro.";
            }
        }
    }


    /*
    |--------------------------------------------------------------------------
    | EDITAR PRODUTO
    |--------------------------------------------------------------------------
    */

    if ($acaoPost === "editar") {

        $id = $_POST["id"] ?? "";
        $jogo = trim($_POST["jogo"] ?? "");
        $pacote = trim($_POST["pacote"] ?? "");
        $preco = $_POST["preco"] ?? "";
        $estoque = $_POST["estoque"] ?? "";

        if ($jogo === "" || $pacote === "" || $preco === "" || $estoque === "") {

            $erro = "Preencha todos os campos.";

        } elseif (!is_numeric($preco) || $preco < 0) {

            $erro = "Digite um preço válido.";

        } elseif (!is_numeric($estoque) || $estoque < 0 || floor($estoque) != $estoque) {

            $erro = "Digite uma quantidade válida.";

        } else {

            $sql = "UPDATE tb_produtos
                    SET nm_produto = ?,
                        nm_jogo = ?,
                        vl_preco = ?,
                        qt_estoque = ?
                    WHERE cd_produto = ?";

            $stmt = $conexao->prepare($sql);

            if ($stmt) {

                $preco = (float) $preco;
                $estoque = (int) $estoque;
                $id = (int) $id;

                $stmt->bind_param(
                    "ssdii",
                    $pacote,
                    $jogo,
                    $preco,
                    $estoque,
                    $id
                );

                if ($stmt->execute()) {

                    $mensagem = "Produto editado com sucesso.";
                    $acao = "";

                } else {

                    $erro = "Erro ao editar o produto.";
                }

                $stmt->close();

            } else {

                $erro = "Erro ao preparar a edição.";
            }
        }
    }


    /*
    |--------------------------------------------------------------------------
    | EXCLUIR PRODUTO
    |--------------------------------------------------------------------------
    */

    if ($acaoPost === "excluir") {

        $id = (int) ($_POST["id"] ?? 0);

        $sql = "DELETE FROM tb_produtos WHERE cd_produto = ?";

        $stmt = $conexao->prepare($sql);

        if ($stmt) {

            $stmt->bind_param("i", $id);

            if ($stmt->execute()) {

                $mensagem = "Produto excluído com sucesso.";

            } else {

                /*
                Se o produto tiver compras ou vendas relacionadas,
                o banco não permitirá a exclusão por causa da
                FOREIGN KEY.
                */

                if ($stmt->errno == 1451) {

                    $erro = "Não é possível excluir este produto porque ele possui compras ou vendas registradas.";

                } else {

                    $erro = "Erro ao excluir o produto.";
                }
            }

            $stmt->close();

        } else {

            $erro = "Erro ao preparar a exclusão.";
        }

        $acao = "";
    }
}


/*
|--------------------------------------------------------------------------
| BUSCAR PRODUTO PARA EDITAR
|--------------------------------------------------------------------------
*/

$produtoEditar = null;

if ($acao === "editar" && $idEditar !== "") {

    $idEditar = (int) $idEditar;

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

        $stmt->bind_param("i", $idEditar);
        $stmt->execute();

        $resultado = $stmt->get_result();

        if ($resultado->num_rows > 0) {

            $produtoEditar = $resultado->fetch_assoc();

        }

        $stmt->close();
    }
}


/*
|--------------------------------------------------------------------------
| BUSCAR JOGOS EXISTENTES
|--------------------------------------------------------------------------
*/

$jogos = [];

$sql = "SELECT DISTINCT nm_jogo
        FROM tb_produtos
        ORDER BY nm_jogo ASC";

$resultadoJogos = $conexao->query($sql);

if ($resultadoJogos) {

    while ($jogo = $resultadoJogos->fetch_assoc()) {

        $jogos[] = $jogo["nm_jogo"];
    }
}


/*
|--------------------------------------------------------------------------
| BUSCAR TODOS OS PRODUTOS
|--------------------------------------------------------------------------
*/

$produtos = [];

$sql = "SELECT
            cd_produto,
            nm_produto,
            nm_jogo,
            vl_preco,
            qt_estoque
        FROM tb_produtos
        ORDER BY cd_produto ASC";

$resultado = $conexao->query($sql);

if ($resultado) {

    while ($produto = $resultado->fetch_assoc()) {

        $produtos[] = $produto;
    }
}

include "includes/header.php";

?>

<main class="container">

<?php if ($acao === "adicionar"): ?>

    <div class="pagina-topo">

        <div>

            <span class="tag">Estoque</span>

            <h1>Adicionar produto</h1>

            <p>
                Cadastre uma nova moeda virtual.
            </p>

        </div>

    </div>


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


    <section class="form-container">

        <form method="POST">

            <input
                type="hidden"
                name="acao"
                value="adicionar"
            >


            <div class="campo">

                <label for="jogo">
                    Jogo
                </label>

                <input
                    type="text"
                    name="jogo"
                    id="jogo"
                    placeholder="Ex: Roblox"
                    required
                >

            </div>


            <div class="campo">

                <label for="pacote">
                    Pacote
                </label>

                <input
                    type="text"
                    name="pacote"
                    id="pacote"
                    placeholder="Ex: 1.000 Robux"
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
                        placeholder="49.90"
                        required
                    >

                </div>


                <div class="campo">

                    <label for="estoque">
                        Quantidade em estoque
                    </label>

                    <input
                        type="number"
                        id="estoque"
                        name="estoque"
                        min="0"
                        placeholder="10"
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

                    <i class="bi bi-plus-lg"></i>

                    Adicionar produto

                </button>

            </div>

        </form>

    </section>


<?php elseif ($acao === "editar"): ?>


    <div class="pagina-topo">

        <div>

            <span class="tag">Estoque</span>

            <h1>Editar produto</h1>

            <p>
                Altere as informações do produto.
            </p>

        </div>

    </div>


    <?php if ($erro): ?>

        <div class="mensagem erro">

            <i class="bi bi-exclamation-circle"></i>

            <?= htmlspecialchars($erro) ?>

        </div>

    <?php endif; ?>


    <?php if ($produtoEditar): ?>


        <section class="form-container">

            <form method="POST">

                <input
                    type="hidden"
                    name="acao"
                    value="editar"
                >

                <input
                    type="hidden"
                    name="id"
                    value="<?= $produtoEditar["cd_produto"] ?>"
                >


                <div class="campo">

                    <label for="jogo">
                        Jogo
                    </label>

                    <input
                        type="text"
                        name="jogo"
                        id="jogo"
                        value="<?= htmlspecialchars($produtoEditar["nm_jogo"]) ?>"
                        required
                    >

                </div>


                <div class="campo">

                    <label for="pacote">
                        Pacote
                    </label>

                    <input
                        type="text"
                        name="pacote"
                        id="pacote"
                        value="<?= htmlspecialchars($produtoEditar["nm_produto"]) ?>"
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
                            value="<?= htmlspecialchars($produtoEditar["vl_preco"]) ?>"
                            required
                        >

                    </div>


                    <div class="campo">

                        <label for="estoque">
                            Quantidade em estoque
                        </label>

                        <input
                            type="number"
                            id="estoque"
                            name="estoque"
                            min="0"
                            value="<?= htmlspecialchars($produtoEditar["qt_estoque"]) ?>"
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


    <?php else: ?>

        <div class="mensagem erro">

            <i class="bi bi-exclamation-circle"></i>

            Produto não encontrado.

        </div>

    <?php endif; ?>


<?php else: ?>


    <div class="pagina-topo">

        <div>

            <span class="tag">Estoque</span>

            <h1>Produtos</h1>

            <p>
                Gerencie as moedas virtuais disponíveis na loja.
            </p>

        </div>


        <a
            href="produtos.php?acao=adicionar"
            class="btn btn-amarelo"
        >

            <i class="bi bi-plus-lg"></i>

            Adicionar produto

        </a>

    </div>


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


    <section class="produtos-grid">

        <?php if (count($produtos) > 0): ?>

            <?php foreach ($produtos as $produto): ?>

                <div class="produto-card">

                    <div class="produto-imagem">

                        <i class="bi bi-controller"></i>

                    </div>


                    <div class="produto-info">

                        <span class="produto-jogo">

                            <?= htmlspecialchars($produto["nm_jogo"]) ?>

                        </span>


                        <h2>

                            <?= htmlspecialchars($produto["nm_produto"]) ?>

                        </h2>


                        <p class="produto-tipo">

                            <?= htmlspecialchars($produto["nm_jogo"]) ?>

                        </p>


                        <p class="preco">

                            R$
                            <?= number_format(
                                $produto["vl_preco"],
                                2,
                                ",",
                                "."
                            ) ?>

                        </p>


                        <?php if ($produto["qt_estoque"] == 0): ?>

                            <span class="status sem-estoque">

                                Sem estoque

                            </span>

                        <?php elseif ($produto["qt_estoque"] <= 5): ?>

                            <span class="status estoque-baixo">

                                Estoque baixo

                            </span>

                        <?php else: ?>

                            <span class="status disponivel">

                                Disponível

                            </span>

                        <?php endif; ?>


                        <p class="quantidade">

                            <?= $produto["qt_estoque"] ?>

                            unidades

                        </p>


                        <div class="acoes">

                            <a
                                href="produtos.php?acao=editar&id=<?= $produto["cd_produto"] ?>"
                                class="btn btn-editar"
                            >

                                <i class="bi bi-pencil"></i>

                                Editar

                            </a>


                            <form
                                action="produtos.php"
                                method="POST"
                                onsubmit="return confirm('Deseja realmente excluir este produto?');"
                            >

                                <input
                                    type="hidden"
                                    name="acao"
                                    value="excluir"
                                >

                                <input
                                    type="hidden"
                                    name="id"
                                    value="<?= $produto["cd_produto"] ?>"
                                >


                                <button
                                    type="submit"
                                    class="btn btn-excluir"
                                >

                                    <i class="bi bi-trash"></i>

                                    Excluir

                                </button>

                            </form>

                        </div>

                    </div>

                </div>

            <?php endforeach; ?>

        <?php else: ?>

            <div class="mensagem">

                <i class="bi bi-box-seam"></i>

                Nenhum produto cadastrado.

            </div>

        <?php endif; ?>

    </section>


<?php endif; ?>

</main>

<?php include "includes/footer.php"; ?>

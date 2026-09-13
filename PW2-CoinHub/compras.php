<?php

require_once "includes/auth.php";

$produtos = [
    "Roblox - R$ 100",
    "Fortnite - 800 V-Bucks",
    "Free Fire - 1.166 Diamantes",
    "Valorant - 1.000 VP",
    "EA FC - 1.050 FC Points"
];

if (!isset($_SESSION["compras"])) {

    $_SESSION["compras"] = [
        [
            "id" => 1,
            "produto" => "Roblox - R$ 100",
            "quantidade" => 50,
            "valor" => 2495.00,
            "data" => "2026-09-05"
        ],
        [
            "id" => 2,
            "produto" => "Fortnite - 800 V-Bucks",
            "quantidade" => 30,
            "valor" => 1170.00,
            "data" => "2026-09-03"
        ],
        [
            "id" => 3,
            "produto" => "Free Fire - 1.166 Diamantes",
            "quantidade" => 20,
            "valor" => 1780.00,
            "data" => "2026-09-01"
        ]
    ];
}

$compras = &$_SESSION["compras"];

$mensagem = "";
$tipo = "";

$acao = $_GET["acao"] ?? "";
$id = $_GET["id"] ?? "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $acao = $_POST["acao"] ?? "";

    if ($acao === "adicionar" || $acao === "editar") {

        $produto = $_POST["produto"] ?? "";
        $quantidade = $_POST["quantidade"] ?? "";
        $valor = $_POST["valor"] ?? "";
        $data = $_POST["data"] ?? "";

        if ($produto === "" || $quantidade === "" || $valor === "" || $data === "") {

            $mensagem = "Preencha todos os campos.";
            $tipo = "erro";
        } elseif (!in_array($produto, $produtos)) {

            $mensagem = "Selecione um produto válido.";
            $tipo = "erro";
        } elseif (!is_numeric($quantidade) || $quantidade <= 0) {

            $mensagem = "Quantidade inválida.";
            $tipo = "erro";
        } elseif (!is_numeric($valor) || $valor < 0) {

            $mensagem = "Valor inválido.";
            $tipo = "erro";
        } else {

            if ($acao === "adicionar") {

                $compras[] = [
                    "id" => count($compras) + 1,
                    "produto" => $produto,
                    "quantidade" => $quantidade,
                    "valor" => $valor,
                    "data" => $data
                ];

                $mensagem = "Compra cadastrada com sucesso.";
                $tipo = "sucesso";
            } else {

                $id = $_POST["id"] ?? "";

                foreach ($compras as &$compra) {

                    if ($compra["id"] == $id) {

                        $compra["produto"] = $produto;
                        $compra["quantidade"] = $quantidade;
                        $compra["valor"] = $valor;
                        $compra["data"] = $data;

                        break;
                    }
                }

                unset($compra);

                $mensagem = "Compra atualizada com sucesso.";
                $tipo = "sucesso";
            }

            $acao = "";
        }
    }

    if ($acao === "excluir") {

        $id = $_POST["id"] ?? "";

        foreach ($compras as $indice => $compra) {

            if ($compra["id"] == $id) {

                unset($compras[$indice]);

                break;
            }
        }

        $compras = array_values($compras);

        $mensagem = "Compra excluída com sucesso.";
        $tipo = "sucesso";
        $acao = "";
    }
}

$compraEditar = null;

if ($acao === "editar") {

    foreach ($compras as $compra) {

        if ($compra["id"] == $id) {

            $compraEditar = $compra;

            break;
        }
    }
}

include "includes/header.php"; ?>

<main class="container">

    <?php if ($acao === "adicionar" || $acao === "editar"): ?>

        <div class="pagina-topo">
            <div>
                <span class="tag"> Compras </span>
                <h1><?= $acao === "adicionar" ? "Adicionar compra" : "Editar compra" ?></h1>
                <p><?= $acao === "adicionar" ? "Registre uma nova compra." : "Altere os dados da compra." ?></p>
            </div>
        </div>

        <?php if ($mensagem): ?>

            <div class="mensagem <?= $tipo ?>">
                <i class="bi bi-exclamation-circle"></i>
                <?= htmlspecialchars($mensagem) ?>
            </div>

        <?php endif; ?>

        <section class="form-container">
            <form method="POST">

                <input type="hidden" name="acao" value="<?= $acao ?>">

                <?php if ($acao === "editar"): ?>

                    <input type="hidden" name="id" value="<?= $compraEditar["id"] ?>">

                <?php endif; ?>

                <div class="campo">
                    <label for="produto"> Produto </label>

                    <select name="produto" id="produto" required>

                        <option value="">
                            Selecione o produto
                        </option>

                        <?php foreach ($produtos as $produto): ?>

                            <option value="<?= htmlspecialchars($produto) ?>" <?= isset($compraEditar) && $compraEditar["produto"] === $produto ? "selected" : "" ?>>
                                <?= htmlspecialchars($produto) ?>
                            </option>

                        <?php endforeach; ?>

                    </select>
                </div>

                <div class="campo">
                    <label for="quantidade"> Quantidade </label>
                    <input type="number" id="quantidade" name="quantidade" value="<?= $compraEditar["quantidade"] ?? "" ?>" placeholder="Ex: 10" min="1" required>
                </div>

                <div class="campo">
                    <label for="valor"> Valor Total </label>
                    <input type="number" id="valor" name="valor" value="<?= $compraEditar["valor"] ?? "" ?>" placeholder="Ex: 99.90" step="0.01" min="0" required>
                </div>

                <div class="campo">
                    <label for="data"> Data </label>
                    <input type="date" id="data" name="data" value="<?= $compraEditar["data"] ?? "" ?>" required>
                </div>

                <div class="form-acoes">
                    <a href="compras.php" class="btn btn-cancelar"> Cancelar </a>

                    <button type="submit" class="btn btn-amarelo">
                        <i class="bi <?= $acao === "adicionar" ? "bi-plus-lg" : "bi-check-lg" ?>"></i>
                        <?= $acao === "adicionar" ? "Cadastrar compra" : "Salvar alterações" ?>
                    </button>
                </div>

            </form>
        </section>

    <?php else: ?>

        <div class="pagina-topo">
            <div>
                <span class="tag"> Compras </span>
                <h1>Compras</h1>
                <p> Gerencie as compras de produtos. </p>
            </div>

            <a href="compras.php?acao=adicionar" class="btn btn-amarelo">
                <i class="bi bi-plus-lg"></i>
                Adicionar compra
            </a>
        </div>

        <?php if ($mensagem): ?>

            <div class="mensagem <?= $tipo ?>">
                <i class="bi bi-check-circle"></i>
                <?= htmlspecialchars($mensagem) ?>
            </div>

        <?php endif; ?>

        <section class="tabela-container">
            <table>

                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Produto</th>
                        <th>Quantidade</th>
                        <th>Valor Total</th>
                        <th>Data</th>
                        <th>Ações</th>
                    </tr>
                </thead>

                <tbody>

                    <?php foreach ($compras as $compra): ?>

                        <tr>
                            <td><?= $compra["id"] ?></td>
                            <td><?= htmlspecialchars($compra["produto"]) ?></td>
                            <td><?= $compra["quantidade"] ?></td>
                            <td> R$ <?= number_format($compra["valor"], 2, ",", ".") ?> </td>
                            <td><?= date("d/m/Y", strtotime($compra["data"])) ?></td>

                            <td class="acoes">

                                <a href="compras.php?acao=editar&id=<?= $compra["id"] ?>" class="btn-editar">
                                    <i class="bi bi-pencil-fill"></i>
                                </a>

                                <form method="POST">
                                    <input type="hidden" name="acao" value="excluir">
                                    <input type="hidden" name="id" value="<?= $compra["id"] ?>">

                                    <button type="submit" class="btn-excluir" onclick="return confirm('Deseja realmente excluir esta compra?')">
                                        <i class="bi bi-trash-fill"></i>
                                    </button>
                                </form>

                            </td>
                        </tr>

                    <?php endforeach; ?>

                </tbody>

            </table>
        </section>

    <?php endif; ?>

</main>

<?php include "includes/footer.php"; ?>
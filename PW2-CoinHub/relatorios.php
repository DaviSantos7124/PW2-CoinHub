<?php

require_once "includes/auth.php";

$tipo = $_GET["tipo"] ?? "mais";

if ($tipo == "menos") {

    $produtos = [

        [
            "posicao" => 1,
            "produto" => "500 Robux",
            "jogo" => "Roblox",
            "quantidade" => 1
        ],

        [
            "posicao" => 2,
            "produto" => "3.000 V-Bucks",
            "jogo" => "Fortnite",
            "quantidade" => 2
        ],

        [
            "posicao" => 3,
            "produto" => "1.500 Moedas",
            "jogo" => "Genshin Impact",
            "quantidade" => 2
        ],

        [
            "posicao" => 4,
            "produto" => "1.000 Diamantes",
            "jogo" => "Free Fire",
            "quantidade" => 3
        ],

        [
            "posicao" => 5,
            "produto" => "2.000 CP",
            "jogo" => "Call of Duty Mobile",
            "quantidade" => 4
        ]

    ];

    $titulo = "Produtos menos vendidos";
    $descricao = "Veja quais produtos tiveram menos saída no período.";

} else {

    $produtos = [

        [
            "posicao" => 1,
            "produto" => "1.000 Robux",
            "jogo" => "Roblox",
            "quantidade" => 52
        ],

        [
            "posicao" => 2,
            "produto" => "1.000 V-Bucks",
            "jogo" => "Fortnite",
            "quantidade" => 38
        ],

        [
            "posicao" => 3,
            "produto" => "5000 Moedas",
            "jogo" => "Genshin Impact",
            "quantidade" => 27
        ],

        [
            "posicao" => 4,
            "produto" => "2.000 CP",
            "jogo" => "Call of Duty Mobile",
            "quantidade" => 21
        ],

        [
            "posicao" => 5,
            "produto" => "1.000 Diamantes",
            "jogo" => "Free Fire",
            "quantidade" => 18
        ]

    ];

    $titulo = "Produtos mais vendidos";
    $descricao = "Veja quais produtos tiveram mais saída no período.";

}

include "includes/header.php"; ?>

<main class="conteudo">

    <div class="titulo-pagina">
        <div>
            <h1><?= $titulo ?></h1>
            <p>
                <?= $descricao ?>
            </p>
        </div>
    </div>

    <div class="filtro">

        <label>Período: </label>

        <input type="date">

        <span>até</span>

        <input type="date">

        <button class="btn btn-amarelo">
            Filtrar
        </button>

    </div>

    <div class="tabela-container">

        <table>

            <thead>

                <tr>
                    <th>#</th>
                    <th>Produto</th>
                    <th>Jogo</th>
                    <th>Quantidade Vendida</th>
                </tr>

            </thead>

            <tbody>

                <?php foreach ($produtos as $produto): ?>

                    <tr>

                        <td>
                            <?= $produto["posicao"] ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($produto["produto"]) ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($produto["jogo"]) ?>
                        </td>

                        <td>
                            <?= $produto["quantidade"] ?>
                        </td>

                    </tr>

                <?php endforeach; ?>

            </tbody>

        </table>

    </div>

</main>

<?php include "includes/footer.php"; ?>
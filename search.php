<?php
    require_once("templates/header.php");

    require_once("dao/GameDAO.php");

    $gameDao = new GameDAO($conn, $BASE_URL);

    $q = filter_input(INPUT_GET, "q");

    $games = $gameDao->findByTitle($q);
?>

<div id="main-container" class="container-fluid">
    <h2 class="section-title" id="search-title">Você está buscando por: <span id="search-result"><?= $q ?></span></h2>
    <p class="section-description">Resultados de busca retornados com base na sua pesquisa.</p>
    <div class="games-container">

        <?php foreach ($games as $game): ?>
            <?php require("templates/game_card.php"); ?>
        <?php endforeach; ?>

        <?php if (count($games) === 0): ?>
            <p class="empty-list">Não há jogos para esta busca, <a href="<?= $BASE_URL ?>" class="back-link">voltar</a></p>
        <?php endif; ?>

    </div>
</div>

<?php
    require_once("templates/footer.php");
?>
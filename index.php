<?php
    require_once("templates/header.php");

    require_once("dao/GameDAO.php");

    $gameDao = new GameDAO($conn, $BASE_URL);

    $latestGames = $gameDao->getLatestGames();

    $RPGames = $gameDao->getGamesByCategory("RPG");

    $FPSGames = $gameDao->getGamesByCategory("FPS");

?>

<div id="main-container" class="container-fluid">
    <h2 class="section-title">Jogos novos</h2>
    <p class="section-description">Veja as críticas dos últimos jogos adicionados no GameStar</p>
    <div class="games-container">

        <?php foreach ($latestGames as $game): ?>
            <?php require("templates/game_card.php"); ?>
        <?php endforeach; ?>

        <?php if (count($latestGames) === 0): ?>
            <p class="empty-list">Ainda não há jogos cadastrados!</p>
        <?php endif; ?>

    </div>

    <h2 class="section-title">RPG</h2>
    <p class="section-description">Veja os melhores jogos de RPG</p>
    <div class="games-container">

        <?php foreach ($RPGames as $game): ?>
            <?php require("templates/game_card.php"); ?>
        <?php endforeach; ?>

        <?php if (count($RPGames) === 0): ?>
            <p class="empty-list">Ainda não há jogos cadastrados!</p>
        <?php endif; ?>

    </div>

    <h2 class="section-title">FPS</h2>
    <p class="section-description">Veja os melhores jogos de FPS</p>
    <div class="games-container">

        <?php foreach ($FPSGames as $game): ?>
            <?php require("templates/game_card.php"); ?>
        <?php endforeach; ?>

        <?php if (count($FPSGames) === 0): ?>
            <p class="empty-list">Ainda não há jogos cadastrados!</p>
        <?php endif; ?>

    </div>
</div>


<!-- https://cdnjs.com/libraries/font-awesome -->
<!-- https://www.flaticon.com/free-icons/game-controller -->
<?php
    require_once("templates/footer.php");
?>
<?php
require_once("templates/header.php");

require_once("models/User.php");

require_once("dao/UserDAO.php");

require_once("dao/GameDAO.php");

$userDao = new UserDao($conn, $BASE_URL);
$user = new User();

$userData = $userDao->verifyToken(true);

$gameDao = new GameDAO($conn, $BASE_URL);

$id = filter_input(INPUT_GET, "id");

if (empty($id)) {
    $message->setMessage("O jogo não foi encontrado.", "error", "index.php");
} else {
    $game = $gameDao->findById($id);

    if (!$game) {
        $message->setMessage("O jogo não foi encontrado.", "error", "index.php");
    }
}
?>

<div id="main-container" class="container-fluid">
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-6 offset-md-1">
                <h1><?= $game->title ?></h1>
                <p class="page-description">
                    Altere os dados do jogo no formulário abaixo:
                </p>

                <form id="edit-game-form" method="POST" action="<?= $BASE_URL ?>game_process.php" enctype="multipart/form-data">
                    <input type="hidden" name="type" value="update">
                    <input type="hidden" name="id" value="<?= $game->id ?>">
                    <div class="form-group">
                        <label for="title">Título:</label>
                        <input type="text" class="form-control" id="title2" name="title" placeholder="Digite o título do seu jogo" value="<?= $game->title ?>">
                    </div>
                    <div class="form-group">
                        <label for="title">Imagem:</label>
                        <input type="file" class="form-controll-file" name="image" id="image">
                    </div>
                    <div class="form-group">
                        <label for="length">Tempo da campanha:</label>
                        <input type="text" class="form-control" id="length" name="length" placeholder="Digite a duração do jogo" value="<?= $game->length ?>">
                    </div>
                    <div class="form-group">
                        <label for="category">Categoria</label>
                        <select name="category" id="category" class="form-control">
                            <option value="">Selecione</option>
                            <option value="FPS" <?= $game->category === "FPS" ? "selected" : "" ?>>FPS</option>
                            <option value="RPG" <?= $game->category === "RPG" ? "selected" : "" ?>>RPG</option>
                            <option value="openWorld" <?= $game->category === "openWorld" ? "selected" : "" ?>>Mundo aberto</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="trailer">Trailer</label>
                        <input type="text" class="form-control" id="trailer" name="trailer" placeholder="Insira o link do trailer" value="<?= $game->trailer ?>">
                    </div>
                    <div class="form-group">
                        <label for="description">Descrição</label>
                        <textarea name="description" id="description" class="form-control" name="description" rows="5" placeholder="Descreva o filme..."><?= $game->description ?></textarea>
                    </div>

                    <input type="submit" class="btn card-btn" value="Editar jogo">
                </form>
            </div>

            <div class="col-md-3">
                <div class="game-image-container" style="background-image: url('<?= $BASE_URL ?>img/games/<?= ($game->image == null) ? 'game_cover.jpg' : $game->image ?>');">

                </div>
            </div>
        </div>
    </div>
</div>

<?php
require_once("templates/footer.php");
?>
<?php
    require_once("templates/header.php");

    require_once("models/Game.php");

    require_once("dao/GameDAO.php");

    $id = filter_input(INPUT_GET, "id");

    $game;

    $gameDao = new GameDAO($conn, $BASE_URL);

    if(empty($id)) {
        $message->setMessage("O jogo não foi encontrado.", "error", "index.php");
    } else {
        $game = $gameDao->findById($id);

        if(!$game) {
            $message->setMessage("O jogo não foi encontrado.", "error", "index.php");
        }
    }

    $userOwnGame = false;

    if(!empty($userData)) {
        if($userData->id === $game->users_id) {
            $userOwnGame = true;
        }
    }

    $youtubeUrl = $game->trailer;
    $embedUrl = str_replace("watch?v=", "embed/", $youtubeUrl);
?>

<div id="main-container" class="container-fluid">
    <div class="row">
        <div class="offset-md-1 col-md-6 game-container">
            <h1 class="page-title"><?= $game->title ?></h1>
            <p class="game-datails">
                <span class="">Duração da campanha: <?= $game->length ?></span>
                <span class="pipe"></span>
                <span><?= $game->category ?></span>
                <span class="pipe"></span>
                <span><i class="fas fa-star"></i> 9</span>
            </p>

            <iframe src="<?= $embedUrl ?>" width="560" height="315" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encryted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
            <p><?= $game->description ?></p>
        </div>

        <div class="col-md4">
            <div class="game-image-container" style="background-image: url('<?= $BASE_URL ?>img/games/<?= ($game->image == null) ? 'game_cover.jpg' : $game->image ?>')"></div>
        </div>

        <div class="offset-md-1 col-md-10" id="reviews-container">
            <h3 id="reviews-title">Avaliações</h3>

            <div class="col-md-12" id="reviews-form-container">
                <h4>Envie sua avaliação</h4>
                <p class="page-description">Preencha o formulário com a nota e comentário sobre o filme</p>
            
                <form action="<?= $BASE_URL ?>review_process.php" id="review-form" method="POST">
                    <input type="hidden" name="type" value="create">
                    <input type="hidden" name="games_id" value="<?= $game->id ?>">
                    <div class="form-group">
                        <label for="rating">Nota do jogo:</label>
                        <select name="rating" id="rating" class="form-control">
                            <option value="">Selecione</option>
                            <?php for ($i = 0; $i <= 10; $i++): ?>
                                <option value="<?= $i ?>"> <?= $i ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="review">Seu comentário:</label>
                        <textarea name="review" id="review" rows="3" class="form-control" placeholder="O que vocÇe achou do jogo?"></textarea>
                    </div>

                    <input type="submit" class="brn card-btn" value="Enviar comentário">
                </form>
            </div>

            <div class="col-md-12 review">
                <div class="row">
                    <div class="col-md-1">
                        <div class="profile-image-container review-image" style="background-image: url('<?= $BASE_URL ?>img/users/user.png')"></div>
                    </div>

                    <div class="col-md-9 author-details-container">
                        <h4 class="author-name">
                            <a href="#">Teste teste</a>
                        </h4>
                        <p><i class="fas fa-star"></i>9</p>
                    </div>

                    <div class="col-md-12">
                        <p class="comment-tile">Comentário:</p>
                        <p>Comentário do usuário</p>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</div>

<?php 
    require_once("templates/footer.php");
?>
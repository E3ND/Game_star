<?php
    require_once("templates/header.php");

    require_once("models/User.php");

    require_once("dao/UserDAO.php");

    $userDao = new UserDao($conn, $BASE_URL);
    $user = new User();

    $userData = $userDao->verifyToken(true);
?>
    <div id="main-container" class="container-fluid">
        <div class="offset-md-4 col-md-4 new-game-container">
            <h1 class="page-title">Adicionar Jogo</h1>
            <p class="page-description">Adicione sua crítica e compartilhe com o mundo!</p>
            <form action="<?= $BASE_URL ?>game_process.php" id="add-game-form" method="post" enctype="multipart/form-data">
                <input type="hidden" name="type" value="create">
                <div class="form-group">
                    <label for="title">Título:</label>
                    <input type="text" class="form-control" id="title" name="title" placeholder="Digite o título do seu jogo">
                </div>
                <div class="form-group">
                    <label for="title">Imagem:</label>
                    <input type="file" class="form-controll-file" name="image" id="image">
                </div>
                <div class="form-group">
                    <label for="length">Tempo da campanha:</label>
                    <input type="text" class="form-control" id="title" name="title" placeholder="Digite a duração do jogo">
                </div>
                <div class="form-group">
                    <label for="category">Categoria</label>
                    <select name="category" id="category" class="form-control">
                        <option value="">Selecione</option>
                        <option value="FPS">FPS</option>
                        <option value="RPG">RPG</option>
                        <option value="openWorld">Mundo aberto</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="trailer">Trailer</label>
                    <input type="text" class="form-control" id="title" name="title" placeholder="Insira o link do trailer">
                </div>
                <div class="form-group">
                    <label for="description">Descrição</label>
                    <textarea name="" id="description" class="form-control" name="description" rows="5" placeholder="Descreva o filme..."></textarea>
                </div>

                <input type="submit" class="btn card-btn" value="Adicionar jogo">
            </form>
        </div>
    </div>

<?php 
    require_once("templates/footer.php");
?>
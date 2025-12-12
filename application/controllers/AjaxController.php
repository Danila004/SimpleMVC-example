<?php
namespace application\controllers;

/**
 * Можно использовать для обработки ajax-запросов.
 */
class AjaxController extends \ItForFree\SimpleMVC\MVC\Controller 
{
    /**
     * Подгрузка "лайков" статей или товаров
     */
    public function likeAction()
    {
       echo 'привет!';
    }

    public function getAction()
    {
        $article = new Article();
        $articleId = (int)$_GET['articleId'];
        $responseArticle = $article->getById($articleId);

        print_r($_GET);        // Проверить параметры
        

        if ($responseArticle) {
        echo json_encode([
            'success' => true,
            'content' => $responseArticle->content,
            'method' => 'GET'
        ], JSON_UNESCAPED_UNICODE);
        } else {
            echo json_encode([
                'success' => false,
                'error' => 'Статья с ID ' . $articleId . ' не найдена'
            ]);
        }
        
    }

    public function postAction() 
    {
        $jsonInput = file_get_contents('php://input');
        $input = json_decode($jsonInput, true);
        $articleId = (int)$input['articleId'];

        // Получаем статью из базы данных
        $article = new Article();
        $responseArticle = $article->getById($articleId);
        
        if ($responseArticle) {   
            // Формируем успешный ответ
            echo json_encode([
                'success' => true,
                'content' => $responseArticle->content,
                'method' => 'POST'
            ], JSON_UNESCAPED_UNICODE);
        } 
    }
}


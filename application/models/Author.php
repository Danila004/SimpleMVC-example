<?php
namespace application\models;

use ItForFree\SimpleMVC\MVC\Model;

/**
 * Класс для обработки статей
 */
class Author extends Model
{
    
    public ?int $id = null;

    /**
    * @var int HTML подкатегория статьи
    */
    public $userId = null;

    /**
    * @var int HTML подкатегория статьи
    */
    public $articleId = null;

    public string $tableName = "authors";
    
    public string $orderBy = "id ASC";

   

    public function myGetList($numRows=1000000, $articleId=null, $order="id ASC") 
    {
        $categoryClause = $articleId ? "WHERE articleId = :articleId" : "";
           
        $sql = "SELECT * FROM $this->tableName $categoryClause
                ORDER BY  $order  LIMIT :numRows";
        
        $st = $this->pdo->prepare($sql);
        $st->bindValue(":numRows", $numRows, \PDO::PARAM_INT);
	/**
	 * Можно использовать debugDumpParams() для отладки параметров, 
	 * привязанных выше с помощью bind()
	 * @see https://www.php.net/manual/ru/pdostatement.debugdumpparams.php
	 */
      
        if ($articleId) 
            $st->bindValue( ":articleId", $articleId, \PDO::PARAM_INT);
        
        $st->execute(); // выполняем запрос к базе данных
        $list = array();

        while ($row = $st->fetch()) {
            $author = new Author($row);
            $list[] = $author;
        }

        // Получаем общее количество статей, которые соответствуют критерию
        $sql = "SELECT COUNT(*) AS totalRows FROM $this->tableName $categoryClause";
	    $st = $this->pdo->prepare($sql);
        if ($articleId) 
            $st->bindValue( ":articleId", $articleId, \PDO::PARAM_INT);
	    $st->execute(); // выполняем запрос к базе данных                    
        $totalRows = $st->fetch();
        $conn = null;
        
        return (array(
            "results" => $list, 
            "totalRows" => $totalRows[0]
            ) 
        );
    }

    public function insert() {

        // Есть уже у объекта Article ID?
        if ( !is_null( $this->id ) ) trigger_error ( "Article::insert(): Attempt to insert an Article object that already has its ID property set (to $this->id).", E_USER_ERROR );

        // Вставляем статью
        
        $sql = "INSERT INTO authors (articleId, userId) VALUES (:articleId, :userId )";
        $st = $this->pdo->prepare ( $sql );
        
        $st->bindValue( ":articleId", $this->articleId, \PDO::PARAM_INT );
        
        $st->bindValue( ":userId", $this->userId, \PDO::PARAM_INT );
        $st->execute();
        $this->id = $this->pdo->lastInsertId();

    }
}

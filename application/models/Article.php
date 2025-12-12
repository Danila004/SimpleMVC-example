<?php
namespace application\models;

use ItForFree\SimpleMVC\MVC\Model;

/**
 * Класс для обработки статей
 */
class Article extends Model
{
    
    public ?int $id = null;

    /**
    * @var int Дата первой публикации статьи
    */
    public $publicationDate = null;

    /**
    * @var string Полное название статьи
    */
    public $title = null;

     /**
    * @var int ID категории статьи
    */
    public $categoryId = null;

    /**
    * @var string Краткое описание статьи
    */
    public $summary = null;

    /**
    * @var string HTML содержание статьи
    */
    public $content = null;

    /**
    * @var bool HTML активность статьи
    */
    public $active = null;

    /**
    * @var int HTML подкатегория статьи
    */
    public $subCategoryId = null;

    public string $tableName = "articles";
    
    

    /**
    * Устанавливаем свойства с помощью значений формы редактирования записи в заданном массиве
    *
    * @param assoc Значения записи формы
    */
    public function storeFormValues ( $params ) {

      // Сохраняем все параметры
      $this->__construct( $params );

      // Разбираем и сохраняем дату публикации
      if ( isset($params['publicationDate']) ) {
        $publicationDate = explode ( '-', $params['publicationDate'] );

        if ( count($publicationDate) == 3 ) {
          list ( $y, $m, $d ) = $publicationDate;
          $this->publicationDate = mktime ( 0, 0, 0, $m, $d, $y );
        }
      }
    }

    /**
    * Возвращает все (или диапазон) объекты Article из базы данных
    *
    * @param int $numRows Количество возвращаемых строк (по умолчанию = 1000000)
    * @param int $categoryId Вернуть статьи только из категории с указанным ID
    * @param string $order Столбец, по которому выполняется сортировка статей (по умолчанию = "publicationDate DESC")
    * @param bool $isOnlyActive
    * @return Array|false Двух элементный массив: results => массив объектов Article; totalRows => общее количество строк
    */
    public function myGetList($isOnlyActive = true, $numRows=1000000, 
            $categoryId=null, $subCategoryId=null, $order="publicationDate DESC") 
    {
        
        $fromPart = "FROM articles";
        $categoryClause = "";
        
        if($categoryId)
            $categoryClause = $categoryId ? "WHERE categoryId = :categoryId" : "";
        else
            $categoryClause = $subCategoryId ? "WHERE subCategoryId = :subCategoryId" : "";
        
        if($isOnlyActive)
            $categoryClause ? $categoryClause .= " AND active = 1" : $categoryClause = "WHERE active = 1";
        $sql = "SELECT *, UNIX_TIMESTAMP(publicationDate) 
                AS publicationDate
                $fromPart $categoryClause
                ORDER BY  $order  LIMIT :numRows";
        
        $st = $this->pdo->prepare($sql);
        $st->bindValue(":numRows", $numRows, \PDO::PARAM_INT);
	/**
	 * Можно использовать debugDumpParams() для отладки параметров, 
	 * привязанных выше с помощью bind()
	 * @see https://www.php.net/manual/ru/pdostatement.debugdumpparams.php
	 */
      
        if ($categoryId) 
            $st->bindValue( ":categoryId", $categoryId, \PDO::PARAM_INT);
        if ($subCategoryId) 
            $st->bindValue( ":subCategoryId", $subCategoryId, \PDO::PARAM_INT);
        
        $st->execute(); // выполняем запрос к базе данных
        $list = array();

        while ($row = $st->fetch()) {
            $article = new Article($row);
            $list[] = $article;
        }

        // Получаем общее количество статей, которые соответствуют критерию
        $sql = "SELECT COUNT(*) AS totalRows $fromPart $categoryClause";
	    $st = $this->pdo->prepare($sql);
	    if ($categoryId) 
            $st->bindValue( ":categoryId", $categoryId, \PDO::PARAM_INT);
        if ($subCategoryId) 
            $st->bindValue( ":subCategoryId", $subCategoryId, \PDO::PARAM_INT);
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
        
        $sql = "INSERT INTO articles ( publicationDate, categoryId, title, summary, content, active, subCategoryId ) VALUES ( FROM_UNIXTIME(:publicationDate), :categoryId, :title, :summary, :content, :active, :subCategoryId )";
        $st = $this->pdo->prepare ( $sql );
        $st->bindValue( ":publicationDate", $this->publicationDate, \PDO::PARAM_INT );
        $st->bindValue( ":categoryId", $this->categoryId, \PDO::PARAM_INT );
        $st->bindValue( ":title", $this->title, \PDO::PARAM_STR );
        $st->bindValue( ":summary", $this->summary, \PDO::PARAM_STR );
        $st->bindValue( ":content", $this->content, \PDO::PARAM_STR );
        $st->bindValue( ":active", $this->active, \PDO::PARAM_BOOL );
        $st->bindValue( ":subCategoryId", $this->subCategoryId, \PDO::PARAM_INT );
        $st->execute();
        $this->id = $this->pdo->lastInsertId();

    }

    public function update() {

      // Есть ли у объекта статьи ID?
      if ( is_null( $this->id ) ) trigger_error ( "Article::update(): "
              . "Attempt to update an Article object "
              . "that does not have its ID property set.", E_USER_ERROR );

      // Обновляем статью
      
      $sql = "UPDATE articles SET publicationDate=FROM_UNIXTIME(:publicationDate),"
              . " categoryId=:categoryId, title=:title, summary=:summary,"
              . " content=:content, active=:active, subCategoryId=:subCategoryId WHERE id = :id";
      
      $st = $this->pdo->prepare ( $sql );
      $st->bindValue( ":publicationDate", $this->publicationDate, \PDO::PARAM_INT );
      $st->bindValue( ":categoryId", $this->categoryId, \PDO::PARAM_INT );
      $st->bindValue( ":title", $this->title, \PDO::PARAM_STR );
      $st->bindValue( ":summary", $this->summary, \PDO::PARAM_STR );
      $st->bindValue( ":content", $this->content, \PDO::PARAM_STR );
      $st->bindValue( ":id", $this->id, \PDO::PARAM_INT );
      $st->bindValue( ":active", $this->active, \PDO::PARAM_BOOL );
      $st->bindValue( ":subCategoryId", $this->subCategoryId, \PDO::PARAM_INT );
      $st->execute();
      
    }

}

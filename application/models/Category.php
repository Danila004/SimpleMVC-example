<?php

namespace application\models;

use ItForFree\SimpleMVC\MVC\Model;
/**
 * Класс для обработки категорий статей
 */

class Category extends Model
{
    // Свойства

    
    public ?int $id = null;

    /**
    * @var string Название категории
    */
    public $name = null;

    /**
    * @var string Короткое описание категории
    */
    public $description = null;

    /**
     *  @var string название таблицы
     */
    public string $tableName = 'categories';

    /**
     * @var string Критерий сортировки строк таблицы
     */
    public string $orderBy = "name ASC";
    

    /**
    * Устанавливаем свойства объекта с использованием значений из формы редактирования
    *
    * @param assoc Значения из формы редактирования
    */

    public function storeFormValues ( $params ) {

      // Store all the parameters
      $this->__construct( $params );
    }



    /**
    * Вставляем текущий объект Category в базу данных и устанавливаем его свойство ID.
    */

    public function insert() {

      // У объекта Category уже есть ID?
      if ( !is_null( $this->id ) ) trigger_error ( "Category::insert(): Attempt to insert a Category object that already has its ID property set (to $this->id).", E_USER_ERROR );

      // Вставляем категорию
      
      $sql = "INSERT INTO categories ( name, description ) VALUES ( :name, :description )";
      $st = $this->pdo->prepare ( $sql );
      $st->bindValue( ":name", $this->name, \PDO::PARAM_STR );
      $st->bindValue( ":description", $this->description, \PDO::PARAM_STR );
      $st->execute();
      $this->id = $this->pdo->lastInsertId();
      
    }

    public function update() {

      // У объекта Category  есть ID?
      if ( is_null( $this->id ) ) trigger_error ( "Category::update(): Attempt to update a Category object that does not have its ID property set.", E_USER_ERROR );

      // Обновляем категорию
      
      $sql = "UPDATE categories SET name=:name, description=:description WHERE id = :id";
      $st = $this->pdo->prepare ( $sql );
      $st->bindValue( ":name", $this->name, \PDO::PARAM_STR );
      $st->bindValue( ":description", $this->description, \PDO::PARAM_STR );
      $st->bindValue( ":id", $this->id, \PDO::PARAM_INT );
      $st->execute();
    
    }
}
	  
	


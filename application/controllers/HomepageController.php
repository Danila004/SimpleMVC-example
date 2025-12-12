<?php

namespace application\controllers;

use \application\models\Article;
use \application\models\Category;
use \application\models\SubCategory;
use Exception;

/**
 * Контроллер для домашней страницы
 */
class HomepageController extends \ItForFree\SimpleMVC\MVC\Controller
{
    /**
     * @var string Название страницы
     */
    public $homepageTitle = "Домашняя страница";
    
    /**
     * @var string Пусть к файлу макета 
     */
    public string $layoutPath = 'main.php';
      
    /**
     * Выводит на экран страницу "Домашняя страница"
     */
    public function indexAction()
    {
        $this->view->addVar('homepageTitle', $this->homepageTitle); // передаём переменную по view
        $articleForList = new Article();
        $categoryForList = new Category();
        $subCategoryForlist = new SubCategory();

        $results['articles'] = $articleForList->myGetList()['results'];

        $data = $categoryForList->getList()['results'];
        foreach ( $data as $category ) 
            $results['categories'][$category->id] = $category;
        
        $data = $subCategoryForlist->getList()['results'];
        foreach ( $data as $subCategory ) 
            $results['subCategories'][$subCategory->id] = $subCategory;
        $this->view->addVar('results', $results);
        $this->view->render('homepage/index.php');
    }

    function archiveAction() 
    {
        $results = [];
        $articleForList = new Article();
        $categoryForList = new Category();
        
        
        $categoryId = ( isset( $_GET['categoryId'] ) && $_GET['categoryId'] ) ? (int)$_GET['categoryId'] : null;
        
        $results['category'] = $categoryForList->getById( $categoryId);
        
        $data = $articleForList->myGetList( true, 100000, $results['category'] ? $results['category']->id : null );
        
        $results['articles'] = $data['results'];
        $results['totalRows'] = $data['totalRows'];
        
        $data = $categoryForList->getList();
        $results['categories'] = array();
        
        foreach ( $data['results'] as $category ) {
            $results['categories'][$category->id] = $category;
        }
        
        $results['pageHeading'] = $results['category'] ?  $results['category']->name : "Article Archive";
        $results['pageTitle'] = $results['pageHeading'] . " | Widget News";
        
        
        $this->view->addVar('results', $results);
        $this->view->render('homepage/archive.php');
    }

    function subArchiveAction() 
    {
        $results = [];
        $articleForList = new Article();
        $subCategoryForList = new SubCategory();
        
        $subCategoryId = ( isset( $_GET['subCategoryId'] ) && $_GET['subCategoryId'] ) ? (int)$_GET['subCategoryId'] : null;
        
        $results['subCategory'] = $subCategoryForList->getById( $subCategoryId );
        
        $data = $articleForList->myGetList( true, 100000, null, $results['subCategory'] ? $results['subCategory']->id : null );
        
        $results['articles'] = $data['results'];
        $results['totalRows'] = $data['totalRows'];
        
        $data = $subCategoryForList->getList();
        $results['subCategories'] = array();
        
        foreach ( $data['results'] as $subCategory ) {
            $results['subCategories'][$subCategory->id] = $subCategory;
        }
        
        $results['pageHeading'] = $results['subCategory'] ?  $results['subCategory']->name : "Article Archive";
        $results['pageTitle'] = $results['pageHeading'] . " | Widget News";
        
        
        $this->view->addVar('results', $results);
        $this->view->render('homepage/subArchive.php');
    }

    function viewArticleAction() 
    {
        $articleForList = new Article();
        $subCategoryForList = new SubCategory();
        $categoryForList = new Category();

        $results = array();
        $articleId = (int)$_GET["articleId"];
        $results['article'] = $articleForList->getById($articleId);
        
        if (!$results['article']) {
            throw new Exception("Статья с id = $articleId не найдена");
        }
        
        $data = $categoryForList->getList()['results'];
        foreach ( $data as $category ) 
            $results['categories'][$category->id] = $category;
        
        $data = $subCategoryForList->getList()['results'];
        foreach ( $data as $subCategory ) 
            $results['subCategories'][$subCategory->id] = $subCategory;
        
        $results['subCategory'] = $subCategoryForList->getById($results['article']->subCategoryId);
        $results['category'] = $categoryForList->getById($results['article']->categoryId);
        $results['pageTitle'] = $results['article']->title . " | Простая CMS";
        
        $this->view->addVar('results', $results);
        $this->view->render('homepage/viewArticle.php');
    }
}


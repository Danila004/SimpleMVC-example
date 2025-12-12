<?php
namespace application\controllers\admin;

use application\models\Article;
use application\models\Author;
use application\models\Category;
use application\models\SubCategory;
use ItForFree\SimpleMVC\Config;
use \application\models\UserModel;

class ArticlesController extends \ItForFree\SimpleMVC\MVC\Controller
{
    
    public string $layoutPath = 'admin-main.php';
    
    protected array $rules = [ //вариант 2:  здесь всё гибче, проще развивать в дальнешем
         ['allow' => true, 'roles' => ['admin']],
         ['allow' => false, 'roles' => ['?', '@']],
    ];
    
    /**
     * Основное действие контроллера
     */
    public function indexAction()
    {
        $Adminusers = new Article();
        $categoryForList = new Category();
        $userId = $_GET['id'] ?? null;
        
        if ($userId) { // если указан конктреный пользователь
            $viewAdminusers = $Adminusers->getById($_GET['id']);
            $this->view->addVar('viewAdminusers', $viewAdminusers);
            $this->view->render('user/view-item.php');
        } else { // выводим полный список
            
            $data = $Adminusers->myGetList(false);
            $results['articles'] = $data['results'];
            $results['totalRows'] = $data['totalRows'];
            $data = $categoryForList->getList()['results'];
            foreach($data as $category) {
                $results['categories'][$category->id] = $category;
            }
            $this->view->addVar('results', $results);
            
            $this->view->render('article/index.php');
        }
    }

    /**
     * Создание нового пользователя
     */
    public function addAction()
    {
        $Url = Config::get('core.router.class');
        $results = array();
        $categoryForList = new Category();
        $subCategory = new SubCategory();
        $results['pageTitle'] = "Add Article";
        $results['formAction'] = "addArticle";
        if (!empty($_POST)) {
            if (!empty($_POST['saveChanges'])) {
                $Adminusers = new Article();
                $newAdminusers = $Adminusers->loadFromArray($_POST);
                $newAdminusers->insert(); 
                $this->redirect($Url::link("admin/articles/index"));
            } 
            elseif (!empty($_POST['cancel'])) {
                $this->redirect($Url::link("admin/articles/index"));
            }
        } else {
            $results['article'] = new Article();
            $data = $categoryForList->getList()['results'];
            foreach($data as $category) {
                $results['categories'][$category->id] = $category;
            }
            $data = $subCategory->getList()['results'];
            foreach($data as $subCategory) {
                $results['subCategories'][$subCategory->id] = $subCategory;
            }
            $this->view->addVar('results', $results);
            
            $this->view->render('article/add.php');
        }
    }
    
    /**
     * Редактирование пользователя
     */
    public function editAction()
    {
        $id = $_GET['id'];
        $Url = Config::get('core.router.class');
        $results = array();
        $category = new Category();
        $subCategory = new SubCategory();
        $article = new Article();
        $author = new Author();
        $user = new UserModel();
        $results['pageTitle'] = "Edit Article";
        $results['formAction'] = "editArticle";
        
        if (!empty($_POST)) { // это выполняется нормально.
            
            if (!empty($_POST['saveChanges'] )) {
                
                $data = $subCategory->getList()['results'];
                foreach($data as $subCategory) {
                    $results['subCategories'][$subCategory->id] = $subCategory;
                }

                if(!isset($_POST['categoryId']) && isset($_POST['subCategoryId']) || 
                isset($_POST['subCategoryId']) && $results['subCategories'][(int)$_POST['subCategoryId']]->categoryId != (int)$_POST['categoryId']) {
                    
                    $results['unavailableSubCategory'] = "Ошибка: выбранная подкатегория не соответствует категории";
                    $results['article'] = $article->getById((int)$_GET['id']);
                    $data = $category->getList()['results'];
                    foreach ( $data as $category ) 
                        $results['categories'][$category->id] = $category;
                    $data = $author->myGetList(1000000, $_GET['id'])['results'];
                    foreach ( $data as $author ) 
                        $results['authors'][$author->userId] = $author;
                    $data = $user->getList()['results'];
                    foreach ( $data as $user ) 
                        $results['users'][$user->id] = $user;
                    $this->view->addVar('results', $results);
                    $this->view->render('article/edit.php');  
                
                } else {
                    $spetialAuthor = new Author();
                    $oldAuthors = array();
                    $newAuthors = array();
                    foreach($_POST['authors'] as $author)
                        if((int)$author)
                            $newAuthors[] = (int)$author;

                    $authorsForArticleId = $spetialAuthor->myGetList(1000000, $_GET['id']);
                    foreach($authorsForArticleId['results'] as $author)
                        $oldAuthors[] = $author->userId;

                    $authorsForAdding = array_diff($newAuthors, $oldAuthors);
                    $authorsForDeleting = array_diff($oldAuthors, $newAuthors);

                    foreach($authorsForAdding as $author) {
                        $newAuthor = new Author(['articleId' => $_POST['articleId'], 'userId' => $author]);
                        $newAuthor->insert();
                    }
                    foreach($authorsForArticleId['results'] as $author) {
                        if(in_array($author->userId, $authorsForDeleting))
                            $author->delete();
                    }

                    
                    $newAdminusers = $article->loadFromArray($_POST);
                    $newAdminusers->id = (int)$_GET['id'];
                    $newAdminusers->update();
                    $this->redirect($Url::link("admin/articles/index"));
                }
        } elseif (!empty($_POST['cancel'])) {
            $this->redirect($Url::link("admin/articles/index"));
        }
        } else {
            $results['article'] = $article->getById($id);
            
            $data = $category->getList()['results'];
            foreach($data as $category) {
                $results['categories'][$category->id] = $category;
            }
            $data = $subCategory->getList()['results'];
            foreach($data as $subCategory) {
                $results['subCategories'][$subCategory->id] = $subCategory;
            }
            $data = $author->myGetList(1000000, $_GET['id'])['results'];
            foreach ( $data as $author ) 
            $results['authors'][$author->userId] = $author;
            $data = $user->getList()['results'];
            foreach ( $data as $user )
                $results['users'][$user->id] = $user;
            
            $this->view->addVar('results', $results);
            $this->view->render('article/edit.php');   
        }
        
    }
    
    /**
     * Удаление пользователя
     */
    public function deleteAction()
    {
        $id = $_GET['id'];
        $Url = Config::get('core.router.class');
        
        if (!empty($_POST)) {
            if (!empty($_POST['deleteArticle'])) {
                $Adminusers = new Article();
                $newAdminusers = $Adminusers->loadFromArray($_POST);
                $newAdminusers->id = (int)$_GET['id'];
                $newAdminusers->delete();
                
                $this->redirect($Url::link("admin/articles/index"));
              
            }
            elseif (!empty($_POST['cancel'])) {
                $this->redirect($Url::link("admin/articles/edit&id=$id"));
            }
        } else {
            
            $Adminusers = new Article();
            $deletedArticle = $Adminusers->getById($id);
            $deleteArticleTitle = "Удаление статьи";
            
            $this->view->addVar('deleteArticleTitle', $deleteArticleTitle);
            $this->view->addVar('deletedArticle', $deletedArticle);
            
            $this->view->render('article/delete.php');
        }
    }
}

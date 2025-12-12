<?php
namespace application\controllers\admin;

use application\models\Category;
use ItForFree\SimpleMVC\Config;
use \application\models\UserModel;

/**
 * Администрирование пользователей
 */
class CategoryController extends \ItForFree\SimpleMVC\MVC\Controller
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
        $category = new Category();
        $userId = $_GET['id'] ?? null;
        
        if ($userId) { // если указан конктреный пользователь
            $viewAdminusers = $category->getById($_GET['id']);
            $this->view->addVar('viewAdminusers', $viewAdminusers);
            $this->view->render('user/view-item.php');
        } else { // выводим полный список
            
            $data = $category->getList();
            $results['categories'] = $data['results'];
            $results['totalRows'] = $data['totalRows'];
            $this->view->addVar('results', $results);
            $this->view->render('category/index.php');
        }
    }

    /**
     * Создание нового пользователя
     */
    public function addAction()
    {
        $Url = Config::get('core.router.class');
        $results = array();
        $results['pageTitle'] = "Add Category";
        $results['formAction'] = "addCategory";
        if (!empty($_POST)) {
            if (!empty($_POST['saveNewCategory'])) {
                $Adminusers = new Category();
                $newAdminusers = $Adminusers->loadFromArray($_POST);
                $newAdminusers->insert(); 
                $this->redirect($Url::link("admin/category/index"));
            } 
            elseif (!empty($_POST['cancel'])) {
                $this->redirect($Url::link("admin/category/index"));
            }
        } else {
            $results['category'] = new Category();
            $this->view->addVar('results', $results);
            
            $this->view->render('category/add.php');
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
        $results['pageTitle'] = "Edit Category";
        $results['formAction'] = "editCategory";
        
        if (!empty($_POST)) { // это выполняется нормально.
            
            if (!empty($_POST['saveChanges'] )) {
                $Adminusers = new Category();
                $newAdminusers = $Adminusers->loadFromArray($_POST);
                $newAdminusers->id = (int)$_GET['id'];
                $newAdminusers->update();
                $this->redirect($Url::link("admin/category/index"));
            } 
            elseif (!empty($_POST['cancel'])) {
                $this->redirect($Url::link("admin/category/index&id=$id"));
            }
        } else {
            $Adminusers = new Category();
            $results['category'] = $Adminusers->getById($id);
            
            $this->view->addVar('results', $results);
    
            
            $this->view->render('category/edit.php');   
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
            if (!empty($_POST['deleteCategory'])) {
                $Adminusers = new Category();
                $newAdminusers = $Adminusers->loadFromArray($_POST);
                $newAdminusers->id = (int)$_GET['id'];
                $newAdminusers->delete();
                
                $this->redirect($Url::link("admin/category/index"));
              
            }
            elseif (!empty($_POST['cancel'])) {
                $this->redirect($Url::link("admin/category/edit&id=$id"));
            }
        } else {
            
            $Adminusers = new Category();
            $deletedCategory = $Adminusers->getById($id);
            $deleteCategoryTitle = "Удаление категории";
            
            $this->view->addVar('deleteCategoryTitle', $deleteCategoryTitle);
            $this->view->addVar('deletedCategory', $deletedCategory);
            
            $this->view->render('category/delete.php');
        }
    }
}

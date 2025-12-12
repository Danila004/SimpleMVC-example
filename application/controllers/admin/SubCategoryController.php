<?php
namespace application\controllers\admin;

use application\models\Category;
use application\models\SubCategory;
use ItForFree\SimpleMVC\Config;
use \application\models\UserModel;

/**
 * Администрирование пользователей
 */
class SubCategoryController extends \ItForFree\SimpleMVC\MVC\Controller
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
        $subCategory = new SubCategory();
        $category = new Category();
        $userId = $_GET['id'] ?? null;
        
        /*if ($userId) { // если указан конктреный пользователь
            $viewAdminusers = $subCategory->getById($_GET['id']);
            $this->view->addVar('viewAdminusers', $viewAdminusers);
            $this->view->render('user/view-item.php');
        } else { // выводим полный список */
            
            $data = $subCategory->getList();
            $results['subCategories'] = $data['results'];
            $results['totalRows'] = $data['totalRows'];
            $data = $category->getList()['results'];
            foreach ($data as $category) {
                $results['categories'][$category->id] = $category;
            }
            $this->view->addVar('results', $results);
            $this->view->render('subCategory/index.php');
        //}
    }

    /**
     * Создание нового пользователя
     */
    public function addAction()
    {
        $Url = Config::get('core.router.class');
        $results = array();
        $category = new Category();
        $results['pageTitle'] = "Add subCategory";
        $results['formAction'] = "addCategory";
        if (!empty($_POST)) {
            if (!empty($_POST['saveNewCategory'])) {
                $Adminusers = new SubCategory();
                $newAdminusers = $Adminusers->loadFromArray($_POST);
                $newAdminusers->insert(); 
                $this->redirect($Url::link("admin/subCategory/index"));
            } 
            elseif (!empty($_POST['cancel'])) {
                $this->redirect($Url::link("admin/subCategory/index"));
            }
        } else {
            $results['subCategory'] = new SubCategory();
            $data = $category->getList()['results'];
            foreach ($data as $category) {
                $results['categories'][$category->id] = $category;
            }
            $this->view->addVar('results', $results);
            
            $this->view->render('subCategory/add.php');
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
        $results['pageTitle'] = "Edit subCategory";
        $results['formAction'] = "editCategory";
        
        if (!empty($_POST)) { // это выполняется нормально.
            
            if (!empty($_POST['saveChanges'] )) {
                $Adminusers = new SubCategory();
                $newAdminusers = $Adminusers->loadFromArray($_POST);
                $newAdminusers->id = (int)$_GET['id'];
                $newAdminusers->update();
                $this->redirect($Url::link("admin/subCategory/index"));
            } 
            elseif (!empty($_POST['cancel'])) {
                $this->redirect($Url::link("admin/subCategory/index&id=$id"));
            }
        } else {
            $Adminusers = new SubCategory();
            $results['subCategory'] = $Adminusers->getById($id);
            $data = $category->getList()['results'];
            foreach ($data as $category) {
                $results['categories'][$category->id] = $category;
            }
            $this->view->addVar('results', $results);
    
            
            $this->view->render('subCategory/edit.php');   
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
            if (!empty($_POST['deletesubCategory'])) {
                $Adminusers = new SubCategory();
                $newAdminusers = $Adminusers->loadFromArray($_POST);
                $newAdminusers->id = (int)$_GET['id'];
                $newAdminusers->delete();
                
                $this->redirect($Url::link("admin/subCategory/index"));
              
            }
            elseif (!empty($_POST['cancel'])) {
                $this->redirect($Url::link("admin/subCategory/edit&id=$id"));
            }
        } else {
            
            $Adminusers = new SubCategory();
            $deletedsubCategory = $Adminusers->getById($id);
            $deletesubCategoryTitle = "Удаление подкатегории";
            
            $this->view->addVar('deletesubCategoryTitle', $deletesubCategoryTitle);
            $this->view->addVar('deletedsubCategory', $deletedsubCategory);
            
            $this->view->render('subCategory/delete.php');
        }
    }
}

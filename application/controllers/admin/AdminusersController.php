<?php
namespace application\controllers\admin;
use ItForFree\SimpleMVC\Config;
use \application\models\UserModel;

/**
 * Администрирование пользователей
 */
class AdminusersController extends \ItForFree\SimpleMVC\MVC\Controller
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
        $Adminusers = new UserModel();
        $userId = $_GET['id'] ?? null;
        
        if ($userId) { // если указан конктреный пользователь
            $viewAdminusers = $Adminusers->getById($_GET['id']);
            $this->view->addVar('viewAdminusers', $viewAdminusers);
            $this->view->render('user/view-item.php');
        } else { // выводим полный список
            
            $data = $Adminusers->getList();
            $users = $data['results'];
            $totalRows = $data['totalRows'];
            $this->view->addVar('users', $users);
            $this->view->addVar('totalRows', $totalRows);
            $this->view->render('user/index.php');
        }
    }

    /**
     * Создание нового пользователя
     */
    public function addUserAction()
    {
        $Url = Config::get('core.router.class');
        $results = array();
        $results['pageTitle'] = "Add User";
        $results['formAction'] = "addUser";
        if (!empty($_POST)) {
            if (!empty($_POST['saveNewUser'])) {
                $Adminusers = new UserModel();
                $newAdminusers = $Adminusers->loadFromArray($_POST);
                $newAdminusers->insert(); 
                $this->redirect($Url::link("admin/adminusers/index"));
            } 
            elseif (!empty($_POST['cancel'])) {
                $this->redirect($Url::link("admin/adminusers/index"));
            }
        } else {
            $results['user'] = new UserModel();
            $this->view->addVar('results', $results);
            
            $this->view->render('user/add.php');
        }
    }
    
    /**
     * Редактирование пользователя
     */
    public function editUserAction()
    {
        $id = $_GET['id'];
        $Url = Config::get('core.router.class');
        $results = array();
        $results['pageTitle'] = "Edit User";
        $results['formAction'] = "editUser";
        
        if (!empty($_POST)) { // это выполняется нормально.
            
            if (!empty($_POST['saveChanges'] )) {
                $Adminusers = new UserModel();
                $newAdminusers = $Adminusers->loadFromArray($_POST);
                $newAdminusers->id = (int)$_GET['id'];
                $newAdminusers->update();
                $this->redirect($Url::link("admin/adminusers/index&id=$id"));
            } 
            elseif (!empty($_POST['cancel'])) {
                $this->redirect($Url::link("admin/adminusers/index&id=$id"));
            }
        } else {
            $Adminusers = new UserModel();
            $results['user'] = $Adminusers->getById($id);
            
            $this->view->addVar('results', $results);
    
            
            $this->view->render('user/edit.php');   
        }
        
    }
    
    /**
     * Удаление пользователя
     */
    public function deleteUserAction()
    {
        $id = $_GET['id'];
        $Url = Config::get('core.router.class');
        
        if (!empty($_POST)) {
            if (!empty($_POST['deleteUser'])) {
                $Adminusers = new UserModel();
                $newAdminusers = $Adminusers->loadFromArray($_POST);
                $newAdminusers->id = (int)$_GET['id'];
                $newAdminusers->delete();
                
                $this->redirect($Url::link("admin/adminusers/index"));
              
            }
            elseif (!empty($_POST['cancel'])) {
                $this->redirect($Url::link("admin/adminusers/editUser&id=$id"));
            }
        } else {
            
            $Adminusers = new UserModel();
            $deletedAdminusers = $Adminusers->getById($id);
            $deleteAdminusersTitle = "Удаление пользователя";
            
            $this->view->addVar('deleteAdminusersTitle', $deleteAdminusersTitle);
            $this->view->addVar('deletedAdminusers', $deletedAdminusers);
            
            $this->view->render('user/delete.php');
        }
    }
}

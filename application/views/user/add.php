<?php 
use ItForFree\SimpleMVC\Config;

$Url = Config::getObject('core.router.class');
$User = Config::getObject('core.user.class');
?>

<?php include('includes/admin-users-nav.php'); ?>


<form id="addUser" method="post" action="<?= $Url::link("admin/adminusers/addUser")?>">
    <h1><?php echo htmlspecialchars($results['pageTitle']); ?></h1>

    <input type="hidden" name="userId" value="<?php echo $results['user']->id; ?>"/>
    
    <ul>
        <li>
            <label for="login">Username</label>
            <input type="text" name="login" id="login" 
                placeholder="Username" required 
                value="<?php echo htmlspecialchars($results['user']->login ?? ''); ?>"/>
        </li>
        
        <li>
            <label for="password">Password</label>
            <input type="password" name="password" id="password" 
                placeholder="Password" required value=<?php $results['user']->pass?> />
            
        </li>
        
        <li>
            <label for="active">Active</label>
            <input type="checkbox" name="active"
                value="1" <?php if($results['user']->active) echo "checked"; ?>/>
        </li>
    </ul>
        
    <div class="buttons">
        <input type="submit" name="saveNewUser" value="Save Changes"/>
        <input type="submit" formnovalidate name="cancel" value="Cancel"/>
    </div>

    
</form>

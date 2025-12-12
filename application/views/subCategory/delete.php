<?php 
use ItForFree\SimpleMVC\Config;

$Url = Config::getObject('core.router.class');
?>

<?php //include('includes/admin-users-nav.php'); ?>

<h2><?= $deletesubCategoryTitle ?></h2>

<form method="post" action="<?= $Url::link("admin/subCategory/delete&id=". $_GET['id'])?>" >
    Вы уверены, что хотите удалить подкатегорию?
    
    <input type="hidden" name="id" value="<?= $deletedsubCategory->id ?>">
    <input type="submit" name="deletesubCategory" value="Удалить">
    <input type="submit" name="cancel" value="Вернуться"><br>
</form>

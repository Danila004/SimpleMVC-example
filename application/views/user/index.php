<?php 
use ItForFree\SimpleMVC\Config;

$User = Config::getObject('core.user.class');
?>

<div id="container">
<h1>Users</h2> 
    
<?php if (!empty($users)): ?>
<table class="table">
    <thead>
    <tr>
      <th scope="col">id</th>
      <th scope="col">Логин</th>
      <th scope="col">Active</th>
      <th scope="col"></th>
    </tr>
     </thead>
    <tbody>
    <?php foreach($users as $user): ?>
    <tr>
        <td> <?= $user->id ?> </td>
        <td> <?= "<a href=" . \ItForFree\SimpleMVC\Router\WebRouter::link('admin/adminusers/index&id=' 
		. $user->id . ">{$user->login}</a>" ) ?> </td>
        <td>  <?= $user->active ? 'Yes' : 'No' ?> </td>
        <td>  <?= $User->returnIfAllowed("admin/adminusers/edit",
                    "<a href=" . \ItForFree\SimpleMVC\Router\WebRouter::link("admin/adminusers/editUser&id=". $user->id) 
                    . ">[Редактировать]</a>");?></td>
    </tr>
    <?php endforeach; ?>
    
    </tbody>
</table>

<p><?php echo $totalRows?> article<?php echo ( $totalRows != 1 ) ? 's' : '' ?> in total.</p>

<p><a class="nav-link" href="<?=ItForFree\SimpleMVC\Router\WebRouter::link("admin/adminusers/addUser") ?>">Add a new User </a></p>

<?php else:?>
    <p> Список пользователей пуст. </p>
<?php endif; ?>
</div>

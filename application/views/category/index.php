<?php 
use ItForFree\SimpleMVC\Config;

$User = Config::getObject('core.user.class');
?>
<div id="container">
<h1>Categories</h1>
	  
	<?php if ( isset( $results['errorMessage'] ) ) { ?>
	        <div class="errorMessage"><?php echo $results['errorMessage'] ?></div>
	<?php } ?>
	  
	  
	<?php if ( isset( $results['statusMessage'] ) ) { ?>
	        <div class="statusMessage"><?php echo $results['statusMessage'] ?></div>
	<?php } ?>
	  
            <table>
                <tr>
                    <th>Category</th>
                    <th></th>
                </tr>

        <?php foreach ( $results['categories'] as $category ) { ?>

                <tr onclick="location='admin.php?action=editCategory&amp;categoryId=<?php echo $category->id?>'">
                    <td>
                        <?php echo $category->name?>
                    </td>

                    <td>
                    <?=  $User->returnIfAllowed("admin/category/edit",
                    "<a href=" . \ItForFree\SimpleMVC\Router\WebRouter::link("admin/category/edit&id=". $category->id) 
                    . ">[Редактировать]</a>");?>
                    </td> 
                </tr>

        <?php } ?>

            </table>

            <p><?php echo $results['totalRows']?> categor<?php echo ( $results['totalRows'] != 1 ) ? 'ies' : 'y' ?> in total.</p>

            <p><a class="nav-link" href="<?=ItForFree\SimpleMVC\Router\WebRouter::link("admin/category/add") ?>">Add a new Category </a></p>
</div>

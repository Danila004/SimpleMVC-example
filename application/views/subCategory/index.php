<?php 
use ItForFree\SimpleMVC\Config;

$User = Config::getObject('core.user.class');
?>
<div id="container">
<h1>SubCategories</h1>
	  
	<?php if ( isset( $results['errorMessage'] ) ) { ?>
	        <div class="errorMessage"><?php echo $results['errorMessage'] ?></div>
	<?php } ?>
	  
	  
	<?php if ( isset( $results['statusMessage'] ) ) { ?>
	        <div class="statusMessage"><?php echo $results['statusMessage'] ?></div>
	<?php } ?>
	  
            <table>
                <tr>
                    <th>subCategory</th>
                    <th>Category</th>
                    <th></th>
                </tr>

        <?php foreach ( $results['subCategories'] as $subCategory ) { ?>

                <tr>
                    <td>
                        <?php echo $subCategory->name?>
                    </td>

                    <td>
                        <?php echo $results['categories'][$subCategory->categoryId]->name ?>
                    </td>

                    <td>
                    <?=  $User->returnIfAllowed("admin/subCategory/edit",
                    "<a href=" . \ItForFree\SimpleMVC\Router\WebRouter::link("admin/subCategory/edit&id=". $subCategory->id) 
                    . ">[Редактировать]</a>");?>
                    </td> 
                </tr>

        <?php } ?>

            </table>

            <p><?php echo $results['totalRows']?> categor<?php echo ( $results['totalRows'] != 1 ) ? 'ies' : 'y' ?> in total.</p>

            <p><a class="nav-link" href="<?=ItForFree\SimpleMVC\Router\WebRouter::link("admin/subCategory/add") ?>">Add a new SubCategory </a></p>
</div>

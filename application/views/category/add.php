<?php 
use ItForFree\SimpleMVC\Config;

$Url = Config::getObject('core.router.class');
$User = Config::getObject('core.user.class');
?>


<h1><?php echo $results['pageTitle']?></h1>

        <form id="addCategory" method="post" action="<?= $Url::link("admin/category/add")?>"> 
          <!-- Обработка формы будет направлена файлу admin.php ф-ции newCategory либо editCategory в зависимости от formAction, сохранённого в result-е -->
        <input type="hidden" name="categoryId" value="<?php echo $results['category']->id ?>"/>

    <?php if ( isset( $results['errorMessage'] ) ) { ?>
            <div class="errorMessage"><?php echo $results['errorMessage'] ?></div>
    <?php } ?>

        <ul>

          <li>
            <label for="name">Category Name</label>
            <input type="text" name="name" id="name" placeholder="Name of the category" required autofocus maxlength="255" value="<?php echo isset($results['category']->name) ? htmlspecialchars( $results['category']->name ) : "" ?>" />
          </li>

          <li>
            <label for="description">Description</label>
            <textarea name="description" id="description" placeholder="Brief description of the category" required maxlength="1000" style="height: 5em;"><?php echo isset($results['category']->description) ? htmlspecialchars( $results['category']->description ) : ""?></textarea>
          </li>

        </ul>

        <div class="buttons">
          <input type="submit" name="saveNewCategory" value="Save Changes" />
          <input type="submit" formnovalidate name="cancel" value="Cancel" />
        </div>

      </form>

    

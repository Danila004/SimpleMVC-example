<?php 
use ItForFree\SimpleMVC\Config;

$Url = Config::getObject('core.router.class');
$User = Config::getObject('core.user.class');
?>


<h1><?php echo $results['pageTitle']?></h1>

        <form id="editsubCategory" method="post" action="<?= $Url::link("admin/subCategory/edit&id=" . $_GET['id'])?>"> 
          <!-- Обработка формы будет направлена файлу admin.php ф-ции newCategory либо editCategory в зависимости от formAction, сохранённого в result-е -->
        <input type="hidden" name="categoryId" value="<?php echo $results['subCategory']->id ?>"/>

    <?php if ( isset( $results['errorMessage'] ) ) { ?>
            <div class="errorMessage"><?php echo $results['errorMessage'] ?></div>
    <?php } ?>

        <ul>

          <li>
            <label for="name">subCategory Name</label>
            <input type="text" name="name" id="name" placeholder="Name of the category" required autofocus maxlength="255" value="<?php echo isset($results['subCategory']->name) ? htmlspecialchars( $results['subCategory']->name ) : "" ?>" />
          </li>

          <li>
           <label for="categoryId">Category Name</label>
            <select name="categoryId">
              <option value="0"<?php echo !$results['subCategory']->categoryId ? " selected" : ""?>>(none)</option>
            <?php foreach ( $results['categories'] as $category ) { ?>
              <option value="<?php echo $category->id?>"<?php echo ( $category->id == $results['subCategory']->categoryId ) ? " selected" : ""?>><?php echo htmlspecialchars( $category->name )?></option>
            <?php } ?>
            </select>
          </li>

        </ul>

        <div class="buttons">
          <input type="submit" name="saveChanges" value="Save Changes" />
          <input type="submit" formnovalidate name="cancel" value="Cancel" />
        </div>

      </form>

    

            <p>
                <?= $User->returnIfAllowed("admin/subCategory/delete", 
                "<a href=" . $Url::link("admin/subCategory/delete&id=" . $_GET['id']) 
                . ">[Удалить]</a>");?>
            </p>    

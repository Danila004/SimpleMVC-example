<?php 
use ItForFree\SimpleMVC\Config;

$Url = Config::getObject('core.router.class');
$User = Config::getObject('core.user.class');
?>

<h1><?php echo $results['pageTitle']?></h1>

        <form id="editrticle" method="post" action="<?= $Url::link("admin/articles/edit&id=" . $_GET['id'])?>">
            <input type="hidden" name="articleId" value="<?php echo $results['article']->id ?>">

    <?php if ( isset( $results['errorMessage'] ) ) { ?>
            <div class="errorMessage"><?php echo $results['errorMessage'] ?></div>
    <?php } ?>

    <?php if ( isset( $results['unavailableSubCategory'] ) ) { ?>
            <div class="errorMessage"><?php echo $results['unavailableSubCategory'] ?></div>
    <?php } ?>

            <ul>

              <li>
                <label for="title">Article Title</label>
                <input type="text" name="title" id="title" placeholder="Name of the article" required autofocus maxlength="255" value="<?php echo $results['article']->title ? htmlspecialchars( $results['article']->title) : ""?>" />
              </li>

              <li>
                <label for="summary">Article Summary</label>
                <textarea name="summary" id="summary" placeholder="Brief description of the article" required maxlength="1000" style="height: 5em;"><?php echo $results['article']->summary ? htmlspecialchars( $results['article']->summary ) : ""?></textarea>
              </li>

              <li>
                <label for="content">Article Content</label>
                <textarea name="content" id="content" placeholder="The HTML content of the article" required maxlength="100000" style="height: 30em;"><?php echo $results['article']->content ? htmlspecialchars( $results['article']->content ) : ""?></textarea>
              </li>

              <li>
                <label for="categoryId">Article Category</label>
                <select name="categoryId">
                  <option value="0"<?php echo !$results['article']->categoryId ? " selected" : ""?>>(none)</option>
                <?php foreach ( $results['categories'] as $category ) { ?>
                  <option value="<?php echo $category->id?>"<?php echo ( $category->id == $results['article']->categoryId ) ? " selected" : ""?>><?php echo htmlspecialchars( $category->name )?></option>
                <?php } ?>
                </select>
              </li>

              <li>
                <label for="subCategoryId">Article SubCategory</label>
                <select name="subCategoryId">
                  <option value="0"<?php echo !$results['article']->subCategoryId ? " selected" : ""?>>(none)</option>
                <?php foreach ( $results['subCategories'] as $subCategory ) { ?>
                  <option value="<?php echo $subCategory->id?>"<?php echo ( $subCategory->id == $results['article']->subCategoryId ) ? " selected" : ""?>><?php echo htmlspecialchars( $subCategory->name )?></option>
                <?php } ?>
                </select>
              </li>

              <li>
                <label for="authors[]">Authors</label>
                <select name="authors[]" multiple>
                  <option value="0"<?php echo !isset($results['authors']) ? " selected" : ""?>>(none)</option>
                  <?php foreach ( $results['users'] as $user ) { ?>
                  <option value="<?php echo $user->id?>"<?php if(isset($results['authors'])) echo array_key_exists($user->id, $results['authors'])  ? " selected" : ""?>><?php echo htmlspecialchars( $user->id ) . " " . htmlspecialchars($user->login)?></option>
                  <?php }?>
                </select>
              </li>

              <li>
                <label for="publicationDate">Publication Date</label>
                <input type="date" name="publicationDate" id="publicationDate" placeholder="YYYY-MM-DD" required maxlength="10" value="<?php echo $results['article']->publicationDate ? date( "Y-m-d", strtotime($results['article']->publicationDate) ) : "" ?>" />
              </li>

              <li>
                  <label for="active">Active</label>
                  <input type = "checkbox" name = "active" value = "1" <?php if($results['article']->active) echo 'checked'; ?>>
              </li>

            </ul>

            <div class="buttons">
              <input type="submit" name="saveChanges" value="Save Changes" />
              <input type="submit" formnovalidate name="cancel" value="Cancel" />
            </div>

            <p>
                <?= $User->returnIfAllowed("admin/articles/delete", 
                "<a href=" . $Url::link("admin/articles/delete&id=" . $_GET['id']) 
                . ">[Удалить]</a>");?>
            </p>

        </form>

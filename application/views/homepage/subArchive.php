<?php 
use ItForFree\SimpleMVC\Config;
use ItForFree\SimpleMVC\Router\WebRouter;
use ItForFree\SimpleAsset\SimpleAssetManager;
use application\assets\CustomCSSAsset;
CustomCSSAsset::add();
SimpleAssetManager::printCss();

$User = Config::getObject('core.user.class');
?>


<?php //session_start()?>
<?php include "header.php" ?>
	  
    <h1><?php echo htmlspecialchars( $results['pageHeading'] ) ?></h1>

    <ul id="headlines" class="subArchive">

    <?php foreach ( $results['articles'] as $article ) { ?>

            <li>
                <h2>
                    <span class="pubDate">
                        <?php echo date('j F Y', $article->publicationDate)?>
                    </span>
                    <a href=".?action=viewArticle&amp;articleId=<?php echo $article->id?>">
                        <?php echo htmlspecialchars( $article->title )?>
                    </a>

                    <?php if ( !$results['subCategory'] && $article->subCategoryId ) { ?>
                    <span class="category">
                        in 
                        <a href=".?action=subArchive&amp;subCategoryId=<?php echo $article->categoryId?>">
                            <?php echo htmlspecialchars( $results['subCategories'][$article->subCategoryId]->name ) ?>
                        </a>
                    </span>
                    <?php } ?>          
                </h2>
              <p class="summary"><?php echo htmlspecialchars( $article->summary )?></p>
            </li>

    <?php } ?>

    </ul>

    <p><?php echo $results['totalRows']?> article<?php echo ( $results['totalRows'] != 1 ) ? 's' : '' ?> in total.</p>

    <?php  if ($User->isAllowed("homepage/index")): ?>
        <p><a class="nav-link" href="<?= WebRouter::link("homepage/index") ?>">Return to Homapage</a></p>
    <?php endif; ?>
	  
<?php //include "templates/include/footer.php" ?>

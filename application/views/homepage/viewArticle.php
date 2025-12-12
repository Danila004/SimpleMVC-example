<?php 
use ItForFree\SimpleMVC\Config;
use ItForFree\SimpleMVC\Router\WebRouter;
use application\assets\DemoJavascriptAsset;
DemoJavascriptAsset::add();
use ItForFree\SimpleAsset\SimpleAssetManager;
use application\assets\CustomCSSAsset;
CustomCSSAsset::add();
SimpleAssetManager::printCss();

$User = Config::getObject('core.user.class');
?>

<?php include "header.php" ?>
	  
    <h1 style="width: 75%;"><?php echo htmlspecialchars( $results['article']->title )?></h1>
    <div style="width: 75%; font-style: italic;"><?php echo htmlspecialchars( $results['article']->summary )?></div>
    <div style="width: 75%;"><?php echo $results['article']->content?></div>
    <p class="pubDate">Published on <?php  echo date('j F Y', strtotime($results['article']->publicationDate))?>
    
        <?php if ( $results['category'] ) { ?>
            in 
            <?php  if ($User->isAllowed("homepage/archive")): ?>
                <a class="nav-link" href="<?= WebRouter::link("homepage/archive") . '&categoryId=' . $results['article']->categoryId  ?>">
                <?php echo htmlspecialchars($results['categories'][$results['article']->categoryId]->name )?>
                </a>
            <?php endif ?>
        <?php } ?>
    </p>

    <p class="pubDate">
        <?php if ( $results['subCategory'] ) { ?>
        Подкатегория 
        <?php  if ($User->isAllowed("homepage/subArchive")): ?>
            <a class="nav-link" href="<?= WebRouter::link("homepage/subArchive") . '&subCategoryId=' . $results['article']->subCategoryId ?>">
            <?php echo htmlspecialchars($results['subCategories'][$results['article']->subCategoryId]->name )?>
            </a>
        <?php endif ?>
        <?php } ?>
    </p>

    <?php  if ($User->isAllowed("homepage/index")): ?>
        <p><a class="nav-link" href="<?= WebRouter::link("homepage/index") ?>">Return to Homapage</a></p>
    <?php endif; ?>

<?php //include "templates/include/footer.php" ?>    
                

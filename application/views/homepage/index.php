<?php

use application\assets\CustomCSSAsset;
use application\assets\DemoJavascriptAsset;
use ItForFree\SimpleAsset\SimpleAssetManager;
DemoJavascriptAsset::add();
CustomCSSAsset::add();
SimpleAssetManager::printCss();
SimpleAssetManager::printJs();

use ItForFree\SimpleMVC\Config;
use ItForFree\SimpleMVC\Router\WebRouter;

$User = Config::getObject('core.user.class');
?>


<?php include "header.php" ?>
<div id="container">
    <img id="logo" src="logo.jpg" alt="Widget News" />
    <ul id="headlines">
    <?php foreach ($results['articles'] as $article) { ?>
        <li class='<?php echo $article->id?>'>
            <h2>
                <span class="pubDate">
                    <?php echo date('j F', $article->publicationDate)?>
                </span>
                
                <a href=".?action=viewArticle&amp;articleId=<?php echo $article->id?>">
                    <?php echo htmlspecialchars( $article->title )?>
                </a>
                
                <?php if (isset($article->categoryId)) { ?>
                    <span class="category">
                        Категория 
                        <?php  if ($User->isAllowed("homepage/archive")): ?>
                            <a href="<?= WebRouter::link("homepage/archive") . '&categoryId=' . $article->categoryId  ?>">
                            <?php echo htmlspecialchars($results['categories'][$article->categoryId]->name )?>
                            </a>
                        <?php endif ?>
                    </span>

                    <span class="category">
                        Подкатегория
                        <?php  if ($User->isAllowed("homepage/subArchive")): ?>
                            <a href="<?= WebRouter::link("homepage/subArchive") . '&subCategoryId=' . $article->subCategoryId ?>">
                            <?php echo htmlspecialchars($results['subCategories'][$article->subCategoryId]->name )?>
                            </a>
                        <?php endif ?>
                    </span>
                <?php } 
                else { ?>
                    <span class="category">
                        <?php echo "Без категории"?>
                    </span>
                <?php } ?>
            </h2>
            <p class="content"><?php echo htmlspecialchars(mb_substr($article->content, 0, 50) . "...")?></p>
            
            <!--<img id="loader-identity" src="JS/ajax-loader.gif" alt="gif">-->
            
            <ul class="ajax-load">
                <li><a href=".?action=viewArticle&amp;articleId=<?php echo $article->id?>" class="ajaxArticleBodyByPost" data-contentId="<?php echo $article->id?>">Показать продолжение (POST)</a></li>
                <li><a href=".?action=viewArticle&amp;articleId=<?php echo $article->id?>" class="ajaxArticleBodyByGet" data-contentId="<?php echo $article->id?>">Показать продолжение (GET)</a></li>
                <li><a href=".?action=viewArticle&amp;articleId=<?php echo $article->id?>" class="">(POST) -- NEW</a></li>
                <li><a href=".?action=viewArticle&amp;articleId=<?php echo $article->id?>" class="">(GET)  -- NEW</a></li>
            </ul>
            <a href="<?= WebRouter::link("homepage/viewArticle") . '&articleId=' . $article->id?>" class="showContent" data-contentId="<?php echo $article->id?>">Показать полностью</a>
        </li>
    <?php } ?>
    </ul>

    <?php  if ($User->isAllowed("homepage/archive")): ?>
        <p><a class="nav-link" href="<?= WebRouter::link("homepage/archive") ?>">Article Archive</a></p>
    <?php endif ?>    
</div>

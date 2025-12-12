<?php 
use ItForFree\SimpleMVC\Config;
use application\assets\DemoJavascriptAsset;
DemoJavascriptAsset::add();

$User = Config::getObject('core.user.class');
?>
<div id="container">
<h1>Articles</h1>

<table>
            <tr>
              <th>Publication Date</th>
              <th>Article</th>
              <th>Category</th>
              <th>Active</th>
              <th></th>
            </tr>

<!--<?php echo "<pre>"; print_r ($results['articles'][2]->publicationDate); echo "</pre>"; ?> Обращаемся к дате массива $results. Дата = 0 -->
            
    <?php foreach ( $results['articles'] as $article ) { ?>

            <tr onclick="location='admin.php?action=editArticle&amp;articleId=<?php echo $article->id?>'">
              <td><?php echo date('j M Y', $article->publicationDate)?></td>
              <td>
                <?php echo $article->title?>
              </td>
              <td>
                  
             <!--   <?php echo $results['categories'][$article->categoryId]->name?> Эта строка была скопирована с сайта-->
             <!-- <?php echo "<pre>"; print_r ($article); echo "</pre>"; ?> Здесь объект $article содержит в себе только ID категории. А надо по ID достать название категории-->
            <!--<?php echo "<pre>"; print_r ($results); echo "</pre>"; ?> Здесь есть доступ к полному объекту $results -->
             
                <?php 
                if(isset ($article->categoryId)) {
                    echo $results['categories'][$article->categoryId]->name;                        
                }
                else {
                echo "Без категории";
                }?>
              </td>

              <td>
                <input type = "checkbox" name = "active" onclick = "return false;" <?php if($article->active) echo "checked"; ?>>
              </td>

              <td>
                   <?=  $User->returnIfAllowed("admin/articles/edit",
                    "<a href=" . \ItForFree\SimpleMVC\Router\WebRouter::link("admin/articles/edit&id=". $article->id) 
                    . ">[Редактировать]</a>");?>
                </td>
            </tr>

    <?php } ?>

          </table>

          <p><?php echo $results['totalRows']?> article<?php echo ( $results['totalRows'] != 1 ) ? 's' : '' ?> in total.</p>

          <p><a class="nav-link" href="<?=ItForFree\SimpleMVC\Router\WebRouter::link("admin/articles/add") ?>">Add a new Article </a></p>
</div>

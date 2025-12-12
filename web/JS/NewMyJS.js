/* 
 * Пример работы JS с помощью Asset.
 */
$(function() {
    console.log('Start work');
    homePageAlert();
});

function homePageAlert() {
    $('h1.callAlert').click(function(){
        alert("Привет! Это обработчик клика по заголовку ;)");
    });
}

function showLoaderIdentity() {
    // Добавьте код для показа загрузчика
    console.log('Показываем загрузчик');
}

function hideLoaderIdentity() {
    // Добавьте код для скрытия загрузчика
    console.log('Скрываем загрузчик');
}

function init_get() {
    $('a.ajaxArticleBodyByGet').one('click', function(){
        var contentId = $(this).attr('data-contentId');
        console.log('GET - ID статьи = ', contentId); 
        
        if (!contentId) {
            console.error('Не указан data-contentId');
            return false;
        }
        
        showLoaderIdentity();
        
        $.ajax({
            url: "<?= \ItForFree\SimpleMVC\Router\WebRouter::link('ajax/get') ?>?articleId=" + contentId, 
            method: 'GET',
            dataType: 'json',
            timeout: 10000
        })
        .done(function(response){
            hideLoaderIdentity();
            console.log('GET - Ответ получен', response);
            alert(response.success);
            
            if (response.success) {
                var fullContent = '<div class="article-content">' + response.content + '</div>';
                $('a.ajaxArticleBodyByGet').append(fullContent);
                
                // Убираем ссылку после загрузки контента
                $(this).remove();
            } else {
                alert('Ошибка: ' + response.error);
            }
        })
        .fail(function(xhr, status, error){
            hideLoaderIdentity();
            console.error('GET - Ошибка:', {
                status: status,
                error: error,
                xhr: xhr
            });
            alert('Ошибка загрузки контента (GET)');
        });

        return false;
    });  
}

function init_post() {
    $('a.ajaxArticleBodyByPost').one('click', function(){
        var contentId = $(this).attr('data-contentId');
        console.log('POST - ID статьи = ', contentId);
        
        if (!contentId) {
            console.error('Не указан data-contentId');
            return false;
        }
        
        showLoaderIdentity();
        
        $.ajax({
            url: "<?= \ItForFree\SimpleMVC\Router\WebRouter::link('ajax/post') ?>", 
            method: 'POST',
            data: JSON.stringify({ articleId: contentId }),
            contentType: 'application/json',
            dataType: 'json',
            timeout: 10000
        })
        .done(function(response){
            hideLoaderIdentity();
            console.log('POST - Ответ получен', response);
            
            if (response.success) {
                var fullContent = '<div class="article-content">' + response.content + '</div>';
                $('a.ajaxArticleBodyByPost').append(fullContent);
                
                // Убираем ссылку после загрузки контента
                $(this).remove();
            } else {
                alert('Ошибка: ' + response.error);
            }
        })
        .fail(function(xhr, status, error){
            hideLoaderIdentity();
            console.error('POST - Ошибка:', {
                status: status,
                error: error,
                xhr: xhr
            });
            alert('Ошибка загрузки контента (POST)');
        });

        return false;
    });  
}

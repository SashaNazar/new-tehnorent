/*
 *  Скрипты задействованные в реализации поиска товаров по сайту
 *
 */
var suggest_count = 0;
var input_initial_value = '';
var suggest_selected = 0;

$(window).load(function(){
    // читаем ввод с клавиатуры
    $("#searchbar").keyup(function(I){
        // определяем какие действия нужно делать при нажатии на клавиатуру
        switch(I.keyCode) {
            // игнорируем нажатия на эти клавишы
            case 13:  // enter
            case 27:  // escape
            case 38:  // стрелка вверх
            case 40:  // стрелка вниз
                break;
            default:
                ajax_search();
                break;
        }
    });

    //считываем нажатие клавишь, уже после вывода подсказки
    $("#searchbar").keydown(function(I){
        switch(I.keyCode) {
            // по нажатию клавишь прячем подсказку
            //case 13: // enter
            case 27: // escape
                $('#search_advice_wrapper').hide();
                return false;
                break;
            // делаем переход по подсказке стрелочками клавиатуры
            case 38: // стрелка вверх
            case 40: // стрелка вниз
                //I.preventDefault();
                if(suggest_count){
                    //делаем выделение пунктов в слое, переход по стрелочкам
                    key_activate( I.keyCode-39 );
                }
                break;
        }
    });

    // делаем обработку клика по подсказке
    $('.advice_variant').on('click',function(){
        // ставим текст в input поиска
        $('#searchbar').val($(this).text());
        // прячем слой подсказки
        $('#search_advice_wrapper').fadeOut(350).html('');
    });

    // если кликаем в любом месте сайта, нужно спрятать подсказку
    $('html').click(function(){
        $('#search_advice_wrapper').hide();
    });
    // если кликаем на поле input и есть пункты подсказки, то показываем скрытый слой
    $('#searchbar').click(function(event) { ajax_search(); });
    $('#s_region_value').change(function() { ajax_search(); });
    $('#s_category_value').change(function() { ajax_search(); });
});

function ajax_search(obj) {
    obj = $('#searchbar');

    // производим поиск только при вводе более 2х символов
    if(obj.val().length>2){
        input_initial_value = obj.val();
        // производим AJAX запрос к /s_test.php, передаем ему GET query, в который мы помещаем наш запрос
        $.get("/includes/ajax/seatch.php", {
                "query":obj.val(),
                "region":$('#s_region_value').val(),
                "group":$('#s_category_value').val()
            },
            function(data){
                //php скрипт возвращает нам строку, ее надо распарсить в массив.
                //console.log(data);
                var list = eval("("+data+")");
                suggest_count = list.length;
                if(suggest_count >= 1){
                    // перед показом слоя подсказки, его обнуляем
                    $("#search_advice_wrapper").html("").show();
                    for(var i in list){
                        if(list[i] != ''){
                            // добавляем слою позиции
                            $('#search_advice_wrapper').append('<div class="advice_variant">'+list[i]+'</div>');
                        }
                    }
                }  else {
                    $('#search_advice_wrapper').hide();
                    return false;
                }
            }, 'html'
        );
        suggest_selected=0;
    }  else {
        $('#search_advice_wrapper').hide();
        return false;
    }

    if(suggest_count) $('#search_advice_wrapper').show();

    event.stopPropagation();
}

function key_activate(n){
    $('#search_advice_wrapper div').eq(suggest_selected-1).removeClass('active');

    if(n == 1 && suggest_selected < suggest_count){
        suggest_selected++;
    }else if(n == -1 && suggest_selected > 0){
        suggest_selected--;
    }

    if( suggest_selected > 0){
        $('#search_advice_wrapper div').eq(suggest_selected-1).addClass('active');
        $("#searchbar").val( $('#search_advice_wrapper div').eq(suggest_selected-1).text() );
    } else {
        $("#searchbar").val( input_initial_value );
    }
}
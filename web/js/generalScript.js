
var param = $('meta[name=csrf-param]').attr("content");
var token = $('meta[name=csrf-token]').attr("content");

//Добавление пользователя к селекту
function funct(id,name){

    $('#search_good').modal('hide');
    $('#good_id').append($('<option selected></option>').val(id).html(name));
    $('#id_changed').val(1);
    return false;
}

function loader(type) {
    if(type == 'show') {
        $('.wrap').css('opacity','0.5');
        $('body').append('<div class="loader"><span></span><span></span><span></span><span></span></div>');
        $('#loadAjax').show();
    }
    if(type == 'hide') {
        $('.wrap').css('opacity','1');
        $('.loader').remove();
        $('#loadAjax').hide();
    }
}

//алерты
function alert_messages (text,status,options) {
    var status_element;
    switch (status) {
        case 1: status_element = 'alert-success'; break; // Успех;
        case 2: status_element = 'alert-danger'; break; // Ошибка:
        case 3: status_element = 'alert-warning'; break; // Внимание;
        default : status_element = 'alert-info'; // Инфо;
    }


    // Показываем увдемоления;
    $(".alert__fix").fadeIn(200).addClass(status_element);
    $(".alert__fix .messages").html(text);
    if(options)
        return false;

    // Закрываем через 3 сек;
    setTimeout(function(){
        $(".alert__fix").fadeOut(600).removeClass(status_element);
        $(".alert__fix .messages").html('');
    },5000);
    return false;
}

function addItemToBasket(goodId){
    console.log('addItem');
    var data = {};
    data[param] = token;
    data['goodId'] = goodId;

    $.ajax({
        url: '/ajax/add-good',
        type: "post",
        data: data,
        success: function(response) {
            //разблокируем Input

            alert_messages('Добавлено',1,false);
            var result = JSON.parse(response);
            if(result.status=='true' && result.error ==0){
                $('.basket_result').empty();
                $('.basket_result').html(result.html);
            }
            else{
                $('.help-block').empty();
                $('.help-block').append(result.message);
            }
            console.log('success');
        },
        error: function () {
            alert_messages('Ошибка',2,false);
        }
    });
}

function removeItemFromBasket(goodId) {
    console.log('removeItem');
    var data = {};
    data[param] = token;
    data['goodId'] = goodId;

    $.ajax({
        url: '/ajax/remove-good',
        type: "post",
        data: data,
        success: function(response) {
            //разблокируем Input

            alert_messages('Удалено',1,false);
            var result = JSON.parse(response);
            if(result.status=='true' && result.error ==0){
                $('.basket_result').empty();
                $('.basket_result').html(result.html);
            }
            else{
                $('.help-block').empty();
                $('.help-block').append(result.message);
            }
            console.log('success');
        },
        error: function () {
            alert_messages('Ошибка',2,false);
        }
    });
}

function createOrder(uni) {
    console.log('create order');
    //показать прелоадер
    var data = {};
    data[param] = token;
    data['uni'] = uni;
    $.ajax({
        url: '/ajax/create-order',
        type: "post",
        data: data,
        success: function (response) {
            //разблокируем Input
            console.log('success');
            //loader('hide');
            var result = JSON.parse(response);
            if (result.status == 'true' && result.error == 0) {

            }
            else {

            }
            console.log('success');
        },
        error: function(responce){
            console.log('error');
            alert_messages('Ошибка', 2, false);
        }
    });
}

//поиск товаров
$(document).on('keyup','#search_goods',function () {
    console.log('search start');
    if($(this).val().length>3){
        //блокируем Input
        console.log('ajax');
        loader('show');
        $(this).attr('readonly','true');
        $('.goods-items').empty();

        //показать прелоадер
        var data = {};
        data[param] = token;
        data['search'] = $(this).val();
        $.ajax({
            url: '/ajax/search-goods',
            type: "post",
            data: data,
            success: function(response) {
                //разблокируем Input
                $('#search_goods').removeAttr('readonly');
                loader('hide');
                var result = JSON.parse(response);
                if(result.status=='true' && result.error ==0){
                    $('.help-block').empty();
                    $('.help-block').append('Описание найденых товаро');
                    $('.goods-items').html(result.html);
                }
                else{
                    $('.help-block').empty();
                    $('.help-block').append(result.message);
                }
                console.log('success');
            }
        });
    }
});
// Поиск;
$(document).on('click','.js-button-search',function () {
    var search = $('input.js-value-search').val();
    console.log(search);
    console.log('++');
    window_global('#modal-global','ajax/search-input',{'search':true, 'value':search},'Поиск сертивикат');
});



// Модальная окно (ГЛОБАЛЬНЫЙ МОЖНО ВЕЗДЕ ИСПОЛЬЗОВАТЬ);
function window_global(name,url,objPost,title) {
    var modalContainer = $(name);
    // Размер окно;
    modalContainer.modal('show');
    if(title){
        modalContainer.find(".modal-header h4").text(title);
    }
    //Если нет объекта по умол. пустой;
    if(!objPost) objPost = {};
    modalContainer.find('.modal-body').html('');
    $.ajax({
        url: '/' + url,
        type: "POST",
        data: objPost,
        async: false,
        success: function (data) {
            modalContainer.find('.modal-body').html(data);
            modalContainer.modal('show');
        }
    }).done(function(data) {
        //
    });

    return false;
}
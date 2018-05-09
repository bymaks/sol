//Добавление пользователя к селекту
function funct(id,name){

    $('#search_good').modal('hide');
    $('#good_id').append($('<option selected></option>').val(id).html(name));
    $('#id_changed').val(1);
    return false;
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
    if(options) return false;
    // Закрываем через 3 сек;
    setTimeout(function(){
        $(".alert__fix").fadeOut(600).removeClass(status_element);
        $(".alert__fix .messages").html('');
    },5000);
    return false;
}

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
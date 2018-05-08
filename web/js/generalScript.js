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
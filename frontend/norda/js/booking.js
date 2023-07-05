$(document).ready(function () {
    get_service();
    get_hour();
    $('input[type=radio][name=category_service_id]').change(function() {
        get_service();
    });
    $('input[type=radio][name=day]').change(function() {
        get_hour();
    });
    $('input[type=radio][name=location]').change(function() {
        get_hour();
    });
});
function get_service(){
    $("#services-booking").children().remove();
    var check = $('input[name=category_service_id]:checked');
    if(check.length>0){
        var service = JSON.parse(check.attr('data-service'));
        jQuery.each(service,function( index,value ) {
            let image = value.image ?? 'uploads/default.png';
            var checked = index == 0 ? 'checked' : '';
            var list_service = '<label class="d-flex" for="service'+index+'"><input '+checked+' hidden type="radio" id="service'+index+'" name="service_id" value="'+value.id+'" required="required"><h5 class="m-0">'+value.name+'</h5></label>';
            $("#services-booking").append(list_service);
        });
    }
}
function get_hour(){
    $("#hours-booking").children().remove();
    var check = $('input[name=day]:checked');
    var location = $('input[name=location]:checked').attr('data-slot') ?? 0;
    if(check.length>0){
        var hours = JSON.parse(check.attr('data-hour'));
        var day = new Date();
        jQuery.each(hours,function( index,value ) {
            var hour = new Date(value.hour);
            var full_place = '';
            if(day > hour || value.count > location){
                full_place = 'full-place';
            }
            hour = padTo2Digits(hour.getHours()) + ':' + padTo2Digits(hour.getMinutes());
            var list_hour = '<label class="text-center" for="hour'+index+full_place+'"><input class="input-hour" hidden type="radio" id="hour'+index+'" name="booking_date" value="'+value.hour+'" required="required"><div class="bg-checked '+full_place+'"><span>'+hour+'</span></div></label>';
            $("#hours-booking").append(list_hour);
        });
    }
}
function padTo2Digits(num) {
    return String(num).padStart(2, '0');
}
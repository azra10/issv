jQuery(document).ready(function() {
$=jQuery;
var $captcha_container = $('.captcha-container');
if ($captcha_container.length > 0) {
        var $image = $('img', $captcha_container),
        $anchor = $('a', $captcha_container);
        $anchor.bind('click', function(e) {
                e.preventDefault();
                $image.attr('src', $image.attr('src').replace(/nocache=[0-9]+/, 'nocache=' + +new Date()));
        });
}
$.validator.setDefaults({
    ignore: [],
});
$.extend($.validator.messages,issue_search_vars.validate_msg);
$('#emd_iss_due_date').datepicker({
'dateFormat' : 'mm-dd-yy'});
$('#issue_search').validate({
onfocusout: false,
onkeyup: false,
onclick: false,
errorClass: 'text-danger',
rules: {
  emd_iss_id:{
},
emd_iss_due_date:{
},
},
success: function(label) {
label.remove();
},
errorPlacement: function(error, element) {
if (typeof(element.parent().attr("class")) != "undefined" && element.parent().attr("class").search(/date|time/) != -1) {
error.insertAfter(element.parent().parent());
}
else if(element.attr("class").search("radio") != -1){
error.insertAfter(element.parent().parent());
}
else if(element.attr("class").search("select2-offscreen") != -1){
error.insertAfter(element.parent().parent());
}
else if(element.attr("class").search("selectpicker") != -1 && element.parent().parent().attr("class").search("form-group") == -1){
error.insertAfter(element.parent().find('.bootstrap-select').parent());
} 
else if(element.parent().parent().attr("class").search("pure-g") != -1){
error.insertAfter(element);
}
else {
error.insertAfter(element.parent());
}
},
});
$(document).on('click','#singlebutton_issue_search',function(event){
     var form_id = $(this).closest('form').attr('id');
     $.each(issue_search_vars.issue_search.req, function (ind, val){
         if(!$('input[name='+val+'],#'+ val).closest('.row').is(":hidden")){
             $('input[name='+val+'],#'+ val).rules("add","required"); 
         }
     });
     var valid = $('#' + form_id).valid();
     if(!valid) {
        event.preventDefault();
        return false;
     }
});
});

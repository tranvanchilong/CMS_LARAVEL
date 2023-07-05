$(function () {
    $(":file").change(function () {
        if (this.files && this.files[0]) {
            var reader = new FileReader();
            reader.onload = imageIsLoaded;
            reader.readAsDataURL(this.files[0]);
            console.log(reader);
        }
    });
});

function imageIsLoaded(e) {
    $('#myImg').attr('src', e.target.result);
    $('#myImg').css('display','block');
    $('#image').val(e.target.result);
};
(function ($) {
	"use strict";
    // menu item
    var arrayjson = $('#arrayjson').val();
    // sortable list options
    var sortableListOptions = {
    	placeholderCss: {'background-color': "#cccccc"}
    };

    var editor = new MenuEditor('myEditor', {listOptions: sortableListOptions});
    editor.setForm($('#frmEdit'));
    editor.setUpdateButton($('#btnUpdate'));
    $('#btnReload').on('click', function () {
    	editor.setData(arrayjson);
    });

    $('#btnOutput').on('click', function () {
    	var str = editor.getString();
    	$("#out").text(str);
    });

    $("#btnUpdate").on('click',function(){
    	if ($('#text').val() != '' && $('#href').val() != '') {
    		editor.update();
    	}	
    });

    $('#btnAdd').on('click',function(){
    	if ($('#text').val() != '' && $('#href').val() != '') {
    		editor.add();
    	}
    });

    $('#form-button').on('click',function(){
    	$("#data").val(editor.getString());
    })
    editor.setData(arrayjson);
    
    
})(jQuery);	
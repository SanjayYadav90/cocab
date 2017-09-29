// listen click, open modal and .load content
/*$('#modalButton').click(function (){
    $('#modal_dialog').modal('show')
        .find('#modalContent')
        .load($(this).attr('value'));
});
*/

function OpenModalDialog(url)
{
    $('#modal_dialog').modal('show')
        .find('#modalContent')
        .load(url);
 
 	return false;
 
}
 
// serialize form, render response and close modal
function submitForm($form) {
    $.post(
        $form.attr("action"), // serialize Yii2 form
        $form.serialize()
    )
        .done(function(result) {
            $form.parent().html(result.message);
            $('#modal').modal('hide');
        })
        .fail(function() {
            console.log("server error");
            $form.replaceWith('<button class="newType">Fail</button>').fadeOut()
        });
    return false;
}


function OpenDialogForCheckbox(url)
{
	var chkArray = [];
	
	/* look for all checkboes that have a parent id called 'checkboxlist' attached to it and check if it was checked */
	$("#grid input[name = 'selection[]']:checked").each(function() {
		chkArray.push($(this).val());
	});
	
	/* we join the array separated by the comma */
	var selected;
	selected = chkArray.join(',') + ",";
	
	/* check if there is selected checkboxes, by default the length is 1 as it contains one single comma */
	if(selected.length < 2){
		alert("Please select at least one of the check box");	
	}
	else{
	 url = url+'?ids='+chkArray;
    $('#modal_dialog').modal('show')
        .find('#modalContent')
        .load(url);
 }
 	return false;
 
}


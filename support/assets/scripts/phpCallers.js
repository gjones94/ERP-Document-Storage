function deleteFile(file_id){
    $.ajax({
        type: "POST",
        url: 'support/file_functions.php',
        data: {DELETE: "yes", id: file_id},

        success: function(){
            //refresh page following deletion
            location.reload();
        },

        error: function(){
            alert("Error");
        }
    });
}

/*
function viewFile(userId, fileId){
    $.ajax({
        type: "POST",
        url: 'support/TODO.php',
        data: { VIEW: "yes", user_id: userId, file_id, fileId },
        success: function(){
            location.reload();
        },
        error: function(){
            alert("Error");
        }
    })
}
*/
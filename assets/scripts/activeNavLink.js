//makes the link to the current url in an "active" state on the header
$(document).ready(() =>{ 
    //get the file link of the page
    var url = window.location.href.substring(window.location.href.lastIndexOf("/") + 1);

    //selects the html a element with attribute href = url
    var target = $('a[href="' + url + '"]');
    target.addClass('custom-active'); //custom css class to modify the background color of the active item
})

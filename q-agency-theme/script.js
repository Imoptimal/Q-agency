document.addEventListener("DOMContentLoaded", function() {
    var test = wp.data.select('core/editor').getEditedPostAttribute('title');
    console.log(test);
    console.log('KHM!');
});
jQuery(document).ready(function($) {

    // Clean cookie
    if ('y' === localStorage.getItem('cookieClean')) {
        console.log("clean cookie");
        localStorage.removeItem('cookieClean');
        document.cookie = "eewee_sellsy=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
    }

});

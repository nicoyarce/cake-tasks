$(window).on("load", function () {
    // makes sure the whole site is loaded
    $("#status").delay(1500).fadeOut(); // will first fade out the loading animation
    $("#preloader").delay(800).fadeOut("slow"); // will fade out the white DIV that covers the website.
    $("body").delay(800).css({ overflow: "visible" });
});

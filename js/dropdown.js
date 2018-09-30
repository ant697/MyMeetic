jQuery(document).ready( function($) {
    $("#dropdown").dropDown();

});
(function($) {
    $.fn.dropDown = function() {
        $($(this).data('target') + " a").css('display', 'block')
        $($(this).data('target')).addClass("d-none");
        $($(this).data('target') + " a").addClass("m-1");
        $($(this).data('target') + " a").css("padding", "12px 16px");
        $($(this).data('target') + " a").css("border-radius", "6px");
        $(this).hover(function () {
            $($(this).data('target')).removeClass("d-none");
        }, function () {
            $($(this).data('target')).addClass("d-none");
        })
    };
})(jQuery);

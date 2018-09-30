
jQuery(document).ready( function($) {
    $($('#btnConnect').data("target")).css("display", "none");
    $("#btnConnect").modal("showFlex");
    $('.validRegister').each(function () {
        $($(this).data("target")).css("display", "none");
    });
    $(".validRegister").modal("showBlock");

    $('.popin-dismiss').modal("hide");
    $(document).keyup(function(e) {
        if (e.keyCode === 27) {
            $('.popin-dismiss').modal("hide");
        }
    });
    $(document).click(function(e) {
        if (e.target.id === "myModal") {
                $('.popin-dismiss').modal("hide");
        }
    });

    $(".tab-list").children().each(function () {
        $(this).css("cursor", "pointer");
        $($(this).data("target")).hide();
        $(this).click(function () {
            $(this).parent().children().each(function () {
                $($(this).data('target')).hide();
            });
            $($(this).data("target")).show();
        });
    });
    $('.tooltip').tooltip();
    $(window).scroll(function() {
        $('.navbarScrolled').scrolledNav();
    });
    $('#placeSearch').autocomplete();
    $('#placeSearch2').autocomplete();
    $('#placeSearch3').autocomplete();
    let buttonNewCity = $('#newCity');
    buttonNewCity.modal("showInline");
    $('#citySearch2').css("display", "none");
    $('#citySearch3').css("display", "none");
    buttonNewCity.click(function (e) {
        if ($(this).data('target') === "#citySearch") {
            $(this).data('target', "#citySearch2");
        } else if ($(this).data('target') === "#citySearch2") {
            $(this).data('target', "#citySearch3");
        }
    });
    $('#searchButton').click(function (e) {
        e.preventDefault();
        $(this).search();

    });



});

(function($) {
    $.fn.modal = function(options) {
        $(this).click(function () {
            if (options === "showFlex") {
                $($(this).data("target")).css("display", "flex");
                $($(this).data("dismiss")).css("display", "none");
            } else if (options === "showBlock") {
                $($(this).data("target")).css("display", "block");
                $($(this).data("dismiss")).css("display", "none");
            } else if (options === "showInline") {
                $($(this).data("target")).css("display", "inline-block");
                $($(this).data("dismiss")).css("display", "none");
            } else {
                $($(this).data("dismiss")).css("display", "none");
            }
        });
        if (options === "hide") {
            $($(this).data("dismiss")).css("display", "none");
        }

    };
})(jQuery);


(function ($) {
    $.fn.tooltip = function () {
        $(this).each(function () {
            if ($(this).attr("title") !== "") {
                if ($(this).data("placement") === undefined) {
                    jQuery.data(this, "placement", "top");
                }
                $(this).append("<span class='tooltiptext'" +
                    " data-placement='" + $(this).data("placement") + "'>" + $(this).attr("title") + "</span>");
            }
        });
    };
})(jQuery);

(function ($) {
    $.fn.scrolledNav = function () {
        let scrollTop = $(window).scrollTop();
        if (scrollTop >= 50) {
            $(this).addClass('scrolled-nav');
            let bar = $('.fixed-bar');
            bar.css("top", 60);
        } else if (scrollTop < 50) {
            $(this).removeClass('scrolled-nav');
            $('.fixed-bar').css("top", 101);
        }

    };
})(jQuery);
(function ($) {
    $.fn.autocomplete = function () {
        $(this).focusout(function () {
            $('#autocomplete').remove();
        });
        $(this).keydown(function (event) {
            let keycode = (event.keyCode ? event.keyCode : event.which);
            if (keycode === 13) {
                if ($(this).val() !== $('.selected').text()) {
                    $(this).val($('.selected').text());
                    event.preventDefault();
                } else {
                    $('#autocomplete').remove();
                }
            }
            var listItems = $('.autocomplete-element');
            var key = event.keyCode, selected = listItems.filter('.selected'), current;

            if (key !== 40 && key !== 38) return;

            listItems.removeClass('selected');

            if (key === 40) {
                if (!selected.length || selected.is(':last-child')) {
                    current = listItems.eq(0);
                } else {
                    current = selected.next();
                }
            } else if (key === 38) {
                if (!selected.length || selected.is(':first-child')) {
                    current = listItems.last();
                } else {
                    current = selected.prev();
                }
            }

            current.addClass('selected');
        });
        $(this).on('input', function() {
            var val = $(this).val();
            if (val.indexOf(' ') != -1) {
                var cut = val.split(" ");
                $(this).val(cut.join('-'));
            }
            $('#autocomplete').remove();
            $(this).after("<ul id='autocomplete' class='list-group autocomplete'></ul>");
            let myList = $('#autocomplete');
            myList.css('max-width', $(this).css('width'));
            myList.css('position', 'absolute')
            myList.css('max-height', "300px");
            myList.css('overflow', 'scroll');
            $.get('../outputJSON.php', {
                     'searchautocomplete' : $(this).val()
                 }, function(data) {
                 let myData = JSON.parse(data);
                 let dataAdd = "";
                 for (let i = 0; i < myData.length; i++) {
                     dataAdd += myData[i];
                 }
                 myList.append(dataAdd);
                    $(".autocomplete-element").first().addClass('selected');
                    $('.autocomplete-element').click(function () {
                        $('.selected').each(function () {
                            $(this).removeClass("selected");
                        });
                        $(this).addClass("selected");
                    });
                }
             );
        });
    };
})(jQuery);



(function ($) {
    $.fn.search = function (opt = false) {

        let from = $("#from").val();
        let to = $("#to").val();
        let firstCity = $('#placeSearch').val();
        let secondCity = $('#placeSearch2').val();
        let thirdCity = $('#placeSearch3').val();
        if (opt !== false) {
            firstCity = $('#city').val();
        }
        let sex = $('#sex').val();
        let sexSearch = $('#sexSearch').val();
        let options = {
            'searchUser': true,
            'sex': sex,
            'sexSearch': sexSearch,
            'from': from,
            'to': to,
            'city1': firstCity,
            'city2': secondCity,
            'city3': thirdCity,
        };

        var myData = $.get('../outputJSON.php', options);
        myData.done(function() {
            myData = JSON.parse(myData.responseText);
            let slider = $('#slider');
            slider.removeClass("d-none");
            slider.slider(myData, 0);
        });
        return myData.responseText;
    };
})(jQuery);
(function ($) {
    $.fn.slider = function (options, index) {
        $($(this).data('table')).empty();
        $('#error').remove();
        let previousButton = $($(this).data('previous'));
        let nextButton = $($(this).data('next'));
        $(this).removeClass("alert-danger");
        $(this).removeClass("alert");
        previousButton.removeClass("d-none");
        nextButton.removeClass("d-none");
        previousButton.attr('disabled', false);
        nextButton.attr('disabled', false);
        if (options.length <= 1) {
            previousButton.attr('disabled', true);
            nextButton.attr('disabled', true);
        }
        previousButton.unbind();
        nextButton.unbind();
        nextButton.click(function () {
            index++;
            if (index >= options.length) {
                index = 0;
            }
            $(this).parent().parent().slider(options, index);
        });
        previousButton.click(function () {
            index--;
            if (index < 0) {
                index = options.length - 1;
            }
            $(this).parent().parent().slider(options, index);

        });
        if (options.length <= 0) {
            $(this).addClass("alert alert-danger");
            $(this).append("<h3 id='error'>Désolé nous n'avons aucun membre de cette tranche d'age pour ces" +
                " villes<br>Etendez votre recherche pour plus de résultats</h3>")
            $($(this).data('img')).addClass("d-none");
            $($(this).data('next')).addClass("d-none");
            $($(this).data('previous')).addClass("d-none");
        } else {
            $($(this).data('img')).removeClass("d-none");
            if (options[index].image !== null) {
                $($(this).data('img')).attr('src', "/PHP_my_meetic/upload/" + options[index].image);
                $($(this).data('img')).attr('alt', "Photo");
            } else if (options[index].sex === "M") {
                $($(this).data('img')).attr('src', '/PHP_my_meetic/view/images/avatarHomme.png');
                $($(this).data('img')).attr('alt', "Photo");
            } else {
                $($(this).data('img')).attr('src', '/PHP_my_meetic/view/images/avatarFemme.jpeg');
                $($(this).data('img')).attr('alt', "Photo");
            }
            let sex = $("#sex").val();
            let tableContent = '';
            if (sex === "F") {
                tableContent += '<tr><td>Elle a pour nom :</td><td>' + options[index].nom + '</td></tr>';
                tableContent += '<tr><td>Elle a pour prénom :</td><td>' + options[index].prenom + '</td></tr>';
                tableContent += '<tr><td>Elle a :</td><td>' + options[index].age + ' ans</td></tr>';
                tableContent += '<tr><td>Elle habite a :</td><td>' + options[index].ville + '</td></tr>';
            } else {
                tableContent += '<tr><td>Il a pour nom :</td><td>' + options[index].nom + '</td></tr>';
                tableContent += '<tr><td>Il a pour prénom :</td><td>' + options[index].prenom + '</td></tr>';
                tableContent += '<tr><td>Il a :</td><td>' + options[index].age + ' ans</td></tr>';
                tableContent += '<tr><td>Il habite a :</td><td>' + options[index].ville + '</td></tr>';
            }
            tableContent += '<tr><td id="titleMessage">Message :</td><td><textarea' +
                ' id="message"></textarea><button class="btn btn-primary" id="sendMessage">envoyer</button></td></tr>';
            var table = $($(this).data('table'));
            table.append(tableContent);
            $('#sendMessage').click(function () {
                $.get('../outputJSON.php', {
                    'message' : $('#message').val(),
                    'sender' : $('#id').val(),
                    'receiver' : options[index].id,
                }, function(data) {
                    if (JSON.parse(data)) {
                        $('#alert').remove();
                        $("#message").remove();
                        $("#titleMessage").remove();
                        $('#sendMessage').remove();
                        table.append("<tr><td id='alert' class='text-success alert alert-success'>Message envoyé avec" +
                            " succès</td></tr>")
                    } else {
                        $('#alert').remove();
                        table.append("<tr><td id='alert' class='text-danger alert alert-danger'>Il y a eu une erreur" +
                            " lors de l'envoi du message</td></tr>")
                    }
                });
            });
        }
    };

})(jQuery);



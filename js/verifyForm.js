jQuery(document).ready(function($) {

$('input').inputVerify();
console.log("slect", $('#sexualSelect').val());
if ($('#sexSelect').val() !== "Sélectionner" && $('#sexualSelect').val() !== "Sélectionner") {
    console.log("okk");
}
    $('.validRegister');
    $(".inputRegister").keypress(function(event){
        if (event.which == '13' || event.keyCode == '13') {
            event.preventDefault();
        }
    });
});

(function($) {
    $.fn.inputVerify = function () {
        $(this).blur(function () {
            switch ($(this).attr('type'))  {
                case "email":
                    if (!verifMail(this)) {
                        return false;
                    }
                    break;
                case "date":
                    if (!verifDate(this)) {
                        return false;
                    }
                    break;
                case "password":
                    if (!verifPass(this)) {
                        return false;
                    }
                    break;
                case "text":
                    if ($(this).attr('name') === "city") {
                        var city = this;
                        $.get('../outputJSON.php', {
                            'checkCity' : $(this).val()
                        }, function(data) {
                            if (JSON.parse(data) === true) {
                                surligne(city, false);
                            } else {
                                surligne(city, true);
                                return false;
                            }
                        });
                    } else {
                        if (!verifName(this)) {
                            return false;
                        }
                    }
            }

        });
    }
})(jQuery);

function surligne(champ, erreur) {
    if(erreur)
        champ.style.backgroundColor = "#fba";
    else
        champ.style.backgroundColor = "";
}

function verifMail(champ) {
    var regex = /^[a-zA-Z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$/;
    if(!regex.test(champ.value)) {
        surligne(champ, true);
        return false;
    } else {
        surligne(champ, false);
        return true;
    }
}

function verifPass(champ) {
    var regex = /^\S*(?=\S{8,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])\S*$/;
    if(!regex.test(champ.value)) {
        surligne(champ, true);
        return false;
    } else {
        surligne(champ, false);
        return true;
    }
}

function verifName(champ) {
    let regex = /^[a-zA-Z0-9_]{3,16}$/;
    if(!regex.test(champ.value)) {
        surligne(champ, true);
        return false;
    } else {
        surligne(champ, false);
        return true;
    }
}

function verifDate(champ) {
    var regex = /^(?:(?:31(\/|-|\.)(?:0?[13578]|1[02]))\1|(?:(?:29|30)(\/|-|\.)(?:0?[1,3-9]|1[0-2])\2))(?:(?:1[6-9]|[2-9]\d)?\d{2})$|^(?:29(\/|-|\.)0?2\3(?:(?:(?:1[6-9]|[2-9]\d)?(?:0[48]|[2468][048]|[13579][26])|(?:(?:16|[2468][048]|[3579][26])00))))$|^(?:0?[1-9]|1\d|2[0-8])(\/|-|\.)(?:(?:0?[1-9])|(?:1[0-2]))\4(?:(?:1[6-9]|[2-9]\d)?\d{2})$/;
    if (!regex.test(champ.value)) {
        surligne(champ, true);
        return false;
    } else {
        surligne(champ, false);
        return true;
    }
}

function verifForm(f) {

    if(nameOk && mailOk && caseHomme.checked
        || nameOk && mailOk && caseFemme.checked) {
        return true;
    } else if (!nameOk && mailOk) {
        alert("Veuillez remplir correctement le mail SVP");
        return false;
    } else if (!mailOk && nameOk) {
        alert("Veuillez remplir correctement le nom SVP");
        return false;
    } else {
        alert("Veuillez remplir correctement tous les champs SVP");
        return false;
    }
}
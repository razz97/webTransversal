$(document).ready(inicio);
function inicio() {
    if ($("#infoFrom").val()==="fan") {
        showFan();
    } else if ($("#infoFrom").val()==="musico") {
        showMusic();
    } else if ($("#infoFrom").val()==="local") {
        showLocal();
    } else {
        $("#info").html="";
    }
    $("#provincia").on('change', getCiudades);
    $("#btnMusic").click(showMusic);
    $("#btnFan").click(showFan);
    $("#btnLocal").click(showLocal);
}
function showMusic() {
    $("#optionsRegister").css({"display": "none"});
    $("#register").css({"display": "inline-block"});
    var table = '<div id="registerMusic" class="specificRegister"><h2>Datos de músico</h2><table>' +
            '<tr><td><label for="name"> Nombre: </label></td><td><input type="text" name="name" maxlength="50" required></td></tr>' +
            '<tr><td><label for="surname" >Apellido: </label></td><td><input type="text" name="surname" maxlength="50" required></td></tr>' +
            '<tr><td><label for="art"> Nombre Artistico: </label></td><td><input type="text" name="art" maxlength="50" required></td></tr>' +
            '<tr><td><label for="comp" >Numero componentes: </label></td><td><input type="number" name="comp" max="128" required></td></tr>' +
            '<tr><td><label for="web" >Web: </label></td><td><input type="url" name="web" maxlength="200" required></td></tr>' +
            '<tr><td><label for="genre" >Género: </label></td><td><select name="genre" id="genre" required>' +
            '<option disabled selected >Genero musical</option> ';
    $.ajax({
        url: 'ajax.php',
        type: 'POST',
        dataType: 'json',
        data: {genre: true},
        success: function (answer) {
            for (var a in answer) {
                $("#genre").append($ ('<option value="' + answer[a]["id"] + '">' + answer[a]["nombre"] + "</option>"));
            }
        }
    });
    table +='</select> </td></tr></table><input type="hidden" name="musico" value="musico">';
    musicForm = document.getElementById("registerUser");
    musicForm.insertAdjacentHTML('afterend', table); 
    $("#backOptions").css({"display": "inline-block"});
    $("#backOptions").click(showOptions);
    $("#backIndex").css({"left": "34.5vw"});
}
function showFan() {
    $("#optionsRegister").css({"display": "none"});
    $("#register").css({"display": "inline-block"});
    var table = "<div class='specificRegister'> <h2>Datos de fan</h2><table><tr><td><label for='name'>Nombre:" +
            "</label></td><td><input type='text' name='name'  maxlength='20' required></td></tr>" +
            "<tr><td><label for='surname'> Apellido: </label></td><td><input type='text' name='surname' maxlength='20' required></td></tr>" +
            "<tr><td><label for='add'> Direccion: </label></td><td><input type='text' name='add' maxlength='50' required></td></tr></table>"+
            "<input type='hidden' name='fan' value='fan'>";
    fanForm = document.getElementById("registerUser");
    fanForm.insertAdjacentHTML('afterend', table);
    $("#backOptions").css({"display": "inline-block"});
    $("#backOptions").click(showOptions);
    $("#backIndex").css({"left": "34.5vw"});
}
function showLocal() {
    $("#optionsRegister").css({"display": "none"});
    $("#register").css({"display": "inline-block"});
    var table='<div id="registerLocal" class="specificRegister"><h2>Datos de local</h2><table>'+
        '<tr><td><label for="localname"> Nombre del local: </label></td><td><input type="text" name="localname" required></td></tr>'+ 
        '<tr><td><label for="aforo"> Aforo maximo</label></td><td><input type="number"  name="aforo" required></td></tr>'+
        '<tr><td><label for="add"> Ubicacion: </label></td><td><input type="text" name="add" maxlength="50" required></td></tr></table></div>'+
        '<input type="hidden" name="local" value="local">';
    localForm = document.getElementById("registerUser");
    localForm.insertAdjacentHTML('afterend', table);
    $("#backOptions").css({"display": "inline-block"});
    $("#backOptions").click(showOptions);
    $("#backIndex").css({"left": "34.5vw"});
}
function showOptions() {
    $("#optionsRegister").css({"display": "inline-block"});
    $("#register").css({"display": "none"});
    $("#backOptions").css({"display": "none"});
    $("#backIndex").css({"left": "41.5vw"});
    $(".specificRegister").remove();
//    $("#infoFrom").remove();
    $("#info").empty();
    
}
function getCiudades() {
    var provincia = this.value;
    $.ajax({
        url: 'ajax.php',
        type: 'POST',
        dataType: 'json',
        data: {prov: provincia},
        success: function (answer) {
            $("#todoCiudad").css({visibility: "visible"});
            $("#selectCiudad").html("");
            for (var a in answer) {
                $("#selectCiudad").append("<option value='" + answer[a]["id"] + "'>" + answer[a]["nombre"] + "</option>");
            }
        }
    });
}
$(document).ready(inicio);
function inicio() {
	if ($("#infoForm").val() === "optionSelected") {
		hideEditOptions();
	}
	$("#provincia").on('change', seleccionar);
	$("#showOptionsEdit").click(function () { 
		show($("#optionsEdit"), $("#showOptionsEdit")); 
		$("#formEdit").fadeIn(1000);
		$("#backOption").fadeIn(1000);
	});
	$("#showCreateConcert").click(function () { show($("#createConcert"), $("#showCreateConcert"));});
	$("#showDeleteConcert").click(function () { show($("#deleteConcert"), $("#showDeleteConcert"));});
	$("#showInfoConcert").click(function () { show($("#infoConcert"), $("#showInfoConcert"));});
	$("#showVoteMusic").click(function () { show($("#voteMusico"), $("#showVoteMusic"));});
	$("#showVoteConcierto").click(function () { show($("#voteConcierto"), $("#showVoteConcierto"));});
	$("#showRequest").click(function () { show($("#request"), $("#showRequest"));});
	$("#showRegister").click(function () { show($("#register"), $("#showRegister"));});
}
function seleccionar() {
	var provincia = this.value;
	$.ajax({
		url: 'ajax.php',
		type: 'POST',
		dataType: 'json',
		data: {prov: provincia},
		success: function (answer) {
			$("#ciudads").css({visibility: "visible"});
			$("#ciudad").html("");
			for (var a in answer) {
				$("#ciudad").append("<option value='" + answer[a]["id"] + "'>" + answer[a]["nombre"] + "</option>");
			}
		}
	});
}
function hoverMenuIn() {
	$(this).css({"background-color": "rgba(255,255,255,0.6)"});
}
function hoverMenuOut() {
	$(this).css({"backgroundColor": "rgb(150, 15, 15)"});
}
function hideEditOptions() {
	$(".optionEdit").css({"display": "none"});
	$("#optionSubmitEdit").css({"display": "none"});
}
function show(nodeToShow, buttonUsed) {
	$(".functionality").fadeOut(1000);
	nodeToShow.fadeIn(1000);
	$(".menuButton").css({"backgroundColor": "rgb(150, 15, 15)"});
	buttonUsed.css({"backgroundColor": "rgba(255,255,255,0.6)"});
	$(".menuButton").hover(hoverMenuIn, hoverMenuOut);
	buttonUsed.off("mouseenter mouseleave");
}
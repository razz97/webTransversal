$(document).ready(inicio);
function inicio() {
    $(".slick").slick({
        autoplay: true,
        speed:2000,
        autoplaySpeed: 4000,
        dots: true,
        slidesToShow: 1,
        nextArrow: "<button type='button' class='slick-next' style='border-radius: 28px;color:black;width:4vw; height:3vh; background-color: #ff3333'; ></button>",
        prevArrow: "<button type='button' class='slick-prev' style='border-radius: 28px; color:black;width:4vw; height:3vh; background-color: #ff3333; z-index:100'; ></button>"
    });
}

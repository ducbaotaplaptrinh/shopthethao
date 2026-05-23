// Swiper JS
const swiperElement = document.querySelector(".swiper");
if (swiperElement) {
    new Swiper(swiperElement, {
        loop: true,
        speed: 1000,
        grabCursor: true,
        autoplay: {
            delay: 3000,
        },
    });
}

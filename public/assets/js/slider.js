document.addEventListener("DOMContentLoaded", function () {
    if (typeof Swiper === "undefined") return;

    function initSwiper(selector, options = {}) {
        const element = document.querySelector(selector);

        if (!element) return null;

        return new Swiper(selector, options);
    }

    function initProductFilter({
        buttonSelector,
        itemSelector,
        swiperInstance,
    }) {
        const buttons = document.querySelectorAll(buttonSelector);

        const items = document.querySelectorAll(itemSelector);

        if (!buttons.length || !items.length) return;

        function setActive(button) {
            buttons.forEach((btn) => {
                btn.classList.remove("active");
            });

            button.classList.add("active");
        }

        function filter(categoryId) {
            items.forEach((item) => {
                const itemCategory = item.getAttribute("data-category-id");

                const isAll =
                    String(categoryId) === "0" || String(categoryId) === "all";

                const isVisible =
                    isAll || String(itemCategory) === String(categoryId);

                item.style.display = isVisible ? "" : "none";
            });

            if (swiperInstance) {
                swiperInstance.update();

                swiperInstance.slideTo(0);
            }
        }

        buttons.forEach((button) => {
            button.addEventListener("click", function () {
                const categoryId = this.getAttribute("data-category-id");

                setActive(this);

                filter(categoryId);
            });

            button.addEventListener("keydown", function (e) {
                if (e.key === "Enter" || e.key === " ") {
                    e.preventDefault();

                    this.click();
                }
            });
        });

        filter("0");
    }

    // slidesPerView:
    // số lượng slide hiển thị

    // spaceBetween:
    // khoảng cách giữa các slide

    // freeMode:
    // cho phép kéo tự do không snap cứng

    // grabCursor:
    // hiện icon bàn tay khi hover

    // simulateTouch:
    // hỗ trợ kéo bằng chuột

    // touchStartPreventDefault:
    // không chặn click mặc định

    // preventClicks:
    // cho phép click khi đang kéo

    // threshold:
    // khoảng cách tối thiểu để bắt đầu drag

    // slideToClickedSlide:
    // click slide sẽ tự scroll tới

    // navigation:
    // nút next prev

    // pagination:
    // dấu chấm pagination

    // clickable:
    // cho phép click pagination

    // loop:
    // lặp vô hạn

    // autoplay:
    // tự động chuyển slide

    // delay:
    // thời gian chuyển slide

    // disableOnInteraction:
    // kéo tay vẫn autoplay

    // breakpoints:
    // responsive theo màn hình

    initSwiper(".product-cats-swiper", {
        slidesPerView: "auto",

        spaceBetween: 10,

        freeMode: true,

        grabCursor: true,

        simulateTouch: true,

        touchStartPreventDefault: false,

        preventClicks: false,

        threshold: 5,

        slideToClickedSlide: true,

        navigation: {
            nextEl: ".product-cats-next",
            prevEl: ".product-cats-prev",
        },

        watchOverflow: true,
    });

    // Home/banner slider on top of the homepage
    initSwiper(".slider-section .swiper", {
        slidesPerView: 1,
        loop: true,
        autoplay: {
            delay: 4000,
            disableOnInteraction: false,
        },
        watchOverflow: true,
    });

    const productSwiper = initSwiper(".product-swiper", {
        slidesPerView: 1.2,

        spaceBetween: 20,

        loop: false,

        pagination: {
            el: ".product-swiper .swiper-pagination",
            clickable: true,
        },

        navigation: {
            nextEl: ".product-swiper .swiper-button-next",
            prevEl: ".product-swiper .swiper-button-prev",
        },

        breakpoints: {
            576: {
                slidesPerView: 2,
            },

            768: {
                slidesPerView: 3,
            },

            992: {
                slidesPerView: 4,
            },

            1200: {
                slidesPerView: 5,
            },
        },
    });

    initProductFilter({
        buttonSelector: ".product-cat-btn",

        itemSelector: ".product-item",

        swiperInstance: productSwiper,
    });

    initSwiper(".sale-cats-swiper", {
        slidesPerView: "auto",

        spaceBetween: 10,

        freeMode: true,
    });

    const saleSwiper = initSwiper(".sale-product-swiper", {
        slidesPerView: 1.2,

        spaceBetween: 20,

        loop: false,

        pagination: {
            el: ".sale-product-swiper .swiper-pagination",
            clickable: true,
        },

        navigation: {
            nextEl: ".sale-next",
            prevEl: ".sale-prev",
        },

        breakpoints: {
            576: {
                slidesPerView: 2,
            },

            768: {
                slidesPerView: 3,
            },

            992: {
                slidesPerView: 4,
            },

            1200: {
                slidesPerView: 5,
            },
        },
    });

    initSwiper(".today-swiper", {
        loop: true,

        navigation: {
            nextEl: ".today-next",
            prevEl: ".today-prev",
        },
    });

    initSwiper(".flash-sale-swiper", {
        loop: true,

        spaceBetween: 20,

        navigation: {
            nextEl: ".flash-next",
            prevEl: ".flash-prev",
        },

        breakpoints: {
            0: {
                slidesPerView: 1,
            },

            768: {
                slidesPerView: 2,
            },

            1200: {
                slidesPerView: 3,
            },
        },
    });
});

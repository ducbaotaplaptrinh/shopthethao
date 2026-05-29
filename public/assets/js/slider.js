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

document.addEventListener("DOMContentLoaded", function () {
    if (typeof Swiper === "undefined") return;

    // Khởi tạo Swiper cho danh mục -------------------------------------

    // Tuỳ chọn chính:
    // - slidesPerView: 'auto' => mỗi slide có chiều rộng riêng (phù hợp cho pill)
    // - freeMode: true => cho phép cuộn theo quán tính (không bắt snap cứng)
    // - grabCursor / simulateTouch => cho phép kéo bằng chuột và cảm ứng mượt hơn
    // - touchStartPreventDefault: false và preventClicks: false => vẫn cho phép click
    //   trên item trong khi hỗ trợ kéo (quan trọng để chọn category)
    // - threshold: số pixel tối thiểu để bắt đầu drag, tránh hiểu nhầm click thành drag
    const categorySwiper = new Swiper(".product-cats-swiper", {
        slidesPerView: "auto",
        spaceBetween: 0,
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

    // Khởi tạo Swiper cho sản phẩm ------------------------------------
    // Carousel chính hiển thị sản phẩm; các breakpoints điều chỉnh
    // số slide hiển thị theo kích thước màn hình.
    // Tuỳ chọn chính:
    // - slidesPerView / spaceBetween: cấu hình bố cục và khoảng cách
    // - pagination: chấm phân trang có thể click
    // - navigation: mũi tên next/prev
    // - breakpoints: điều chỉnh slidesPerView theo điểm dừng responsive
    const productSwiper = new Swiper(".product-swiper", {
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

    const categoryButtons = document.querySelectorAll(".product-cat-btn");
    const productSlides = document.querySelectorAll(
        ".product-swiper .product-item",
    );

    function setActiveCategory(button) {
        categoryButtons.forEach((item) => item.classList.remove("active"));
        button.classList.add("active");
    }

    function filterProducts(categoryId) {
        productSlides.forEach((slide) => {
            const slideCategory = slide.getAttribute("data-category-id");
            const isAll =
                String(categoryId) === "0" || String(categoryId) === "all";
            const isVisible =
                isAll || String(slideCategory) === String(categoryId);
            slide.style.display = isVisible ? "" : "none";
        });

        productSwiper.update();
        productSwiper.slideTo(0);
    }

    categoryButtons.forEach((button) => {
        button.addEventListener("click", function () {
            const categoryId = this.getAttribute("data-category-id");
            setActiveCategory(this);
            filterProducts(categoryId);
        });
        button.addEventListener("keydown", function (e) {
            if (e.key === "Enter" || e.key === " ") {
                e.preventDefault();
                this.click();
            }
        });
    });
    filterProducts("0");
});

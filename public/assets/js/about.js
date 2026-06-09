document.addEventListener("DOMContentLoaded", function () {
    const aboutPage = document.querySelector(".about-page");

    if (!aboutPage) return;

    const revealItems = aboutPage.querySelectorAll("[data-reveal]");

    if (!revealItems.length) return;

    aboutPage.classList.add("reveal-ready");

    if (!("IntersectionObserver" in window)) {
        revealItems.forEach(function (item) {
            item.classList.add("is-visible");
        });

        return;
    }

    const observer = new IntersectionObserver(
        function (entries, observerInstance) {
            entries.forEach(function (entry) {
                if (!entry.isIntersecting) return;

                const delay = Number(entry.target.dataset.delay || 0);

                window.setTimeout(function () {
                    entry.target.classList.add("is-visible");
                }, delay);

                observerInstance.unobserve(entry.target);
            });
        },
        {
            threshold: 0.2,
            rootMargin: "0px 0px -8% 0px",
        },
    );

    revealItems.forEach(function (item) {
        observer.observe(item);
    });
});

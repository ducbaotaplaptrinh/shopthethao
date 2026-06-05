(function () {
    const filterPanel = document.querySelector(".js-filter-panel");
    const openFilterButtons = document.querySelectorAll(".js-open-filters");
    const closeFilterButton = document.querySelector(".js-close-filters");
    const productItems = Array.from(
        document.querySelectorAll("[data-product-card]"),
    );
    const paginationRoot = document.querySelector(".js-pagination");
    const resultCount = document.querySelector("[data-result-count]");
    const sortLabel = document.querySelector("[data-sort-label]");
    const sortButtons = document.querySelectorAll("[data-sort-value]");
    const techSearch = document.querySelector("[data-filter-search]");
    const techList = document.querySelector(".js-tech-list");
    const brandChecks = document.querySelectorAll('input[data-filter="brand"]');

    if (!filterPanel || !paginationRoot || productItems.length === 0) {
        return;
    }

    const state = {
        page: 1,
        perPage: 6,
        sort: "featured",
    };

    const priceMatch = (price, range) => {
        if (range === "under500") return price < 500000;
        if (range === "500-1000") return price >= 500000 && price < 1000000;
        if (range === "1000-2000") return price >= 1000000 && price < 2000000;
        if (range === "2000-3000") return price >= 2000000 && price < 3000000;
        if (range === "over3000") return price >= 3000000;
        return true;
    };

    const getCheckedValues = (selector) =>
        Array.from(document.querySelectorAll(selector + ":checked")).map(
            (item) => item.value,
        );

    const activeTechTerm = () =>
        techSearch ? techSearch.value.trim().toLowerCase() : "";

    const filteredItems = () => {
        const prices = getCheckedValues('input[data-filter="price"]');
        const weights = getCheckedValues('input[data-filter="weight"]');
        const brands = getCheckedValues('input[data-filter="brand"]');
        const styles = getCheckedValues('input[data-filter="style"]');
        const techs = getCheckedValues('input[data-filter="tech"]');
        const searchTerm = activeTechTerm();

        return productItems.filter((item) => {
            const price = Number(item.dataset.price || 0);
            const weight = (item.dataset.weight || "").toLowerCase();
            const brand = (item.dataset.brand || "").toLowerCase();
            const style = (item.dataset.style || "").toLowerCase();
            const tech = (item.dataset.tech || "").toLowerCase();
            const series = (item.dataset.series || "").toLowerCase();

            const priceOk =
                prices.length === 0 ||
                prices.some((range) => priceMatch(price, range));
            const weightOk =
                weights.length === 0 ||
                weights.some(
                    (range) =>
                        weight.includes(range.replace("-", "")) ||
                        weight.includes(range),
                );
            const brandOk =
                brands.length === 0 ||
                brands.map((value) => value.toLowerCase()).includes(brand);
            const styleOk =
                styles.length === 0 ||
                styles.map((value) => value.toLowerCase()).includes(style);
            const techOk =
                techs.length === 0 ||
                techs.some((value) => tech.includes(value.toLowerCase()));
            const searchOk =
                !searchTerm ||
                tech.includes(searchTerm) ||
                series.includes(searchTerm);

            return (
                priceOk && weightOk && brandOk && styleOk && techOk && searchOk
            );
        });
    };

    const sortItems = (items) => {
        const sorted = [...items];

        switch (state.sort) {
            case "price-asc":
                sorted.sort(
                    (a, b) => Number(a.dataset.price) - Number(b.dataset.price),
                );
                break;
            case "price-desc":
                sorted.sort(
                    (a, b) => Number(b.dataset.price) - Number(a.dataset.price),
                );
                break;
            case "rating":
                sorted.sort(
                    (a, b) =>
                        Number(b.dataset.rating) - Number(a.dataset.rating),
                );
                break;
            case "newest":
                sorted.reverse();
                break;
            default:
                sorted.sort((a, b) => {
                    const badgeA = a.querySelector(".product-card__badge");
                    const badgeB = b.querySelector(".product-card__badge");
                    return (
                        (badgeB ? badgeB.textContent.length : 0) -
                        (badgeA ? badgeA.textContent.length : 0)
                    );
                });
        }

        return sorted;
    };

    const renderPagination = (visibleCount) => {
        const totalPages = Math.max(1, Math.ceil(visibleCount / state.perPage));
        paginationRoot.innerHTML = "";

        const previousDisabled = state.page === 1;
        const nextDisabled = state.page === totalPages;

        const createPageItem = (
            label,
            pageNumber,
            disabled = false,
            active = false,
        ) => {
            const li = document.createElement("li");
            li.className =
                `page-item ${disabled ? "disabled" : ""} ${active ? "active" : ""}`.trim();

            const button = document.createElement("button");
            button.className = "page-link";
            button.type = "button";
            button.textContent = label;

            if (!disabled) {
                button.addEventListener("click", () => {
                    state.page = pageNumber;
                    render();
                });
            } else {
                button.disabled = true;
            }

            li.appendChild(button);
            return li;
        };

        paginationRoot.appendChild(
            createPageItem("«", Math.max(1, state.page - 1), previousDisabled),
        );

        for (let pageNumber = 1; pageNumber <= totalPages; pageNumber += 1) {
            if (
                pageNumber <= 2 ||
                pageNumber === totalPages ||
                Math.abs(pageNumber - state.page) <= 1
            ) {
                paginationRoot.appendChild(
                    createPageItem(
                        String(pageNumber),
                        pageNumber,
                        false,
                        pageNumber === state.page,
                    ),
                );
            } else if (
                paginationRoot.lastElementChild?.dataset?.ellipsis !== "true"
            ) {
                const li = document.createElement("li");
                li.className = "page-item disabled";
                li.dataset.ellipsis = "true";
                li.innerHTML = '<span class="page-link">…</span>';
                paginationRoot.appendChild(li);
            }
        }

        paginationRoot.appendChild(
            createPageItem(
                "»",
                Math.min(totalPages, state.page + 1),
                nextDisabled,
            ),
        );
    };

    const updateFilterPills = () => {
        document.querySelectorAll(".filter-check--pill").forEach((item) => {
            const input = item.querySelector("input");
            item.classList.toggle("is-active", Boolean(input && input.checked));
        });
    };

    const render = () => {
        const matchedItems = sortItems(filteredItems());
        const totalPages = Math.max(
            1,
            Math.ceil(matchedItems.length / state.perPage),
        );
        state.page = Math.min(state.page, totalPages);

        productItems.forEach((item) => {
            item.closest(".js-product-item").style.display = "none";
        });

        const start = (state.page - 1) * state.perPage;
        const visibleItems = matchedItems.slice(start, start + state.perPage);

        visibleItems.forEach((item) => {
            item.closest(".js-product-item").style.display = "";
        });

        if (resultCount) {
            resultCount.textContent = String(matchedItems.length);
        }

        renderPagination(matchedItems.length);

        if (sortLabel) {
            const activeSort = document.querySelector(
                `[data-sort-value="${state.sort}"]`,
            );
            sortLabel.textContent = activeSort
                ? activeSort.textContent.trim()
                : "Nổi bật";
        }

        updateFilterPills();
    };

    sortButtons.forEach((button) => {
        button.addEventListener("click", () => {
            state.sort = button.dataset.sortValue || "featured";
            state.page = 1;
            sortButtons.forEach((item) =>
                item.classList.toggle("active", item === button),
            );
            render();
        });
    });

    document.querySelectorAll("input[data-filter]").forEach((input) => {
        input.addEventListener("change", () => {
            state.page = 1;
            render();
        });
    });

    if (techSearch) {
        techSearch.addEventListener("input", () => {
            state.page = 1;
            render();
        });
    }

    brandChecks.forEach((input) => {
        input.addEventListener("change", () => {
            input
                .closest(".filter-check--pill")
                ?.classList.toggle("is-active", input.checked);
        });
    });

    openFilterButtons.forEach((button) => {
        button.addEventListener("click", () => {
            filterPanel.classList.add("is-open");
            document.body.classList.add("filter-open");
        });
    });

    const closeFilters = () => {
        filterPanel.classList.remove("is-open");
        document.body.classList.remove("filter-open");
    };

    if (closeFilterButton) {
        closeFilterButton.addEventListener("click", closeFilters);
    }

    document.addEventListener("click", (event) => {
        if (!filterPanel.classList.contains("is-open")) {
            return;
        }

        if (
            filterPanel.contains(event.target) ||
            Array.from(openFilterButtons).some((button) =>
                button.contains(event.target),
            )
        ) {
            return;
        }

        closeFilters();
    });

    render();
})();

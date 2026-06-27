document.addEventListener("DOMContentLoaded", function () {
    // AJAX Search Autocomplete Suggestion
    const searchInput = document.querySelector(".search-input");
    const suggestionsDropdown = document.getElementById("searchSuggestions");
    let debounceTimer;

    if (searchInput && suggestionsDropdown) {
        searchInput.setAttribute("autocomplete", "off");

        searchInput.addEventListener("input", function () {
            clearTimeout(debounceTimer);
            const query = this.value.trim();

            if (query.length < 2) {
                suggestionsDropdown.innerHTML = "";
                suggestionsDropdown.style.display = "none";
                return;
            }

            debounceTimer = setTimeout(() => {
                fetch(
                    `?page=search-suggest&keyword=${encodeURIComponent(query)}`,
                )
                    .then((response) => response.json())
                    .then((data) => {
                        suggestionsDropdown.innerHTML = "";
                        if (data.length === 0) {
                            suggestionsDropdown.innerHTML =
                                '<div class="p-3 text-center text-muted small">Không tìm thấy sản phẩm phù hợp</div>';
                            suggestionsDropdown.style.display = "block";
                            return;
                        }

                        data.forEach((item) => {
                            const hasSale = item.old_price !== null;
                            const priceHtml = hasSale
                                ? `<span class="suggestion-price-current">${item.price_formatted}</span><span class="suggestion-price-old">${item.old_price_formatted}</span>`
                                : `<span class="suggestion-price-current">${item.price_formatted}</span>`;

                            const element = document.createElement("a");
                            element.href = `?page=product-detail&slug=${item.slug}`;
                            element.className = "suggestion-item";
                            element.innerHTML = `
                                <img src="${item.image}" class="suggestion-thumb" alt="${item.name}">
                                <div class="suggestion-info">
                                    <div class="suggestion-name text-truncate" style="max-width: 300px; display: block;">${item.name}</div>
                                    <div class="suggestion-price">${priceHtml}</div>
                                </div>
                            `;
                            suggestionsDropdown.appendChild(element);
                        });
                        suggestionsDropdown.style.display = "block";
                    })
                    .catch((err) => {
                        console.error(
                            "Error fetching search suggestions:",
                            err,
                        );
                    });
            }, 300);
        });

        // Hide suggestions when clicking outside
        document.addEventListener("click", function (e) {
            if (
                !searchInput.contains(e.target) &&
                !suggestionsDropdown.contains(e.target)
            ) {
                suggestionsDropdown.style.display = "none";
            }
        });

        // Show suggestions again when focusing on input with existing suggestions
        searchInput.addEventListener("focus", function () {
            if (
                suggestionsDropdown.children.length > 0 &&
                this.value.trim().length >= 2
            ) {
                suggestionsDropdown.style.display = "block";
            }
        });
    }
    const backToTopBtn = document.getElementById("backToTopBtn");

    // Theo dõi hành động cuộn trang của người dùng
    window.onscroll = function () {
        scrollFunction();
    };

    function scrollFunction() {
        // Nếu cuộn xuống hơn 300px thì thêm class 'show' để hiện nút, ngược lại xóa class để ẩn đi
        if (
            document.body.scrollTop > 300 ||
            document.documentElement.scrollTop > 300
        ) {
            backToTopBtn.classList.add("show");
        } else {
            backToTopBtn.classList.remove("show");
        }
    }
});

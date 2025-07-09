document.addEventListener("DOMContentLoaded", () => {
    function setupAutocomplete({ inputId, suggestionsId, onSelect }) {
        const input = document.getElementById(inputId);
        const suggestions = document.getElementById(suggestionsId);

        if (!input || !suggestions) return;

        input.addEventListener("input", () => {
            const query = input.value.trim();

            if (query.length < 2) {
                suggestions.innerHTML = "";
                suggestions.style.display = "none";
                return;
            }

            fetch(`/crypto/autocomplete?query=${encodeURIComponent(query)}`)
                .then((res) => res.json())
                .then((data) => {
                    if (!data.length) {
                        suggestions.innerHTML = "";
                        suggestions.style.display = "none";
                        return;
                    }

                    suggestions.innerHTML = data
                        .map(
                            (coin) => `
                        <li class="px-4 py-2 hover:bg-blue-100 cursor-pointer flex items-center gap-2"
                            data-uuid="${coin.uuid}"
                            data-symbol="${coin.symbol}"
                            data-name="${coin.name}"
                        >
                            <img src="${coin.iconUrl}" alt="${coin.name}" class="w-5 h-5 object-contain" />
                            <span>${coin.name} (${coin.symbol})</span>
                        </li>
                    `
                        )
                        .join("");
                    suggestions.style.display = "block";

                    suggestions.querySelectorAll("li").forEach((li) => {
                        li.addEventListener("click", () => {
                            onSelect(li);
                            suggestions.style.display = "none";
                        });
                    });
                });
        });

        document.addEventListener("click", (e) => {
            if (!input.contains(e.target) && !suggestions.contains(e.target)) {
                suggestions.style.display = "none";
            }
        });
    }

    // Buscador home
    setupAutocomplete({
        inputId: "search-input-home",
        suggestionsId: "suggestions-home",
        onSelect: (li) => {
            const uuid = li.getAttribute("data-uuid");
            window.location.href = `/home/${uuid}`;
        },
    });

    // Buscador transacción
    setupAutocomplete({
        inputId: "search-input-transaction",
        suggestionsId: "suggestions-transaction",
        onSelect: (li) => {
            const uuid = li.getAttribute("data-uuid");
            const symbol = li.getAttribute("data-symbol");
            const name = li.getAttribute("data-name");

            document.getElementById("crypto_id").value = uuid;
            document.getElementById("crypto_name").value = name;
            document.getElementById(
                "search-input-transaction"
            ).value = `${name} (${symbol})`;

            tryGetPrice();
        },
    });

    function getPriceAtDate(cryptoUuid, dateStr) {
        if (!cryptoUuid || !dateStr) return;

        const timestamp = Math.floor(new Date(dateStr).getTime() / 1000);
        const url = `/coin/price?uuid=${cryptoUuid}&timestamp=${timestamp}`;

        fetch(url)
            .then((res) => res.json())
            .then((data) => {
                if (data?.status === "success") {
                    const price = parseFloat(data.data.price).toFixed(2);
                    document.getElementById(
                        "price_usd"
                    ).placeholder = `El precio de cierre el día ${dateStr} es de $${price}`;
                } else {
                    console.warn("No se pudo obtener el precio", data);
                    document.getElementById("price_usd").value = "";
                }
            })
            .catch(() => {
                document.getElementById("price_usd").value = "";
            });
    }

    const cryptoIdInput = document.getElementById("crypto_id");
    const dateInput = document.getElementById("date");

    function tryGetPrice() {
        const cryptoId = cryptoIdInput.value;
        const date = dateInput.value;
        if (cryptoId && date) {
            getPriceAtDate(cryptoId, date);
        }
    }

    //* Lanza la búsqueda cada vez que cambia el crypto o la fecha *//
    cryptoIdInput.addEventListener("change", tryGetPrice);
    dateInput.addEventListener("change", tryGetPrice);

    function updateQuantity() {
        const amountInput = document.getElementById("amount_usd");
        const priceInput = document.getElementById("price_usd");
        const quantityInput = document.getElementById("quantity");

        const amount = parseFloat(amountInput.value);
        const price = parseFloat(priceInput.value);

        if (!isNaN(amount) && amount > 0 && !isNaN(price) && price > 0) {
            quantityInput.value = (amount / price).toFixed(8);
        } else {
            quantityInput.value = "";
        }
    }

    //* Actualiza cantidad al cambiar monto invertido o precio *//
    document
        .getElementById("amount_usd")
        .addEventListener("input", updateQuantity);
    document
        .getElementById("price_usd")
        .addEventListener("input", updateQuantity);
});

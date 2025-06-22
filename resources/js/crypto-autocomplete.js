document.addEventListener('DOMContentLoaded', () => {

    function setupAutocomplete({inputId, suggestionsId, onSelect}) {
        const input = document.getElementById(inputId);
        const suggestions = document.getElementById(suggestionsId);

        if (!input || !suggestions) return;

        input.addEventListener('input', () => {
            const query = input.value.trim();

            if (query.length < 2) {
                suggestions.innerHTML = '';
                suggestions.style.display = 'none';
                return;
            }

            fetch(`/crypto/autocomplete?query=${encodeURIComponent(query)}`)
                .then(res => res.json())
                .then(data => {
                    if (!data.length) {
                        suggestions.innerHTML = '';
                        suggestions.style.display = 'none';
                        return;
                    }

                    suggestions.innerHTML = data.map(coin => `
                        <li class="px-4 py-2 hover:bg-blue-100 cursor-pointer flex items-center gap-2"
                            data-uuid="${coin.uuid}"
                            data-symbol="${coin.symbol}"
                            data-name="${coin.name}"
                        >
                            <img src="${coin.iconUrl}" alt="${coin.name}" class="w-5 h-5 object-contain" />
                            <span>${coin.name} (${coin.symbol})</span>
                        </li>
                    `).join('');
                    suggestions.style.display = 'block';

                    suggestions.querySelectorAll('li').forEach(li => {
                        li.addEventListener('click', () => {
                            onSelect(li);
                            suggestions.style.display = 'none';
                        });
                    });
                });
        });

        document.addEventListener('click', (e) => {
            if (!input.contains(e.target) && !suggestions.contains(e.target)) {
                suggestions.style.display = 'none';
            }
        });
    }

    // Buscador home
    setupAutocomplete({
        inputId: 'search-input-home',
        suggestionsId: 'suggestions-home',
        onSelect: (li) => {
            const uuid = li.getAttribute('data-uuid');
            window.location.href = `/home/${uuid}`;
        }
    });

    // Formulario transacción
    setupAutocomplete({
        inputId: 'search-input-transaction',
        suggestionsId: 'suggestions-transaction',
        onSelect: (li) => {
            const symbol = li.getAttribute('data-symbol');
            const name = li.getAttribute('data-name');

            // Cambia los inputs visibles y ocultos que uses
            document.getElementById('crypto_id').value = symbol;
            document.getElementById('crypto_name').value = name;
            document.getElementById('search-input-transaction').value = `${name} (${symbol})`;
        }
    });
});

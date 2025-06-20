document.addEventListener('DOMContentLoaded', function () {
    const input = document.getElementById('search-input');
    const suggestions = document.getElementById('suggestions');

    input.addEventListener('input', function () {
        const query = this.value.trim();

        if (query.length < 2) {
            suggestions.innerHTML = '';
            suggestions.style.display = 'none';
            return;
        }

        fetch(`/crypto/autocomplete?query=${encodeURIComponent(query)}`)
            .then(res => res.json())
            .then(data => {
                if (data.length === 0) {
                    suggestions.innerHTML = '';
                    suggestions.style.display = 'none';
                    return;
                }

                suggestions.innerHTML = data.map(coin => `
                    <li class="px-4 py-2 hover:bg-blue-100 cursor-pointer flex items-center gap-2" data-uuid="${coin.uuid}">
                        <img src="${coin.iconUrl}" alt="${coin.name}" class="w-5 h-5 object-contain" />
                        <span>${coin.name} (${coin.symbol})</span>
                    </li>
                `).join('');
                suggestions.style.display = 'block';

                suggestions.querySelectorAll('li').forEach(li => {
                    li.addEventListener('click', () => {
                        window.location.href = `/home/${li.getAttribute('data-uuid')}`;
                    });
                });
            });
    });

    document.addEventListener('click', (e) => {
        if (!input.contains(e.target) && !suggestions.contains(e.target)) {
            suggestions.style.display = 'none';
        }
    });
});

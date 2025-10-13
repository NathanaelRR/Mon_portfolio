document.addEventListener('DOMContentLoaded', () => {
    const buttons = document.querySelectorAll('.filter-btn');
    const cards = document.querySelectorAll('.projet-card');

    buttons.forEach(btn => {
        btn.addEventListener('click', () => {
            buttons.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');

            const filter = btn.dataset.filter.toLowerCase();

            cards.forEach(card => {
                const technos = card.dataset.techno ? card.dataset.techno.split(' ') : [];

                if (filter === 'all' || technos.includes(filter)) {
                    card.classList.remove('hidden'); // display: flex revient
                } else {
                    card.classList.add('hidden'); // display: none
                }
            });
        });
    });
});






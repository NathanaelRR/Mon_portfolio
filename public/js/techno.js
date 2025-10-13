document.addEventListener('DOMContentLoaded', function () {
    const buttons = document.querySelectorAll('#techno-buttons .techno-btn');
    const container = document.getElementById('techno-container');

    if (!buttons.length || !container) return;

    buttons.forEach(btn => {
        btn.addEventListener('click', () => {
            const techno = btn.dataset.techno;

            // Si déjà sélectionné → on retire
            if (btn.classList.contains('selected')) {
                btn.classList.remove('selected');
                // Supprimer badge
                const badge = container.querySelector(`.techno-item[data-techno="${techno}"]`);
                if (badge) badge.remove();
                // Supprimer input hidden
                const input = container.querySelector(`input[name="technologies[]"][value="${techno}"]`);
                if (input) input.remove();
                return;
            }

            // Sinon → on ajoute
            btn.classList.add('selected');

            // Créer badge visible
            const span = document.createElement('span');
            span.className = 'techno-item';
            span.dataset.techno = techno;
            span.innerHTML = `${techno} <button type="button" class="remove-techno"></button>`;
            container.appendChild(span);

            // Créer input hidden
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'technologies[]';
            input.value = techno;
            container.appendChild(input);
        });
    });
});

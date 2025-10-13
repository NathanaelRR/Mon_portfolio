document.addEventListener('DOMContentLoaded', function () {

    // === AJOUTER une étape de conception ===
    document.getElementById('add-etape').addEventListener('click', function() {
        let container = document.getElementById('etapes-conception');
        let index = Date.now();
        let html = `
            <div class="etape">
                <input type="text" name="etapes[conception][${index}][titre]" placeholder="Titre" required>
                <textarea name="etapes[conception][${index}][description]" placeholder="Description"></textarea>
                <button type="button" class="remove-etape">Supprimer</button>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', html);
    });

    // === AJOUTER une étape de développement ===
    document.getElementById('add-etape-dev').addEventListener('click', function() {
        let container = document.getElementById('etapes-developpement');
        let index = Date.now();
        let html = `
            <div class="etape">
                <input type="text" name="etapes[developpement][${index}][titre]" placeholder="Titre" required>
                <textarea name="etapes[developpement][${index}][description]" placeholder="Description"></textarea>
                <button type="button" class="remove-etape">Supprimer</button>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', html);
    });

    // === SUPPRIMER une étape ===
    document.body.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-etape')) {
            e.target.closest('.etape').remove();
        }
    });
});

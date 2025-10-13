document.addEventListener('DOMContentLoaded', function () {

    // === AJOUTER une étape de conception ===
    document.getElementById('add-etape').addEventListener('click', function() {
        let container = document.getElementById('etapes-conception');
        let index = Date.now();
        let html = `
            <div class="etape">
                <input type="text" name="etapes[conception][new_${index}][titre]" placeholder="Titre" required>
                <textarea name="etapes[conception][new_${index}][description]" placeholder="Description"></textarea>
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
                <input type="text" name="etapes[developpement][new_${index}][titre]" placeholder="Titre" required>
                <textarea name="etapes[developpement][new_${index}][description]" placeholder="Description"></textarea>
                <button type="button" class="remove-etape">Supprimer</button>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', html);
    });

    // === SUPPRIMER une étape ===
    document.querySelectorAll('#etapes-conception, #etapes-developpement').forEach(container => {
        container.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-etape')) {
                const etapeDiv = e.target.closest('.etape');
                const etapeId = etapeDiv.dataset.id;          // <-- récupère l'ID existant
                const categorie = etapeDiv.dataset.categorie; // <-- "conception" ou "developpement"

                if (etapeId) {
                    // Créer un input hidden pour signaler au backend la suppression
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = categorie === 'conception'
                                ? 'delete_etapes_conception[]'
                                : 'delete_etapes_developpement[]';
                    input.value = etapeId;
                    document.querySelector('form').appendChild(input);

                    // Supprimer visuellement
                    etapeDiv.remove();
                } else {
                    // Nouvelle étape non enregistrée → suppression directe
                    etapeDiv.remove();
                }
            }
        });
    });
});

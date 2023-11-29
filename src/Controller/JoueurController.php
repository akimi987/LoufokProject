<form action="/administrateur/demarrerCadavre" method="POST" class="cadavre-form">

    <div class="form-group">
        <label for="titre">Titre du Cadavre</label>
        <input type="text" id="titre" name="titre" required>
    </div>

    <div class="form-group">
        <label for="dateDebut">Date de Début</label>
        <input type="datetime-local" id="dateDebut" name="dateDebut" required>
    </div>

    <div class="form-group">
        <label for="dateFin">Date de Fin</label>
        <input type="datetime-local" id="dateFin" name="dateFin" required>
    </div>

    <div class="form-group">
        <label for="nbContributions">Nombre Max de Contributions</label>
        <input type="number" id="nbContributions" name="nbContributions" min="1" required>
    </div>

    <div class="form-group">
        <label for="premiereContribution">Première Contribution</label>
        <textarea id="premiereContribution" name="premiereContribution" rows="4" required></textarea>
        <span id="charCount" style="position: relative; bottom: 0; right: 0;">0/280</span>
    </div>
    <button type="submit" class="submit-btn">Planifier</button>
</form>
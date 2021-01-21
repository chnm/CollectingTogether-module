document.addEventListener('DOMContentLoaded', e => {
    const form = document.getElementById('collecting-together-form');
    const projects = document.getElementById('collecting-together-projects');
    const cfSelect = document.getElementById('cf-select');
    const gfSelect = document.getElementById('gf-select');
    const mfSelect = document.getElementById('mf-select');
    const amSelect = document.getElementById('am-select');

    form.addEventListener('submit', e => e.preventDefault());
    form.addEventListener('change', e => {
        saveState();
        filterProjects();
    });
    setSavedState();
    filterProjects()

    async function filterProjects() {
        query = {
            cf: cfSelect.value,
            gf: gfSelect.value,
            mf: mfSelect.value,
            am: amSelect.value,
        };
        let response = await fetch(form.dataset.projectsUrl, {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify(query)
        });
        let text = await response.text();
        projects.innerHTML = text;
    };

    function saveState() {
        const state = {
            cf: cfSelect.value,
            gf: gfSelect.value,
            mf: mfSelect.value,
            am: amSelect.value,
        };
        localStorage.setItem('collecting_together_form', JSON.stringify(state));
    };

    function setSavedState() {
        const state = JSON.parse(localStorage.getItem('collecting_together_form'));
        if (state) {
            cfSelect.value = state.cf ? state.cf : '';
            gfSelect.value = state.gf ? state.gf : '';
            mfSelect.value = state.mf ? state.mf : '';
            amSelect.value = state.am ? state.am : '';
        }
    };
});

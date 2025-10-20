document.addEventListener('DOMContentLoaded', function () {
    if (typeof Chart === 'undefined') { console.error('Chart.js não carregou!'); return; }

    // Usuários
    const userCanvas = document.getElementById('userChart');
    if (userCanvas) {
        const doutores = parseInt(userCanvas.dataset.doutores) || 0;
        const estagiarios = parseInt(userCanvas.dataset.estagiarios) || 0;
        const vitimas = parseInt(userCanvas.dataset.vitimas) || 0;

        if (doutores + estagiarios + vitimas > 0) {
            new Chart(userCanvas.getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: ['Doutores', 'Estagiários', 'Vítimas'],
                    datasets: [{
                        data: [doutores, estagiarios, vitimas],
                        backgroundColor: ['#0d6efd','#09cc06','#ff5b5b'],
                        hoverOffset: 4
                    }]
                }
            });
        }
    }

    // Consultas
    const consultasCanvas = document.getElementById('consultasChart');
    if (consultasCanvas) {
        let labels = [];
        let values = [];
        try {
            labels = JSON.parse(consultasCanvas.dataset.labels || '[]');
            values = JSON.parse(consultasCanvas.dataset.values || '[]');
        } catch(e) { console.error('Erro ao parsear dados das consultas', e); }

        if (labels.length && values.length) {
            new Chart(consultasCanvas.getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: labels,
                    datasets: [{
                        data: values,
                        backgroundColor: ['#FF6384','#36A2EB','#FFCE56','#4BC0C0','#9966FF','#FF9F40','#E7E9ED','#6A5ACD','#F08080'],
                        hoverOffset: 4
                    }]
                }
            });
        }
    }
});

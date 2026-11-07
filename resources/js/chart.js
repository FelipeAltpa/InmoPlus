import { Chart, registerables } from 'chart.js';

Chart.register(...registerables);

// Verde pino, terracota y neutros cálidos de la marca.
const PALETTE = ['#277a67', '#b4532a', '#55a78f', '#d4a373', '#195247', '#8a7f72', '#8ac7b2', '#7c371c'];

function initPropertiesByTypeChart() {
    const canvas = document.getElementById('properties-by-type-chart');
    if (!canvas) {
        return;
    }

    const labels = JSON.parse(canvas.dataset.labels || '[]');
    const data = JSON.parse(canvas.dataset.data || '[]');

    if (labels.length === 0 || data.length === 0) {
        const ctx = canvas.getContext('2d');
        ctx.font = '14px "Instrument Sans", system-ui, sans-serif';
        ctx.fillStyle = '#9ca3af';
        ctx.textAlign = 'center';
        ctx.fillText('No hay datos para mostrar', canvas.width / 2, canvas.height / 2);
        return;
    }

    new Chart(canvas, {
        type: 'doughnut',
        data: {
            labels: labels,
            datasets: [{
                label: 'Propiedades por tipo',
                data: data,
                backgroundColor: PALETTE,
                borderColor: '#ffffff',
                borderWidth: 2,
            }],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                title: { display: false },
                legend: {
                    display: true,
                    position: 'right',
                    labels: {
                        font: {
                            size: 12,
                            family: '"Instrument Sans", system-ui, sans-serif',
                        },
                        color: '#4b5563',
                        padding: 15,
                        usePointStyle: true,
                        pointStyle: 'circle',
                    },
                },
                tooltip: {
                    backgroundColor: 'rgba(17, 24, 39, 0.9)',
                    titleFont: {
                        size: 13,
                        family: '"Instrument Sans", system-ui, sans-serif',
                    },
                    bodyFont: {
                        size: 12,
                        family: '"Instrument Sans", system-ui, sans-serif',
                    },
                    padding: 12,
                    cornerRadius: 8,
                    callbacks: {
                        label: function(context) {
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const value = context.raw;
                            const percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                            return `${context.label}: ${value} (${percentage}%)`;
                        },
                    },
                },
            },
            cutout: '60%',
        },
    });
}

document.addEventListener('DOMContentLoaded', initPropertiesByTypeChart);

export { initPropertiesByTypeChart };

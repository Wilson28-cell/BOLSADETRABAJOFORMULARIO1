<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    function renderDashboardCharts(data) {
        const defaultFont = {
            family: 'Inter, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif',
            size: 12,
            color: '#4b5563'
        };

        function destroyExistingChart(canvasId) {
            const existingChart = window.dashboardCharts?.[canvasId];
            if (existingChart) {
                existingChart.destroy();
                delete window.dashboardCharts[canvasId];
            }
        }

        function toRgba(color, alpha) {
            if (color.startsWith('rgba')) {
                return color;
            }
            if (color.startsWith('rgb')) {
                return color.replace('rgb(', 'rgba(').replace(')', `, ${alpha})`);
            }
            return color;
        }

        function createBarChart(canvasId, labels, values, label, color, horizontal = false) {
            const canvas = document.getElementById(canvasId);
            if (!canvas) return;

            destroyExistingChart(canvasId);

            window.dashboardCharts[canvasId] = new Chart(canvas.getContext('2d'), {
                type: 'bar',
                data: {
                    labels,
                    datasets: [{
                        label,
                        data: values,
                        backgroundColor: color,
                        borderRadius: 16,
                        borderSkipped: false,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    indexAxis: horizontal ? 'y' : 'x',
                    animation: { duration: 850, easing: 'easeOutQuart' },
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#111827',
                            titleColor: '#f8fafc',
                            bodyColor: '#f8fafc',
                            padding: 12,
                            borderColor: 'rgba(255,255,255,0.08)',
                            borderWidth: 1,
                        }
                    },
                    scales: {
                        x: {
                            ticks: { color: '#6b7280' },
                            grid: { color: 'rgba(226,232,240,0.6)', display: !horizontal },
                        },
                        y: {
                            ticks: { color: '#111827', font: { weight: '600' } },
                            grid: { display: false },
                        }
                    },
                    layout: { padding: { top: 10, right: 4, bottom: 6, left: 4 } },
                    font: defaultFont,
                }
            });
        }

        function createLineChart(canvasId, labels, values, label, color) {
            const canvas = document.getElementById(canvasId);
            if (!canvas) return;

            destroyExistingChart(canvasId);
            const rgbaColor = toRgba(color, 0.16);

            window.dashboardCharts[canvasId] = new Chart(canvas.getContext('2d'), {
                type: 'line',
                data: {
                    labels,
                    datasets: [{
                        label,
                        data: values,
                        borderColor: color,
                        backgroundColor: rgbaColor,
                        fill: true,
                        tension: 0.35,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        pointBackgroundColor: '#ffffff',
                        pointBorderColor: color,
                        pointBorderWidth: 2,
                        borderWidth: 3,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: { intersect: false, mode: 'nearest' },
                    animation: { duration: 900, easing: 'easeOutQuart' },
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#111827',
                            titleColor: '#f8fafc',
                            bodyColor: '#f8fafc',
                            padding: 12,
                            borderColor: 'rgba(255,255,255,0.08)',
                            borderWidth: 1,
                        }
                    },
                    scales: {
                        x: { ticks: { color: '#6b7280' }, grid: { display: false } },
                        y: { beginAtZero: true, ticks: { color: '#6b7280' }, grid: { color: 'rgba(226,232,240,0.6)' } },
                    },
                    layout: { padding: { top: 10, right: 4, bottom: 6, left: 4 } },
                    font: defaultFont,
                }
            });
        }

        function createDoughnutChart(canvasId, labels, values, colors) {
            const canvas = document.getElementById(canvasId);
            if (!canvas) return;

            destroyExistingChart(canvasId);

            window.dashboardCharts[canvasId] = new Chart(canvas.getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels,
                    datasets: [{
                        data: values,
                        backgroundColor: Array.isArray(colors) ? colors : [colors],
                        borderColor: '#ffffff',
                        borderWidth: 2,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '68%',
                    animation: { duration: 850, easing: 'easeOutQuart' },
                    plugins: {
                        legend: { position: 'bottom', labels: { color: '#6b7280', boxWidth: 14, padding: 14 } },
                        tooltip: {
                            backgroundColor: '#ffffff',
                            titleColor: '#212529',
                            bodyColor: '#212529',
                            padding: 12,
                            borderColor: 'rgba(226,232,240,0.8)',
                            borderWidth: 1,
                        }
                    },
                    layout: { padding: { top: 10, right: 10, bottom: 10, left: 10 } },
                    font: defaultFont,
                }
            });
        }

        const applicationsByMonth = data.charts.applicationsByMonth || [];
        const topOffersByApplications = data.charts.topOffersByApplications || [];
        const applicationsByCategory = data.charts.applicationsByCategory || [];
        const companiesByApplicants = data.charts.companiesByApplicants || [];
        const viewsByMonth = data.charts.viewsByMonth || [];
        const topProductsByViewsChart = data.charts.topProductsByViewsChart || [];
        const topServicesByViewsChart = data.charts.topServicesByViewsChart || [];
        const viewsByCategory = data.charts.viewsByCategory || data.charts.viewsByCategoryChart || [];
        const companiesByViews = data.charts.companiesByViews || [];

        createLineChart(
            'applicationsByMonthChart',
            applicationsByMonth.map(item => item.label),
            applicationsByMonth.map(item => item.total),
            'Postulaciones',
            'rgb(37,99,235)'
        );

        createBarChart(
            'topOffersByApplicationsChart',
            topOffersByApplications.map(item => `${item.titulo_puesto} (${item.nombre_empresa})`),
            topOffersByApplications.map(item => item.total),
            'Postulaciones por oferta',
            'rgba(59,130,246,0.9)'
        );

        createDoughnutChart(
            'applicationsByCategoryChart',
            applicationsByCategory.map(item => item.label || item.categoria),
            applicationsByCategory.map(item => item.total),
            applicationsByCategory.map((item, index) => item.color || ['#2563eb', '#3b82f6', '#60a5fa', '#818cf8', '#93c5fd'][index % 5])
        );

        createBarChart(
            'companiesByApplicantsChart',
            companiesByApplicants.map(item => item.nombre_empresa),
            companiesByApplicants.map(item => item.total),
            'Postulantes',
            'rgba(16,185,129,0.92)',
            true
        );

        createLineChart(
            'viewsByMonthChart',
            viewsByMonth.map(item => item.label),
            viewsByMonth.map(item => item.total),
            'Visualizaciones',
            'rgb(32,100,244)'
        );

        createBarChart(
            'topProductsByViewsChart',
            topProductsByViewsChart.map(item => `${item.nombre_producto || item.nombre_servicio} (${item.nombre_empresa})`),
            (topProductsByViewsChart.length ? topProductsByViewsChart : topServicesByViewsChart).map(item => item.metric),
            'Visualizaciones',
            'rgba(37,99,235,0.9)',
            true
        );

        createBarChart(
            'topServicesByViewsChart',
            (topServicesByViewsChart.length ? topServicesByViewsChart : topProductsByViewsChart).map(item => `${item.nombre_servicio || item.nombre_producto} (${item.nombre_empresa})`),
            (topServicesByViewsChart.length ? topServicesByViewsChart : topProductsByViewsChart).map(item => item.metric),
            'Visualizaciones',
            'rgba(37,99,235,0.9)',
            true
        );

        createDoughnutChart(
            'viewsByCategoryChart',
            viewsByCategory.map(item => item.categoria),
            viewsByCategory.map(item => item.total),
            viewsByCategory.map((item, index) => {
                const palette = ['#2563eb', '#3b82f6', '#60a5fa', '#818cf8', '#93c5fd', '#7dd3fc', '#38bdf8', '#38bdf8', '#0ea5e9', '#0ea5e9'];
                return palette[index % palette.length];
            })
        );

        createBarChart(
            'companiesByViewsChart',
            companiesByViews.map(item => item.nombre_empresa),
            companiesByViews.map(item => item.total),
            'Visualizaciones',
            'rgba(14,165,233,0.92)',
            true
        );
    }

    function parseDashboardDateValue(value) {
        if (!value) return '';

        const trimmed = String(value).trim();
        if (!trimmed) return '';

        const isoMatch = trimmed.match(/^\d{4}-\d{2}-\d{2}$/);
        if (isoMatch) return trimmed;

        const slashMatch = trimmed.match(/^(\d{1,2})\/(\d{1,2})\/(\d{4})$/);
        if (!slashMatch) return trimmed;

        const [, day, month, year] = slashMatch;
        return `${year}-${String(month).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
    }

    function syncDashboardDateInputs(root = document) {
        root.querySelectorAll('input[name="desde"], input[name="hasta"]').forEach(function (input) {
            input.setAttribute('autocomplete', 'off');
            input.type = 'date';

            if (input.value) {
                input.value = parseDashboardDateValue(input.value);
            }
        });
    }

    function normalizeDashboardDateInputs(form) {
        if (!form) return;

        form.querySelectorAll('input[name="desde"], input[name="hasta"]').forEach(function (input) {
            if (input.value) {
                input.value = parseDashboardDateValue(input.value);
            }
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        window.dashboardCharts = window.dashboardCharts || {};

        const dashboardContent = document.getElementById('dashboardContent');
        const dashboardUrl = dashboardContent?.dataset.dashboardUrl || '{{ url('admin/indicadores-bolsa') }}';

        syncDashboardDateInputs(document);

        const initialStateElement = document.getElementById('dashboardStateData');
        if (initialStateElement) {
            try {
                const initialData = JSON.parse(initialStateElement.textContent);
                renderDashboardCharts(initialData);
            } catch (error) {
                console.error('Error al procesar datos iniciales del dashboard:', error);
            }
        }

        async function refreshDashboard(queryString = '') {
            dashboardContent.classList.add('opacity-50');
            try {
                const response = await fetch(`${dashboardUrl}${queryString}`, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });
                if (!response.ok) throw new Error('No se pudo cargar el dashboard');
                const html = await response.text();
                dashboardContent.innerHTML = html;
                syncDashboardDateInputs(dashboardContent);
                const jsonData = JSON.parse(document.getElementById('dashboardStateData').textContent);
                renderDashboardCharts(jsonData);
            } catch (error) {
                console.error(error);
            } finally {
                dashboardContent.classList.remove('opacity-50');
            }
        }

        document.addEventListener('submit', function (event) {
            const filtersForm = event.target.closest('#dashboardFiltersForm');
            if (!filtersForm) {
                return;
            }

            event.preventDefault();
            normalizeDashboardDateInputs(filtersForm);
            const params = new URLSearchParams(new FormData(filtersForm));
            refreshDashboard('?' + params.toString());
        });

        document.addEventListener('click', function (event) {
            const resetBtn = event.target.closest('#dashboardResetFilters');
            if (!resetBtn) {
                return;
            }

            const filtersForm = document.getElementById('dashboardFiltersForm');
            if (!filtersForm) {
                return;
            }

            filtersForm.reset();
            refreshDashboard('');
        });
    });
</script>

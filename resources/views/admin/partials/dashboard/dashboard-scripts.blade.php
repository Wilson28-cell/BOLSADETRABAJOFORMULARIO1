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

        const productsByMonth = data.charts.productsByMonth || [];
        const offersByMonth = data.charts.offersByMonth || [];
        const viewsByMonth = data.charts.viewsByMonth || [];
        const applicationsByMonth = data.charts.applicationsByMonth || [];
        const stateDistribution = data.charts.stateDistribution || [];
        const approvalDistribution = data.charts.approvalDistribution || [];
        const categories = data.charts.publicationsByCategory || [];
        const companies = data.charts.publicationsByCompany || [];
        const topCompanies = data.charts.topCompanies || [];
        const topCategories = data.charts.topCategories || [];

        createBarChart(
            'productsByMonthChart',
            productsByMonth.map(item => item.label),
            productsByMonth.map(item => item.total),
            'Publicaciones',
            '#3b82f6'
        );

        createBarChart(
            'offersByMonthChart',
            offersByMonth.map(item => item.label),
            offersByMonth.map(item => item.total),
            'Ofertas',
            '#3b82f6'
        );

        createLineChart(
            'viewsByMonthChart',
            viewsByMonth.map(item => item.label),
            viewsByMonth.map(item => item.total),
            'Visualizaciones',
            'rgb(79,70,229)'
        );

        createLineChart(
            'applicationsByMonthChart',
            applicationsByMonth.map(item => item.label),
            applicationsByMonth.map(item => item.total),
            'Postulaciones',
            'rgb(79,70,229)'
        );

        createDoughnutChart(
            'stateDistributionChart',
            stateDistribution.map(item => item.label),
            stateDistribution.map(item => item.total),
            stateDistribution.map(item => item.color || '#3b82f6')
        );

        createDoughnutChart(
            'approvalDistributionChart',
            approvalDistribution.map(item => item.label),
            approvalDistribution.map(item => item.total),
            approvalDistribution.map(item => item.color || ['#198754', '#dc3545'])
        );

        createBarChart(
            'categoryDistributionChart',
            categories.map(item => item.categoria),
            categories.map(item => item.total),
            'Categorías',
            '#8b5cf6'
        );

        createBarChart(
            'companyRankingChart',
            companies.map(item => item.nombre_empresa),
            companies.map(item => item.total),
            'Empresas',
            'rgba(16,185,129,0.9)',
            true
        );

        createBarChart(
            'topCompaniesChart',
            topCompanies.map(item => item.nombre_empresa),
            topCompanies.map(item => item.total),
            'Empresas top',
            'rgba(16,185,129,0.9)',
            true
        );

        createBarChart(
            'topCategoriesChart',
            topCategories.map(item => item.categoria),
            topCategories.map(item => item.total),
            'Categorías top',
            '#f97316'
        );
    }

    document.addEventListener('DOMContentLoaded', function () {
        window.dashboardCharts = window.dashboardCharts || {};

        const dashboardContent = document.getElementById('dashboardContent');
        const dashboardUrl = dashboardContent?.dataset.dashboardUrl || '{{ url('admin/indicadores-bolsa') }}';

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

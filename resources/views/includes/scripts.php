<script src="assets/static/js/initTheme.js"></script>

<!-- Core scripts -->
<script src="assets/static/js/components/dark.js"></script>
<script src="assets/static/js/pages/horizontal-layout.js"></script>
<script src="assets/extensions/perfect-scrollbar/perfect-scrollbar.min.js"></script>
<script src="assets/compiled/js/app.js"></script>

<!-- Charts -->
<script src="assets/extensions/apexcharts/apexcharts.min.js"></script>

<!-- Demo chart wiring (IDs match the elements above). Replace with real data calls later. -->
<script>
    // Revenue & Downloads (area + column)
    (function() {
        const el = document.querySelector('#chart-revenue-downloads');
        if (!el || typeof ApexCharts === 'undefined') return;
        const options = {
            chart: {
                type: 'line',
                height: 320,
                toolbar: {
                    show: false
                }
            },
            stroke: {
                width: [3, 3]
            },
            series: [{
                    name: 'Revenue (IDR juta)',
                    data: [12, 10, 14, 18, 22, 25, 28, 30, 27, 29, 33, 35]
                },
                {
                    name: 'Downloads',
                    data: [380, 420, 510, 690, 720, 760, 820, 910, 860, 900, 980, 1050]
                }
            ],
            xaxis: {
                categories: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des']
            },
            yaxis: [{
                labels: {
                    formatter: (val) => `${val}`
                }
            }],
            tooltip: {
                shared: true
            },
        };
        new ApexCharts(el, options).render();
    })();

    // Top Genres (donut)
    (function() {
        const el = document.querySelector('#chart-top-genres');
        if (!el || typeof ApexCharts === 'undefined') return;
        const options = {
            chart: {
                type: 'donut',
                height: 280
            },
            series: [35, 28, 18, 10, 9],
            labels: ['Cinematic', 'Hip-Hop', 'Lo‑Fi', 'EDM', 'Acoustic'],
            legend: {
                position: 'bottom'
            },
        };
        new ApexCharts(el, options).render();
    })();

    // Traffic sources (radial or pie)
    (function() {
        const el = document.querySelector('#chart-traffic-sources');
        if (!el || typeof ApexCharts === 'undefined') return;
        const options = {
            chart: {
                type: 'pie',
                height: 280
            },
            series: [44, 26, 18, 12],
            labels: ['Direct', 'Search', 'Referral', 'Social'],
            legend: {
                position: 'bottom'
            },
        };
        new ApexCharts(el, options).render();
    })();
</script> <!-- end script -->

        </div>

    </div>

    <script>
        const revenueChartData = {
            labels: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin'],
            datasets: [{
                label: 'Revenus',
                data: [400000, 650000, 900000, 750000, 1200000, 1500000],
                borderColor: '#2563EB',
                backgroundColor: 'rgba(37,99,235,0.15)',
                fill: true,
                tension: 0.4,
                borderWidth: 3
            }]
        };

        const revenueChartOptions = {
            responsive: true,
            plugins: {
                legend: {
                    display: false
                }
            }
        };

        new Chart(document.getElementById('revenueChart'), {
            type: 'line',
            data: revenueChartData,
            options: revenueChartOptions
        });
    </script>

</body>
</html>
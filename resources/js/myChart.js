import { Chart } from "chart.js/auto";

const labels = [
    'Januari',
    'Februari',
    'Maret',
    'April',
    'Mei',
    'Juni',
    'Juli',
    'Agustus',
    'September',
    'Oktober',
    'November',
    'Desember'
];

const data = {
    labels: labels,
    datasets: [{
        label: 'Jumlah Pengumpulan File Perbulan',
        fill: false,
        // borderColor: 'rgb(75, 192, 192)',
        data: [0, 10, 5, 2, 20, 30, 45, 47, 48, 41, 32, 74],
    }]
};

const config = {
    type: 'line',
    data: data,
    options: {
        animations: {
            tension: {
                duration: 1000,
                easing: 'linear',
                from: 1,
                to: 0,
                loop: true
            }
            },
            scales: {
            y: { // defining min and max so hiding the dataset does not change scale range
                min: 0,
                max: 100
            }
        }
    }
};

// new Chart(
//     document.getElementById('myChart'),
//     config
// );

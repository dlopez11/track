function createPie(data) {
   $(data.container).highcharts({
        chart: {
            plotBackgroundColor: null,
            plotBorderWidth: null,
            plotShadow: false
        },
        title: {
            text: data.title
        },
        tooltip: {
            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: false
                },
                showInLegend: true
            }
        },
        series: [{
            type: 'pie',
            name: data.serie,
            //data: [['lala', 40],['lala', 60]]
            data: data.data[0]
        }]
    });
}

function createLineGraphic() {
    
}

function createBarGraphic() {
    
}
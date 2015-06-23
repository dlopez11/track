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
    $(data.line).highcharts({
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
            line: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: false
                },
                showInLegend: true
            }
        },
        series: [{
            type: 'line',
            name: data.serie,
            //data: [['lala', 40],['lala', 60]]
            data: data.data[0]
        }]
    });
}

function createBarGraphic(data) {
    $(data.container).highcharts({
        chart: {
            type: 'column'
        },
        title: {
            text: data.title
        },
        subtitle: {
            text: 'Sigma Móvil S.A.S'
        },
        xAxis: {
            categories: [
                'Día 1',
                'Día 2',
                'Día 3',
                'Día 4',
                'Día 5',
                'Día 6'                     
            ],
            crosshair: true
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Rainfall (mm)'
            }
        },
        tooltip: {
            headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
            pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                '<td style="padding:0"><b>{point.y:.1f} mm</b></td></tr>',
            footerFormat: '</table>',
            shared: true,
            useHTML: true
        },
        plotOptions: {
            column: {
                pointPadding: 0.2,
                borderWidth: 0
            }
        },
        series: [{
            name: 'Adriana Lopez',
            data: [49, 71, 106, 129, 144, 176]

        }, {
            name: 'Luz Adriana Lopez',
            data: [83, 78, 98, 93, 106, 84]
        }]
    });        
}
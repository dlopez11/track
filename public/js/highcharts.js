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

function createLineGraphic(data) {
    $('#container-line').highcharts({
        title: {
            text: data.title,
            x: -20 //center
        },
        xAxis: {
            categories: ['30-Jun','29-Jun','28-Jun','27-Jun','26-Jun','25-Jun','24-Jun','23-Jun','22-Jun','21-Jun','20-Jun','19-Jun','18-Jun','17-Jun','16-Jun','15-Jun','14-Jun','13-Jun','12-Jun','11-Jun','10-Jun','09-Jun','08-Jun','07-Jun','06-Jun','05-Jun','04-Jun','03-Jun','02-Jun','01-Jun']
        },
        yAxis: {
            title: {
                text: 'Numero de visitas'
            },
            plotLines: [{
                value: 0,
                width: 1,
                color: '#808080'
            }]
        },
        legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'middle',
            borderWidth: 0
        },
        series: [{
            name: data.serie,
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
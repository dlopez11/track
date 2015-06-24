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
    $(data.container).highcharts({
        title: {
            text: data.title,
            x: -20 //center
        },
        xAxis: {
            categories: data.categories
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
            layout: 'horizontal',
            align: 'right',
            verticalAlign: 'bottom',
            borderWidth: 0
        },
        series: data.data
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
        xAxis: {
            categories: data.categories,
            crosshair: true
        },
        yAxis: {
            min: 0,
            title: {
                text: data.yAxis
            }
        },
        tooltip: {
            headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
            pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                '<td style="padding:0"><b>{point.y}</b></td></tr>',
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
        series: data.data
    });        
}
function createTimelineGraphic(data) {
    $(data.container).highcharts({
        chart: {
            type: 'line'
        },
        title: {
            text: data.title
        },
        xAxis: {
            categories: data.categories
        },
        yAxis: {
            title: {
                text: data.yAxis
            }
        },
        plotOptions: {
            line: {
                dataLabels: {
                    enabled: true
                },
                enableMouseTracking: false
            }
        },
        series: data.data
    });  
}
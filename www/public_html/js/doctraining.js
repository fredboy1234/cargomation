  /*
     * BAR CHART
     * ---------
     */

    var bar_data = {
      data : [[1,96], [2,83], [3,89], [4,75], [5,43], [6,62], [7,42]],
      bars: { show: true }
    }
    $.plot('#bar-chart', [bar_data], {
      grid  : {
        borderWidth: 1,
        borderColor: '#f3f3f3',
        tickColor  : '#f3f3f3'
      },
      series: {
         bars: {
          show: true, barWidth: 0.3, align: 'center',
        },
      },
      colors: ['#3c8dbc'],
      xaxis : {
        ticks: [[1,'OOCL'], [2,'ANL'], [3,'PAE'], [4,'COSCO'], [5,'HAPAG-LLOYD'], [6,'CMA CGM'], [7,'NYK']]
      }
    })
    /* END BAR CHART */
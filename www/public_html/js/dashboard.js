$(document).ready(function(){
    
    $(".datepicker-days table").on('click',"td[data-action='selectDay']",function(){
        var day = $(this).data('day');
        window.location.href = "/doctracker?calendar="+day;
    });
    var shipdata = [3,4,5,3,2,3,4,3,5,5,4];
    var docsdata = [12,4,5,3,2,3,3,3,6,7,4];
    dashChart('shipment-chart','rgba(0, 172, 193, 1)',shipdata);
    dashChart('docs-aprvd-chart','rgba(156, 204, 101, 1)',docsdata);

    // $('.tablue').on('click',function(){
    //     console.log($(this).attr('id'));
    // });
    
});

//on click wont work need to check so we use this javascript temporarily
function tablueshipment(){
    $('.navshipment').addClass('show active');
    $('.navcontainer').removeClass('show active');
}
function tablueContainer(){
    $('.navcontainer').addClass('show active');
    $('.navshipment').removeClass('show active'); 
}
function dashChart(id,bgcolor,data){
    var ctx = document.getElementById(id).getContext('2d');
    var labelTime = ['12am','1am','2am','3am','4am','5am','6am','7am','8am','9am','10am','11am'];
    var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
           labels: labelTime,
           datasets: [{
               label:"",
                data: data,
                backgroundColor: bgcolor
           }]
        },
        options: {
            legend: {
                display: false,
            },
           scales: {
                xAxes: [{
                   gridLines: {
                      display: false,
                   },
                   ticks: {
                    display: false,
                    autoSkip: false
                   }  
                }],
                yAxes: [{
                   gridLines: {
                      display: false
                   },
                   ticks: {
                    display: false
                   }  
                }]
           }
        }
    });
    
    if(theme == 'template_one' || theme == 'template_three'){
        $('.table').removeClass("table-sm").addClass("table-md");
    }
}


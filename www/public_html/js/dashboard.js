$(document).ready(function(){  

    // The Calender
    $('#calendar').datetimepicker({
      format: 'L',
      inline: true
    });
  
    // Make the dashboard widgets sortable Using jquery UI
    $('.connectedSortable').sortable({
      placeholder: 'sort-highlight',
      connectWith: '.connectedSortable',
      handle: '.card-header, .nav-tabs',
      forcePlaceholderSize: true,
      zIndex: 999999
    });
    $('.connectedSortable .card-header').css('cursor', 'move');

    $(".datepicker-days table").on('click',"td[data-action='selectDay']",function(){
        var day = $(this).data('day');
        window.location.href = "/doctracker?calendar="+day;
    }); 
    
    var preventer = [];
    var pointObject=[];
    var counter =0;
    var stoper = 0;
    var bar = new Promise((resolve, reject) => {
      $.each(port_loading_couint,function(okey,oval){
        var loading = oval.port_loading; 
        var ccount = oval.count;
    
        if(loading !==""){
          if ($.inArray(loading, preventer) == -1){
            preventer.push(loading);
            var mcolor ="#dc3545";
            if(oval.transport_mode === "Air"){
              mcolor = "#007bff";
            }else if(oval.transport_mode === "Sea"){
              mcolor = "#28a745";
            }
            var txtcontent = 'Location:'+loading+' Count:'+oval.count;
            var items = [50, 60, 80];
            var item = items[Math.floor(Math.random() * items.length)];
            var data = [];
              $.ajax({
                url: document.location.origin + '/shipment/getCity/',
                type: "POST",
                dataType: "json",
                data: { location: loading },
                success: function (res) {
                    data = res;
                    if(data.length > 0) {
                      var latitude = parseFloat( data[0].lat);
                      var longitude = parseFloat(data[0].lng);
                      pointObject.push({
                        title: txtcontent,
                        latitude: latitude,
                        longitude: longitude,
                        color: mcolor
                        });
                        counter++;
                    }
                }
            }); 
          }  
        }
        resolve();
      });
    });

    bar.then(() => {
      var waiter = 0;
      let map = new Map(pointObject,"chartdiv");
      var recheckObject = setInterval(function(){
    
        //cancel interval if push is done
        if(stoper == counter){
          waiter++;
          if(waiter == 10 && stoper == counter){
            clearInterval(recheckObject);
          }
        }else{
          waiter = 0;
        }
        
        console.log(stoper + " ---- " + counter + ' -waiter- '+waiter);
        map.executeMap();
        stoper = counter;
      }, 1000);
    });
});

//on click wont work need to check so we use this javascript temporarily
function tablueshipment(){
    $('.navshipment').addClass('show active');
    $('.navcontainer').removeClass('show active');
    $('.navimport').removeClass('show active');
}
function tablueContainer(){
    $('.navcontainer').addClass('show active');
    $('.navshipment').removeClass('show active');
    $('.navimport').removeClass('show active');
}
function tablueImport(){
    $('.navimport').addClass('show active');
    $('.navshipment').removeClass('show active'); 
    $('.navcontainer').removeClass('show active');
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
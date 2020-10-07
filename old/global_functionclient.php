<script>

	   $(document).ready(function(){
	   $("#olUsers").load("functions/req_onlineUsers.php");
	   setInterval (function(){
	   $("#olUsers").load("functions/req_onlineUsers.php");
	   },2000);
	   
	   $("#id_log").load("functions/req_LogUser.php");
	   setInterval (function(){
	   $("#id_log").load("functions/req_LogUser.php");
	   },10000);
	   
	   $("#activeLogUser").load("functions/req_sideBarOnline.php");
	   setInterval (function(){
	   $("#activeLogUser").load("functions/req_sideBarOnline.php");
	   },5000); 
	   
	   $("#getCnames").load("functions/req_getCompanyName.php");
	   setInterval (function(){
	   $("#getCnames").load("functions/req_getCompanyName.php");
	   },3000);
	   
	    $("#sendfileuser").load("functions/show_sendfileuser.php");
	   setInterval (function(){
	   //$("#sendfileuser").load("functions/show_sendfileuser.php");
	   },3000);
	   

	   
	   setInterval(function(){
	    $.ajax({
					url:"functions/auth_setters_online.php",
					method:"POST",
					data:this,
					contentType:false,
					processData:false,
					success:function(data){
						if(data !== null && data !== '') {
							// $.toast({
							//	heading: 'Login Notification',
							//	text: data.toUpperCase() + ' is now online !!',
							//	position: 'top-right',
							//	loaderBg:'#ff6849',
							//	icon: 'success',
							//	hideAfter: 3500, 
							//	stack: 1
							//});
						
						}						
						
					}
				});
				
		 $.ajax({
					url:"functions/auth_setters.php",
					method:"POST",
					data:this,
					contentType:false,
					processData:false,
					success:function(data){	
					}
				});
	    },2000);

		
	});
	
function reqLogoutNow(){

	 $.ajax({
			url:"functions/req_logoutclient.php",
			method:"POST",
			data:this,
			contentType:false,
			processData:false,
			success:function(data){ 
				
				if(data == 'x'){
					
				  //Auto Close Timer
     Swal.fire({
                title: 'Logging out!',
				allowOutsideClick: false,
                html:
                    'closing in <strong></strong> seconds.<br/><br/>',
 
                timer: 5000,
                onBeforeOpen: () => {
                    const content = Swal.getContent()
                    const $ = content.querySelector.bind(content)
                    Swal.showLoading()
                    timerInterval = setInterval(() => {
                        Swal.getContent().querySelector('strong')
                            .textContent = (Swal.getTimerLeft() / 1000)
                                .toFixed(0)
                    }, 100)
                },
                onClose: () => {
                    clearInterval(timerInterval)
					window.location.href = "/login";
                }
            })
       
							 
				}
	}
});
}

function isExistClient(){

	var isEmail = document.getElementById("pw_email").value;
	//document.getElementById("myText").value = "Johnny Bravo"; 
	//alert(x);
	
	$.ajax({
			url:"functions/req_EmailExistClient.php",
			method:"POST",
			data:{EmailData:$("#pw_email").val()},	
			success:function(data){ 

				if(data == '1'){
					document.getElementById('isExistClient').innerHTML = '&nbsp;&nbsp;is already taken !!';
					setTimeout (function(){
					document.getElementById('pw_email').value = "";
					document.getElementById('isExistClient').innerHTML = "";
					},5000);
					
				}
				else{
					data = null;
				}
	}
	});

}
</script>
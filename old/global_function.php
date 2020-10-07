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
	
function reqLogout(){
	 $.ajax({
			url:"functions/req_logout.php",
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

function isExist(){
   
	var isEmail = document.getElementById("pw_email").value;

	$.ajax({
			url:"functions/req_EmailExist.php",
			method:"POST",
			data:{EmailData:$("#pw_email").val()},	
			success:function(data){ 
			//alert(data);
				if(data == '1'){
					document.getElementById('isExist').innerHTML = '&nbsp;&nbsp;is already taken !!';
					setTimeout (function(){
					document.getElementById('pw_email').value = "";
					document.getElementById('isExist').innerHTML = "";
					},5000);
					
				}
				else{
					data = null;
				}
	}
	});

}
</script>
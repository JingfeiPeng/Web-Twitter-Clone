<footer class="footer">
	<div class="container">
		<p>&copy; Twitter Clone 2018</p>
	</div>
</footer>
	
	
	
	
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
	  
	<!-- Modal -->
	<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	  <div class="modal-dialog" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<h5 class="modal-title" id="loginModalTitle">Login</h5>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			  <span aria-hidden="true">&times;</span>
			</button>
		  </div>
		  <div class="modal-body">
		  <div class="alert alert-danger" id="loginAlert"></div>
			<form>
				<input type="hidden" name="loginActive" id="loginActive" value="1">
			  <div class="form-group row">
				<label for="email" class="col-sm-2 col-form-label">Email</label>
				<div class="col-sm-10">
				  <input type="email" class="form-control" id="email" placeholder="email address">
				</div>
			  </div>
			  <div class="form-group row">
				<label for="password" class="col-sm-2 col-form-label">Password</label>
				<div class="col-sm-10">
				  <input type="password" class="form-control" id="password" placeholder="Password">
				</div>
			  </div>
			</form>
		  </div>
		  <div class="modal-footer">
			<a id="toggleLogin">Sign Up</a>
			<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
			<button type="button" class="btn btn-primary" id="loginSignUpButton">Login</button>
		  </div>
		</div>
	  </div>
	</div>
	<script>
		$("#toggleLogin").click(function(){
			if ($('#loginActive').val() == "1"){
				$('#loginActive').val("0")
				$("#loginModalTitle").html("Sign Up");
				$("#loginSignUpButton").html("&nbsp; Sign Up");
				$("#toggleLogin").html("Login");
			}else{
				$('#loginActive').val("1");
				$("#loginModalTitle").html("Login");
				$("#loginSignUpButton").html("Login");
				$("#toggleLogin").html("Sign Up");
			}
		});
		
		
		// Ajax to send login information
		$("#loginSignUpButton").click(function(){
			$.ajax({
				type:"POST",
				url:"actions.php?action=loginSignup",
				data:"email="+$("#email").val() + "&password="+$("#password").val()+"&loginActive="+$("#loginActive").val(),
				success:function(result){
					if (result == "1"){
						window.location.assign("http://jingfeipeng.tech/MVC-Twitter-App/");
				    }else{
						$("#loginAlert").html(result).show();
					}
				}
			})
		})
		
		
		$(".toggleFollow").click(function() {
			var id = $(this).attr("data-userId");
			$.ajax({
				type:"POST",
				url:"actions.php?action=toggleFollow",
				data:"userId="+id,
				success:function(result){
					if (result == 1){
						$("a[data-userId='"+id+"']").html("Follow");
					} else {
						$("a[data-userId='"+id+"']").html("Unfollow");
					}
				}
			})
		})
		
	</script>
  </body>
</html>
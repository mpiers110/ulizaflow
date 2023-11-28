async function loadUsers() {
	try{
		const url = document.location.origin+'/ulizaflow/backend/account/users/?fetchAll';
		const results = await fetch(url)
		const finalData = await results.json()
		finalData.forEach((user) => {
			if(user.profilepic != "user-default.jpg"){
				$('#row').append(`
				<div class="col-md-3">
			        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
			        <div class="image">
			          <img src="../../file-uploads/${user.profilepic}" class="img-circle elevation-2" alt="${user.username}'s Profile Pic">
			        </div>
			        <div class="info">
			          <a href="?user=${user.user_id}" class="d-block">${user.username}</a>
			        </div>
			      </div>
			    </div> 
			`);
			}else{
				$('#row').append(`
				<div class="col-md-3">
			        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
			        <div class="image">
			          <img src="../../dist/img/user-default.jpg" class="img-circle elevation-2" alt="${user.username}'s Profile Pic">
			        </div>
			        <div class="info">
			          <a href="?user=${user.user_id}" class="d-block">${user.username}</a>
			        </div>
			      </div>
			    </div> 
			`);
			}
		});
	} catch (err) {
		console.error(err)
	}
}
loadUsers()
$(document).ready(function(){
	try{
		$.ajax({
	    url: document.location.origin+'/ulizaflow/backend/account/?getCategories',
	    type: "GET",
	    success: function(response){
	    	response.forEach((tag) => {
					$('#tag').append(`
						<option value="${tag.category_id}">${tag.category_name}</option>
					`);
				});
	    }
		});		
	} catch (err) {
		console.error(err)
	}	
})
async function viewUser(id) {
	try{
		const url = document.location.origin+'/ulizaflow/backend/account/users/?existing='+id;
		const results = await fetch(url)
		const finalData = await results.json()
		$('#askbox').append(`
			&nbsp;
			<a href="../users/" type="button" class="btn btn-outline-info" >Back</a>
		`);
		$('#row').attr('id', 'quizbox');
		finalData.forEach((user) => {
			if(user.profilepic != "user-default.jpg"){
				$('#quizbox').html(`
					<div class="col-md-4">
	            		<div class="card card-widget widget-user shadow">
	                        <div class="widget-user-header bg-info">
	                			<h3 class="widget-user-username">${user.username}</h3>
	              			</div>
	              			<div class="widget-user-image">
	                			<img class="img-circle elevation-2" src="../../file-uploads/${user.profilepic}" alt="${user.username}'s Profile Pic">
	              			</div>
	              			<div class="card-footer">
	                			<div class="row">
	                  				<div class="col-sm-4 border-right">
	                    				<div class="description-block">
	                      					<h5 class="description-header bg-danger">{user.3,200}</h5>
	                      					<span class="description-text">Asked Questions</span>
	                    				</div>
	                  				</div>
	                  				<div class="col-sm-4 border-right">
	                    				<div class="description-block">
	                      					<h5 class="description-header bg-warning">{user.3,200}</h5>
	                      					<span class="description-text">Answered Questions</span>
	                    				</div>
	                  				</div>
	                  				<div class="col-sm-4">
	                    				<div class="description-block">
	                      					<h5 class="description-header bg-success">{user.3,200}</h5>
	                      					<span class="description-text">Rating</span>
	                    				</div>
	                  				</div>
	                  			</div>
	                  		</div>
	                  	</div>
	                </div>
				`);
			}else{
				$('#quizbox').html(`
					<div class="col-md-4">
	            		<div class="card card-widget widget-user shadow">
	                        <div class="widget-user-header bg-info">
	                			<h3 class="widget-user-username">${user.username}</h3>
	              			</div>
	              			<div class="widget-user-image">
	                			<img class="img-circle elevation-2" src="../../dist/img/user-default.jpg" alt="${user.username}'s Profile Pic">
	              			</div>
	              			<div class="card-footer">
	                			<div class="row">
	                  				<div class="col-sm-4 border-right">
	                    				<div class="description-block">
	                      					<h5 class="description-header bg-danger">{user.3,200}</h5>
	                      					<span class="description-text">Asked Questions</span>
	                    				</div>
	                  				</div>
	                  				<div class="col-sm-4 border-right">
	                    				<div class="description-block">
	                      					<h5 class="description-header bg-warning">{user.3,200}</h5>
	                      					<span class="description-text">Answered Questions</span>
	                    				</div>
	                  				</div>
	                  				<div class="col-sm-4">
	                    				<div class="description-block">
	                      					<h5 class="description-header bg-success">{user.3,200}</h5>
	                      					<span class="description-text">Rating</span>
	                    				</div>
	                  				</div>
	                  			</div>
	                  		</div>
	                  	</div>
	                </div>
				`);
			}
		});
	} catch (err) {
		console.error(err)
	}
}
$('#ulizaQuestion').validate({
  rules: {
    title: {
      required: true
    },
    description: {
      required: true,
      minlength: 10
    },
    tag: {
      required: true
    }
  },
  messages: {
    title: {
      required: "Please provide a title for your question"
    },
    description: {
      required: "Please provide a description of your question",
      minlength: "Your description must be at least 10 characters long"
    },
    tag: {
      required: "Please choose a tag for your question"
    }
  },
  errorElement: 'span',
  errorPlacement: function (error, element) {
    error.addClass('invalid-feedback');
    element.closest('.form-group').append(error);
  },
  highlight: function (element, errorClass, validClass) {
    $(element).addClass('is-invalid');
  },
  unhighlight: function (element, errorClass, validClass) {
    $(element).removeClass('is-invalid');
  },
  submitHandler: function(){
  	let formData = $('#ulizaQuestion').serializeArray()
  	$.ajax({
	    url: document.location.origin+'/ulizaflow/backend/account/thread/?askQuestion',
	    type: "POST",
	    data: formData, 
	    cache: false,
	    success: function(response){
	    	if (response == "OK"){
	    		$('#ask-question').modal("hide")
					Toast.fire({
		        icon: 'success',
		        title: 'Your Question has been Posted.'
		      })
				}else{
					$('#ask-question').modal("hide")
					Toast.fire({
		        icon: 'error',
		        title: 'Failed to post your question..Try Again.'
		      })
				}
	    }
		});
  }
});
async function logout(id){
	const doLogout = async () => {
	  	try{
			const results = await $.ajax({
										url: document.location.origin+'/ulizaflow/backend/auth/logout/?endSession='+id,
										type: "GET",
										dataType: "text"
									});
			return results;		
			} catch (err) {
				console.error(err)
			}
	}
	doLogout().then(data => {
			let response = data
			if(response == "User Logged Out"){
	    	location.href = document.location.origin+'/ulizaflow/'
	    }else{
	    	Toast.fire({
			    icon: 'error',
			    title: response
			  });
	    }
	})
}
$('#modalClose').click( function(){
	$('#ulizaQuestion').trigger('reset');
})
async function loadUserData(id) {
	let user_id = id;
	try{
		const url = document.location.origin+'/ulizaflow/backend/account/users/?userProfile='+user_id;
		const results = await fetch(url)
		const finalData = await results.json()
		finalData.forEach((user) => {
				$('#row').html(`
				<div class="col-md-3">
					<div class="card card-primary card-outline">
				        <div class="card-body box-profile">
				            <div class="text-center">
					            ${user.profilepic != "user-default.jpg" ? 
					            	`<img class="profile-user-img img-fluid img-circle" src="../../file-uploads/${user.profilepic}" alt="${user.username}'s profile pic">`
					            : 
					             	`<img class="profile-user-img img-fluid img-circle" src="../../dist/img/user-default.jpg" alt="${user.username}'s profile pic">`
					           	}				              
				            </div>
				            <h3 class="profile-username text-center">${user.username}</h3>
				            <p class="text-center" style="color:green"><b>${user.status}</b></p>
					        <ul class="list-group list-group-unbordered mb-3">
					          <li class="list-group-item">
					            <b>Email:</b> <a class="float-right">${user.user_email}</a>
					          </li>
					          <li class="list-group-item">
					            <b>Rating:</b> <a class="float-right" id="userR">0</a>
					          </li>
					          <li class="list-group-item">
					            <b>Asked Questions:</b> <a class="float-right" id="askedQ">0</a>
					          </li>
					          <li class="list-group-item">
					            <b>Answered Questions:</b> <a class="float-right" id="answeredQ">0</a>
					          </li>
					        </ul>
				        </div>
				    </div>
				</div>
				<div class="col-md-9">
				   	<div class="card">
				        <div class="card-header p-2"><p>Update Profile</p>
				        </div>
				        <div class="card-body">
				            <div class="tab-content">
				               	<div class="active tab-pane" id="settings">
					                <form class="form-horizontal" id="userProfile" method="POST" enctype="multipart/form-data">
					                  <div class="form-group row">
					                    <label for="inputEmail" class="col-sm-2 col-form-label">Email:</label>
					                    <div class="col-sm-10">
					                      <input type="email" class="form-control" id="inputEmail" name="inputEmail" placeholder="${user.user_email}">
					                      <input type="hidden" name="user_id" id="user_id" value="${user.user_id}">
					                    </div>
					                  </div>
					                  <div class="form-group row">
						                <label for="profilePic" class="col-sm-2 col-form-label">Profile Picture:</label>
						                <div class="col-sm-10">
							                <div class="input-group">
							                  <div class="custom-file">
							                    <input type="file" class="custom-file-input" name="profilePic" id="profilePic">
							                    <label class="custom-file-label" for="profilePic">Choose file</label>
							                  </div>
							                </div>
							            </div>    
						              </div>
					                  <div class="form-group row">
					                    <div class="offset-sm-2 col-sm-10">
					                      <button type="submit" id="updateProfile" class="btn btn-danger">Submit</button>
					                    </div>
					                  </div>
					                </form>
				              	</div>
				            </div>
				        </div>
				    </div>
				</div>
			`);			
			$('#userProfile').validate({
			  	rules: {
			    	profilePic: {
			      		required: true
			    	}
			  	},
			  	messages: {
			    	profilePic: {
			      		required: "Please upload a picture for your profile"
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
			  	submitHandler: function (form) {
			    let formData = new FormData(form)
			    $.ajax({
				    url: document.location.origin+'/ulizaflow/backend/account/users/?updateUser',
				    //dataType: 'text', 
				    cache: false, 
				    contentType: false, 
				    processData: false,
				    data: formData,
				    type: 'POST',
				    success: function(response){
				    	console.log(typeof response)
				    	console.log(response)
				    	if (response == "OK"){
							Toast.fire({
						        icon: 'success',
						        title: 'Your Answer has been Posted.'
						    })
						}else{
							Toast.fire({
						        icon: 'error',
						        title: 'Failed to post your answer..Try Again.'
						    })

						}
				    }
				});
			    return false;
			  }			  
			});
		});
	} catch (err) {
		console.error(err)
	}
}
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
function userAsked(id){
	let user_id = id;
	$.ajax({
    url: document.location.origin+'/ulizaflow/backend/account/users/?askedQuestions='+user_id,
    type: "GET",
    success: function(response){
		if (response !== 0 && response !== null) {
			$(document).find('.card-body.box-profile #askedQ').text(response);
		}		
    }
	});
}
function userAnswered(id){
	let user_id = id;
	$.ajax({
    url: document.location.origin+'/ulizaflow/backend/account/users/?answeredQuestions='+user_id,
    type: "GET",
    success: function(response){
		if (response !== 0 && response !== null) {
			$(document).find('.card-body.box-profile #answeredQ').text(response);
		}
    }
	});
}
function userRating(id){
	let user_id = id;
	$.ajax({
    url: document.location.origin+'/ulizaflow/backend/account/users/?userRating='+user_id,
    type: "GET",
    success: function(response){
		if (response !== 0 && response !== null) {
    		let rate = Math.round((response/$(document).find('.card-body.box-profile #answeredQ').text())*100);
			$(document).find('.card-body.box-profile #userR').text(rate+" %");
		}
    }
	});
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
$('#modalClose').click( function(){
	$('#ulizaQuestion').trigger('reset');
})
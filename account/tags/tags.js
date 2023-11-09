async function loadData () {
	try{
		const url = document.location.origin+'/ulizaflow/backend/account/?allCategories';
		const results = await fetch(url)
		const finalData = await results.json()
		finalData.forEach((category) => {
			$('#row').append(`
				<div class="col-lg-3 col-6">
            		<div class="small-box bg-info">
              			<div class="inner">
                			<h3>${category.category_name}</h3>
                			<p>${category.category_description}</p>
              			</div>
		              	<div class="icon">
		                	<i class="ion ion-bag"></i>
		              	</div>
              			<a onclick="fetchThread(${category.category_id})" class="small-box-footer">View Thread <i class="fas fa-arrow-circle-right"></i></a>
            		</div>
          		</div>
			`);
		});
	} catch (err) {
		console.error(err)
	}
}
loadData()
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
async function fetchThread(key) {
	let id = key;
	try{
		const url = document.location.origin+'/ulizaflow/backend/account/thread/?fetchAll='+id;
		const results = await fetch(url)
		const finalData = await results.json()
		$('#askbox').append(`
			&nbsp;
			<a href="../tags/" type="button" class="btn btn-outline-info" >Back</a>
		`);
		$('#row').html(`
			<div class="col-md-8" id="quizbox">
        		<div class="timeline" id="timeline"></div>
      		</div>
      	`);
      	if(finalData == "None"){
      		$('#timeline').html(`
				<div>
            		<i class="fas fa-info bg-info"></i>
            		<div class="timeline-item">
              			<h3 class="timeline-header no-border">No Questions so far :) </h3>
              		<div class="timeline-body">Be the first one to ask a question.</div>
            	</div>
	    	`);
      	}else{
      		finalData.forEach((question) => {
      			if(question.status == 0){
      				$('#timeline').append(`
						<div>
			      			<i class="fas fa-envelope bg-blue"></i>
			      			<div class="timeline-item">
						  		<span class="time"><i class="fas fa-clock"></i> ${question.date_posted}</span>
						  		<h5 class="timeline-header"><a href="?q=${question.thread_id}">${question.thread_title}</a></h5>
							</div>
			    		</div>
			    	`);
      			}else{
      				$('#timeline').append(`
						<div>
			      			<i class="fas fa-lock bg-danger"></i>
			      			<div class="timeline-item">
						  		<span class="time"><i class="fas fa-clock"></i> ${question.date_posted}</span>
						  		<h5 class="timeline-header"><a href="?q=${question.thread_id}">${question.thread_title}</a></h5>
							</div>
			    		</div>
			    	`);
      			}
			});
		}
	} catch (err) {
		console.error(err)
	}
}
async function openQuiz(id) {
	try{
		const url = document.location.origin+'/ulizaflow/backend/account/thread/?existing='+id;
		const results = await fetch(url)
		const finalData = await results.json()
		$('#askbox').append(`
			&nbsp;
			<a href="../tags/" type="button" class="btn btn-outline-info" >Back</a>
		`);
		$('#row').html("")
		$('#row').html(`
			<div class="col-md-8" id="quizbox"></div>
			<div class="col-md-4" id="replybox">
      			<table class="table table-bordered">
  					<tbody id="replydata"></tbody>
				</table>	
      		</div>
      	`);
      	
    $('.container-fluid').append(`
    <div class="modal fade" id="answer" tabindex="-1" role="dialog" aria-labelledby="answerLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <!--button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button-->
                <center><h4 class="modal-title" id="answerQuestionLabel"></h4></center>
            </div>
            <div class="modal-body" id="answerQuestionbox">
					    <span id="user"></span>
					  </div>
            <div class="modal-footer">
                <button id="accept-answer" onclick="acceptAnswer();" data-val="" class="btn btn-success"> Accept</button>
                <button id="reject-answer" onclick="rejectAnswer();" data-val="" class="btn btn-danger"> Reject</button>
            </div>
        </div>
    </div>
</div>`);
		finalData.forEach((val) => {
			$('#quizbox').html(`
				<div class="col-sm-12">
					<div class="card card-primary">
					  <div class="card-header">
					    <h5 class="card-title">${val.thread_title}</h5>
					    <div class="card-tools">
			                <span class="badge">Asked by <a href="#">${val.username}</a> on ${val.date_posted}</span>                      
			              </div>
						  </div>
						  <div class="card-body">
						    ${val.thread_desc}
						  </div>
						  <div class="card-footer">
						  	${val.status == 0 ? 
						    `<button type="button" class="btn btn-warning btn-sm" onclick="answer()">Answer</button>
						  </div>
						</div><br>
						<div class="card card-primary" id="answerbox">
							<form class="form-validate-summernote" method="POST" id="giveAnswer">
								<div class="card-body">
							        <div class="form-group">
							          <label>My Answer</label>
							          <textarea class="summernote" required="required" data-msg="Please write something :)" id="my_answer" name="my_answer"></textarea>
							        </div>
							        <input type="hidden" id="quiz_id" name="quiz_id" value="${val.thread_id}" >
						            <input type="hidden" id="user_id" name="user_id" value="${val.user_id}">
							    </div>				        
					            <div class="card-footer">
					            	<div class="form-group">
				                  		<a id="postAnswer" class="btn btn-primary">Submit</a>
				                  	</div>
				                </div>
						    </form>							
				        </div>`
				        	 : '<button type="button" class="btn btn-warning btn-sm" title="This Thread was closed" disabled>Answer</button>'}
			        </div>
				`);
		});
		$('#answerbox').hide()
		$(function () {
			let summernoteElement = $('.summernote');
			summernoteElement.summernote({
		        height: 300,
		        callbacks: {
		            onChange: function (contents, $editable) {
		                summernoteElement.val(summernoteElement.summernote('isEmpty') ? "" : contents);
		                summernoteValidator.element(summernoteElement);
		            }
		        }
		    });
			$('#postAnswer').click(function(){
				let formData = $('#giveAnswer').serializeArray()
			    $.ajax({
				    url: document.location.origin+'/ulizaflow/uliza.php',
				    type: "POST",
				    data: formData, 
				    cache: false,
				    success: function(response){
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
			})
		    var summernoteForm = $('.form-validate-summernote');
		    
		    var summernoteValidator = summernoteForm.validate({
		        errorElement: "div",
		        errorClass: 'is-invalid',
		        validClass: 'is-valid',
		        ignore: ':hidden:not(.summernote),.note-editable.card-block',
		        errorPlacement: function (error, element) {
		            // Add the `help-block` class to the error element
		            error.addClass("invalid-feedback");
		            if (element.prop("type") === "checkbox") {
		                error.insertAfter(element.siblings("label"));
		            } else if (element.hasClass("summernote")) {
		                error.insertAfter(element.siblings(".note-editor"));
		            } else {
		                error.insertAfter(element);
		            }
		        }
		    });

		});
	} catch (err) {
		console.error(err)
	}	
}
async function showAnswers(id){
	try{
		const url = document.location.origin+'/ulizaflow/backend/account/answer/?question_id='+id;
		const results = await fetch(url)
		const finalData = await results.json()		
			if(finalData == "No Answers"){
				$('#replybox').html(`
					<p>No Answers available :)</p>
					<p>Be the first one to give an answer</p>`)
			}else{
				finalData.forEach((val) => {
					$('#replydata').append(`
				    <tr>
				      <td>
				        <div class="btn-group-vertical">
				          <button class="btn btn-default" id="upvote" onclick="upvote(this);" ${val.accepted == 1 ? 'disabled' : ''}><i class="fas fa-angle-up"></i></button>
				          <button class="btn btn-default" id="rating">${val.accepted == 1 ? '<i class="fas fa-check" style="color:#28a745;"></i>' : val.votes}</button>
				          <button class="btn btn-default" id="downvote" onclick="downvote(this);" ${val.accepted == 1 ? 'disabled' : ''}><i class="fas fa-angle-down"></i></button>
				        </div>
				      </td>
				      <td>
				        <div>
				          <div class="timeline-item">
				            <h5 class="timeline-header">
				              <a id="answer" onclick="popAnswer(${val.id})">
				                ${val.description}
				              </a>
				            </h5>
				            <small id="username">${val.username}</small>
				          </div>
				        </div>
				      </td>
				    </tr>		
					`);	
				});		
			}	
			
	} catch (err) {
		console.error(err)
	}	
}
function answer() {	
	$('#answerbox').toggle();
	$('#commentbox').hide()
}
function comment() {	
	$('#commentbox').toggle();
	$('#answerbox').hide()
}
async function checkQuestion() {
	let value = $('#title').val()
	const url = document.location.origin+'/ulizaflow/backend/account/thread/?userProfile='+value;
	const docheck = async () => {
	  	try{
			const results = await $.ajax({
									    	url: document.location.origin+'/ulizaflow/backend/account/thread/?checktitle',
									    	type: "POST",
									    	data: {
									    		value:value
									    	}
										});
			return results;		
		} catch (err) {
			console.error(err)
		}
	}
	docheck().then(data => {
			let response = data
			if (response == 'OK') {
	    		$('#title').removeClass('is-invalid');
	    		$('#titleHelp').html("Keep the title as simple as possible.");
	    		$('#desc').html(`
	    			<label for="description">Question Description</label>
	          		<textarea type="text" class="form-control" id="description" name="description" rows="3" placeholder="Enter a description of your problem"></textarea> 
	          	`);
	          	$('#postQuestion').prop('disabled', false);
	    	}else{
	    		$('#title').addClass('is-invalid');
	    		$('#titleHelp').html("")
		      	$('#desc').html(`
		      		<p>A <a href="?q=${response.thread_id}">similar thread</a> already exists check it out or restructure your question<p> 
		      	`);
		    	$('#postQuestion').prop('disabled', true);				
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
	    	location.href = appUrl
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
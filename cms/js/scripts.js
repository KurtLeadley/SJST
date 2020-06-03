tinymce.init({
		selector: "textarea",
		forced_root_block_attrs: { "style": "margin: 0;" },
		theme: "modern",
		width: '100%',
		height: 50,
		content_css : '../css/main.css',
		plugins: [
				 "advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker",
				 "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
				 "save table contextmenu directionality emoticons template paste textcolor",
				 "autoresize"
		],
		menubar:false,
		toolbar: "bold italic | bullist numlis | link image | media fullpage | emoticons", 
});
 
function postComment(event) {
	
  var title = document.getElementById("title").value;
  var pageID = document.getElementById("pageID").value;
	var userID = document.getElementById("userID").value;
	var comment = tinyMCE.get('comment').getContent();
	var userName = document.getElementById("userName").value;
	var time = document.getElementById("time").value;
	var image = document.getElementById("image").value;
	console.log(image);
  console.log(time);
	if((title.replace(/ /g, "")) || (comment)) {
    $.ajax
    ({
      type: 'POST',
      url: 'post_comment.php',
      data: 
      {
       user_title:title,
	     page_id:pageID,
			 user_id:userID,
			 user_comment:comment
      },
      success: function (response)
      {
	  var tinymce_editor_id = 'comment'; 
      tinymce.get(tinymce_editor_id).setContent('');
      document.getElementById("title").value="";
      $('#allComments').append('<div class="comment">' +
				'<div class="comment-title" id="divTableID">' +
				'<div class="divTableBody">' + 
				'<div class="divTableRow">' +
				'<div class="comment-avatar"><img width="50px" height="50px" src="avatars/' + image + '"></div>' +
				'<div class="comment-title">' + title + '</div>' +
				'</div>' +
				'<div class="divTableRow">' +
				'<div class="comment-body">' + comment + '</div>' +
				'</div>' +
				'<div class="divTableRow">' +
				'<div class="comment-user-name">Posted by: ' + userName + '&nbsp;&nbsp;' + time + '&nbsp;&nbsp;&nbsp;&nbsp; rec (0)</div>' +
				'</div>' +
				'</div>' +
				'</div>' +
				'</div>');
      }
    });
  }
  event.preventDefault();
  return false;
}
function recComment(event, commentID) {	

	var title = document.getElementById("title-" + commentID).value;
  var pageID = document.getElementById("pageID").value;
	var userID = document.getElementById("userID").value;
	var comment = document.getElementById('comment-' + commentID).value;
	var userName = document.getElementById('userName-' + commentID).value;
	var time = document.getElementById("time-" + commentID).value;
	var count = document.getElementById("count-" + commentID).value;
	var image = document.getElementById("image-" + commentID).value;
	var sig = document.getElementById("sig-" + commentID).value;
	console.log(image);
  console.log(time);
	count++;
	$.ajax
    ({
      type: 'POST',
      url: 'like_comment.php',
      data: 
      {
       comment_id:commentID,
      },
      success: function (response)
      {
				if (count > 1) {
					$('#' + commentID).addClass('comment_liked');
					console.log(commentID);
				}		
				$('#' + commentID).html(
				'<div class="divTable" id="divTableID">' +
				'<div class="divTableBody">' + 
				'<div class="divTableRow">' +
				'<div class="comment-avatar"><img width="50px" height="50px" src="avatars/' + image + '"></div>' +
				'<div class="comment-title">' + title + '</div>' +
				'</div>' +
				'<div class="divTableRow">' +
				'<div class="comment-body">' + comment + '</div>' +
				'</div>' +
				'<div class="divTableRow">' +
				'<div class="comment-user-sig"><p class="sigClass">' + sig + '</p></div>' +
				'</div>' +
				'<div class="divTableRow">' +
				'<div class="comment-user-name">Posted by: ' + userName + '&nbsp;&nbsp;' + time + '&nbsp;&nbsp;&nbsp;&nbsp;' + ' unrec ('+ count +')</div>' +
				'</div>' +
				'</div>' +
				'</div>');
			}
    });	
	event.preventDefault();
  return false;
}

function unrecComment(event, commentID) {	

	var title = document.getElementById("title-" + commentID).value;
    var pageID = document.getElementById("pageID").value;
	var userID = document.getElementById("userID").value;
	var comment = document.getElementById('comment-' + commentID).value;
	var userName = document.getElementById('userName-' + commentID).value;
	var time = document.getElementById("time-" + commentID).value;
	var count = document.getElementById("count-" + commentID).value;
	var image = document.getElementById("image-" + commentID).value;
	var sig = document.getElementById("sig-" + commentID).value;
	console.log(image);
    console.log(time);
	count--;
	$.ajax
    ({
      type: 'POST',
      url: 'unlike_comment.php',
      data: 
      {
       comment_id:commentID,
      },
      success: function (response)
      {
				if (count > 1) {
					$('#' + commentID).addClass('comment_liked');
					console.log(commentID);
					console.log(count);
				}	else if (count < 2){
					$('#' + commentID).addClass('comment');
				}	
				$('#' + commentID).html(
				'<div class="divTable" id="divTableID">' +
				'<div class="divTableBody">' + 
				'<div class="divTableRow">' +
				'<div class="comment-avatar"><img width="50px" height="50px" src="avatars/' + image + '"></div>' +
				'<div class="comment-title">' + title + '</div>' +
				'</div>' +
				'<div class="divTableRow">' +
				'<div class="comment-body">' + comment + '</div>' +
				'</div>' +
				'<div class="divTableRow">' +
				'<div class="comment-user-sig"><p class="sigClass">' + sig + '</p></div>' +
				'</div>' +
				'<div class="divTableRow">' +
				'<div class="comment-user-name">Posted by: ' + userName + '&nbsp;&nbsp;' + time + '&nbsp;&nbsp;&nbsp;&nbsp;' + ' rec ('+ count +')</div>' +
				'</div>' +
				'</div>' +
				'</div>');
			}
    });	
	event.preventDefault();
  return false;
}



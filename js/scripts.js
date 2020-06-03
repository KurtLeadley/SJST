function showHeaderFor(date) {
	var url = (date) ? 'get_scores.php?date=' + date : 'get_scores.php';
	 $.get(url, function(header) { 
		 console.log(date);
		   $('#liveScores').html(header);
		// removes extra date picker that gets added on date changes. Need to move date picker logic out of get_scores.php to really fix this
		   $('#wrapper > #notLiveScores').first().remove();  
	   })
   };
   function customDateUrl() {
	   var month = $("#month").val();
	   var day = $("#day").val();
	   var year = $("#year").val(); 
	   var customDate = year +  month +  day ;
	   showHeaderFor(customDate);
   }
   $(function() { 
	   setInterval(customDateUrl, 30000); 
	   $("#datepicker").hide();
   });  // $() shortcut for run this as soon as page loads.
   
   $("#buttonHere").click(function(){
	   $("#datepicker").toggle();
   }); 
   
   $("#datepicker").datepicker({	 
	   onSelect: function(value, date) { 
			$("#datepicker").hide(); 
	   } 
   });
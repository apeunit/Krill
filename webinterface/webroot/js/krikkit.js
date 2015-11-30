function isStreaming(id) {
	$.ajax({
	    type: 'get',
	    url: '/raspberries/isStreaming/' + id + '.json',
	    beforeSend: function(xhr) {
	        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
	    },
	    success: function(response) {
	        if (response.result.error) {
	            console.log(response.result.error);
	        }
	        if (response.result.content) {
	        	$('#videostate' + id).removeClass('label-default');
	        	$('#videostate' + id).addClass('label-success');
	        	$('#videostate' + id).text('streaming');
	        } else {
	        	$('#videostate' + id).removeClass('label-default');
	        	$('#videostate' + id).addClass('label-warning');
	        	$('#videostate' + id).text('not streaming');
	        }
	    	console.log(response.result.content);
	    },
	    error: function(e) {
	        $('#videostate' + id).removeClass('label-default');
	        $('#videostate' + id).addClass('label-danger');
	        $('#videostate' + id).text('offline');
	    }
	});
};

$('.raspiID').each(function(){
	isStreaming($(this).text().trim());
});
<?php
	$tbid=$_GET['id'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN""http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel='stylesheet' type='text/css' href='../fullcalendar.css' />
<script type='text/javascript' src='../jquery/jquery.js'></script>
<script type='text/javascript' src='../jquery/ui.core.js'></script>
<script type='text/javascript' src='../jquery/ui.draggable.js'></script>
<script type='text/javascript' src='../jquery/ui.resizable.js'></script>
<script type='text/javascript' src='../fullcalendar.min.js'></script>
<script type='text/javascript'>

	$(document).ready(function() {
	
		$('#calendar').fullCalendar({
		
			editable: true,
			
			events: "json-events.php?id=<?php echo $tbid;?>",
			
			//eventDrop: function(event, delta) {
//				alert(event.title + ' was moved ' + delta + ' days\n' +
//					'(should probably update your database)');
//			},
			
			loading: function(bool) {
				if (bool) $('#loading').show();
				else $('#loading').hide();
			}
			
		});
		$(".fc-event a").live("click",function(){
			var oid=$(this).children("span").text()
			var riqi=$(this).attr("href")
			var xid=riqi.split("#");
			window.parent.location.href = '/member_data.php?ac=addxc&xid='+xid[1];
		});
		
		
	});

</script>
<style type='text/css'>

	body {
		margin-top: 40px;
		text-align: center;
		font-size: 14px;
		font-family: "Lucida Grande",Helvetica,Arial,Verdana,sans-serif;
		}
		
	#loading {
		position: absolute;
		top: 5px;
		right: 5px;
		}

	#calendar {
		width: 620px;
		margin: 0 auto;
		}

</style>
</head>
<body>
<div id='calendar'></div>
</body>
</html>

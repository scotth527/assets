<!DOCTYPE html>
<html>
<head>
	<title>HTTP Sniffer</title>
	<link rel="stylesheet" type="text/css" href="style.css?v04">
</head>
<body>
	

	<h1>The server received the following HTTP package:</h1>
	<a href="#" class="button" onClick="toggleAnimation();">OPEN</a>
	
	<div class="email">
	  <div class="envelope">
	    <div class="back paper"></div>
	    <div class="note">
	    	<ul class="requestcontent">
	    	<?php foreach($_REQUEST as $key => $val){ ?>
	    		<li><span class="requestproperty"><?php echo $key; ?></span> <?php echo $val; ?></li>
	    	<?php } ?>
	    	</ul>
	    </div>
	    <div class="front paper">
	    <?php
	    $envelope = getallheaders();
	    ?>
	    	<ul class="envelopecontent">
	    	<?php foreach($envelope as $key => $val){ ?>
	    		<li><span class="contentproperty"><?php echo $key; ?></span> <?php echo $val; ?></li>
	    	<?php } ?>
	    	</ul>
	    </div>
	  </div>
	</div>
<script type="text/javascript">
	function toggleAnimation()
	{
		var btns = document.querySelectorAll(".back");
		var notes = document.querySelectorAll(".note");
		for(var i =0; i<btns.length;i++)
		{
			toggleClass(btns[i],'animate');
			toggleClass(notes[i],'animate');
		}

	}

	function toggleClass(elm,className)
	{
		if(!elm.classList.contains(className))
		{
		  elm.classList.add(className);
		}
		else
		{
		  elm.classList.remove(className);
		}
	}
</script>
</body>
</html>
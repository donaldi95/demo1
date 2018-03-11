<!DOCTYPE html>
<html>
<head>
	<title>Links</title>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
</head>
<body>

<script type="text/javascript">
	$(document).ready(function(){
    $(".textinput").on('keyup', function postinput(){
        var str = $(this).serialize();
       var val =  $(".textinput")
              .map(function(){return $(this).val().length;}).get();
        $('#count').text(val[0]+val[1]);
    });
});

</script>


<label id="count" name="count">

</label>

<form method="post" action="">
	<input type="varchar" class="textinput" name="link1">
	<input type="varchar" class="textinput" name="link2">
	<button id="Submit" name="submit">Submit</button>
</form>


</body>
</html>

<?php
#to call the function findAndCompare
if($_REQUEST){
    if(isset($_REQUEST['submit'])){
        findAndCompare(htmlspecialchars($_REQUEST['link1']),htmlspecialchars($_REQUEST['link2']));
    }
}

function findAndCompare($link1,$link2){

	$array1 =  array();
	$array2 = array();
	$merge = array();
		# WE CHECK IF INPUT IS NOT NULL
  		if ($link1 != "" && $link2 != "") {
  		#WE CHECK IF INPUT is a variable
  		if (filter_var($link1, FILTER_VALIDATE_URL) === FALSE && filter_var($link2 , FILTER_VALIDATE_URL) === FALSE  ) {
  			echo "Url Is Not Valid";

  		}else{

		$url = $link1;
  		$url2 = $link2;
		$html = file_get_contents($url);

		$doc = new DOMDocument();
		libxml_use_internal_errors(true);

		$doc->loadHTML($html); //helps if html is well formed and has proper use of html entities!
		libxml_use_internal_errors(false);
		
		
		$xpath = new DOMXpath($doc);

		$nodes = $xpath->query('//a');

		foreach($nodes as $node => $n) {
		    $array1[] = $n->getAttribute('href');
		}
		
		#For Link 2
		

		$html2 = file_get_contents($url2);

		$doc2 = new DOMDocument();
		libxml_use_internal_errors(true);



		$doc2->loadHTML($html2); //helps if html is well formed and has proper use of html entities!
		libxml_use_internal_errors(false);
		$xpath2 = new DOMXpath($doc2);

		$nodes2 = $xpath2 -> query('//a');

		foreach($nodes2 as $node2 => $n2) {
		    $array2[] = $n2->getAttribute('href');
		}

		#compare the links from 2 websites
		
		foreach ($array1 as $a1 => $i) {
			foreach ($array2 as $a2 => $i2) {
				$sim =  similar_text($i,$i2);
				#echo 'Similarity between: '.$i.' and '.$i2.' is '.$sim.'% <br>';
				$merge[] = $i.', '.$i2.' : '.$sim.'% .';
				
			}
		}

		$fp = fopen('file.csv', 'w');
		foreach ($merge as $key => $value) {
			fputcsv($fp, array($value));
		}
		fclose($fp);

		}
		
  }else{
  	 echo "U need to fill both text fields";
  }
}
 ?>


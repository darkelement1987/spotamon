<?php
include 'frontend/functions.php';
include 'frontend/menu.php';
include 'config/dbbuilding.php';
?>

<head>
<link rel="stylesheet" type="text/css" href="style.css">
<meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
<meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<script type="text/javascript" src="//code.jquery.com/jquery-1.8.3.js"></script>        
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
<script>
 $(document).ready(function(){
  $("#pokesearch").select2({
   templateResult: formatState,
   width:'100%'
  });
 });
 
 function formatState (state) {
  if (!state.id) { return state.text; }
  var $state = $(
   '<span ><img style="display: inline-block;" src="./static/icons/' + state.element.value.toLowerCase() + '.png" heigth="24" width="24"/> ' + state.text + '</span>'
  );
  return $state;
 }
 
 function enablespotbutton() {
	 document.getElementById("spotbutton").disabled = false;
	 }
</script>
</head>

<?php
menu();

pokesubmission();
?>

</body>

<footer></footer>


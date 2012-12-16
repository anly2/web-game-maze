<?php
fetch_maze:{
   $name = 'Mazes/'.((isset($_REQUEST['level']))? $_REQUEST['level'] : "1_");
   if(!file_exists($name))
      exit("<strong><big>Sorry, Maze ".basename($name)." not found!</big></strong>");

   $codeline = file($name);
   parse_str(trim(array_shift($codeline)), $details);
   $special = explode(",", trim(array_shift($codeline)));
}

define("em", 20, 1);

echo '<html>'."\n";
echo '<head>'."\n";
echo '   <title>Maze Grid Test</title>'."\n";

echo '   <style type="text/css">'."\n";
echo '   [name="cell"] {'."\n";
echo '      display: inline-block;'."\n";
echo '      position: absolute;'."\n";
echo '      width: '.(em-2).';'."\n";
echo '      height: '.(em-2).';'."\n";
echo '      border-width: 1px;'."\n";
echo '      border-style: solid;'."\n";
echo '      border-color: black;'."\n";
echo '   }'."\n";
echo '[name="cell"].c0000 {'."\n";
echo '   border-color: transparent transparent transparent transparent;'."\n";
echo '}'."\n";
echo '[name="cell"].c0001 {'."\n";
echo '   border-color: transparent transparent transparent black;'."\n";
echo '}'."\n";
echo '[name="cell"].c0010 {'."\n";
echo '   border-color: transparent transparent black transparent;'."\n";
echo '}'."\n";
echo '[name="cell"].c0011 {'."\n";
echo '   border-color: transparent transparent black black;'."\n";
echo '}'."\n";
echo '[name="cell"].c0100 {'."\n";
echo '   border-color: transparent black transparent transparent;'."\n";
echo '}'."\n";
echo '[name="cell"].c0101 {'."\n";
echo '   border-color: transparent black transparent black;'."\n";
echo '}'."\n";
echo '[name="cell"].c0110 {'."\n";
echo '   border-color: transparent black black transparent;'."\n";
echo '}'."\n";
echo '[name="cell"].c0111 {'."\n";
echo '   border-color: transparent black black black;'."\n";
echo '}'."\n";
echo '[name="cell"].c1000 {'."\n";
echo '   border-color: black transparent transparent transparent;'."\n";
echo '}'."\n";
echo '[name="cell"].c1001 {'."\n";
echo '   border-color: black transparent transparent black;'."\n";
echo '}'."\n";
echo '[name="cell"].c1010 {'."\n";
echo '   border-color: transparent black black transparent;'."\n";
echo '}'."\n";
echo '[name="cell"].c1011 {'."\n";
echo '   border-color: black transparent black black;'."\n";
echo '}'."\n";
echo '[name="cell"].c1100 {'."\n";
echo '   border-color: black black transparent transparent;'."\n";
echo '}'."\n";
echo '[name="cell"].c1101 {'."\n";
echo '   border-color: black black transparent black;'."\n";
echo '}'."\n";
echo '[name="cell"].c1110 {'."\n";
echo '   border-color: black black black transparent;'."\n";
echo '}'."\n";
echo '[name="cell"].c1111 {'."\n";
echo '   border-color: black black black black;'."\n";
echo '}'."\n";
echo '   </style>'."\n";

echo '</head>'."\n";
echo '<body>'."\n";

   foreach($codeline as $key => $value){
      echo '<div class="row">'."\n";

      $fields = explode(".", $value);
      foreach($fields as $i=>$t)
         echo '   <span id="'.$i.':'.$key.'" name="cell" class="c'.trim($t).'"></span>'."\n";

      echo '</div>'."\n";
   }

echo '</body>'."\n";

echo '<script type="text/javascript">'."\n";
echo 'var marginTop  = 50;'."\n";
echo 'var marginLeft = 50;'."\n";
echo ''."\n";
echo 'var em = '.em.'-1;'."\n";
echo 'var cells = document.getElementsByName("cell");'."\n";
echo ''."\n";
echo 'var i;'."\n";
echo 'for (i=0; i<cells.length; i++)'."\n";
echo '{'."\n";
echo '   var coords = cells[i].id.split(":");'."\n";
echo '   var x = coords[0];'."\n";
echo '   var y = coords[1];'."\n";
echo ''."\n";
echo '   var cell = cells[i];'."\n";
echo '   cell.style.left = marginLeft + x*em;'."\n";
echo '   cell.style.top  = marginTop  + y*em;'."\n";
echo '}'."\n";
echo '</script>'."\n";

echo '</html>'."\n";

?>
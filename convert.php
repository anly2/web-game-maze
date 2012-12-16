<?php
fetch_maze:{
   $name = 'Mazes/'.((isset($_REQUEST['level']))? $_REQUEST['level'] : "");
   if(!file_exists($name))
      exit("<strong><big>Sorry, Maze ".basename($name)." not found!</big></strong>");

   $codeline = file($name);
   $details = trim(array_shift($codeline));
   $special = trim(array_shift($codeline));
}

convert:{
   $converted = "";

   foreach ($codeline as $k=>$line)
   {
      $e = explode(".", $line);

      foreach ($e as $i=>$c)
      {
         $t1 = (($i - 1) < 0)? 1 : substr($e[$i-1], 2, 1);
         $t2 = substr($c, 0, 1);
         $t3 = (($k - 1) < 0)? 1 : substr($codeline[$k-1], $j, 1);
      }
   }
}
?>
<?php
define('grid_width' , 20, true);
define('grid_height', 30, true);

$code = "";

for ($i=0; $i<grid_width; $i++)
{
   for ($j=0; $j<grid_height; $j++)
   {
      $cls = (rand(0,99) >= 50 ? "0":"1") . (rand(0,99) >= 50 ? "0":"1") . (rand(0,99) >= 50 ? "0":"1") . (rand(0,99) >= 50 ? "0":"1");
      $code .= ($j==0? "" : ".").$cls;
   }
   $code .= "\n";
}

$src = "time=20&start=3.3&end=5.0&next=2\n\n";
$src .= $code;
file_put_contents("Mazes/1_", $src);
?>
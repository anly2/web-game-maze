<?php

if(isset($_REQUEST['js'])){
   if(strlen(trim($_REQUEST['js']))<=0)
      $_REQUEST['js'] ='additional';

   if($_REQUEST['js']=='additional'){
      echo 'var all = new Array();'."\n";
      echo 'function addEvent(obj, evt, fn){'."\n";
      echo '   if(obj.addEventListener)'."\n";
      echo '      obj.addEventListener(evt, fn, false);'."\n";
      echo '   else'."\n";
      echo '   if(obj.attachEvent)'."\n";
      echo '      obj.attachEvent("on"+evt, fn);'."\n";
      echo '   else'."\n";
      echo '      obj["on"+evt] = fn;'."\n";
      echo '}'."\n";
      echo "\n";
      echo 'function extendEvent(ele, evn, fcn, unshift){'."\n";
      echo '   if(typeof ele == "string") elem = document.getElementById(ele);'."\n";
      echo "\n";
      echo '   eval("var fn   = (elem.on"+evn+")? elem.on"+evn+" : function(){} ;");'."\n";
      echo '   eval("elem.on"+evn+" = function(){ "+(unshift? "fcn(); fn()" : "fn(); fcn();")+" };");'."\n";
      echo '}'."\n";
      echo "\n";
      echo '   function unique(myarray) {'."\n";
      echo '     var a = [];'."\n";
      echo '     var l = myarray.length;'."\n";
      echo '     for(var i=0; i<l; i++) {'."\n";
      echo '       for(var j=i+1; j<l; j++) {'."\n";
      echo '         // If this[i] is found later in the array'."\n";
      echo '         if (myarray[i] === myarray[j])'."\n";
      echo '           j = ++i;'."\n";
      echo '       }'."\n";
      echo '       a.push(myarray[i]);'."\n";
      echo '     }'."\n";
      echo '     return a;'."\n";
      echo '   }'."\n";
      echo "\n";
      echo '   Array.prototype.exclude = function(elements){'."\n";
      echo '      if(elements.constructor == Array)'."\n";
      echo '         for(ii=0;ii<elements.length;ii++)'."\n";
      echo '            this.splice(this.indexOf(elements[ii]),1);'."\n";
      echo '      else'."\n";
      echo '         this.splice(this.indexOf(elements), 1);'."\n";
      echo '      return this;'."\n";
      echo '   }'."\n";

      //Connect Sections (dependency)
      $_REQUEST['js']='main';
   }
   if($_REQUEST['js']=='main'){
      echo '   var current_position = "0.0";'."\n";
      echo '   var initiated        = false;'."\n";
      echo '   var gameover         = false;'."\n";
      echo '   var visibility_      = "";'."\n";
      echo "\n";
      echo '   function start_maze(){'."\n";
      echo '      visibility_ = visibility;'."\n";
      echo "\n";
      echo '      current_position = start_position;'."\n";
      echo "\n";
      echo '      if(visibility == 0) show(all);'."\n";
      echo '      else show_visables(current_position);'."\n";
      echo "\n";
      echo '      highlight(current_position);'."\n";
      echo "\n";
      echo '      addEvent(window, "keydown", function (evn){'."\n";
      echo '         evn = evn==null? window.event : evn;'."\n";
      echo "\n";
      echo '         switch(evn.keyCode){'."\n";
      echo '            case 37: move("left"); break;'."\n";
      echo '            case 38: move("up"); break;'."\n";
      echo '            case 39: move("right"); break;'."\n";
      echo '            case 40: move("down"); break;'."\n";
      echo '         }'."\n";
      echo '      });'."\n";
      echo "\n";
      echo '      initiated = true;'."\n";
      echo '      timer_start(maze_time);'."\n";
      echo '      timer_tick();'."\n";
      echo '   }'."\n";
      echo "\n";
      echo '   function end_maze(successfully){'."\n";
      echo '      gameover = true;'."\n";
      echo "\n";
      echo '      if(successfully){'."\n";
      echo '         var announce = "Done in :&nbsp; <strong>" +(maze_time - timer.innerHTML) +"</strong> seconds"+'."\n";
      echo '                        "<br />Time left: <strong>"+timer.innerHTML               +"</strong> seconds<br />";'."\n";
      echo "\n";
      echo '         if(visibility_!=2){'."\n";
      echo '            announce += "<a href=\"?level="+current_level+"&mode=21\" style=\"text-decoration:none;color:red;\">Try this maze at <b>highest</b> difficulty</a>";'."\n";
      echo '            announce += "  <br />";'."\n";
      echo '         }'."\n";
      echo '         if(visibility_!=1){'."\n";
      echo '            announce += "<a href=\"?level="+current_level+"&mode=10\" style=\"text-decoration:none;color:orange;\">Try this maze at <b>normal</b> difficulty</a>";'."\n";
      echo '            announce += "  <br />";'."\n";
      echo '         }'."\n";
      echo '         if(visibility_!=0){'."\n";
      echo '            announce += "<a href=\"?level="+current_level+"&mode=00\" style=\"text-decoration:none;color:green;\">Try this maze at <b>lowest</b> difficulty</a>";'."\n";
      echo '            announce += "  <br />";'."\n";
      echo '         }'."\n";
      echo '         if(visibility_==2 && next_level){'."\n";
      echo '            announce += "<a href=\"?level="+next_level+"\" style=\"text-decoration:none;color:turquise;\"><strong>Try <b>next</b> maze</strong></a>";'."\n";
      echo '            announce += "  <br />";'."\n";
      echo '         }'."\n";
      echo '            announce += "  <br />";'."\n";
      echo "\n";
      echo '         alert("Congratulations! Maze Completed!");'."\n";
      echo '         timer.innerHTML = announce;'."\n";
      echo '      }else{'."\n";
      echo '         alert("Sorry, Time\'s up!");'."\n";
      echo '         timer.innerHTML = "<a href=\"?level="+current_level+"&mode=00\" style=\"text-decoration:none;color:green;\">Try this maze at <b>lowest</b> difficulty</a>";'."\n";
      echo '      }'."\n";
      echo "\n";
      echo '      initiated = false;'."\n";
      echo '   }'."\n";
      echo "\n";
      echo '   var tpSickness = false;'."\n";
      echo '   function move(direction){'."\n";
      echo '      if(gameover) return false;'."\n";
      echo "\n";
      echo '      if( (["left", "up", "right", "down"]).indexOf(direction)==-1 ){'."\n";
      echo '         if(tpSickness) return false;'."\n";
      echo '         tpSickness = true;'."\n";
      echo "\n";
      echo '         unhighlight();'."\n";
      echo '         current_position = direction;'."\n";
      echo "\n";
      echo '         if(current_position == end_position)'."\n";
      echo '            end_maze(true);'."\n";
      echo "\n";
      echo '         show_visables(current_position);'."\n";
      echo '         highlight(current_position);'."\n";
      echo '      }else'."\n";
      echo "\n";
      echo '      if(movable(current_position, direction)){'."\n";
      echo '         unhighlight();'."\n";
      echo '         current_position = neighbour(current_position, direction);'."\n";
      echo "\n";
      echo '         if(current_position == end_position)'."\n";
      echo '            end_maze(true);'."\n";
      echo "\n";
      echo '         show_visables(current_position);'."\n";
      echo '         highlight(current_position);'."\n";
      echo "\n";
      echo '         tpSickness = false;'."\n";
      echo '      }else'."\n";
      echo '         applyPenelty();'."\n";
      echo '   }'."\n";

      //Connect Sections (dependency)
      $_REQUEST['js']='toggle';
   }
   if($_REQUEST['js']=='toggle'){
      echo '   var visible = new Array();'."\n";
      echo '   function show(position){'."\n";
      echo '      if (position.constructor == Array) // Is array'."\n";
      echo '         for(sth=0;sth<position.length;sth++){'."\n";
      echo '            document.getElementById(position[sth]).style.visibility="visible";'."\n";
      echo '            visible.push(position[sth]);'."\n";
      echo '         }'."\n";
      echo '      else{'."\n";
      echo '         document.getElementById(position).style.visibility="visible";'."\n";
      echo '         visible.push(position);'."\n";
      echo '      }'."\n";
      echo '   }'."\n";
      echo "\n";
      echo '   function hide(position){'."\n";
      echo '      if (position.constructor == Array){ // Is array'."\n";
      echo '         for(sth=0;sth<position.length;sth++){'."\n";
      echo '            document.getElementById(position[sth]).style.visibility="hidden";'."\n";
      echo '            visible.exclude(position[sth]);'."\n";
      echo '         }'."\n";
      echo '      }else{'."\n";
      echo '         document.getElementById(position).style.visibility="hidden";'."\n";
      echo '         visible.exclude(position);'."\n";
      echo '      }'."\n";
      echo '   }'."\n";
      echo "\n";
      echo "\n";
      echo '   function show_visables(position){'."\n";
      echo "\n";
      echo '      if(visibility == 0) {show(all); return false;}'."\n";
      echo '      if(visibility == 2) hide(all);'."\n";
      echo '      var m = (visibility == 2)? 0 : 1;'."\n";
      echo "\n";
      echo '      var visables_left = get_visables(position, "left");'."\n";
      echo '      for(i=0;i<visables_left.length;i++)'."\n";
      echo '         setTimeout("show(\'"+visables_left[i]+"\')", 250*i*m);'."\n";
      echo "\n";
      echo '      var visables_down = get_visables(position, "down");'."\n";
      echo '      for(i=0;i<visables_down.length;i++)'."\n";
      echo '         setTimeout("show(\'"+visables_down[i]+"\')", 250*i*m);'."\n";
      echo "\n";
      echo '      var visables_right = get_visables(position, "right");'."\n";
      echo '      for(i=0;i<visables_right.length;i++)'."\n";
      echo '         setTimeout("show(\'"+visables_right[i]+"\')", 250*i*m);'."\n";
      echo "\n";
      echo '      var visables_up = get_visables(position, "up");'."\n";
      echo '      for(i=0;i<visables_up.length;i++)'."\n";
      echo '         setTimeout("show(\'"+visables_up[i]+"\')", 250*i*m);'."\n";
      echo "\n";
      echo '   }'."\n";

      //Connect Sections (dependency)
      $_REQUEST['js']='highlight';
   }
   if($_REQUEST['js']=='highlight'){
      echo 'var highlighted      = "";'."\n";
      echo '   function highlight(position){'."\n";
      echo '      highlighted = position;'."\n";
      echo '      var ele = document.getElementById(position);'."\n";
      echo '      ele.prevBackgroundColor = ele.style.backgroundColor;'."\n";
      echo '      ele.style.visibility = "visible";'."\n";
      echo '      ele.style.backgroundColor = "green";'."\n";
      echo '      if(ele.onmark) ele.onmark();'."\n";
      echo '   }'."\n";
      echo "\n";
      echo '   function unhighlight(){'."\n";
      echo '      var ele = document.getElementById(highlighted);'."\n";
      echo '      ele.style.backgroundColor = ele.prevBackgroundColor;'."\n";
      echo '      highlighted = "";'."\n";
      echo '   }'."\n";

      //Connect Sections (dependency)
      $_REQUEST['js']='properties';
   }
   if($_REQUEST['js']=='properties'){
      echo '   function neighbour(position, direction){'."\n";
      echo '      var rc = position.split(".");'."\n";
      echo '      var row = rc[0];'."\n";
      echo '      var col = rc[1];'."\n";
      echo '      '."\n";
      echo '      if(direction == "left")  col--;'."\n";
      echo '      if(direction == "right") col++;'."\n";
      echo '      if(direction == "up")    row--;'."\n";
      echo '      if(direction == "down")  row++;'."\n";
      echo "\n";
      echo '      return row+"."+col;'."\n";
      echo '   }'."\n";
      echo "\n";
      echo '   function movable(position, direction){'."\n";
      echo '      if(direction=="left")  pindex = 0;'."\n";
      echo '      if(direction=="down")  pindex = 1;'."\n";
      echo '      if(direction=="right") pindex = 2;'."\n";
      echo '      if(direction=="up")    pindex = 3;'."\n";
      echo "\n";
      echo '      if(!document.getElementById(position))'."\n";
      echo '         var fieldclass = "1111";'."\n";
      echo '      else'."\n";
      echo '         var fieldclass = document.getElementById(position).className;'."\n";
      echo "\n";
      echo '      if(fieldclass.charAt(pindex) == "0")'."\n";
      echo '         return true;'."\n";
      echo "\n";
      echo '      return false;'."\n";
      echo '   }'."\n";
      echo "\n";
      echo '   function get_visables(position, direction){'."\n";
      echo '      var left_position = position;'."\n";
      echo '      var down_position = position;'."\n";
      echo '      var right_position = position;'."\n";
      echo '      var up_position = position;'."\n";
      echo "\n";
      echo '      if(!direction) direction = "*";'."\n";
      echo '      var visables = new Array();'."\n";
      echo '      var i = 0;'."\n";
      echo "\n";
      echo '      if(direction=="left" || direction=="*")'."\n";
      echo '      while(movable(left_position, "left") && !document.getElementById(left_position).unseeable){'."\n";
      echo '         visables[i++] = left_position;'."\n";
      echo '         left_position = neighbour(left_position, "left");'."\n";
      echo '      }if(document.getElementById(left_position)){ visables[i] = left_position; i++;  }'."\n";
      echo "\n";
      echo '      if(direction=="down" || direction=="*")'."\n";
      echo '      while(movable(down_position, "down") && !document.getElementById(down_position).unseeable){'."\n";
      echo '         visables[i] = down_position;'."\n";
      echo '         down_position = neighbour(down_position, "down");'."\n";
      echo '         i++;'."\n";
      echo '      }if(document.getElementById(down_position)){ visables[i] = down_position; i++; }'."\n";
      echo "\n";
      echo '      if(direction=="right" || direction=="*")'."\n";
      echo '      while(movable(right_position, "right") && !document.getElementById(right_position).unseeable){'."\n";
      echo '         visables[i] = right_position;'."\n";
      echo '         right_position = neighbour(right_position, "right");'."\n";
      echo '         i++;'."\n";
      echo '      }if(document.getElementById(right_position)){ visables[i] = right_position; i++; }'."\n";
      echo "\n";
      echo '      if(direction=="up" || direction=="*")'."\n";
      echo '      while(movable(up_position, "up") && !document.getElementById(up_position).unseeable){'."\n";
      echo '         visables[i] = up_position;'."\n";
      echo '         up_position = neighbour(up_position, "up");'."\n";
      echo '         i++;'."\n";
      echo '      }if(document.getElementById(up_position)){ visables[i] = up_position; i++; }'."\n";
      echo "\n";
      echo '      visables = unique(visables);'."\n";
      echo '      visables.exclude(position);'."\n";
      echo "\n";
      echo '      return visables;'."\n";
      echo '   }'."\n";

      //Connect Sections (dependency)
      $_REQUEST['js']='time';
   }
   if($_REQUEST['js']=='time'){
      echo '   var timer     = "";'."\n";
      echo '   var timeout   = "";'."\n";
      echo '   var time_step = 1;'."\n";
      echo "\n";
      echo '   function timer_start(time){'."\n";
      echo '      timer = document.getElementById("timer");'."\n";
      echo "\n";
      echo '      if(timer_type == 1)'."\n";
      echo '         timer.innerHTML = time;'."\n";
      echo '      else'."\n";
      echo '         timer.innerHTML = "0";'."\n";
      echo '   }'."\n";
      echo "\n";
      echo '   function timer_tick(){'."\n";
      echo '      if(!initiated) return false;'."\n";
      echo "\n";
      echo '      var current_time = parseInt(timer.innerHTML);'."\n";
      echo "\n";
      echo '      if(timer_type == 1){'."\n";
      echo '         current_time -= time_step;'."\n";
      echo "\n";
      echo '         if(current_time<=0)'."\n";
      echo '            end_maze(false);'."\n";
      echo '      }else'."\n";
      echo '         current_time -= -time_step;'."\n";
      echo "\n";
      echo '      timer.innerHTML = current_time;'."\n";
      echo '      timeout = setTimeout(arguments.callee, time_step*1000);'."\n";
      echo '   }'."\n";
      echo "\n";
      echo '   function applyPenelty(n){'."\n";
      echo '      var current_time = parseInt(timer.innerHTML);'."\n";
      echo '      var penelty = (!n)? 1+(visibility) : n'."\n";
      echo "\n";
      echo '      if(timer_type == 1)'."\n";
      echo '         current_time -= penelty;'."\n";
      echo '      else'."\n";
      echo '         current_time += penelty;'."\n";
      echo "\n";
      echo '      timer.innerHTML = current_time;'."\n";
      echo "\n";
      echo '      if(timer_type == 1 && current_time<=0)'."\n";
      echo '         end_maze(false);'."\n";
      echo '   }'."\n";
   }
   exit;
}



fetch_maze:
   $name = 'Mazes/'.((isset($_REQUEST['level']))? $_REQUEST['level'] : 1);
   if(!file_exists($name))
      exit("<strong><big>Sorry, Maze ".basename($name)." not found!</big></strong>");

   $codeline = file($name);
   parse_str(trim(array_shift($codeline)), $details);
   $special = explode(",", trim(array_shift($codeline)));


mode:
   //$visibility = (($mode[0] == 0 && $ref_mode[0] == 2) || $mode[0] == 1)? $mode[0] : 2;
      // $visibility == 0  -->  "All            Visible"
      // $visibility == 1  -->  "Already Passed Visible"
      // $visibility == 2  -->  "Only Currently Visible"


   //$timer_type = ($mode[1] == 0 && $ref_mode[1] == 1)? $mode[1] : 1;
      // $timer_type == 0  -->  "Required Time"
      // $timer_type == 1  -->  "Timeout"

   $visibility = isset($_REQUEST['mode'])? substr($_REQUEST['mode'], 0, 1)  :  2;
   $timer_type = isset($_REQUEST['mode'])? substr($_REQUEST['mode'], 1, 1)  :  1;


grid:
   $grid = '';
   $indent = '            ';

   foreach($codeline as $key => $value){
      $grid .= $indent.'<tr>'."\n";

      $fields = explode(".", $value);
      foreach($fields as $i=>$t)
         $grid .= $indent.'   <td width="22" height="22" id="'.$key.'.'.$i.'" class="'.trim($t).'" style="visibility:hidden;"><img src="img/'.trim($t).'.png" /></td>'."\n";

      $grid .= $indent.'</tr>'."\n";
   }


specials:
$specials = '<script type="text/javascript">'."\n";
foreach($special as $def_str){
   switch(substr($def_str, 0, 1)){
      case "p":
         $specials .= '//Portal'."\n";
         $specials .= '   document.getElementById("'.current(explode("-", substr($def_str, 1))).'").style.backgroundColor = "lightblue";'."\n";
         $specials .= '   extendEvent( "'.current(explode("-", substr($def_str, 1))).'", "mark", function(){move("'.next(explode("-", substr($def_str, 1))).'");} );'."\n";
         $specials .= '   document.getElementById("'.next(explode("-", substr($def_str, 1))).'").style.backgroundColor = "lightblue";'."\n";
         $specials .= '   extendEvent( "'.next(explode("-", substr($def_str, 1))).'", "mark", function(){move("'.current(explode("-", substr($def_str, 1))).'");} );'."\n";
         $specials .= "\n";
         break;
      case "b":
         $specials .= '//Shadow'."\n";
         $specials .= '   document.getElementById("'.substr($def_str, 1).'").style.backgroundColor = "#f8f8f8";'."\n";
         $specials .= '   document.getElementById("'.substr($def_str, 1).'").unseeable = true;'."\n";
         $specials .= "\n";
         break;
      case "f":
         $specials .= '//Trap'."\n";
         $specials .= '   document.getElementById("'.current(explode(":", substr($def_str, 1))).'").style.backgroundColor = "#ff8888";'."\n";
         $specials .= '   extendEvent( "'.current(explode(":", substr($def_str, 1))).'", "mark", function(){applyPenelty('.(strlen($pen = next(explode(":", substr($def_str, 1))))>0? $pen : '').');} );'."\n";
         $specials .= "\n";
         break;
      case "s":
         $specials .= '//Speed Boost'."\n";
         $specials .= '   document.getElementById("'.current(explode(":", substr($def_str, 1))).'").style.backgroundColor = "#a4ffda";'."\n";
         $specials .= '   extendEvent( "'.current(explode(":", substr($def_str, 1))).'", "mark", function(){var ele = document.getElementById("'.current(explode(":", substr($def_str, 1))).'"); if(!ele.extracted){ applyPenelty('.(next(explode(":", substr($def_str, 1)))? "-".next(explode(":", substr($def_str, 1))) : '-'.($visibility+2)).'); ele.extracted = true;} } );'."\n";
         $specials .= "\n";
         break;
      case "o":
         $specials .= '//Orb'."\n";
         $specials .= '   document.getElementById("'.substr($def_str, 1).'").style.backgroundColor = "#8264b1";'."\n";
         $specials .= '   extendEvent( "'.substr($def_str, 1).'", "mark", function(){ var ele = document.getElementById("'.substr($def_str, 1).'"); if(!ele.extracted){ visibility = Math.max(0, visibility-1); ele.extracted = true; } });'."\n";
         $specials .= "\n";
         break;
      case "t":
         $specials .= '//Trigger'."\n";
         $specials .= '   document.getElementById("'.current(explode(":", substr($def_str, 1))).'").style.backgroundColor = "#f8f8f8";'."\n";
         $specials .= '   extendEvent( "'.current(explode(":", substr($def_str, 1))).'", "mark", function(){ var tar = document.getElementById("'.current(explode("-", next(explode(":", substr($def_str, 1))))).'"); tar.className = "'.next(explode("-", next(explode(":", substr($def_str, 1))))).'"; tar.innerHTML = "<img src=\"img/"+tar.className+".png\" />"; } );'."\n";
         $specials .= "\n";
         break;
      case "v":
         $specials .= '//Veil'."\n";
         $specials .= '   document.getElementById("'.substr($def_str, 1).'").style.backgroundColor = "#ffffff";'."\n";
         $specials .= "\n";
         break;
   }
}
$specials .= '</script>'."\n";


head:
echo '<head>'."\n";
echo '   <title>Maze: '.basename($name).'</title>'."\n";
echo "\n";
echo '   <script type="text/javascript" src="?js"></script>'."\n";
echo '   <script type="text/javascript">'."\n";
echo '      var start_position   = "'.$details['start'].'";'."\n";
echo '      var end_position     = "'.$details['end'].'";'."\n";
echo '      var maze_time        = '.$details['time'].';'."\n";
echo '      var visibility       = '.$visibility.';'."\n";
echo '      var timer_type       = '.$timer_type.';'."\n";
echo '      var current_level    = "'.basename($name)   .'";'."\n";
echo '      var next_level       = '.( (strlen(trim($details['next']))>0)? '"'.$details['next'].'"' : 'false'  ).';'."\n";
echo '   </script>'."\n";
echo '   <style type="text/css">'."\n";
echo '      table{'."\n";
echo '         border-collapse: collapse'."\n";
echo '      }'."\n";
echo '   </style>'."\n";
echo '</head>'."\n";


body:
echo '<body>'."\n";

echo '<table width="100%" height="100%">'."\n";
echo '   <tr>'."\n";
echo '      <td width="80%" align="center" valign="middle">'."\n";

echo '         <div id="timer" style="font-weight:bold;"></div>'."\n";
echo '         <table id="maze">'."\n";
echo              $grid;
echo '         </table>'."\n";
echo '         <script type="text/javascript">document.getElementById("'.$details['end'].'").style.backgroundColor = "orange";</script>'."\n";
echo '         <script type="text/javascript">'."\n";
echo '            var r = c = 0;'."\n";
echo '            while(document.getElementById(r+"."+c)){'."\n";
echo '               while(document.getElementById(r+"."+c)){'."\n";
echo '                  all.push(r+"."+c);'."\n";
echo '                  c++;'."\n";
echo '               }'."\n";
echo '               r++; c=0;'."\n";
echo '            }'."\n";
echo '         </script>'."\n";
echo $specials;
echo "\n";
echo '      </td>'."\n";
echo '   </tr>'."\n";
echo '   <tr>'."\n";
echo '      <td width="80%" align="center" valign="top">'."\n";
echo '         <br />'."\n";
echo '         <input type="button" value="Start!" onclick="javascript:start_maze();this.style.display=\'none\';" />'."\n";
echo '      </td>'."\n";
echo '   </tr>'."\n";
echo '</table>'."\n";

echo '</body>'."\n";
?>
<?php
// Sub-Pages
if(isset($_REQUEST['queue'])){
   if(isset($_REQUEST['form'])){
      echo '         <form id="source:form" action="?queue&add" method="POST">'."\n";
      echo '            <input type="hidden" id="source:sent" name="sent" value="false" />'."\n";
      echo '            <input type="hidden" id="source:name" name="name" value="" />'."\n";
      echo '            <textarea id="source:code" name="source" style="text-align: center; width:400px; height:60px;"></textarea>'."\n";
      echo '         </form>'."\n";
      exit;
   }
   if(isset($_REQUEST['add'])){
      $name = preg_replace(array("/ /", "/[^_a-zA-Z0-9]*/"), array("_", ""), $_REQUEST['name']);

      $code = explode("\n", $_REQUEST['source']);
      $details  = array_shift($code);
      $specials = array_shift($code);
      $grid     = join("\n", $code);

      $details = preg_replace("/.*time.*=.*(\d+).*&.*start.*=((\d+)\.(\d+)|).*&.*end.*=((\d+)\.(\d+)|).*&.*next.*=.*/", "time=$1&start=$2&end=$5&next=", $details);
      $specials = preg_replace("/[^pcbtvfso:\-\.,0-9]*/", "", $specials);
      $grid = preg_replace("/[^\n\.0-9]*/", "", $grid);

      $source = $details."\n".$specials."\n".$grid;
      $n = count(glob('Queue/*'));
      file_put_contents('Queue/'.$n.'.'.$name, $source);

      if($_REQUEST['sent'] != 'true'){
         $new['message']  = "Date: ".date('l jS \of F Y h:i:s A')."<br />\n";
         $new['message'] .= "IP: ".$_SERVER['REMOTE_ADDR']."<br />\n";
         $new['message'] .= "<br />\n";
         $new['message'] .= "Maze Name: ".$name."<br />\n";
         $new['message'] .= 'Link: <a href="http://localhost/Maze/editor.php?queue#i'.$n.'" target="_BLANK">Queue</a>'."<br />\n";
         $new['type'] = "maze";
         include "../pending.php";
      }

      echo '<script type="text/javascript">window.close();</script>';
      exit;
   }
   if(isset($_REQUEST['approve'])){
      $file = glob("Queue/".$_REQUEST['approve'].".*");
      if(count($file)!=1){
         echo 'File count too '.((count($file)>1)? 'big' : 'small').'('.count($file).'). Proccess Aborted!'."\n";
         echo '<br /><a href="?queue">Back to Queue List</a>'."\n";
         exit;
      }
      rename($file[0], "Mazes/".next(explode(".", basename($file[0]))));
      $old['type']  = "maze";
      $old['index'] = $_REQUEST['approve']+1;
      include "../pending.php";

      echo '<script type="text/javascript">window.location.href="?queue";</script>';
      exit;
   }

   echo '<a href="?">Back to Editor</a><br /><br />'."\n";
   $awaiting = glob("Queue/*");
   if(count($awaiting)<1)
      echo '<em>No Pending Mazes</em>'."\n";
   else
      foreach($awaiting as $k=>$v)
         echo '<a name="i'.($k).'">#'.($k+1)."</a> &nbsp; &nbsp; <strong>".next(explode(".", basename($v))).'</strong> : &nbsp; &nbsp; <small><a href="'.$v.'" target="_BLANK">Code</a></small> &nbsp; <small><a href="./?mode=00&level=../'.$v.'" target="_BLANK">View</a></small> &nbsp; <small><a href="?queue&approve='.($k).'">Approve</a></small>';
   exit;
}


//Javascript
js:
if(isset($_REQUEST['js'])){
   if(strlen(trim($_REQUEST['js']))<=0)
      $_REQUEST['js'] ='keyHandle';

   if($_REQUEST['js']=='keyHandle'){
      echo 'function addEvent(obj, evt, fn){'."\n";
      echo '   if(obj.addEventListener)'."\n";
      echo '      obj.addEventListener(evt, fn, false);'."\n";
      echo '   else'."\n";
      echo '   if(obj.attachEvent)'."\n";
      echo '      obj.attachEvent("on"+evt, fn);'."\n";
      echo '   else'."\n";
      echo '      obj["on"+evt] = fn;'."\n";
      echo '}'."\n";
      echo 'addEvent(window, "keydown", keyHandle);'."\n";
      echo 'addEvent(window, "keyup",   keyHandle);'."\n";
      echo "\n";
      echo "\n";
      echo 'var keys = new Array();'."\n";
      echo 'keys["decorator"]   = [67,32];'."\n";
      echo 'keys["tunneler"]    = [88,17];'."\n";
      echo 'keys["builder"]     = [90,16];'."\n";
      echo 'keys["left"]        = [65,37];'."\n";
      echo 'keys["up"]          = [87,38];'."\n";
      echo 'keys["right"]       = [68,39];'."\n";
      echo 'keys["down"]        = [83,40];'."\n";
      echo 'keys["toggleSE"]    = [81]; //Q'."\n";
      echo 'keys["special"]     = [69]; //E'."\n";
      echo 'keys["allSpecials"] = [49, 50, 51, 52, 53, 54, 55, 56, 57]; //1 to 9  // keyCode-49 = specialType'."\n";
      echo "\n";
      echo "\n";
      echo 'var toggleSE       = 0;  // toggle between Start and End'."\n";
      echo "\n";
      echo "\n";
      echo 'decorator = false;'."\n";
      echo 'tunneler  = false;'."\n";
      echo 'builder   = false;'."\n";
      echo "\n";
      echo 'function keyHandle(evn){'."\n";
      echo '   if(keys["decorator"].indexOf(evn.keyCode)!=-1)'."\n";
      echo '      decorator = (evn.type=="keydown");'."\n";
      echo '   if(keys["tunneler"].indexOf( evn.keyCode)!=-1)'."\n";
      echo '      tunneler  = (evn.type=="keydown");'."\n";
      echo '   if(keys["builder"].indexOf(  evn.keyCode)!=-1)'."\n";
      echo '      builder   = (evn.type=="keydown");'."\n";
      echo "\n";
      echo '   if(evn.type=="keyup")'."\n";
      echo '      return;'."\n";
      echo '   if(document.activeElement!=document.body)'."\n";
      echo '      if(evn.keyCode>=49 && evn.keyCode<=58)'."\n";
      echo '         return false;'."\n";
      echo "\n";
      echo "\n";
      echo '   if(keys["toggleSE"].indexOf(evn.keyCode)!=-1)'."\n";
      echo '      alter(toggleSE);'."\n";
      echo '   if(keys["allSpecials"].indexOf(evn.keyCode)!=-1){'."\n";
      echo '      alter( evn.keyCode-49 );'."\n";
      echo '      listSpecials(0);'."\n";
      echo '   }'."\n";
      echo '   if(keys["special"].indexOf(evn.keyCode)!=-1)'."\n";
      echo '      if(!specialsListed())'."\n";
      echo '         listSpecials(1);'."\n";
      echo '      else{'."\n";
      echo '         if(lastSpecial > 1 && lastSpecial < 9)'."\n";
      echo '            alter(lastSpecial);'."\n";
      echo '         listSpecials(0);'."\n";
      echo '      }'."\n";
      echo "\n";
      echo "\n";
      echo '   if(decorator){'."\n";
      echo '      if(keys["left"].indexOf( evn.keyCode)!=-1)'."\n";
      echo '         modify(current, 0, "!");'."\n";
      echo '      if(keys["up"].indexOf(   evn.keyCode)!=-1)'."\n";
      echo '         modify(current, 3, "!");'."\n";
      echo '      if(keys["right"].indexOf(evn.keyCode)!=-1)'."\n";
      echo '         modify(current, 2, "!");'."\n";
      echo '      if(keys["down"].indexOf( evn.keyCode)!=-1)'."\n";
      echo '         modify(current, 1, "!");'."\n";
      echo '   }else'."\n";
      echo "\n";
      echo '   if(tunneler || builder){'."\n";
      echo '      if(keys["left"].indexOf( evn.keyCode)!=-1){'."\n";
      echo '         modify(current, 0, builder);'."\n";
      echo '         mark( left(current) );'."\n";
      echo '         modify(current, 2, builder);'."\n";
      echo '      }'."\n";
      echo '      if(keys["up"].indexOf(   evn.keyCode)!=-1){'."\n";
      echo '         modify(current, 3, builder);'."\n";
      echo '         mark( up(current) );'."\n";
      echo '         modify(current, 1, builder);'."\n";
      echo '      }'."\n";
      echo '      if(keys["right"].indexOf(evn.keyCode)!=-1){'."\n";
      echo '         modify(current, 2, builder);'."\n";
      echo '         mark( right(current) );'."\n";
      echo '         modify(current, 0, builder);'."\n";
      echo '      }'."\n";
      echo '      if(keys["down"].indexOf( evn.keyCode)!=-1){'."\n";
      echo '         modify(current, 1, builder);'."\n";
      echo '         mark( down(current) );'."\n";
      echo '         modify(current, 3, builder);'."\n";
      echo '      }'."\n";
      echo '   }else{'."\n";
      echo '      if(keys["left"].indexOf( evn.keyCode)!=-1)'."\n";
      echo '         mark( left(current) );'."\n";
      echo '      if(keys["up"].indexOf(   evn.keyCode)!=-1)'."\n";
      echo '         mark( up(current) );'."\n";
      echo '      if(keys["right"].indexOf(evn.keyCode)!=-1)'."\n";
      echo '         mark( right(current) );'."\n";
      echo '      if(keys["down"].indexOf( evn.keyCode)!=-1)'."\n";
      echo '         mark( down(current) );'."\n";
      echo '   }'."\n";
      echo '}'."\n";

      //Connect Sections (dependency)
      $_REQUEST['js'] = 'mark';
   }
   if($_REQUEST['js']=='mark'){
      echo 'var current = false;'."\n";
      echo 'var start   = 0;'."\n";
      echo 'var end     = 1;'."\n";
      echo 'var special = new Array();//'."\n";
      echo "\n";
      echo 'function mark(elem){'."\n";
      echo '   if(typeof elem == "string") elem = document.getElementById(elem);'."\n";
      echo "\n";
      echo '   if(current){'."\n";
      echo '      current = document.getElementById(current);'."\n";
      echo '      current.style.backgroundColor = current.prevBackgroundColor;'."\n";
      echo '   }'."\n";
      echo "\n";
      echo '   if(elem.innerHTML=="")'."\n";
      echo '      elem.innerHTML = "<img src=\'img/1111.png\' />";'."\n";
      echo '   elem.prevBackgroundColor = elem.style.backgroundColor;'."\n";
      echo '   elem.style.backgroundColor = "red";'."\n";
      echo "\n";
      echo '   current = elem.id;'."\n";
      echo '   maintenance();'."\n";
      echo "\n";
      echo '   return elem;'."\n";
      echo '}'."\n";
      echo "\n";
      echo 'function modify(elem, side, wall){'."\n";
      echo '   if(!elem) elem = current;'."\n";
      echo '   if(typeof elem == "string") elem = document.getElementById(elem);'."\n";
      echo "\n";
      echo '   if(wall=="!") wall = ((elem.className.charAt(side)==0)? 1 : 0);'."\n";
      echo '   wall = (wall==0)? "0" : "1";'."\n";
      echo "\n";
      echo '   var cn = elem.className.split("");'."\n";
      echo '       cn.splice(side, 1, wall);'."\n";
      echo '   elem.className = cn.join("");'."\n";
      echo '   elem.innerHTML = "<img src=\'img/"+elem.className+".png\' />";'."\n";
      echo '}'."\n";

      //Connect Sections (dependency)
      $_REQUEST['js'] = 'specials';
   }
   if($_REQUEST['js']=='specials'){
      echo '   var specials = new Array();'."\n";
      echo '   specials["portals"]  = new Array();'."\n";
      echo '   specials["shadows"]  = new Array();'."\n";
      echo '   specials["triggers"] = new Array();'."\n";
      echo '   specials["veils"]    = new Array();'."\n";
      echo '   specials["traps"]    = new Array();'."\n";
      echo '   specials["speeds"]   = new Array();'."\n";
      echo '   specials["orbs"]     = new Array();'."\n";
      echo '   specials["closures"]  = new Array();'."\n";
      echo '   specials["pending"]  = new Array();'."\n";
      echo '      specials[2] = specials["portals"];'."\n";
      echo '      specials[3] = specials["shadows"];'."\n";
      echo '      specials[4] = specials["triggers"];'."\n";
      echo '      specials[5] = specials["veils"];'."\n";
      echo '      specials[6] = specials["traps"];'."\n";
      echo '      specials[7] = specials["speeds"];'."\n";
      echo '      specials[8] = specials["orbs"];'."\n";
      echo "\n";
      echo '      specials[2].color = "lightblue";'."\n";
      echo '      specials[3].color = "#f8f8f8";'."\n";
      echo '      specials[4].color = "#f8f8f8";'."\n";
      echo '      specials[5].color = "#ffffff";'."\n";
      echo '      specials[6].color = "#ff8888";'."\n";
      echo '      specials[7].color = "#a4ffda";'."\n";
      echo '      specials[8].color = "#8264b1";'."\n";
      echo "\n";
      echo 'var lastSpecial = -1;  // save last used special'."\n";
      echo "\n";
      echo 'function alter(type, ele){'."\n";
      echo '   if(!current && !ele) return false;'."\n";
      echo '   if(!ele) ele = current;'."\n";
      echo "\n";
      echo '   switch(type){'."\n";
      echo '      case 0:'."\n";
      echo '         //Start'."\n";
      echo '         if(document.getElementById(start))'."\n";
      echo '            document.getElementById(start).style.backgroundColor = "";'."\n";
      echo '         start = current;'."\n";
      echo '         toggleSE = 1;'."\n";
      echo "\n";
      echo '         document.getElementById("special:0.key1").style.display=\'none\';'."\n";
      echo '         document.getElementById("special:1.key1").style.display=\'\';'."\n";
      echo "\n";
      echo '         var color = "green";'."\n";
      echo '         break;'."\n";
      echo '      case 1:'."\n";
      echo '         //End'."\n";
      echo '         if(document.getElementById(end))'."\n";
      echo '            document.getElementById(end).style.backgroundColor = "";'."\n";
      echo '         end = current;'."\n";
      echo '         toggleSE = 0;'."\n";
      echo "\n";
      echo '         document.getElementById("special:1.key1").style.display=\'none\';'."\n";
      echo '         document.getElementById("special:0.key1").style.display=\'\';'."\n";
      echo "\n";
      echo '         var color = "orange";'."\n";
      echo '         break;'."\n";
      echo '      case 2:'."\n";
      echo '         //Portal'."\n";
      echo '         if(typeof specials["portals"].pending != "undefined")'."\n";
      echo '            handlePending(specials["portals"].pending);'."\n";
      echo '         else{'."\n";
      echo '            var m = specials["portals"].push([ele]) -1;'."\n";
      echo '            specials["portals"].pending = (specials["pending"].push({type:2, index:m}))-1;'."\n";
      echo "\n";
      echo '            document.getElementById("special:2.name").style.display ="none";'."\n";
      echo '            document.getElementById("special:2.keys").style.display ="none";'."\n";
      echo '            document.getElementById("special:2.pending").style.display ="";'."\n";
      echo '            listSpecials(2);'."\n";
      echo '         }'."\n";
      echo "\n";
      echo '         document.getElementById("special:"+lastSpecial+".key1").style.display=\'none\';'."\n";
      echo '         document.getElementById("special:2.key1").style.display=\'\';'."\n";
      echo '         lastSpecial = 2;'."\n";
      echo "\n";
      echo '         break;'."\n";
      echo '      case 3:'."\n";
      echo '         //Shadow'."\n";
      echo '         specials["shadows"].push(ele);'."\n";
      echo "\n";
      echo '         document.getElementById("special:"+lastSpecial+".key1").style.display=\'none\';'."\n";
      echo '         document.getElementById("special:3.key1").style.display=\'\';'."\n";
      echo '         lastSpecial = 3;'."\n";
      echo "\n";
      echo '         break;'."\n";
      echo '      case 4:'."\n";
      echo '         //Trigger'."\n";
      echo '         if(typeof specials["triggers"].pending != "undefined")'."\n";
      echo '            handlePending(specials["triggers"].pending);'."\n";
      echo '         else{'."\n";
      echo '            var m = specials["triggers"].push([ele]) -1;'."\n";
      echo '            specials["triggers"].pending = (specials["pending"].push({type:4, index:m, awaits:0}))-1;'."\n";
      echo "\n";
      echo '            document.getElementById("special:4.name").style.display = "none";'."\n";
      echo '            document.getElementById("special:4.keys").style.display = "none";'."\n";
      echo '            document.getElementById("special:4.pending").style.display = "";'."\n";
      echo '            listSpecials(4);'."\n";
      echo '         }'."\n";
      echo "\n";
      echo '         document.getElementById("special:"+lastSpecial+".key1").style.display=\'none\';'."\n";
      echo '         document.getElementById("special:4.key1").style.display=\'\';'."\n";
      echo '         lastSpecial = 4;'."\n";
      echo "\n";
      echo '         break;'."\n";
      echo '      case 5:'."\n";
      echo '         //Veil'."\n";
      echo '         specials["veils"].push(ele);'."\n";
      echo "\n";
      echo '         document.getElementById("special:"+lastSpecial+".key1").style.display=\'none\';'."\n";
      echo '         document.getElementById("special:5.key1").style.display=\'\';'."\n";
      echo '         lastSpecial = 5;'."\n";
      echo "\n";
      echo '         break;'."\n";
      echo '      case 6:'."\n";
      echo '         //Trap'."\n";
      echo '         if(typeof specials["traps"].pending != "undefined")'."\n";
      echo '            handlePending(specials["traps"].pending);'."\n";
      echo '         else{'."\n";
      echo '            var m = specials["traps"].push([ele]) -1'."\n";
      echo '            specials["traps"].pending = (specials["pending"].push({type:6, index:m}))-1;'."\n";
      echo "\n";
      echo '            document.getElementById("special:6.name").style.display = "none";'."\n";
      echo '            document.getElementById("special:6.keys").style.display = "none";'."\n";
      echo '            document.getElementById("special:6.pending").style.display = "";'."\n";
      echo '            document.getElementById("special:6.input").focus();'."\n";
      echo '            listSpecials(6);'."\n";
      echo '         }'."\n";
      echo "\n";
      echo '         document.getElementById("special:"+lastSpecial+".key1").style.display=\'none\';'."\n";
      echo '         document.getElementById("special:6.key1").style.display=\'\';'."\n";
      echo '         lastSpecial = 6;'."\n";
      echo "\n";
      echo '         var color   = "#ff8888";'."\n";
      echo '         break;'."\n";
      echo '      case 7:'."\n";
      echo '         //Speed'."\n";
      echo '         if(typeof specials["speeds"].pending != "undefined")'."\n";
      echo '            handlePending(specials["speeds"].pending);'."\n";
      echo '         else{'."\n";
      echo '            var m = specials["speeds"].push([ele]) -1'."\n";
      echo '            specials["speeds"].pending = (specials["pending"].push({type:7, index:m}))-1;'."\n";
      echo "\n";
      echo '            document.getElementById("special:7.name").style.display = "none";'."\n";
      echo '            document.getElementById("special:7.keys").style.display = "none";'."\n";
      echo '            document.getElementById("special:7.pending").style.display = "";'."\n";
      echo '            document.getElementById("special:7.input").focus();'."\n";
      echo '            listSpecials(7);'."\n";
      echo '         }'."\n";
      echo "\n";
      echo '         document.getElementById("special:"+lastSpecial+".key1").style.display=\'none\';'."\n";
      echo '         document.getElementById("special:7.key1").style.display=\'\';'."\n";
      echo '         lastSpecial = 7;'."\n";
      echo "\n";
      echo '         break;'."\n";
      echo '      case 8:'."\n";
      echo '         //Orb'."\n";
      echo '         specials["orbs"].push(ele)-1;'."\n";
      echo "\n";
      echo '         document.getElementById("special:"+lastSpecial+".key1").style.display=\'none\';'."\n";
      echo '         document.getElementById("special:8.key1").style.display=\'\';'."\n";
      echo '         lastSpecial = 8;'."\n";
      echo "\n";
      echo '         break;'."\n";
      echo '      case 9:'."\n";
      echo '         //Closure'."\n";
      echo '         specials["closures"].push(ele)-1;'."\n";
      echo '         return true;'."\n";
      echo '      default:'."\n";
      echo '         lastSpecial = -1;'."\n";
      echo '   }'."\n";
      echo "\n";
      echo '   document.getElementById(ele).style.backgroundColor = document.getElementById(ele).prevBackgroundColor = (specials[type])? specials[type].color : color;'."\n";
      echo '}'."\n";
      echo "\n";
      echo "\n";
      echo 'function handlePending(i){'."\n";
      echo '   switch(specials["pending"][i].type){'."\n";
      echo '      case 2:'."\n";
      echo '         specials["portals"][specials["pending"][i].index][1] = current;'."\n";
      echo "\n";
      echo '         document.getElementById("special:2.name").style.display = "";'."\n";
      echo '         document.getElementById("special:2.keys").style.display = "";'."\n";
      echo '         document.getElementById("special:2.pending").style.display = "none";'."\n";
      echo '         break;'."\n";
      echo "\n";
      echo '      case 4:'."\n";
      echo '         if(specials["pending"][i].awaits == 0){'."\n";
      echo '            specials["triggers"][specials["pending"][i].index][1] = current;'."\n";
      echo '            specials["pending"][i].awaits = 1;'."\n";
      echo "\n";
      echo '            document.getElementById("special:4.input").value = document.getElementById(current).className;'."\n";
      echo '            trigger_phase3_refreshPreview(2);'."\n";
      echo "\n";
      echo '            document.getElementById("special:4.pending1").style.display = "";'."\n";
      echo '            document.getElementById("special:4.pending").style.display  = "none";'."\n";
      echo '            listSpecials(4);'."\n";
      echo '         }else'."\n";
      echo '         if(specials["pending"][i].awaits == 1){'."\n";
      echo '            if(document.getElementsByName("trigger:phase3")[2].checked)'."\n";
      echo '               document.getElementById("special:4.input").value = document.getElementById(current).className;'."\n";
      echo '            specials["triggers"][specials["pending"][i].index][2] = document.getElementById("special:4.input").value;'."\n";
      echo '            delete specials["pending"][i].awaits;'."\n";
      echo "\n";
      echo '            document.getElementById("special:4.name").style.display = "";'."\n";
      echo '            document.getElementById("special:4.keys").style.display = "";'."\n";
      echo '            document.getElementById("special:4.pending1").style.display = "none";'."\n";
      echo '         }'."\n";
      echo '         break;'."\n";
      echo "\n";
      echo '      case 6:'."\n";
      echo '         specials["traps"][specials["pending"][i].index][1] = document.getElementById("special:6.input").value;'."\n";
      echo "\n";
      echo '         document.getElementById("special:6.name").style.display = "";'."\n";
      echo '         document.getElementById("special:6.keys").style.display = "";'."\n";
      echo '         document.getElementById("special:6.pending").style.display = "none";'."\n";
      echo '         document.getElementById("special:6.input").value = "";'."\n";
      echo '         document.getElementById("special:6.input").blur();'."\n";
      echo '         break;'."\n";
      echo "\n";
      echo '      case 7:'."\n";
      echo '         specials["speeds"][specials["pending"][i].index][1] = document.getElementById("special:7.input").value;'."\n";
      echo "\n";
      echo '         document.getElementById("special:7.name").style.display = "";'."\n";
      echo '         document.getElementById("special:7.keys").style.display = "";'."\n";
      echo '         document.getElementById("special:7.pending").style.display = "none";'."\n";
      echo '         document.getElementById("special:7.input").value = "";'."\n";
      echo '         document.getElementById("special:7.input").blur();'."\n";
      echo '         break;'."\n";
      echo '   }'."\n";
      echo "\n";
      echo '   if(!specials["pending"][i].awaits){'."\n";
      echo '      delete specials[specials["pending"][i].type].pending;'."\n";
      echo '      specials["pending"].splice(i, 1);'."\n";
      echo '   }'."\n";
      echo '}'."\n";
      echo "\n";
      echo "\n";
      echo 'function removeSpecial(type, pos){'."\n";
      echo '   if(!pos) pos = current;'."\n";
      echo "\n";
      echo '   if(type==1){'."\n";
      echo '      for(i=2; i<=8; i++)'."\n";
      echo '         for(s=0; s<specials[i].length; s++)'."\n";
      echo '            if(specials[i][s].constructor == Array){ //If Array'."\n";
      echo '               if( (j = specials[i][s].indexOf(pos)) != -1){'."\n";
      echo '                  var secondary = specials[i][s][1-j]'."\n";
      echo '                  specials[i].splice(s, 1);'."\n";
      echo '                  refreshPosition( secondary );'."\n";
      echo '               }'."\n";
      echo '            }else'."\n";
      echo '               if(specials[i][s] == pos)'."\n";
      echo '                  specials[i].splice(s, 1);'."\n";
      echo '   }else'."\n";
      echo '      for(s=0; s<specials[type].length; s++)'."\n";
      echo '         if(specials[type][s].constructor == Array){ //If Array'."\n";
      echo '               if( (j=specials[type][s].indexOf(pos)) != -1){'."\n";
      echo '                  var secondary = specials[type][s][1-j]'."\n";
      echo '                  specials[type].splice(s, 1);'."\n";
      echo '                  refreshPosition( secondary );'."\n";
      echo '               }'."\n";
      echo '         }else'."\n";
      echo '            if(specials[type][s] == pos)'."\n";
      echo '               specials[type].splice(s, 1);'."\n";
      echo "\n";
      echo '   refreshPosition(pos);'."\n";
      echo '}'."\n";
      echo "\n";
      echo 'function refreshPosition(pos){'."\n";
      echo '   var ele = document.getElementById(pos);'."\n";
      echo "\n";
      echo '   ele.style.backgroundColor = ele.prevBackgroundColor = "#ffffff";'."\n";
      echo "\n";
      echo '   for(i=2; i<=8; i++)'."\n";
      echo '      for(s=0; s<specials[i].length; s++)'."\n";
      echo '         if(specials[i][s].constructor == Array){ //If Array'."\n";
      echo '            if(specials[i][s][0] == pos || specials[i][s][1] == pos)'."\n";
      echo '               ele.style.backgroundColor = ele.prevBackgroundColor = specials[i].color;'."\n";
      echo '         }else'."\n";
      echo '            if(specials[i][s] == pos)'."\n";
      echo '               ele.style.backgroundColor = ele.prevBackgroundColor = specials[i].color;'."\n";
      echo '}'."\n";
      echo "\n";
      echo "\n";
      echo 'function specialsListed(){'."\n";
      echo '   var listed = new Array();'."\n";
      echo "\n";
      echo '   for(i=0; i<=8; i++)'."\n";
      echo '      if(document.getElementById("special:"+i).style.display!="none")'."\n";
      echo '         listed.push(i);'."\n";
      echo "\n";
      echo '   return (listed.length)? listed : false;'."\n";
      echo '}'."\n";
      echo "\n";
      echo "\n";
      echo 'var interval = 20; // The interval between each special`s display in miliseconds'."\n";
      echo 'function listSpecials(extra){'."\n";
      echo '   for(i=0; i<=8; i++)'."\n";
      echo '      if(extra == 0){'."\n";
      echo '         if(i<2 || typeof specials[i].pending == "undefined")'."\n";
      echo '            setTimeout(\'document.getElementById("special:\'+i+\'").style.display = "none";\', interval*(8-i));'."\n";
      echo '      }else'."\n";
      echo "\n";
      echo '      if(extra == 1 || extra == i)'."\n";
      echo '         setTimeout(\'document.getElementById("special:\'+i+\'").style.display = "";\', interval*i);'."\n";
      echo "\n";
      echo '}'."\n";

      //Connect Sections (dependency)
      $_REQUEST['js'] = 'maintenance';
   }
   if($_REQUEST['js']=='maintenance'){
      echo '      topMost    = "0:0";'."\n";
      echo '      leftMost   = "0:0";'."\n";
      echo '      rightMost  = "0:0";'."\n";
      echo '      bottomMost = "0:0";'."\n";
      echo "\n";
      echo 'function maintenance(elem){'."\n";
      echo '   if(!elem) elem = current;'."\n";
      echo '   if(typeof elem == "string") elem = document.getElementById(elem);'."\n";
      echo '   var table = document.getElementById("game:grid");'."\n";
      echo "\n";
      echo '  //If the Cell clicked is:'."\n";
      echo '   //The Last Cell on the Left'."\n";
      echo '   if(x(elem.id)==x(leftMost)){'."\n";
      echo '      for(i=0;i<table.rows.length;i++){'."\n";
      echo '         cell = document.createElement("td");'."\n";
      echo "\n";
      echo '         cell.className = "1111";'."\n";
      echo '         cell.onclick   = function(){ mark(this); };'."\n";
      echo '         cell.id = (x(leftMost)-1)+":"+(y(topMost)-i);'."\n";
      echo "\n";
      echo '         document.getElementById(":"+(y(topMost)-i)).insertBefore(cell, document.getElementById(":"+(y(topMost)-i)).firstChild);'."\n";
      echo '      }'."\n";
      echo "\n";
      echo '      leftMost = cell.id;'."\n";
      echo '   }'."\n";
      echo "\n";
      echo '   //The Last Cell on the Right'."\n";
      echo '   if(x(elem.id)==x(rightMost)){'."\n";
      echo '      for(i=0;i<table.rows.length;i++){'."\n";
      echo '         cell = document.createElement("td");'."\n";
      echo "\n";
      echo '         cell.className = "1111";'."\n";
      echo '         cell.onclick   = function(){ mark(this); };'."\n";
      echo '         cell.id = (x(rightMost)-(-1))+":"+(y(topMost)-i);'."\n";
      echo "\n";
      echo '         document.getElementById(":"+(y(topMost)-i)).appendChild(cell);'."\n";
      echo '      }'."\n";
      echo "\n";
      echo '      rightMost = cell.id;'."\n";
      echo '   }'."\n";
      echo "\n";
      echo '   //The Last Cell on the Top'."\n";
      echo '   if(y(elem.id)==y(topMost)){'."\n";
      echo '      cells = table.rows[0].cells.length;'."\n";
      echo "\n";
      echo '      row = document.createElement("tr");'."\n";
      echo '      row.id = ":"+(y(topMost)-(-1));'."\n";
      echo '      table.insertBefore(row, table.firstChild);'."\n";
      echo "\n";
      echo '      for(i=0; i<cells; i++){'."\n";
      echo '         cell = document.createElement("td");'."\n";
      echo "\n";
      echo '         cell.className = "1111";'."\n";
      echo '         cell.onclick   = function(){ mark(this); };'."\n";
      echo '         cell.id = (x(leftMost)-(-i))+":"+(y(topMost)-(-1));'."\n";
      echo "\n";
      echo '         row.appendChild(cell);'."\n";
      echo '      }'."\n";
      echo "\n";
      echo '      topMost = cell.id;'."\n";
      echo '   }'."\n";
      echo "\n";
      echo '   //The Last Cell on the Bottom'."\n";
      echo '   if(y(elem.id)==y(bottomMost)){'."\n";
      echo '      cells = table.rows[0].cells.length;'."\n";
      echo "\n";
      echo '      row = document.createElement("tr");'."\n";
      echo '      row.id = ":"+(y(bottomMost)-1);'."\n";
      echo '      table.appendChild(row);'."\n";
      echo "\n";
      echo '      for(i=0; i<cells; i++){'."\n";
      echo '         cell = document.createElement("td");'."\n";
      echo "\n";
      echo '         cell.className = "1111";'."\n";
      echo '         cell.onclick   = function(){ mark(this); };'."\n";
      echo '         cell.id = (x(leftMost)-(-i))+":"+(y(bottomMost)-1);'."\n";
      echo "\n";
      echo '         row.appendChild(cell);'."\n";
      echo '      }'."\n";
      echo "\n";
      echo '      bottomMost = cell.id;'."\n";
      echo '   }'."\n";
      echo '}'."\n";

      //Connect Sections (dependency)
      $_REQUEST['js'] = 'coordinates';
   }
   if($_REQUEST['js']=='coordinates'){
      echo 'function x(coordinates){'."\n";
      echo '   return coordinates.split(":")[0];'."\n";
      echo '}'."\n";
      echo "\n";
      echo 'function y(coordinates){'."\n";
      echo '   return coordinates.split(":")[1];'."\n";
      echo '}'."\n";
      echo "\n";
      echo "\n";
      echo 'function left(pos){'."\n";
      echo '   return (x(pos)-1) +":"+ y(pos);'."\n";
      echo '}'."\n";
      echo 'function up(pos){'."\n";
      echo '   return x(pos) +":"+ (y(pos)-(-1));'."\n";
      echo '}'."\n";
      echo 'function right(pos){'."\n";
      echo '   return (x(pos)-(-1)) +":"+ y(pos);'."\n";
      echo '}'."\n";
      echo 'function down(pos){'."\n";
      echo '   return x(pos) +":"+ (y(pos)-1);'."\n";
      echo '}'."\n";

      //Connect Sections
      $_REQUEST['js'] = 'export';
   }
   if($_REQUEST['js']=='export'){
      echo 'function export_(){'."\n";
      echo '   my = y(bottomMost);'."\n";
      echo '   mx = x(rightMost);'."\n";
      echo '   var cells = new Array();'."\n";
      echo '   var time  = ( (y(topMost)-1-my)*(mx-1-x(leftMost)) )/3;'."\n";
      echo '   var code  = "time="+(Math.round(time)-(-3))+"&start="+convert(start)+"&end="+convert(end)+"&next=\n";'."\n";
      echo "\n";
      echo '   //Closures'."\n";
      echo '   for(i=0; i<specials["closures"].length; i++)'."\n";
      echo '      code += "c"+convert(specials["closures"][i])+",";'."\n";
      echo '   //Portals'."\n";
      echo '   for(i=0; i<specials[2].length; i++)'."\n";
      echo '      code += "p"+convert(specials[2][i][0])+"-"+convert(specials[2][i][1])+",";'."\n";
      echo '   //Shadows'."\n";
      echo '   for(i=0; i<specials[3].length; i++)'."\n";
      echo '      code += "b"+convert(specials[3][i])+",";'."\n";
      echo '   //Triggers'."\n";
      echo '   for(i=0; i<specials[4].length; i++)'."\n";
      echo '      code += "t"+convert(specials[4][i][0])+":"+convert(specials[4][i][1])+"-"+specials[4][i][2]+",";'."\n";
      echo '   //Traps'."\n";
      echo '   for(i=0; i<specials[6].length; i++)'."\n";
      echo '      code += "f"+convert(specials[6][i][0])+":"+specials[6][i][1]+",";'."\n";
      echo '   //Speeds'."\n";
      echo '   for(i=0; i<specials[7].length; i++)'."\n";
      echo '      code += "s"+convert(specials[7][i][0])+":"+specials[7][i][1]+",";'."\n";
      echo '   //Orbs'."\n";
      echo '   for(i=0; i<specials[8].length; i++)'."\n";
      echo '      code += "o"+convert(specials[8][i])+",";'."\n";
      echo '   //Veils'."\n";
      echo '   for(i=0; i<specials[5].length; i++)'."\n";
      echo '      code += "v"+convert(specials[5][i])+",";'."\n";
      echo "\n";
      echo '   if(code.charAt(code.length-1)==",") code = code.substr(0, code.length-1);'."\n";
      echo '   code += "\n";'."\n";
      echo "\n";
      echo "\n";
      echo '   for(r=(y(topMost)-1); r>my; r--){'."\n";
      echo "\n";
      echo '      for(c=(x(leftMost)-(-1)); c<mx; c++)'."\n";
      echo '         code += document.getElementById(c+":"+r).className + ".";'."\n";
      echo "\n";
      echo '      code = code.substr(0, code.length-1)+"\n";'."\n";
      echo '   }'."\n";
      echo "\n";
      echo '   document.getElementById("source:code").value = code;'."\n";
      echo '   document.getElementById("source:container").style.display = "";'."\n";
      echo '}'."\n";
      echo "\n";
      echo 'function convert(pos){'."\n";
      echo '   if(!pos || typeof pos != "string") return "";'."\n";
      echo '   return (y(topMost) - y(pos) -1)+"."+(x(pos) - x(leftMost) -1);'."\n";
      echo '}'."\n";
      echo "\n";
      echo 'var submitName = false;'."\n";
      echo 'var sent       = false;'."\n";
      echo 'function submit_(){'."\n";
      echo '   if(submitName===false){'."\n";
      echo '      submitName = prompt("Choose a name for the maze");'."\n";
      echo '      if(submitName.split(" ").join("").length<=0) submitName = "New Maze";'."\n";
      echo '   }'."\n";
      echo "\n";
      echo '   var newWindow = window.open("?queue&form", "Blank Form");'."\n";
      echo '   newWindow.onload = function(){'."\n";
      echo '      newWindow.document.getElementById("source:sent").value = sent? "true" : "false";'."\n";
      echo '      newWindow.document.getElementById("source:name").value = submitName;'."\n";
      echo '      newWindow.document.getElementById("source:code").value = document.getElementById("source:code").value;'."\n";
      echo '      newWindow.document.getElementById("source:form").submit();'."\n";
      echo '      sent = true;'."\n";
      echo '      document.getElementById("source:container").style.display = "none";'."\n";
      echo '   }'."\n";
      echo '}'."\n";
   }
   exit;
}

//CSS
css:
if(isset($_REQUEST['css'])){
   if(strlen(trim($_REQUEST['css']))<=0)
      $_REQUEST['css'] ='main';

   if($_REQUEST['css'] == 'main'){
      echo 'table.special{'."\n";
      echo '   width:         230px;'."\n";
      echo '   border-top:    2px solid black;'."\n";
      echo '   border-bottom: 2px solid black; '."\n";
      echo '}'."\n";
      echo 'table.special td{'."\n";
      echo '   height: 40px;'."\n";
      echo '   padding-left: 10px;'."\n";
      echo '}'."\n";
      echo 'table.special td td{'."\n";
      echo '   padding-left: 0px;'."\n";
      echo '}'."\n";
      echo 'em a:hover small{'."\n";
      echo '   text-decoration: underline;'."\n";
//      echo '   font-weight:bold;'."\n";
      echo '}'."\n";
   }
   exit;
}

head:{
echo '<head>'."\n";
echo '   <title>Create a Maze</title>'."\n";
echo '   <script type="text/javascript" src="?js"></script>'."\n";
echo '   <link rel="stylesheet" type="text/css" href="?css" />'."\n";
echo '</head>'."\n";
}

body:{
   menu:{
   echo '   <div id="menu" style="position:fixed;">'."\n";
      buttons:{
      echo '         <button onclick="export_();">Export</button>'."\n";
      echo '         <button onclick="var ele = document.getElementById(\'keysCont\');     if(ele.style.display==\'\') ele.style.display=\'none\'; else ele.style.display=\'\';">Keys</button>'."\n";
      echo '         <button onclick="listSpecials( ((specialsListed())? 0 : 1) );">Specials (<b>E</b>)</button>'."\n";
      }
   echo '         <br />'."\n";
   echo '         <table>'."\n";
   echo '            <tr>'."\n";
      cont_keys:{
      echo '               <td valign="top">'."\n";
      echo '                  <table id="keysCont" style="display:none;">'."\n";
      echo '                        <tr> <td>Decorator mode:</td> <td> &nbsp; &nbsp; </td> <td><b>Space</b> <i>(hold)</i></td> <td> &nbsp; , &nbsp; </td> <td><b>C</b> <i>(hold)</i></td> </tr>'."\n";
      echo '                        <tr> <td>Tunneler mode:</td> <td> &nbsp; &nbsp; </td> <td><b>Ctrl</b> <i>(hold)</i></td> <td> &nbsp; , &nbsp; </td> <td><b>X</b> <i>(hold)</i></td> </tr>'."\n";
      echo '                        <tr> <td>Builder &nbsp;mode:<br /><sup>opposite of tunneler</sup></td> <td> &nbsp; &nbsp; </td> <td><b>Shift</b> <i>(hold)</i></td> <td> &nbsp; , &nbsp; </td> <td><b>Z</b> <i>(hold)</i></td> </tr>'."\n";
      echo '                        <tr> <td> <br /> </td> </tr>'."\n";
      echo '                        <tr> <td>Left &nbsp;&nbsp;direction:</td> <td> &nbsp; &nbsp; </td> <td><b>Left Arrow</b></td> <td> &nbsp; , &nbsp; </td> <td><b>A</b></td> </tr>'."\n";
      echo '                        <tr> <td>Up &nbsp; &nbsp;direction:</td> <td> &nbsp; &nbsp; </td> <td><b>Up Arrow</b></td> <td> &nbsp; , &nbsp; </td> <td><b>W</b></td> </tr>'."\n";
      echo '                        <tr> <td>Right direction:</td> <td> &nbsp; &nbsp; </td> <td><b>Right Arrow</b></td> <td> &nbsp; , &nbsp; </td> <td><b>D</b></td> </tr>'."\n";
      echo '                        <tr> <td>Down direction:</td> <td> &nbsp; &nbsp; </td> <td><b>Down Arrow</b></td> <td> &nbsp; , &nbsp; </td> <td><b>S</b></td> </tr>'."\n";
      echo '                        <tr> <td> <br /> </td> </tr>'."\n";
      echo '                        <tr> <td>Mark as Start/End <br /><sup>toggled in a sequence</sup>:</td> <td> &nbsp; &nbsp; </td> <td><b>Q</b></td> </tr>'."\n";
      echo '                        <tr> <td>Mark as a Special:<br /><sup>double tap selects last</sup>:</td> <td> &nbsp; &nbsp; </td> <td><b>E</b></td> </tr>'."\n";
      echo '                  </table>'."\n";
      echo '               </td>'."\n";
      }
      cont_specials:{
         echo '               <td valign="top">'."\n";
         echo '                     <table style="margin-left:25px; border-collapse:collapse;">'."\n";
         echo '                           <tr id="special:-1.key1"></tr>'."\n";
            js_toggleRemoves:{
            echo '                           <script type="text/javascript">'."\n";
            echo '                              var delay = new Array();'."\n";
            echo '                              delay.show = 500;'."\n";
            echo '                              delay.hide = 500;'."\n";
            echo '                              delay.step = 20;'."\n";
            echo "\n";
            echo '                              function toggleRemoves(special, force, delayed){'."\n";
            echo "\n";
            echo '                                 if(special==1 || force){'."\n";
            echo '                                    clearTimeout(delay[special]);'."\n";
            echo '                                    delay[special] = setTimeout("toggleRemoves("+special+", 1, delay.show);", 500);'."\n";
            echo '                                 }else{'."\n";
            echo '                                    clearTimeout(delay[special]);'."\n";
            echo '                                    delay[special] = setTimeout("toggleRemoves("+special+", 0, delay.show);", 500);'."\n";
            echo '                                 }'."\n";
            echo "\n";
            echo '                                 if(!delayed) return false;'."\n";
            echo "\n";
            echo '                                 for(i=2; i<=8; i++)'."\n";
            echo '                                    if(special==0){'."\n";
            echo '                                       setTimeout(\'document.getElementById("special:\'+i+\'.remove").style.display="none";\', delay.step);'."\n";
            echo '                                       document.getElementById("special:"+i+".remove:all").style.display="none";'."\n";
            echo '                                    }else'."\n";
            echo '                                    if(special==1){'."\n";
            echo '                                       document.getElementById("special:"+i+".remove").style.display="";'."\n";
            echo '                                       setTimeout(\'document.getElementById("special:\'+i+\'.remove:all").style.display="";\', delay.step);'."\n";
            echo '                                    }else'."\n";
            echo '                                    if(special==i){'."\n";
            echo '                                       if(typeof force != "undefined")'."\n";
            echo '                                          var to = force? 1 : 0;'."\n";
            echo '                                       else'."\n";
            echo '                                       if(document.getElementById("special:"+i+".remove").style.display!="none")'."\n";
            echo '                                          var to = 1;'."\n";
            echo '                                       else'."\n";
            echo '                                          var to = 0;'."\n";
            echo "\n";
            echo '                                       setTimeout(\'document.getElementById("special:\'+i+\'.remove").style.display="\'+(to?"":"none")+\'";\',     delay.step*(1-to) );'."\n";
            echo '                                       setTimeout(\'document.getElementById("special:\'+i+\'.remove:all").style.display="\'+(to?"":"none")+\'";\', delay.step*(to)   );'."\n";
            echo '                                    }'."\n";
            echo '                                 }'."\n";
            echo '                              </script>'."\n";
            }

         special_0:{
         echo "\n";
         echo '                        <tr>'."\n";
         echo '                           <td>'."\n";
         echo '                              <table id="special:0" class="special" style="display:none;" title="Choose where the player should start from"'."\n";
         echo '                                 onclick="alter(0); listSpecials(0);" onmouseover="this.style.backgroundColor = \'#f8f8f8\';" onmouseout="this.style.backgroundColor = \'\';">'."\n";
         echo '                                    <tr>'."\n";
         echo '                                       <td id="special:0.color"><img src="img/1111.png" style="background-color:green;" /></td>'."\n";
         echo '                                       <td id="special:0.name" style="min-width:90px;"> <strong>Start</strong> :</td>'."\n";
         echo '                                       <td id="special:0.keys" style="min-width:75px;"> <sub>Press</sub> <strong>1</strong> <a id="special:0.key1" style="display:;"><sub>or</sub> <strong>Q</strong></a> </td>'."\n";
         echo '                                    </tr>'."\n";
         echo '                              </table>'."\n";
         echo '                           </td>'."\n";
         echo '                        </tr>'."\n";
         }

         special_1:{
         echo "\n";
         echo '                        <tr>'."\n";
         echo '                           <td>'."\n";
         echo '                              <table id="special:1" class="special" style="display:none;" title="Choose where the end should be"'."\n";
         echo '                                 onclick="alter(1); listSpecials(0);" onmouseover="this.style.backgroundColor = \'#f8f8f8\';" onmouseout="this.style.backgroundColor = \'\';">'."\n";
         echo '                                    <tr>'."\n";
         echo '                                       <td id="special:1.color"><img src="img/1111.png" style="background-color:orange;" /></td>'."\n";
         echo '                                       <td id="special:1.name" style="min-width:90px;"> <strong>Ending</strong> :</td>'."\n";
         echo '                                       <td id="special:1.keys" style="min-width:75px;"> <sub>Press</sub> <strong>2</strong> <a id="special:1.key1" style="display:none;"><sub>or</sub> <strong>Q</strong></a> </td>'."\n";
         echo '                                    </tr>'."\n";
         echo '                              </table>'."\n";
         echo '                           </td>'."\n";
         echo '                        </tr>'."\n";
         }

         special_2:{
         echo "\n";
         echo '                        <tr onmouseover="toggleRemoves(2, 1);" onmouseout="toggleRemoves(2,0);">'."\n";
         echo '                           <td align="center">'."\n";
         echo '                              <table id="special:2" class="special" style="display:none;"'."\n";
         echo '                                 onclick="alter(2); listSpecials(0);" onmouseover="this.style.backgroundColor = \'#f8f8f8\';" onmouseout="this.style.backgroundColor = \'\';"> '."\n";
         echo '                                    <tr> '."\n";
         echo '                                       <td id="special:2.color"><div style="background-color:lightblue;"><img src="img/1111.png" /></div></td> '."\n";
         echo '                                       <td id="special:2.name" style="min-width:90px;"><strong>Portal</strong> :</td> '."\n";
         echo '                                       <td id="special:2.keys" style="min-width:75px;"> <sub>Press</sub> <strong>3</strong> <a id="special:2.key1" style="display:none;"><sub>or</sub> <strong>E</strong></a> </td> '."\n";
         echo '                                       <td id="special:2.pending" colspan="2" style="display:none;"> '."\n";
         echo '                                          <table> '."\n";
         echo '                                             <tr> '."\n";
         echo '                                                <td style="text-align:center;"><small>Choose the other end of</br ></small> the <strong>Portal</strong></td> '."\n";
         echo '                                                <td style="padding-left:10px;"><button style="width:23px;"><strong>3</strong></button><br /><button style="width:23px;"><strong>E</strong></button></td> '."\n";
         echo '                                             </tr> '."\n";
         echo '                                          </table>'."\n";
         echo '                                       </td> '."\n";
         echo '                                    </tr> '."\n";
         echo '                              </table> '."\n";
         echo '                                 <script type="text/javascript">var toggleRemoves_old = toggleRemoves; toggleRemoves = function(a, b, c){ if(a==2&&c){document.getElementById("special:2.closure").style.display=(b==1? "" : "none");}  toggleRemoves_old(a,b,c);};</script>'."\n";
         echo '                                 <em id="special:2.closure"    style="display:none;" onclick="alter(9); listSpecials(0);"><a><small>Make &quot;<b>destination-only</b>&quot; block</small></a><br /></em>'."\n";
         echo '                                 <em id="special:2.remove"     style="display:none;" onclick="removeSpecial(2);"><a><small>Remove <b>Portals</b> from current block</small></a><br /></em>'."\n";
         echo '                                 <em id="special:2.remove:all" style="display:none;" onclick="removeSpecial(1);"><a><small>Remove <b>All Specials</b> from current block</small></a></em>'."\n";
         echo '                           </td> '."\n";
         echo '                        </tr>'."\n";
         }

         special_3:{
         echo "\n";
         echo '                        <tr onmouseover="toggleRemoves(3,1);" onmouseout="toggleRemoves(3,0);">'."\n";
         echo '                           <td align="center">'."\n";
         echo '                              <table id="special:3" class="special" style="display:none;"'."\n";
         echo '                                 onclick="alter(3); listSpecials(0);" onmouseover="this.style.backgroundColor = \'#f8f8f8\';" onmouseout="this.style.backgroundColor = \'\';">'."\n";
         echo '                                    <tr>'."\n";
         echo '                                       <td id="special:3.color"><img src="img/1111.png" style="background-color:#f8f8f8;" /></td>'."\n";
         echo '                                       <td id="special:3.name" style="min-width:90px;"> <strong>Shadow</strong> :</td>'."\n";
         echo '                                       <td id="special:3.keys" style="min-width:75px;"> <sub>Press</sub> <strong>4</strong> <a id="special:3.key1" style="display:none;"><sub>or</sub> <strong>E</strong></a> </td>'."\n";
         echo '                                    </tr>'."\n";
         echo '                              </table>'."\n";
         echo '                                 <em id="special:3.remove"     style="display:none;" onclick="removeSpecial(3);"><a><small>Remove <b>Shadows</b> from current block</small></a><br /></em>'."\n";
         echo '                                 <em id="special:3.remove:all" style="display:none;" onclick="removeSpecial(1);"><a><small>Remove <b>All Specials</b> from current block</small></a></em>'."\n";
         echo '                           </td>'."\n";
         echo '                        </tr>'."\n";
         }

         special_4:{
         echo "\n";
         echo '                        <tr onmouseover="toggleRemoves(4,1);" onmouseout="toggleRemoves(4,0);">'."\n";
         echo '                           <td align="center">'."\n";
         echo '                              <table id="special:4" class="special" style="display:none;"'."\n";
         echo '                                 onclick="alter(4); listSpecials(0);" onmouseover="this.style.backgroundColor = \'#f8f8f8\';" onmouseout="this.style.backgroundColor = \'\';"> '."\n";
         echo '                                    <tr> '."\n";
         echo '                                       <td id="special:4.color"><img src="img/1111.png" style="background-color:#f8f8f8;" /></td> '."\n";
         echo '                                       <td id="special:4.name" style="min-width:90px;"> <strong>Trigger</strong> :</td> '."\n";
         echo '                                       <td id="special:4.keys" style="min-width:75px;"> <sub>Press</sub> <strong>5</strong> <a id="special:4.key1" style="display:none;"><sub>or</sub> <strong>E</strong></a> </td> '."\n";
         echo '                                       <td id="special:4.pending" style="display:none;"> '."\n";
         echo '                                          <table> '."\n";
         echo '                                             <tr> '."\n";
         echo '                                                <td style="text-align:center;"><small>Choose the affected block by the</small> </br > <strong>Trigger</strong></td> '."\n";
         echo '                                                <td><button style="width:23px;"><strong>5</strong></button><br /><button style="width:23px;"><strong>E</strong></button></td> '."\n";
         echo '                                             </tr> '."\n";
         echo '                                          </table> '."\n";
         echo '                                       </td> '."\n";
         echo '                                       <td id="special:4.pending1" style="padding-left:0px; display:none;"> '."\n";
         echo '                                          <table> '."\n";
         echo '                                             <tr> '."\n";
         echo '                                                <td style="text-align:center;"><small>The affected should change to:</small></td> '."\n";
         echo '                                                <td style="text-align:center;"> '."\n";
         echo '                                                      <table style="width:80px;"> '."\n";
         echo '                                                         <tr> '."\n";
         echo '                                                            <td colspan="3" style="height:20px;"> '."\n";
         echo '                                                               <script type="text/javascript">function trigger_phase3_displaySub(){var subs = document.getElementsByName("trigger:phase3-sub"); for(si=0; si<subs.length; si++) subs[si].style.display="none"; var arr = document.getElementsByName("trigger:phase3"); for(i=0; i<arr.length; i++) if(arr[i].checked){ subs[i].style.display = ""; trigger_phase3_refreshPreview(i-(-1)); } }</script> '."\n";
         echo '                                                               <script type="text/javascript">function stopBubble(evn){ if(evn.stopPropagation) evn.stopPropagation(); else evn.cancelBubble(); }</script> '."\n";
         echo '                                                               <form style="margin:0px"> '."\n";
         echo '                                                                  <input type="radio" name="trigger:phase3" onclick="trigger_phase3_displaySub(); stopBubble(event);" title="Choose to what the affected block should change by writing the code manually" /> '."\n";
         echo '                                                                  <input type="radio" name="trigger:phase3" onclick="trigger_phase3_displaySub(); stopBubble(event);" title="Choose to what the affected block should change by choosing the walls" checked="checked" /> '."\n";
         echo '                                                                  <input type="radio" name="trigger:phase3" onclick="trigger_phase3_displaySub(); stopBubble(event);" title="Choose to what the affected block should change by selecting a field on the grid to be copied" /> '."\n";
         echo '                                                               </form> '."\n";
         echo '                                                            </td> '."\n";
         echo '                                                         </tr> '."\n";
         echo '                                                         <tr> '."\n";
         echo '                                                            <td name="trigger:phase3-sub" style="height:25px" align="center" valign="middle"> '."\n";
         echo '                                                               <script type="text/javascript">function trigger_phase3_refreshPreview(sub){if(sub==2){ document.getElementById("special:4.phase3-sub2-preview").src = "img/"+document.getElementById("special:4.input").value+".png";  trigger_phase3_refreshCheckboxes();}else if(sub==3){document.getElementById("special:4.phase3-sub3-preview").src = "img/"+document.getElementById(current).className+".png";} }</script> '."\n";
         echo '                                                               <script type="text/javascript">var modify_old = modify; modify = function(elem, side, wall){ if(typeof specials[4].pending != "undefined" && specials["pending"][specials[4].pending].awaits == 1) if(document.getElementsByName("trigger:phase3")[1].checked){var e = document.getElementById("special:4.input"); if(wall=="!") wall = ((e.value.charAt(side)==0)? 1 : 0); wall = (wall==0)? "0" : "1"; var cn = e.value.split(""); cn.splice(side, 1, wall); e.value = cn.join(""); return trigger_phase3_refreshPreview(2); } modify_old(elem, side, wall);};</script>'."\n";
         echo '                                                               <input type="text" id="special:4.input" size="4" value="1111" style="text-align:center;" title="1 means `wall`, 0 means `no wall`; (left-down-right-up)" /> '."\n";
         echo '                                                            </td> '."\n";
         echo '                                                            <td name="trigger:phase3-sub" style="height:55px; display:none;" align="center"> '."\n";
         echo '                                                               <script type="text/javascript">function trigger_phase3_changeWall(wall, exist){var inp = document.getElementById("special:4.input"); var e = inp.value.split(""); e.splice(wall, 1, exist); inp.value = e.join(""); trigger_phase3_refreshPreview(2);}</script> '."\n";
         echo '                                                               <script type="text/javascript"> function trigger_phase3_refreshCheckboxes(){ var inp = document.getElementById("special:4.input"); var e = inp.value.split(""); for(i=0; i<4; i++) document.getElementById("special:4.phase3-sub2-checkbox"+i).checked = e[i]==1;}</script> '."\n";
         echo '                                                               <form style="margin:0px; height:55px;"> '."\n";
         echo '                                                                  <input type="checkbox" id="special:4.phase3-sub2-checkbox3" onclick="trigger_phase3_changeWall(3, (this.checked? 1 : 0)); stopBubble(event);" title="Toggle the top wall" /><br /> '."\n";
         echo '                                                                  <input type="checkbox" id="special:4.phase3-sub2-checkbox0" onclick="trigger_phase3_changeWall(0, (this.checked? 1 : 0)); stopBubble(event);" style="position:relative; bottom:5px; left:4px;" title="Toggle the left wall" /> <img id="special:4.phase3-sub2-preview" src="img/1111.png" /> <input type="checkbox" id="special:4.phase3-sub2-checkbox2" onclick="trigger_phase3_changeWall(2, (this.checked? 1 : 0)); stopBubble(event);" style="position:relative; bottom:5px; right:5px;" title="Toggle the right wall" /><br /> '."\n";
         echo '                                                                  <input type="checkbox" id="special:4.phase3-sub2-checkbox1" onclick="trigger_phase3_changeWall(1, (this.checked? 1 : 0)); stopBubble(event);"style="position:relative; bottom:5px;" title="Toggle the bottom wall" /> '."\n";
         echo '                                                               </form> '."\n";
         echo '                                                            </td> '."\n";
         echo '                                                            <td name="trigger:phase3-sub" style="height:25px; display:none;" align="center"> '."\n";
         echo '                                                               <script type="text/javascript">mark_old = mark; mark = function(elem){ var elem = mark_old(elem); if(document.getElementsByName("trigger:phase3-sub")[2].style.display!="none") setTimeout(\'document.getElementById("special:4.input").value = document.getElementById("\'+elem.id+\'").className; trigger_phase3_refreshPreview(3);\', 100); };</script> '."\n";
         echo '                                                               <img id="special:4.phase3-sub3-preview" src="img/1111.png" title="Preview of the currently selected" />'."\n";
         echo '                                                            </td> '."\n";
         echo '                                                         </tr> '."\n";
         echo '                                                         <script type="text/javascript">trigger_phase3_displaySub();</script> '."\n";
         echo '                                                      </table> '."\n";
         echo '                                                </td> '."\n";
         echo '                                                <td><button style="width:23px;"><strong>5</strong></button><br /><button style="width:23px;"><strong>E</strong></button></td> '."\n";
         echo '                                             </tr> '."\n";
         echo '                                          </table> '."\n";
         echo '                                       </td> '."\n";
         echo '                                    </tr> '."\n";
         echo '                              </table> '."\n";
         echo '                                 <em id="special:4.remove"     style="display:none;" onclick="removeSpecial(4);"><a><small>Remove <b>Triggers</b> from current block</small></a><br /></em> '."\n";
         echo '                                 <em id="special:4.remove:all" style="display:none;" onclick="removeSpecial(1);"><a><small>Remove <b>All Specials</b> from current block</small></a></em> '."\n";
         echo '                           </td> '."\n";
         echo '                        </tr> '."\n";
         }

         special_5:{
         echo "\n";
         echo '                        <tr onmouseover="toggleRemoves(5,1);" onmouseout="toggleRemoves(5,0);">'."\n";
         echo '                           <td align="center"> '."\n";
         echo '                              <table id="special:5" class="special" style="display:none;"'."\n";
         echo '                                 onclick="alter(5); listSpecials(0);" onmouseover="this.style.backgroundColor = \'#f8f8f8\';" onmouseout="this.style.backgroundColor = \'\';"> '."\n";
         echo '                                    <tr> '."\n";
         echo '                                       <td id="special:5.color"><img src="img/1111.png" style="background-color:#ffffff;" /></td> '."\n";
         echo '                                       <td id="special:5.name" style="min-width:90px;"> <strong>Veil</strong> :</td> '."\n";
         echo '                                       <td id="special:5.keys" style="min-width:75px;"> <sub>Press</sub> <strong>6</strong> <a id="special:5.key1" style="display:none;"><sub>or</sub> <strong>E</strong></a> </td> '."\n";
         echo '                                    </tr> '."\n";
         echo '                              </table> '."\n";
         echo '                                 <em id="special:5.remove"     style="display:none;" onclick="removeSpecial(5);"><a><small>Remove <b>Veils</b> from current block</small></a><br /></em> '."\n";
         echo '                                 <em id="special:5.remove:all" style="display:none;" onclick="removeSpecial(1);"><a><small>Remove <b>All Specials</b> from current block</small></a></em> '."\n";
         echo '                           </td> '."\n";
         echo '                        </tr>'."\n";
         }

         special_6:{
         echo "\n";
         echo '                        <tr onmouseover="toggleRemoves(6,1);" onmouseout="toggleRemoves(6,0);">'."\n";
         echo '                           <td align="center">'."\n";
         echo '                              <table id="special:6" class="special" style="display:none;"'."\n";
         echo '                                 onclick="alter(6); listSpecials(0);" onmouseover="this.style.backgroundColor = \'#f8f8f8\';" onmouseout="this.style.backgroundColor = \'\';">'."\n";
         echo '                                    <tr>'."\n";
         echo '                                       <td id="special:6.color"><img src="img/1111.png" style="background-color:#ff8888;" /></td>'."\n";
         echo '                                       <td id="special:6.name" style="min-width:90px;"> <strong>Trap</strong> :</td>'."\n";
         echo '                                       <td id="special:6.keys" style="min-width:75px;"> <sub>Press</sub> <strong>7</strong> <a id="special:6.key1" style="display:none;"><sub>or</sub> <strong>E</strong></a> </td>'."\n";
         echo '                                       <td id="special:6.pending" style="display:none;">'."\n";
         echo '                                          <table>'."\n";
         echo '                                             <tr>'."\n";
         echo '                                                <td style="text-align:center;"><small>Choose the penelty <!--<br /> for the</small> <br /> <strong>Trap</strong>--></td>'."\n";
         echo '                                                <td style="text-align:center; vertical-align:middle;"><input type="text" id="special:6.input" style="width:20px; margin:10px;" onclick="stopBubble(event);" onkeydown="if(event.keyCode>=49 && event.keyCode<=58) stopBubble(event); else return false;" title="The penelty in seconds. Leave blank for default" /></td>'."\n";
         echo '                                                <td><button style="width:23px;"><strong>E</strong></button></td>'."\n";
         echo '                                             </tr>'."\n";
         echo '                                          </table>'."\n";
         echo '                                       </td>'."\n";
         echo '                                    </tr>'."\n";
         echo '                              </table>'."\n";
         echo '                                 <em id="special:6.remove"     style="display:none;" onclick="removeSpecial(6);"><a><small>Remove <b>Traps</b> from current block</small></a><br /></em> '."\n";
         echo '                                 <em id="special:6.remove:all" style="display:none;" onclick="removeSpecial(1);"><a><small>Remove <b>All Specials</b> from current block</small></a></em> '."\n";
         echo '                           </td>'."\n";
         echo '                        </tr>'."\n";
         }

         special_7:{
         echo "\n";
         echo '                        <tr onmouseover="toggleRemoves(7,1);" onmouseout="toggleRemoves(7,0);">'."\n";
         echo '                           <td align="center">'."\n";
         echo '                              <table id="special:7" class="special" style="display:none;"'."\n";
         echo '                                 onclick="alter(7); listSpecials(0);" onmouseover="this.style.backgroundColor = \'#f8f8f8\';" onmouseout="this.style.backgroundColor = \'\';">'."\n";
         echo '                                    <tr>'."\n";
         echo '                                       <td id="special:7.color"><img src="img/1111.png" style="background-color:#a4ffda;" /></td>'."\n";
         echo '                                       <td id="special:7.name" style="min-width:90px;"> <strong>Speed Boost</strong> :</td>'."\n";
         echo '                                       <td id="special:7.keys" style="min-width:75px;"> <sub>Press</sub> <strong>8</strong> <a id="special:7.key1" style="display:none;"><sub>or</sub> <strong>E</strong></a> </td>'."\n";
         echo '                                       <td id="special:7.pending" style="display:none;">'."\n";
         echo '                                          <table>'."\n";
         echo '                                             <tr>'."\n";
         echo '                                                <td style="text-align:center;"><small>Choose the bonus <!--<br /> of the</small> <br /> <strong>Speed Boost</strong>-->:</td>'."\n";
         echo '                                                <td style="text-align:center; vertical-align:middle;"><input type="text" size="1" id="special:7.input" style="width:20px; margin:10px;" onclick="stopBubble(event);" onkeydown="if(event.keyCode>=49 && event.keyCode<=58) stopBubble(event); else return false;" title="The bonus in seconds. Leave blank for default" /></td>'."\n";
         echo '                                                <td><button style="width:23px;"><strong>E</strong></button></td>'."\n";
         echo '                                             </tr>'."\n";
         echo '                                          </table>'."\n";
         echo '                                       </td>'."\n";
         echo '                                    </tr>'."\n";
         echo '                              </table>'."\n";
         echo '                                 <em id="special:7.remove"     style="display:none;" onclick="removeSpecial(7);"><a><small>Remove <b>Speed Boosts</b> from current block</small></a><br /></em> '."\n";
         echo '                                 <em id="special:7.remove:all" style="display:none;" onclick="removeSpecial(1);"><a><small>Remove <b>All Specials</b> from current block</small></a></em> '."\n";
         echo '                           </td>'."\n";
         echo '                        </tr>'."\n";
         }

         special_8:{
         echo "\n";
         echo '                        <tr onmouseover="toggleRemoves(8,1);" onmouseout="toggleRemoves(8,0);">'."\n";
         echo '                           <td align="center">'."\n";
         echo '                              <table id="special:8" class="special" style="display:none;"'."\n";
         echo '                                 onclick="alter(8); listSpecials(0);" onmouseover="this.style.backgroundColor = \'#f8f8f8\';" onmouseout="this.style.backgroundColor = \'\';">'."\n";
         echo '                                    <tr>'."\n";
         echo '                                       <td id="special:8.color"><img src="img/1111.png" style="background-color:#8264b1;" /></td>'."\n";
         echo '                                       <td id="special:8.name" style="min-width:90px;"> <strong>Orb of Sight</strong> :</td>'."\n";
         echo '                                       <td id="special:8.keys" style="min-width:75px;"> <sub>Press</sub> <strong>9</strong> <a id="special:8.key1" style="display:none;"><sub>or</sub> <strong>E</strong></a> </td>'."\n";
         echo '                                    </tr>'."\n";
         echo '                              </table>'."\n";
         echo '                                 <em id="special:8.remove"     style="display:none;" onclick="removeSpecial(8);"><a><small>Remove <b>Orbs of Sight</b> from current block</small></a><br /></em> '."\n";
         echo '                                 <em id="special:8.remove:all" style="display:none;" onclick="removeSpecial(1);"><a><small>Remove <b>All Specials</b> from current block</small></a></em> '."\n";
         echo '                           </td>'."\n";
         echo '                        </tr>'."\n";
         }

         echo "\n";
         echo '                     </table>'."\n";
         echo '                  </div>'."\n";
         echo '               </td>'."\n";
      }
   echo '            </tr>'."\n";
   echo '         </table>'."\n";
   echo '   </div>'."\n";
   }

echo "\n";
echo '<table width="100%" height="100%" style="margin-top:35px;">'."\n";

   cont_grid:{
   echo '   <tr>'."\n";
   echo '      <td width="80%" align="center" valign="middle">'."\n";
   echo "\n";
   echo '         <table id="game:grid" style="border-collapse: collapse;">'."\n";
   echo '            <tr id=":0">'."\n";
   echo '               <td id="0:0" class="1111" onclick="mark(this);"><img src="img/1111.png"> </td>'."\n";
   echo '            </tr>'."\n";
   echo '         </table>'."\n";
   echo '         <script type="text/javascript"> mark("0:0"); </script>'."\n";
   echo "\n";
   echo '      </td>'."\n";
   echo '   </tr>'."\n";
   }
   cont_source:{
   echo '   <tr>'."\n";
   echo '      <td id="source:container" style="display:none;" width="80%" align="center" valign="middle">'."\n";
   echo "\n";
   echo '            <div style="width:400px;">'."\n";
   echo '               <button id="source:hide"   style="float:left;"  onclick="document.getElementById(\'source:container\').style.display=\'none\';">Hide Source</button>'."\n";
   echo '               <button id="source:submit" style="float:right;" onclick="submit_()">Submit</button>'."\n";
   echo '            </div>'."\n";
   echo "\n";
   echo '            <textarea id="source:code" name="source" style="text-align: center; width:400px; height:60px;"></textarea>'."\n";
   echo "\n";
   echo '      </td>'."\n";
   echo '   </tr>'."\n";
   }
echo '</table>'."\n";
}
?>
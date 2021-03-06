<?php
session_start();
ob_start();
require "functions.php";

function generate_attendance($title) {
    if(!check_login() ) header('location:./login.php');
    else {
		if(!check_day() ) {
			
		include('config/globals.php');

		$p = $_SERVER['QUERY_STRING'];
		$reg = "/^".$globalbranch."[1-".$classno."]{1}$/";
		
		if(preg_match($reg,$p)) {
			
			include 'config/db.php';
			include 'config/settings.php';
			include 'config/globals.php';
			
			echo "<!DOCTYPE html>\n<html>\n";
			display_headers($title);
			echo "\n<body>";
			menu();
			echo <<< a
			\n\t<div class="container" style="margin-top:-10px;"><br>
			<div id="error" style="display:none;margin-top:10px;"></div>
			
a;
			$table='';
			//$spread='';
			
			$dbname = $branchyear.'_Users';
			$table = $branchyear.'_Students';
			//if(!mysql_select_db($dbname)) die(mysql_error());
			
			$userid = $_SESSION['UserId'];
			$q = "select Branch,Class,Position from $table where Id = '$userid'";
			$res = mysql_query($q) or die(mysql_error());
			$row = mysql_fetch_array($res);
			if($row['Position'] == "BA") {
				$branch = $globalbranch;
				$class = substr($p,-1);	}
			else {
				$branch = $row['Branch'];
				$class = $row['Class'];
				$class1 = substr($p,-1);
				if($class1 != $class) {
				//echo 'i am in';
				echo "<script type='text/javascript'>show_error('Error: Not authorised to access $branch$class1 details.');</script>";}
			}
			
			
				
			$dbname = $branchyear.'_Attendance';
			$table = $branch.$class.'_Attendance';
			//if(!mysql_select_db($dbname)) die(mysql_error());
			$class_total = mysql_num_rows(mysql_query("select `Id` from $table"));
			$sample = mt_rand(1,$class_total);
			
			$da = date('d-m-Y');
			$date = date('d-m-Y');
			
				
			echo <<< a
			
			\n\t\t<div class="row">
				<div class='span12'>
					<div class="well well-large" style="background:#FFF;">
a;
			
		
			$dbname = $branchyear.'_Dates';
			$table = $branch.$class.'_Dates';
			//if(!mysql_select_db($dbname))  die(mysql_error());
			
			$remaining = array() ; $j = 0;
			$remaining1 = array() ; $j1 = 0;
			$confired = array() ; $j2 = 0;
			$confired1 = array() ; $j3 = 0;
			
			for($i = 1;$i<=4;$i++) {
				$tmp = "P".$i; $tmp1 = "P".$i."_Con";
				
				$q = mysql_query("select ISNUll(`$tmp`) as `P$i`,ISNUll(`$tmp1`) as `P".$i."_C` from $table where Date = '$da' ") or die(mysql_error());
				$res = mysql_fetch_array($q);
				
				if($res["P".$i]) { $remaining[$j] = "P".$i; $j++;}
				else { $remaining1[$j1] = "P".$i; $j1++;}
				if($res["P".$i."_C"]) { $confired[$j2] = "P".$i; $j2++;}
				else { $confired1[$j3] = "P".$i; $j3++;}
				
			}
			
			
			$dates1=getdate(strtotime($da));
			$day=substr($dates1["weekday"],0,3);
			
			$dbname = $branchyear.'_TimeTable';
			$table = $branch.$class.'_TimeTable';
			//if(!mysql_select_db($dbname)) die(mysql_error());
			
			$ti=mysql_query("SELECT DayPeriod,$day from $table;") or die(mysql_error());
			
			$subjects=array();	
			while($da1=mysql_fetch_array($ti)){$subjects[$da1[0]]=$da1[1];}	
			//print_r($subjects);
			//$sub = $subjects[$p];
			
				
			//echo "<h5 style='text-align:center;'> Attendance Submission Details - $branch&nbsp;$class - $date</h5><br>";
			echo <<< a
			
				<div id="step1" class="span4">     
					<h5 class='text-info'>Today's Attendance Submission Details </h5>
					<h6> &emsp;&emsp;&emsp; - &emsp; Listing Data submitted from CR @ $branch&nbsp;$class </h6><br>
				</div>
				<div id="side1" class="span7" >
					<h6 class='text-right'><a href='./?sub'><i class='icon-home'></i> Home&nbsp;</a> </h6>
a;
			if($row['Position'] == "BA") {
				echo <<< a
					<h6 class='text-right'>
a;
				for($cl = 1;$cl<=$classno;$cl++)	
					{echo "<a href='?$globalbranch$cl'>$globalbranch$cl</a>&emsp;";}
					echo <<< a
					</h6>
a;
				}
			echo <<< a
				</div>
			
a;
			$dbname = $branchyear.'_Dates';
			$table = $branch.$class.'_Dates';
			//if(!mysql_select_db($dbname)) die(mysql_error());
			
			$q = mysql_query("select Date from $table where Date = '$da'") or die(mysql_error()) ;
			
			
			
			if(count($remaining1) != 0  && mysql_num_rows($q) != 0) {
			$html = "";
			echo 
			 
			 '<table class="table  table-hover table-bordered "  style="padding:0px;">
						<thead>
							<tr> 
								<th style="text-align:center;"  rowspan="2" valign="top"> RNo  </th> 
								<th style="text-align:center;"  rowspan="2" valign="top"> Id  </th> 
								<th  style="text-align:center;"  > P1 </th> <th  style="text-align:center;" > P2 </th>
								<th  style="text-align:center;"  > P3 </th>  <th  style="text-align:center;"  > P4 </th> 
								<th  style="text-align:center;" rowspan=2> Presents </th> <th rowspan=2 style="text-align:center;"  > Absents </th>
							</tr>
							<tr> 
								<th  style="text-align:center;"  > '.$subjects['P1'].' </th> <th  style="text-align:center;" > '.$subjects['P2'].' </th>
								<th  style="text-align:center;"  > '.$subjects['P3'].' </th>  <th  style="text-align:center;"  > '.$subjects['P4'].' </th> 
								
							</tr>
							
						</thead>
						<tbody>';
			//$spread.=" \t \t P1  \t P2 \t  P3 \t P4 \t \t \t\nRno\tID\t".$subjects['P1']."\t".$subjects['P2']."\t".$subjects['P3']."\t".$subjects['P4']."\tPresents\tAbsents\n";
			$html .= "<thead><tr> 
								<th rowspan=\"2\" width=8%> RNo  </th> 
								<th rowspan=\"2\" width=17%> ID  </th> 
								<th  > P1 </th> <th > P2 </th>
								<th  > P3 </th>  <th  > P4 </th> 
								<th rowspan=2 width=14%> Presents </th> <th rowspan=2  width=14%> Absents </th>
							</tr>
							<tr> 
								<th  > {$subjects['P1']}</th> <th >{$subjects['P2']}</th>
								<th  > {$subjects['P3']} </th>  <th  > {$subjects['P4']} </th> 
							</tr></thead>";
			$Rnos=array();$uid = array();
			$dbname = $branchyear.'_Attendance';
			$table = $branch.$class.'_Attendance';
			//if(!mysql_select_db($dbname)) die(mysql_error());
			$q = mysql_query("select Id, RNo from $table;") or die(mysql_error());
			while($res = mysql_fetch_array($q)) $uid[$res['RNo']]=$res['Id'];
			for($s=0;$s<count($remaining1);$s++) {
				$p1=$remaining1[$s];
				if(in_array($p1,$confired1)){	
					$period = $p1;
					$dbname = $branchyear.'_Attendance';
					$table = $branch.$class.'_Attendance';
					//if(!mysql_select_db($dbname)) die(mysql_error());
					
					$q = mysql_query("select RNo,Id,`$date` from $table;")  or die(mysql_error());
					
					$aindex=0;$pindex=0;
					
					while($res = mysql_fetch_array($q)){
						//$uid[$res['RNo']]=$res['Id'];
						$z= explode(",",$res[$date]);
						for($m=0;$m<count($z);$m++) {
							$y = explode("_",$z[$m]);
							if($y[0]==$period) {
								if($y[1] == "A") {$Rnos[$p1]['Absents'][$aindex] = $res[0];$aindex++;}
								else {$Rnos[$p1]['Presents'][$pindex] = $res[0];$pindex++;}
								}
							}
						}
					}
					
				else {
					$da = date('d-m-Y');
					$a = $da.'_'.$p1;
					$dbname = $branchyear.'_Cache';
					$table = $branch.$class.'_Cache';
					//if(!mysql_select_db($dbname)) die(mysql_error());
					
					$q3=mysql_query("SELECT RNo, `".$a."` FROM $table") or die(mysql_error());
					$aindex=0;$pindex=0;
					
					while($individual=mysql_fetch_array($q3)){
						if($individual[$a]=="A"){$Rnos[$p1]["Absents"][$aindex]=$individual["RNo"];$aindex++;}
						if($individual[$a]=="P"){$Rnos[$p1]["Presents"][$pindex]=$individual["RNo"];$pindex++;}
						}
				}
			
			}
			$tot = array('A' => 0, 'P' => 0);
			//print_r($uid);
			for($w=1;$w<=$class_total;$w++){
				$tr2 =  
					 '<tr >  
						<td style="text-align:center;">'.$w.'  </td>
						<td style="text-align:center;"> '.$uid[$w].'  </td>';
				echo $tr2;
				//$spread.="$w \t {$uid[$w]} \t";
				$html .= $tr2;

				$pc=0;$ac=0;
				for($l=1;$l<=4;$l++){
					$pl= "P".$l;
					if(in_array($pl,$remaining1)) {
						
						if(array_key_exists("Absents",$Rnos[$pl]) && in_array($w,$Rnos[$pl]['Absents'])) {echo '<td style="text-align:center;" class="text-error"><b> <i class="icon-remove"></i> </b></td>';$ac++;$html.="<td><font color=\"darkred\">&#x2716;</font></td>";}
						else {echo '<td style="text-align:center;" class="text-success"><b> <i class="icon-ok"></i> </b></td>';$pc++;$html.="<td><font color=\"green\">&#x2714;</font></td>";}	
						}
					else {echo '<td></td>'; $html .= '<td></td>';//$spread.=" \t";
}
						
					}
				$tot['A']+=$ac;$tot['P']+=$pc;
				echo <<< a
						<td style="text-align:center;" class="text-success"><b> $pc </b> </td> 
						<td style="text-align:center;" class="text-error"><b> $ac </b></td>  
					</tr>
					
a;
			$html .= "<td><font color=\"green\"><b>$pc</b></font></td><td><font color=\"darkred\"><b>$ac</b></font></td></tr>";
			//$spread.="$pc \t $ac \n";
			}
			
			echo "<tr><td colspan=6 style='text-align:center;'><b>Total</b></td><td style='text-align:center;' class='text-success'><b>".$tot['P']."</b></td><td style='text-align:center;' class='text-error'><b>".$tot['A']."</b></td></tr>";
			$html .= "<tr><th colspan=6 style=\"text-align:center;\"> <b>Total</b> </th><td><b><font color=\"green\">".$tot['P']."</font></b></td><td><b><font color=\"darkred\">".$tot['A']."</font></b></td></tr>";
			//$spread.="\t \tTotal\t \t \t \t{$tot['P']} \t {$tot['A']}";
			
			echo "</tbody></table>";
				
			
			if($row['Position']=="BA"){
			echo <<< a
			<form action='print.php' method='post' name='abc'>
			<input type='hidden' name='Title1' value="$branch $class - Daily Report">
			<input type='hidden' name='Table1' value='$html'>
		<center><button type="submit" class="btn btn-primary"><i class='icon-download-alt'></i>&nbsp;&nbsp;Save as PDF</button></center>
			</form>
a;
			echo <<< b
			<form action='excel.php' method='post' name='abc'>
			<input type='hidden' name='Title1' value="$branch $class - Daily Report">
			<input type='hidden' name="sheet" value='$html'>
		<center><button type="submit" class="btn btn-primary"><i class='icon-download-alt'></i>&nbsp;&nbsp;Save as Excel Sheet</button></center>
			</form>
b;
}
			echo <<< a
					</div>
				</div>
				
a;
		}
			else echo "<br><h6><span class='text-error'><br><br><br>No Submissions found till now ...</span></h6><br></div>";
			echo "</div> </div> ";
		
			display_footer();
			echo "\n</body>\n</html>";
			@mysql_close($con);	
		}else echo "<script type='text/javascript'>document.location.href='404.php';</script>";
	}
		else noservice();
	} 
	
}

	

generate_attendance('Attendance - Today');
?>


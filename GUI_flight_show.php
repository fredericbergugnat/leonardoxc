<? 
/************************************************************************/
/* Leonardo: Gliding XC Server					                        */
/* ============================================                         */
/*                                                                      */
/* Copyright (c) 2004-5 by Andreadakis Manolis                          */
/* http://leonardo.thenet.gr                                            */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/
    require_once dirname(__FILE__)."/FN_brands.php";
	require_once $moduleRelPath."/CL_template.php";
	$Ltemplate = new LTemplate($moduleRelPath.'/templates/'.$PREFS->themeName);

	$Ltemplate ->set_filenames(array(
		'body' => 'flight_show.html'));


  $flightID+=0;
  $flight=new flight();
  if ( ! $flight->getFlightFromDB($flightID) ) {
  	echo "<br><div align='center'>No such flight exists</div><br><BR>";
	return;  
  }
  
  if ( $flight->private && $flight->userID!=$userID && ! is_leo_admin($userID) ) {
		echo "<TD align=center>"._FLIGHT_IS_PRIVATE."</td>";
		return;
  }

  $flight->incViews();
  $location=formatLocation(getWaypointName($flight->takeoffID),$flight->takeoffVinicity,$takeoffRadious);
  $firstPoint=new gpsPoint($flight->FIRST_POINT,$flight->timezone);						

 
  ?>
  <script type="text/javascript" src="<?=$moduleRelPath ?>/js/tipster.js"></script>
<? echo makePilotPopup(); ?>
<? echo maketakeoffPopup(1,$userID); ?>
<script language="javascript">
function submitForm(extendedInfo) {
	var flightID= document.geOptionsForm.flightID.value;
	var lineWidth= document.geOptionsForm.lineWidth.value;
	var lineColor= document.geOptionsForm.lineColor.value;
	var ex= document.geOptionsForm.ex.value;  
	window.location = "<?=$moduleRelPath?>/download.php?type=kml_trk&flightID="+flightID+"&w="+lineWidth+"&c="+lineColor+"&ex="+ex+"&an="+extendedInfo;
	return false;
}

function setSelectColor(theDiv) {
	oColour="#"+theDiv.options[theDiv.selectedIndex].value;
	//oColour="#00ff00";
	if( theDiv.style ) { theDiv = theDiv.style; } if( typeof( theDiv.bgColor ) != 'undefined' ) {
		theDiv.bgColor = oColour; } else if( theDiv.backgroundColor ) { theDiv.backgroundColor = oColour; }
	else { theDiv.background = oColour; }
}

var unknownTakeoffTip = new TipObj('unknownTakeoffTip');
with (unknownTakeoffTip)
{
	template = '<table bgcolor="#000000" cellpadding="0" cellspacing="0" width="%3%" border="0">' +
	'<tr><td class="infoBoxHeader" style="width:%3%px">%4%</td></tr>'+
	'<tr><td class="infoBox" style="width:%3%px">%5%</td></tr></table>';
	showDelay = 0;
	hideDelay = 2;
	doFades = false;
	tips.floating = new Array(150, 5, "attentionLinkPos", 350, '<?=_unknown_takeoff_tooltip_1?>','<?=_unknown_takeoff_tooltip_2?>');
	tipStick = 0;
}

</script>
<div id="unknownTakeoffTipLayer" class="shadowBox" style="position: absolute; z-index: 10000; visibility: hidden; left: 0px; top: 0px; width: 10px">&nbsp;</div>


<? if ( is_leo_admin($userID) ) { ?>
<script language="javascript">
function add_takeoff(lat,lon,id) {	 
	takeoffTip.hide();
	document.getElementById('takeoffBoxTitle').innerHTML = "<?=_ADD_WAYPOINT?>";	
	document.getElementById('addTakeoffFrame').src='modules/<?=$module_name?>/GUI_EXT_waypoint_add.php?lat='+lat+'&lon='+lon+'&takeoffID='+id;
	 MWJ_changeSize('addTakeoffFrame',410,320);
	 MWJ_changeSize( 'takeoffAddID', 410,350 );
	toggleVisible('takeoffAddID','takeoffAddPos',14,0,410,320);
}
	 
function edit_takeoff(id) {	 
	 takeoffTip.hide();
	 document.getElementById('takeoffBoxTitle').innerHTML = "Change Takeoff";		 
 	 document.getElementById('addTakeoffFrame').src='modules/<?=$module_name?>/GUI_EXT_waypoint_edit.php?waypointIDedit='+id;
	 MWJ_changeSize('addTakeoffFrame',410,320);
	 MWJ_changeSize( 'takeoffAddID', 410,350 );
	 toggleVisible('takeoffAddID','takeoffAddPos',14,0,410,320);
 }

function delete_takeoff(id) {	 
	 takeoffTip.hide();
	 document.getElementById('takeoffBoxTitle').innerHTML = "Delete Takeoff";		 
 	 document.getElementById('addTakeoffFrame').src='modules/<?=$module_name?>/GUI_EXT_waypoint_delete.php?waypointIDdelete='+id;
	 MWJ_changeSize('addTakeoffFrame',410,220);
	 MWJ_changeSize( 'takeoffAddID', 410,250 );
	 toggleVisible('takeoffAddID','takeoffAddPos',14,0,410,220);
 }

</script>
<? }  ?>
<script language="javascript">
	function set_flight_bounds(id) {
		MWJ_changeContents('takeoffBoxTitle',"Set Start - Stop time for flight");
		document.getElementById('addTakeoffFrame').src='modules/<?=$module_name?>/GUI_EXT_flight_set_bounds.php?flightID='+id;		
		MWJ_changeSize('addTakeoffFrame',740,395);
		MWJ_changeSize( 'takeoffAddID', 745,415 );
		toggleVisible('takeoffAddID','setBoundsPos',18,-690,725,435);	
	}
	
	 function user_add_takeoff(lat,lon,id) {	 
		MWJ_changeContents('takeoffBoxTitle',"<?=_ADD_WAYPOINT?>");
		document.getElementById('addTakeoffFrame').src='modules/<?=$module_name?>/GUI_EXT_user_waypoint_add.php?lat='+lat+'&lon='+lon+'&takeoffID='+id;		
		MWJ_changeSize('addTakeoffFrame',720,380);
		MWJ_changeSize( 'takeoffAddID', 725,400 );
		toggleVisible('takeoffAddID','takeoffAddPos',30,0,725,455);
	 }
</script>

<div id="takeoffAddID" class="dropBox takeoffOptionsDropDown" style="visibility:hidden;">
	<table width="100%" cellpadding="0" cellspacing="0">
	<tr><td class="infoBoxHeader" style="width:725px;" >
	<div align="left" style="display:inline; float:left; clear:left;" id="takeoffBoxTitle"><?=_ADD_WAYPOINT?></div>
	<div align="right" style="display:inline; float:right; clear:right;">
	<a href='#' onclick="toggleVisible('takeoffAddID','takeoffAddPos',14,-20,0,0);return false;"><img src='<? echo $moduleRelPath."/templates/".$PREFS->themeName ?>/img/exit.png' border=0></a></div>
	</td></tr></table>
	<div id='addTakeoffDiv'>
		<iframe name="addTakeoffFrame" id="addTakeoffFrame" width="700" height="320" frameborder=0 style='border-width:0px'></iframe>
	</div>
</div>




<div id="geOptionsID" class="dropBox googleEarthDropDown" style="visibility:hidden;">
<form name="geOptionsForm" method="POST">
<input type="hidden" name="flightID" value="<?=$flightID?>">

<table bgcolor="#EEEEEE" cellpadding="0" cellspacing="0" width="100%" border="0" >
<tr>
<td  colspan=2 class="tableBox" style="background-color:#6F9ED3">
<div align="left" style="display:inline; float:left; clear:left;">&nbsp;<b>Google Earth</b></div>
	<div align="right" style="display:inline; float:right; clear:right;">
		<a href='#' onclick="toggleVisible('geOptionsID','geOptionsPos',14,-20,0,0);return false;">
		<img src='<? echo $moduleRelPath."/templates/".$PREFS->themeName ?>/img/exit.png' border=0></a></div>
</td>
</tr>
<tr >
	<td class="infoBox" align>
	<?=_Line_Color?>
	</td>
	<td class="tableBox">
	 <select name="lineColor" style="background-color:#ff0000" onChange="setSelectColor(this)">
	<option value='FF0000' style='background-color: #FF0000'>&nbsp;&nbsp;&nbsp;</option>
	<option value='00FF00' style='background-color: #00FF00'>&nbsp;&nbsp;&nbsp;</option>
	<option value='0000FF' style='background-color: #0000FF'>&nbsp;&nbsp;&nbsp;</option>	
	<option value='FFD700' style='background-color: #FFD700'>&nbsp;&nbsp;&nbsp;</option>
	<option value='FF1493' style='background-color: #FF1493'>&nbsp;&nbsp;&nbsp;</option>
	<option value='FFFFFF' style='background-color: #FFFFFF'>&nbsp;&nbsp;&nbsp;</option>
	<option value='FF4500' style='background-color: #FF4500'>&nbsp;&nbsp;&nbsp;</option>
	<option value='8B0000' style='background-color: #8B0000'>&nbsp;&nbsp;&nbsp;</option>
	

	</select> 
	</td>
	
</tr>
<tr>
	<td class="infoBox" align="right">
		<?=_Line_width?>
	</td>
	<td class="tableBox" align="left">
		<select  name="lineWidth">	
		<option value='1' >1</option>
		<option value='2' selected >2</option>
		<option value='3' >3</option>
		<option value='4' >4</option>
		<option value='5' >5</option>
		</select> 
		<input name="ex" type="hidden" value="1" />
	</td>
</tr>

<tr>
	<td colspan=2 class="infoBox">
	<?
	echo "<a href='javascript:submitForm(0)'>"._Display_on_Google_Earth."</a><br>"; 
	?>
	</td>
</tr>
<tr>
	<td colspan=2 class="infoBox">
<?
	echo "<a href='javascript:submitForm(1)'>"._Use_Man_s_Module."</a><br>"; 
	//	echo "<a href='".$moduleRelPath."/download.php?type=kml_trk&flightID=".$flight->flightID."'>Display on Google Earth</a>"; 
	?>

	</td>
</tr>
</TABLE>

</form>
</div>
<?
	$legendRight="";

	if (is_leo_admin($userID) ) {
		$legendRight.="<div id='setBoundsPos'></div><a href='javascript:set_flight_bounds($flightID)'><img src='".$moduleRelPath."/img/icon_clock.png' title='Set Start-Stop Time for flight' border=0 align=bottom></a> ";
	}

	if ( $flight->userID==$userID || is_leo_admin($userID) ) {
		$legendRight.="<a href='?name=$module_name&op=delete_flight&flightID=".$flightID."'><img src='".$moduleRelPath."/img/x_icon.gif' border=0 align=bottom></a>
				   <a href='?name=$module_name&op=edit_flight&flightID=".$flightID."'><img src='".$moduleRelPath."/img/change_icon.png' border=0 align=bottom></a>"; 
	}

	$legend="<img src='$moduleRelPath/img/icon_cat_".$flight->cat.".png' align='absmiddle'> ".
			_PILOT.": <a href=\"javascript:pilotTip.newTip('inline', 60, 19, 'pilot_pos', 200, '".
			$flight->userID."','".addslashes(prepare_for_js($flight->userName))."' )\"  onmouseout=\"pilotTip.hide()\">".
			$flight->userName."</a>&nbsp;&nbsp; "._DATE_SORT.": ".formatDate($flight->DATE);
	
	$Ltemplate->assign_vars(array(
		'legend'=>$legend,
		'legendRight'=>$legendRight,
	));



  if (!$flight->active &&  (mktime() - datetime2UnixTimestamp($flight->dateAdded) > 5 ) )  {  //  5 secs
		$flight->activateFlight();
  } else if (!$flight->active) {
		open_tr();
		echo "<TD align=center>"._FLIGHT_WILL_BE_ACTIVATED_SOON."<a href=''>"._TRY_AGAIN."</a></td>";
  		close_tr(); 
		close_inner_table();  
		return;
  }

  if ($_REQUEST['updateMap']) $flight->getMapFromServer();		
  if ($_REQUEST['updateMap3d']) $flight->getMapFromServer(1);		
  if ($_REQUEST['updateCharts']) $flight->updateCharts(1);		
  if ($_REQUEST['updateData'])  {
	$flight->getFlightFromIGC( $flight->getIGCFilename() );
	$flight->updateTakeoffLanding();
	$flight->putFlightToDB(1); // 1== UPDATE
  }

  if ($_REQUEST['updateScore'] || $flight->FLIGHT_POINTS==0) { 
		$flight->getOLCscore();
		$flight->putFlightToDB(1); // 1== UPDATE
  }

  $flight->updateAll(0);
  
  DEBUG_END(); // flush debug here
	
	$flightHours=$flight->DURATION/3600;
	if ($flightHours) {
		$openDistanceSpeed=formatSpeed($flight->LINEAR_DISTANCE/($flightHours*1000));
		$maxDistanceSpeed=formatSpeed($flight->MAX_LINEAR_DISTANCE/($flightHours*1000));
		$olcDistanceSpeed=formatSpeed($flight->FLIGHT_KM/($flightHours*1000));
	} else {
		$openDistanceSpeed=0;
		$maxDistanceSpeed=0;
		$olcDistanceSpeed=0;	
	}
	

	$takeoffLink="<div id='takeoffAddPos'><a href=\"javascript:takeoffTip.newTip('inline',0,13, 'takeoffAddPos', 250, '".$flight->takeoffID."','".str_replace("'","\'",$location)."',".$firstPoint->lat.",".$firstPoint->lon.")\"  onmouseout=\"takeoffTip.hide()\">$location</a>";
		
	if ($flight->takeoffVinicity>$takeoffRadious*2 ) {
		$takeoffLink.="<div id='attentionLinkPos' class='attentionLink'><a
			 href=\"javascript:user_add_takeoff(".$firstPoint->lat.",".$firstPoint->lon.",".$flight->takeoffID.")\" 
			 onmouseover='unknownTakeoffTip.show(\"floating\")'  onmouseout='unknownTakeoffTip.hide()'><img
			 src='$moduleRelPath/img/icon_att3.gif' border=0 align=absmiddle>"._Unknown_takeoff."<img 
			 src='$moduleRelPath/img/icon_att3.gif' border=0 align=absmiddle></a></div>";
	}
	$takeoffLink.="</div>";


$Ltemplate->assign_vars(array(
	'TAKEOFF_LOCATION'=>$takeoffLink,
	'TAKEOFF_TIME'=>sec2Time($flight->START_TIME),
	'LANDING_LOCATION'=>formatLocation(getWaypointName($flight->landingID),$flight->landingVinicity,$landingRadious),
	'LANDING_TIME'=>sec2Time($flight->END_TIME),
	'LINEAR_DISTANCE'=>formatDistanceOpen($flight->LINEAR_DISTANCE)." ($openDistanceSpeed)",
	'DURATION'=>sec2Time($flight->DURATION),
));

if ( $scoringServerActive ) {
		$olcScoreType=$flight->BEST_FLIGHT_TYPE;
		if ($olcScoreType=="FREE_FLIGHT") {
			$olcScoreTypeImg="icon_turnpoints.gif";
		} else if ($olcScoreType=="FREE_TRIANGLE") {
			$olcScoreTypeImg="icon_triangle_free.gif";
		} else if ($olcScoreType=="FAI_TRIANGLE") {
			$olcScoreTypeImg="icon_triangle_fai.gif";
		} else { 
			$olcScoreTypeImg="photo_icon_blank.gif";
		}
	$Ltemplate->assign_vars(array(
		'MAX_DISTANCE'=>formatDistanceOpen($flight->MAX_LINEAR_DISTANCE)." ($maxDistanceSpeed)",		
		'OLC_TYPE'=>formatOLCScoreType($flight->BEST_FLIGHT_TYPE)." <img src='$moduleRelPath/img/$olcScoreTypeImg' align='absmiddle'>",
		'OLC_KM'=>formatDistanceOpen($flight->FLIGHT_KM)." ($olcDistanceSpeed)",
		'OLC_SCORE'=>formatOLCScore($flight->FLIGHT_POINTS),
	));
} else {
	$Ltemplate->assign_vars(array(
		'MAX_DISTANCE'=>0,		
		'OLC_TYPE'=>0,
		'OLC_KM'=>0,
		'OLC_SCORE'=>0,
	));
}

$Ltemplate->assign_vars(array(
	'MAX_SPEED'=>formatSpeed($flight->MAX_SPEED),
    'MAX_VARIO'=>formatVario($flight->MAX_VARIO),  
   	'MEAN_SPEED'=>formatSpeed($flight->MEAN_SPEED),
   	'MIN_VARIO'=>formatVario($flight->MIN_VARIO),
));	

if ($flight->is3D()) {
	$Ltemplate->assign_vars(array(
		'MAX_ALT'=>formatAltitude($flight->MAX_ALT),
		'TAKEOFF_ALT'=>formatAltitude($flight->TAKEOFF_ALT),
		'MIN_ALT'=>formatAltitude($flight->MIN_ALT),
		'ALTITUDE_GAIN'=>formatAltitude($flight->MAX_ALT-$flight->TAKEOFF_ALT),
	));
} else {
	$Ltemplate->assign_vars(array(
		'MAX_ALT'=>0,
		'TAKEOFF_ALT'=>0,
		'MIN_ALT'=>0,
		'ALTITUDE_GAIN'=>0,
	));
}
 

/* 	$flight->filename
		echo "<div id='geOptionsPos' style='float:right'>";
		echo "<a href='javascript:nop()' onclick=\"toggleVisible('geOptionsID','geOptionsPos',14,-80,170,'auto');return false;\">Google Earth&nbsp;<img src='".$moduleRelPath."/img/icon_arrow_down.gif' border=0></a></div>";
*/

  if ( $flight->olcFilename  || ( $flight->insideOLCsubmitWindow() && $flight->FLIGHT_POINTS ) ) $showOLCsubmit=1;
  else  $showOLCsubmit=0;
  if ( $enableOLCsubmission && $showOLCsubmit && 0) {
	  open_tr();
		//   echo "<TD>&nbsp</td>";
		   echo "<TD bgcolor=".$Theme->color2."><div align=".$Theme->table_cells_align.">OLC</div></TD>";
		   echo "<TD  colspan=4><div align=left>";
			if ($flight->olcFilename) {
			  $olc_url="http://www2.onlinecontest.org/holc/".$flight->getOLCYear();
			  $olcName=strtolower (substr($flight->olcFilename,0,-4) );
			  echo "[ <a href='$olc_url/map/".$olcName.".jpg' target='_blank'>"._OLC_MAP."</a> ] ";
			  echo "[ <a href='$olc_url/ENL/".$olcName.".png' target='_blank'>"._OLC_BARO."</a> ] ";
			  echo "[".substr($flight->olcDateSubmited,0,10)."] ";
			  if ( in_array($userID,$admin_users)  || $flight->userID==$userID  ) echo "(Ref: ".$flight->olcRefNum.") ";
			  echo "<img src='".$moduleRelPath."/img/olc_icon_submited.gif' border=0 align=bottom>";
			  // echo _SUBMITED_SUCCESSFULLY_ON." ".$flight->olcDateSubmited;
			  if ($flight->insideOLCsubmitWindow()  && ( in_array($userID,$admin_users)  || $flight->userID==$userID  )  ) {
				echo "<a href='?name=".$module_name."&op=olc_remove&flightID=".$flight->flightID."'>";	
				echo "<img src='".$moduleRelPath."/img/x_icon.gif' border=0 align=bottom></a>";
			  }
			}
			else if ($flight->insideOLCsubmitWindow() && $flight->FLIGHT_POINTS ) {
				echo _READY_FOR_SUBMISSION;
				if ( in_array($userID,$admin_users)  || $flight->userID==$userID  ) 
				echo " <a href='?name=".$module_name."&op=olc_submit&flightID=".$flight->flightID."'>"._SUBMIT_TO_OLC."</a>";
			}
			else  echo _CANNOT_BE_SUBMITTED;
		   echo "</div></TD>";
	//	   echo "<TD>&nbsp</td>";
	  close_tr();
  }

if ($flight->comments) {
	 $comments=$flight->comments;
}

$linkURL=_N_A;
if ($flight->linkURL) {
	$linkURL="<a href='".formatURL($flight->linkURL,0)."' title='".formatURL($flight->linkURL,0)."' target=_blank>".
		formatURL($flight->linkURL,15)."</a>";
}

if ($flight->glider) {
 	$brandID=guessBrandID($flight->cat,$flight->glider);
	if ($brandID) $gliderBrandImg="<img src='$moduleRelPath/img/brands/$flight->cat/".sprintf("%03d",$brandID).".gif' border=0 align='absmiddle'> ";
	else $gliderBrandImg="";

	$glider=$gliderBrandImg.$flight->glider;
}

$gliderCat=" [ <img src='".$moduleRelPath."/img/icon_cat_".$flight->cat.".png' align='absmiddle'> ".	
$gliderCatList[$flight->cat]." ]";
 
$images="";
for ( $photoNum=1;$photoNum<=$CONF_photosPerFlight;$photoNum++){
	$photoFilename="photo".$photoNum."Filename";
	if ($flight->$photoFilename) {
		$images.="<a class='shadowBox imgBox' href='".$flight->getPhotoRelPath($photoNum).
				"' target=_blank><img src='".$flight->getPhotoRelPath($photoNum).".icon.jpg' border=0></a>";
	}		
}

//-------------------------------------------------------------------
// get from paraglidingearth.com
//-------------------------------------------------------------------
	$takoffsList=getExtrernalServerTakeoffs(1,$firstPoint->lat,-$firstPoint->lon,50,5);
	
	if (count($takoffsList) >0 ) {
		$linkToInfoHdr1="<a href='http://www.paraglidingearth.com/en-html/sites_around.php?lng=".-$firstPoint->lon."&lat=".$firstPoint->lat."&dist=20' target=_blank>";
	    $linkToInfoHdr1.="<img src='".$moduleRelPath."/img/paraglidingearth_logo.gif' border=0> "._FLYING_AREA_INFO."</a>";
		
		$linkToInfoStr1="<ul>";
		foreach ($takoffsList as $takeoffItem)  {
				$distance=$takeoffItem['distance']; 
				if ($takeoffItem['area']!='not specified')
					$areaStr=" - ".$takeoffItem['area'];
				else 
					$areaStr="";

				$takeoffLink="<a href='".$takeoffItem['url']."' target=_blank>".$takeoffItem['name']."$areaStr (".$takeoffItem['countryCode'].") [~".formatDistance($distance,1)."]</a>";

				$linkToInfoStr1.="<li>$takeoffLink";
		}
		$linkToInfoStr1.="</ul>";
  }
  $xmlSites1=str_replace("<","&lt;",$xmlSites);
//-------------------------------------------------------------------
// get from paragliding365.com
//-------------------------------------------------------------------
  	$takoffsList=getExtrernalServerTakeoffs(2,$firstPoint->lat,-$firstPoint->lon,50,5);
	if (count($takoffsList) >0 ) {
		$linkToInfoHdr2="<a href='http://www.paragliding365.com/paragliding_sites.html?longitude=".-$firstPoint->lon."&latitude=".$firstPoint->lat."&radius=50' target=_blank>";
		$linkToInfoHdr2.="<img src='".$moduleRelPath."/img/paraglider365logo.gif' border=0> "._FLYING_AREA_INFO."</a>";
			
		$linkToInfoStr2="<ul>";
		foreach ($takoffsList as $takeoffItem)  {
				if ($takeoffItem['area']!='not specified')	$areaStr=" - ".$takeoffItem['area'];
				else $areaStr="";
				$linkToInfoStr2.="<li><a href='".$takeoffItem['url']."' target=_blank>".
									$takeoffItem['name']."$areaStr (".$takeoffItem['countryCode'].
									") [~".formatDistance($takeoffItem['distance'],1)."]</a>";
		}
		$linkToInfoStr2.="</ul>";
  }
  $xmlSites2=str_replace("<","&lt;",$xmlSites);
  
$adminPanel="";
if (in_array($userID,$admin_users) ) {
	$adminPanel="<b>"._TIMES_VIEWED.":</b> ".$flight->timesViewed."  ";
	$adminPanel.="<b>"._SUBMISION_DATE.":</b> ".$flight->dateAdded." :: ";
	// DEBUG MANOLIS
	// processIGC($flight->getIGCFilename());
	// display the trunpoints
	//echo "<hr> ";
	//for($k=1;$k<=5;$k++) { $vn="turnpoint$k"; echo " ".$flight->$vn." <BR>"; }
	if ($CONF_show_DBG_XML) {
		$adminPanel.="<div style='display:inline'><a href='javascript:toggleVisibility(\"xmlOutput\")';>See XML</a></div>";
	}
		
	$adminPanel.="<div style='display:inline'> :: <a href='javascript:toggleVisibility(\"adminPanel\")';>Admin options</a></div>";
	
	if ($CONF_show_DBG_XML) {
		$adminPanel.="<div id=xmlOutput style='display:none; text-align:left;'><hr>";
		$adminPanel.="XML from paraglidingEarth.com<br>";
		$adminPanel.="<pre>$xmlSites1</pre><hr>XML from paragliding365.com<br><pre>$xmlSites2</pre></div>";
	}
	
	$adminPanel.="<div id='adminPanel' style='display:none; text-align:center;'><hr>";
	$adminPanel.="<a href='?name=".$module_name."&op=show_flight&flightID=".$flight->flightID."&updateData=1'>"._UPDATE_DATA."</a> | ";
	$adminPanel.="<a href='?name=".$module_name."&op=show_flight&flightID=".$flight->flightID."&updateMap=1'>"._UPDATE_MAP."</a> | ";
	$adminPanel.="<a href='?name=".$module_name."&op=show_flight&flightID=".$flight->flightID."&updateCharts=1'>"._UPDATE_GRAPHS."</a> | ";
	$adminPanel.="<a href='?name=".$module_name."&op=show_flight&flightID=".$flight->flightID."&updateScore=1'>"._UPDATE_SCORE."</a> ";

	$adminPanel.=get_include_contents(dirname(__FILE__)."/site/admin_takeoff_info.php");
}

if ( is_file($flight->getMapFilename() ) )
	$mapImg="<img src='".$flight->getMapRelPath()."' border=0>";	

if ($flight->is3D() &&  is_file($flight->getChartfilename("alt",$PREFS->metricSystem))) 
	$chart1= "<br><br><img src='".$flight->getChartRelPath("alt",$PREFS->metricSystem)."'>";
if ( is_file($flight->getChartfilename("takeoff_distance",$PREFS->metricSystem)) )
	$chart2="<br><br><img src='".$flight->getChartRelPath("takeoff_distance",$PREFS->metricSystem)."'>";
if ( is_file($flight->getChartfilename("speed",$PREFS->metricSystem)) )
	$chart3="<br><br><img src='".$flight->getChartRelPath("speed",$PREFS->metricSystem)."'>";
if ($flight->is3D() &&  is_file($flight->getChartfilename("vario",$PREFS->metricSystem))) 
	$chart4="<br><br><img src='".$flight->getChartRelPath("vario",$PREFS->metricSystem)."'>";


$Ltemplate->assign_vars(array(
	'M_PATH'=> $moduleRelPath,
	'T_PATH'=> $moduleRelPath.'/templates/'.$PREFS->themeName,
	
	'ADMIN_PANEL'=>$adminPanel,
	'MAP_IMG'=>$mapImg,
	'CHART_IMG1'=>$chart1,
	'CHART_IMG2'=>$chart2,
	'CHART_IMG3'=>$chart3,
	'CHART_IMG4'=>$chart4,		
	'images'=>$images,
	'2col'=>($images?"2col":""),
	'comments'=>$comments,
	'linkURL'=>$linkURL,
	'glider'=>$glider,
	'gliderCat'=>$gliderCat,
	'igcPath'=> $flight->getIGCRelPath(),

	'linkToInfoHdr1'=>$linkToInfoHdr1,
	'linkToInfoHdr2'=>$linkToInfoHdr2,
	'linkToInfoStr1'=>$linkToInfoHdr1.$linkToInfoStr1.$linkToInfoHdr2.$linkToInfoStr2,
	'linkToInfoStr2'=>$linkToInfoStr2,

));

if ($comments) {
   	$Ltemplate->assign_block_vars('COMMENTS', array() );
}

if ($adminPanel) {
   	$Ltemplate->assign_block_vars('ADMIN_PANEL', array() );
}

$Ltemplate->pparse('body');

?>
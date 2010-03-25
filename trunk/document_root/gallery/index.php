<?php
/////////////////////////////////////////////////////////////////////////////////////
////////        JV2 Quick Gallery                                           /////////
////////                                             Joonas Viljanen 2008   /////////
////////                                               jv2 at jv2 dot net   /////////
////////                                      http://quickgallery.jv2.net   /////////
/////////////////////////////////////////////////////////////////////////////////////
//    This work is licensed under a Creative Commons Attribution 3.0 License.      //
//    http://creativecommons.org/licenses/by/3.0/				   //
//    You are free:								   //
//        * to Share - to copy, distribute and transmit the work		   //
//        * to Remix - to adapt the work					   //
//    Under the following conditions:						   //
//        * Attribution. You must attribute the work in the manner		   //
//          specified by the author or licensor (but not in any way 		   //
//          that suggests that they endorse you or your use of the work).	   //
/////////////////////////////////////////////////////////////////////////////////////
//    Javascript Image display functions in this gallery are from		   //
//        * LyteBox v3.22							   //
//        * Author: Markus F. Hay						   //
//        * Website: http://www.dolem.com/lytebox				   //
/////////////////////////////////////////////////////////////////////////////////////
// 						Please feel free to donate... :)   //
/////////////////////////////////////////////////////////////////////////////////////
$version = "1.2";
$version_lytebox = "3.22";
/////////////////////////////////////////////////////////////////////////////////////
// SETUP
/////////////////////////////////////////////////////////////////////////////////////

$gallery_page_title = "Intersob 2010";		// title displayed in browser window and on top of page
$maxthumbwidth = 120;				// max width of thumbnail (default 120)
$maxthumbheight = 90;				// max height of thumbnail (default 90 for standard OR 79 for most SLR type photos)
$slideshow = true;				// use the slideshow feature
$displayimgname = true;				// show image names (file extensions will be removed and "_" replace by " ")
$cachequality = 75;				// quality of thumbnails and cached images
$cachethumbs = true;				// save the thumbnails (loads faster)
$cachefolder = "thumbs";			// this is the folder the script will create and save thumbs in
$filesort = "asc"; 				// file sorting asc or desc
$foldersort = "asc"; 				// folder sorting asc or desc
$ordernumber_separator = "__";			// You can order images with numbers in front and those number will be removed from displayname when followed by this
$show_breadcrumbs = true;			// show links to folders when browsing
$show_backlink = true;				// show first item in gallery as back link when in subfolder
$folder_icon_set = "2";				// folder icon set "1" or "2"
$folder_icon_height1 = "64";			// height of folder icon for set 1 (used to position in center)
$folder_icon_height2 = "79";			// height of folder icon for set 2 (used to position in center)
$accepted_img = array( 'jpg', 'jpeg', 'gif', 'png' ); // image extensions of images that will be displayed
$not_found_msg = "No Images found in this folder..."; // text shown when nothing found
$not_folder_msg = "Requested Album does not exist..."; // text shown when folder not found

$galleryfolder = ""; 				// sub directory where images are held MUST END IN /  - (or leave empty for same dir)
$includemode = true;				// if you are including it in site use true else use false
$urlinclude = "";			// if you are using something in url to display gallery add it here like "page=gallery&"


/*** Paging ***/
$paging = true;					// divide into pages and show page numbers
$limit = 15; 					// limit of items to show per page (10,15,20,25,30...)
$displaylinkstop = false;			// show the paging links on top of gallery
$displaylinksbottom = true;			// show the paging links on bottom of gallery
$showpages = "5"; 				// 1,3,5,7,9... // how many page numbers to show in list at a time
$lytebox_folder_all = true;			// let LyteBox link to all images in folder... not just visible pics on current page

/*** Global Lytebox Configuration ***/
$lytebox_hideFlash		= "true";	// controls whether or not Flash objects should be hidden
$lytebox_outerBorder		= "true";	// controls whether to show the outer grey (or theme) border
$lytebox_resizeSpeed		= 8;		// controls the speed of the image resizing (1=slowest and 10=fastest)
$lytebox_maxOpacity		= 70;		// higher opacity = darker overlay, lower opacity = lighter overlay
$lytebox_navType		= 1;		// 1 = "Prev/Next" buttons on top left and left (default), 2 = "<< prev | next >>" links next to image number
$lytebox_autoResize		= "true";	// controls whether or not images should be resized if larger than the browser window dimensions
$lytebox_doAnimations		= "true";	// controls whether or not "animate" Lytebox, i.e. resize transition between images, fade in/out effects, etc.
$lytebox_borderSize		= 12;		// if you adjust the padding in the CSS, you will need to update this variable -- otherwise, leave this alone...

/*** Configure Slideshow Options ***/
$lytebox_slideInterval		= 4000;		// Change value (milliseconds) to increase/decrease the time between "slides" (10000 = 10 seconds)
$lytebox_showNavigation		= "true";	// true to display Next/Prev buttons/text during slideshow, false to hide
$lytebox_showClose		= "true";	// true to display the Close button, false to hide
$lytebox_showDetails		= "true";	// true to display image details (caption, count), false to hide
$lytebox_showPlayPause		= "true";	// true to display pause/play buttons next to close button, false to hide
$lytebox_autoEnd		= "true";	// true to automatically close Lytebox after the last image is reached, false to keep open
$lytebox_pauseOnNextClick	= "false";	// true to pause the slideshow when the "Next" button is clicked
$lytebox_pauseOnPrevClick 	= "true";	// true to pause the slideshow when the "Prev" button is clicked








if (!empty($galleryfolder) ) {
	if (!endsWithSlash($galleryfolder)) $galleryfolder .= '/';
	$cachefoldername = $cachefolder;
	$cachefolder = $galleryfolder.$cachefolder;
}

$self = $_SERVER['PHP_SELF']."?".$urlinclude;
$getimgurl = $galleryfolder."?";




/////////////////////////////////////////////////////////////////
// Page CSS
/////////////////////////////////////////////////////////////////

function pageCSS() {
	Global $maxthumbwidth; Global $getimgurl;
	$css = "
	
	body {
		color:#000000; margin:0px;padding:0px;
		background: url(".$getimgurl."img=page_bg) #999999 top left repeat-x;
		font-family: 'Trebuchet MS',Verdana,Arial;
	}
	#gallery {
		width:770px; margin: 0px auto 20px auto; padding:8px;
		color:#000000; background-color:#ffffff; border:1px solid #777777;
		background: url(".$getimgurl."img=gallery_bg) #ffffff top left repeat-x;
	}
	#header {
		width:770px; height:30px;
		margin:0px auto 0px auto; padding:0px 8px 2px 8px;
		color:#aaaaaa; font-size:24px; text-align:center; letter-spacing:2px;
		border-left:1px solid #777777; border-right:1px solid #777777; border-bottom:1px solid #777777;
		background: url(".$getimgurl."img=header_bg) #ffffff bottom left repeat-x;
	}
	#breadcrumbs {
		width:770px; height:16px;
		margin:0px auto 0px auto; padding-top:4px;
		font-size:10px; line-height:10px;
	}
	#breadcrumbs a { color:#555555; text-decoration:none;}
	#breadcrumbs a:hover { color:#000000;}
	
	#footer {
		width:770px; height:30px;
		margin:0px auto 0px auto; padding:2px 8px 0px 8px;
		color:#aaaaaa; font-size:10px; text-align:right; letter-spacing:1px;
		border-left:1px solid #777777; border-right:1px solid #777777; border-top:1px solid #777777;
		background: url(".$getimgurl."img=footer_bg) #ffffff bottom left repeat-x;
	}
	#footer a { color:#aaaaaa; text-decoration:none;}
	.galleryimage {
		width:".$maxthumbwidth."px;
		float:left; text-align:center; margin:8px; padding:8px; border:1px solid #888888;
		background: url(".$getimgurl."img=photo_bg) #bbbbbb top left repeat-x;
	}
	.imagetitle { font-size:10px; text-align:center; }
	#pageLinks { height:20px; color:#000000; font-size:12px; text-align:center; clear:both; }
	#pageLinks a { color:#000000; text-align:center; }
	.hidden { display:none; }
	
	";
		
	$css = str_replace(array("\n", "\r", "\t"), '', $css);
	return $css;
}

/////////////////////////////////////////////////////////////////
// Gallery Header and Footer
/////////////////////////////////////////////////////////////////

function printHeader() {
	
Global $gallery_page_title; Global $getimgurl;

$header = '
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>
<title>'.$gallery_page_title.'</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<meta http-equiv="Content-Style-Type" content="text/css" />
<meta http-equiv="imagetoolbar" content="false" />
<script type="text/javascript" src="'.$getimgurl.'js=lytebox"></script>
<style type="text/css">
'.pageCSS().lyteboxCSS().'
</style>
</head>
<body>
<div id="header">'.$gallery_page_title.'</div>
';
$header.= '<div id="breadcrumbs">'.getBreadcrumbs().'</div>';
$header.= '
<div id="gallery">
';

return $header;
}

function printFooter() {
	Global $version; Global $version_lytebox;
$footer = '<div style="clear:both;"></div>
</div>
<div id="footer"><a target="_blank" href="http://quickgallery.jv2.net">JV2 Quick Gallery '.$version.'</a> - <a target="_blank" href="http://www.dolem.com/lytebox/">Lytebox '.$version_lytebox.'</a></div>
</body>
</html>
';

return $footer;
}

/////////////////////////////////////////////////////////////////
// LYTEBOX CSS
/////////////////////////////////////////////////////////////////

function lyteboxCSS() {
	Global $getimgurl;
$css = "
#lbOverlay { position: fixed; top: 0; left: 0; z-index: 99998; width: 100%; height: 500px; }
#lbOverlay.grey { background-color: #000000; }

#lbMain { position: absolute; left: 0; width: 100%; z-index: 99999; text-align: center; line-height: 0; }
#lbMain a img { border: none; }

#lbOuterContainer { position: relative; background-color: #fff; width: 200px; height: 200px; margin: 0 auto; }
#lbOuterContainer.grey { border: 3px solid #888888; }

#lbDetailsContainer { font: 10px Verdana, Helvetica, sans-serif; background-color: #fff; width: 100%; line-height: 1.4em;	overflow: auto; margin: 0 auto; }
#lbDetailsContainer.grey { border: 3px solid #888888; border-top: none; }

#lbImageContainer, #lbIframeContainer { padding: 10px; }
#lbLoading { position: absolute; top: 45%; left: 0%; height: 32px; width: 100%; text-align: center; line-height: 0; background: url(images/loading.gif) center no-repeat; }

#lbHoverNav { position: absolute; top: 0; left: 0; height: 100%; width: 100%; z-index: 10; }
#lbImageContainer>#lbHoverNav { left: 0; }
#lbHoverNav a { outline: none; }

#lbPrev { width: 49%; height: 100%; background: transparent url(".$getimgurl."img=blank) no-repeat; display: block; left: 0; float: left; }
#lbPrev.grey:hover, #lbPrev.grey:visited:hover { background: url(".$getimgurl."img=prev) left 15% no-repeat; }

#lbNext { width: 49%; height: 100%; background: transparent url(".$getimgurl."img=blank) no-repeat; display: block; right: 0; float: right; }
#lbNext.grey:hover, #lbNext.grey:visited:hover { background: url(".$getimgurl."img=next) right 15% no-repeat; }

#lbPrev2, #lbNext2 { text-decoration: none; font-weight: bold; }
#lbPrev2.grey, #lbNext2.grey, #lbSpacer.grey { color: #333333; }

#lbPrev2_Off, #lbNext2_Off { font-weight: bold; }
#lbPrev2_Off.grey, #lbNext2_Off.grey { color: #CCCCCC; }
	
#lbDetailsData { padding: 0 10px; }
#lbDetailsData.grey { color: #333333; }

#lbDetails { width: 60%; float: left; text-align: left; }
#lbCaption { display: block; font-weight: bold; }
#lbNumberDisplay { float: left; display: block; padding-bottom: 1.0em; }
#lbNavDisplay { float: left; display: block; padding-bottom: 1.0em; }

#lbClose { width: 26px; height: 26px; float: right; margin-bottom: 1px; }
#lbClose.grey { background: url(".$getimgurl."img=close) no-repeat; }

#lbPlay { width: 64px; height: 26px; float: right; margin-bottom: 1px;margin-right: 30px; }
#lbPlay.grey { background: url(".$getimgurl."img=play) no-repeat; }
	
#lbPause { width: 64px; height: 26px; float: right; margin-bottom: 1px;margin-right: 30px; }
#lbPause.grey { background: url(".$getimgurl."img=pause) no-repeat; }
";

$css = str_replace(array("\n", "\r", "\t"), '', $css);
return $css;
}



/////////////////////////////////////////////////////////////////
// Gallery CODE
/////////////////////////////////////////////////////////////////

	$items = array();
	$folder = "";
	$subfolder = "";
	$sublink = "";
	$f = "";

	if($folder_icon_set == 1) {
		$vspace_folder = ($maxthumbheight - $folder_icon_height1) /2;
	} else if($folder_icon_set == 2) {
		$vspace_folder = ($maxthumbheight - $folder_icon_height2) /2;
	}
	


// CLEAN GET VARS
foreach($_GET as $var => $val) {
	$_GET[$var] = htmlspecialchars(strip_tags($val));
}


if (isset($_GET['source'])) {
	$source = (get_magic_quotes_gpc()) ? $_GET['source'] : addslashes($_GET['source']);
	createThumb($source);
	die();
} else if (isset($_GET['js'])) {
	$js = (get_magic_quotes_gpc()) ? $_GET['js'] : addslashes($_GET['js']);
	outputJS($js);
	die();
} else if( isset($_GET['img']) ) {
	$img = (get_magic_quotes_gpc()) ? $_GET['img'] : addslashes($_GET['img']);
	getEncodedImage($img);
 	die();   
} else {

	echo printHeader();
	echo printGallery();
	echo printFooter();
}


function printGallery() {

	Global $filesort; Global $foldersort; Global $accepted_img; Global $slideshow; Global $cachefolder; Global $cachefoldername;
	Global $maxthumbheight; Global $show_backlink; Global $folder_icon_set; Global $folder_icon_height; Global $vspace_folder;
	Global $items; Global $limit; Global $folder; Global $f; Global $subfolder; Global $sublink; Global $cachethumbs;
	Global $paging; Global $displaylinkstop; Global $displaylinksbottom; Global $lytebox_folder_all;
	Global $galleryfolder; Global $self; Global $urlinclude; Global $getimgurl; Global $not_found_msg; Global $not_folder_msg;

	if ($cachethumbs == true) {
		if (!file_exists($cachefolder)) {
			if( @mkdir($cachefolder, 0777) ) {$cache_error="";} else { $cache_error='Could not Create Directory "'.$cachefolder.'".';}
		} else if (!is_writable($cachefolder)) {
			if( @chmod($cachefolder, 0777) ) {$cache_error="";} else { $cache_error='Could not make directory "'.$cachefolder.'" writable.';}
		}
		if (!empty($cache_error)){
			$gallery_code .= '<div style="font-size:10px;color:#ff0000;text-align:center;">';
			$gallery_code .= '<b>CACHE ERROR: <i>'.$cache_error.'</i></b><br/>';
			$gallery_code .= 'You have "cachethumbs" turned on, but your server does not allow the script to modify or create the folder.<br/>';
			$gallery_code .= 'Please create the folder "'.$cachefolder.'" and make it writable (quicker), or turn off "cachethumbs" (slower). ';
			$gallery_code .= '</div>';
		}
	}
	

	
	if (isset($_GET['f'])) {
		$f = (get_magic_quotes_gpc()) ? $_GET['f'] : addslashes($_GET['f']);
		$folder = "./".$galleryfolder.$f;
		$sublink = $f."/";

	} else {
		$folder = "./".$galleryfolder; //getcwd()
		$sublink = "";
	}
	
	
	if(is_dir($folder)) {
		$galleryimg_dir = dir($folder);
		$img_array = array();
		$folder_array = array();
		while(($file = $galleryimg_dir->read()) !== false) { 
			$file_ext = explode(".",$file);
			$file_ext = strtolower($file_ext[1]);
			if( in_array( $file_ext , $accepted_img ) ) {
				$img_array[] = $file;
			} else if (is_dir($folder."/".$file) && $file!="." && $file!=".." && $file!=$cachefolder && $file!=$cachefoldername) {
				$folder_array[] = $file;
			}
		}

		if ($filesort == "desc") { rsort($img_array); } else { sort($img_array); }
		if ($foldersort == "desc") { rsort($folder_array); } else { sort($folder_array); }

		$items = array_merge($folder_array,$img_array);
		$total = sizeof($items);
	} else {
		$notFolder = true;
	}
	
	if($paging) {
		if ( !empty($_GET['f']) && $show_backlink){
			$limit--;
		}
		if (isset($_GET['p'])) {
			$pagenumber = (get_magic_quotes_gpc()) ? $_GET['p'] : addslashes($_GET['p']);
			$loopstart = ($pagenumber-1) * $limit;
			$looplimit = $limit*$pagenumber;
		} else {
			$pagenumber = 1;
			$loopstart = 0;
			$looplimit = $limit;
		}
		if(isset($_GET['f'])) {
			$baseurl = "?".$urlinclude."f=".$f."&amp;p=";
		} else {
			$baseurl = "?".$urlinclude."p=";
		}
		$pagelinks = display_paging( $total, $limit, $pagenumber, $baseurl );
		if($displaylinkstop) {
			$gallery_code .= $pagelinks;
		}
	} else {
		$loopstart = 0;
		$looplimit = $total;
	}
	if($total < $looplimit) { $looplimit = $total; }
	

		if ( !empty($_GET['f']) && $show_backlink){
			
			$displayfolders = explode("/",$f);
			if(sizeof($displayfolders)>1) {
				array_pop($displayfolders);
				$backlink.= "?".$urlinclude."f=";
				foreach($displayfolders as $fl) {
					$backlink.= $fl."/";
				}
				$backlink = substr($backlink, 0, -1);
			} else {
				$backlink = $self;
			}
			
			$gallery_code .= '<div class="galleryimage"><a href="'.$backlink.'" title="&lt;&lt;">';
			$gallery_code .= '<img border="0" vspace="'.$vspace_folder.'" src="'.$getimgurl.'img=folder_up'.$folder_icon_set.'" alt="&lt;&lt;" />';
			$gallery_code .= '</a><div class="imagetitle">&lt;&lt;</div></div>'."\n";
		}


	
	if ( $notFolder ){ $gallery_code .= '<div style="text-align:center;padding:55px;">'.$not_folder_msg.'</div>'; }
	else if ( sizeof($items) == 0 ){ $gallery_code .= '<div style="text-align:center;padding:55px;">'.$not_found_msg.'</div>'; }



	if ($lytebox_folder_all){
		for ( $i=0; $i<=$loopstart-1; $i++) {
			$gallery_code .= displayLyteRef($i);
		}
	}
	for ( $i=$loopstart; $i<=$looplimit-1; $i++) {
		$gallery_code .= displayItem($i);
	}
	if ($lytebox_folder_all){
		for ( $i=$looplimit; $i<=$total; $i++) {
			$gallery_code .= displayLyteRef($i);
		}
	}
	if($paging && $displaylinksbottom) {
		$gallery_code .= $pagelinks;
	}
	return $gallery_code;

}


function getBreadcrumbs() {
	Global $gallery_page_title;
	Global $show_breadcrumbs; Global $self;
	
	if (isset($_GET['f']) && $show_breadcrumbs) {
		$f = (get_magic_quotes_gpc()) ? $_GET['f'] : addslashes($_GET['f']);
		$displayfolders = explode("/",$f);
		$breadcrumbs_code = '<a href="'.$self.'">'.$gallery_page_title.'</a>';
		
		for ($i=0; $i <= sizeof($displayfolders); $i++) {
			
			if ($displayfolders[$i]!=null) { 
				$breadcrumbs_code .= ' / <a href="?f='.$displayfolders[0];
				$bc_path = "";
				for ($x=1; $x <= $i; $x++) {
					$breadcrumbs_code .= '/'.$displayfolders[$x];
					$bc_path .= '/'.$displayfolders[$x];
				}
				$breadcrumbs_code .= '">'.str_replace("_"," ", RemoveOrderNumber($displayfolders[$i]) ).'</a>';
			}
			
		}
	} else {
		$breadcrumbs_code = "&nbsp;";
	}
	
	return $breadcrumbs_code;
}

function displayItem($i) {
	Global $items; Global $folder; Global $f; Global $vspace_folder; Global $sublink; Global $folder_icon_set; Global $slideshow;
	Global $galleryfolder; Global $self; Global $urlinclude; Global $getimgurl;

	if(is_dir($folder."/".$items[$i]) ){
		$gallery_code .= '<div class="galleryimage"><a href="?'.$urlinclude.'f='.$sublink.$items[$i].'" title="'.$items[$i].'">';
		$gallery_code .= '<img border="0" vspace="'.$vspace_folder.'" src="'.$getimgurl.'img=folder'.$folder_icon_set.'" alt="'.$items[$i].'" />';
		$gallery_code .= '</a><div class="imagetitle">'.str_replace("_"," ", RemoveOrderNumber($items[$i]) ).'</div></div>'."\n";
	} else {
		$imgtext = getImgText($items[$i]);
		if ($slideshow){ $js_rel = "lyteshow"; } else { $js_rel = "lytebox"; }
		
		$gallery_code .= '<div class="galleryimage"><a href="'.$galleryfolder.$sublink.$items[$i].'" rel="'.$js_rel.'[jv2gallery]" title="'.$imgtext['descr'].'">';
		$gallery_code .= getThumbnail($folder,$items[$i]);
		$gallery_code .= '</a><div class="imagetitle">'.$imgtext['title'].'</div></div>'."\n";		
	}
	
	return $gallery_code;
}

function displayLyteRef($i) {
	Global $items; Global $folder; Global $sublink;
	if(is_dir($folder."/".$items[$i]) ){
	} else {
		$imgtext = getImgText($items[$i]);
		$gallery_code .= '<span class="hidden"><a href="'.$sublink.$items[$i].'" rel="lyteshow[jv2gallery]" title="'.$imgtext['descr'].'">';
		$gallery_code .= $i.'</a></span>'."\n";		
	}
	
	return $gallery_code;	
}

function getImgText($image) {
	Global $displayimgname; 
	Global $folder;
	$img = array();
	if ($displayimgname == true) {
		$img['title'] = getImageName($image);
	} else {
		$img['title'] = "&nbsp;";
	}
	$commentsfile = getCommentsFileName($folder."/".$image);
	if (file_exists($commentsfile)) {
		if ($img['title'] != "&nbsp;") {
			$img['descr'] = $img['title']." - ";
		}
		$comments = getCommentsText($commentsfile,true);	
		$img['descr'] .= $comments;
		
	} else {
		$img['descr'] = $img['title'];
	}
	return $img;
}
	
function getThumbnail($folder,$image) {
	Global $maxthumbwidth; Global $f; Global $maxthumbheight; Global $cachefolder; Global $getimgurl;
	//$folder.="/";
	if(strpos("..",$folder.$image) ) {die();}

	$size = getimagesize ($folder."/".$image);
	$xratio = $maxthumbwidth/$size[0];
	$yratio = $maxthumbheight/$size[1];
	if($xratio < $yratio) {
		$thumbwidth = $maxthumbwidth;
		$thumbheight = floor($size[1]*$xratio);
	} else {
		$thumbheight = $maxthumbheight;
		$thumbwidth = floor($size[0]*$yratio);
	}
	
	$modifed = filemtime($folder."/".$image);
	$filesize = filesize($folder."/".$image);
	$hash = md5($folder."/".$image.$size[0].$size[1].$modifed.$filesize);
	$cacheimagename = $cachefolder."/thumb_".$hash.".jpg";	
	
	$gallery_code .= "<img border='0' src='";
	if (file_exists($cacheimagename)) {
		$gallery_code .= $cacheimagename;
	} else {
		if( isset($_GET['f']) ) { $f2 = $f."/"; } else { $f2=""; }
		$gallery_code .= $getimgurl."source=".$f2.$image;
	}
	$gallery_code .= "' alt='&nbsp;' height='".$thumbheight."' width='".$thumbwidth."' />";
	
	return $gallery_code;
}
	
	
function createThumb($source) {
	
	Global $maxthumbwidth;
	Global $maxthumbheight;
	Global $cachethumbs;
	Global $cachefolder;
	Global $cachequality;
	
	if(strpos("..",$source) ) {die();}
	
	$path = pathinfo($source);
	
	switch(strtolower($path["extension"])){
		case "jpeg":
		case "jpg":
				$original=imagecreatefromjpeg($source);
				break;
		case "gif":
				$original=imagecreatefromgif($source);
				break;
		case "png":
				$original=imagecreatefrompng($source);
				break;
		default:
				break;			
	}
	$xratio = $maxthumbwidth/(imagesx($original));
	$yratio = $maxthumbheight/(imagesy($original));

	if($xratio < $yratio) {
			$thumb = imagecreatetruecolor($maxthumbwidth,floor(imagesy($original)*$xratio));
			$thumb_width = $maxthumbwidth;
			$thumb_height = floor(imagesy($original)*$xratio);
	} else {
			$thumb = imagecreatetruecolor(floor(imagesx($original)*$yratio), $maxthumbheight);
			$thumb_width = floor(imagesx($original)*$yratio);
			$thumb_height = $maxthumbheight;
	}


	imagecopyresampled($thumb, $original, 0, 0, 0, 0, imagesx($thumb)+1,imagesy($thumb)+1,imagesx($original),imagesy($original));
	imagedestroy($original);

	//CACHE IMAGE
	if ($cachethumbs == true) {
		if (is_writable($cachefolder)) {
			$size = getimagesize($source);
			$modifed = filemtime($source);
			$filesize = filesize($source);
			$hash = md5($source.$size[0].$size[1].$modifed.$filesize);
			$cacheimagename = $cachefolder."/thumb_".$hash.".jpg";
			imagejpeg($thumb,$cacheimagename,$cachequality);
		}
	} 
	//RETURN A JPG TO THE BROWSER 
	imagejpeg($thumb);
	imagedestroy($thumb);

}


function getImageName($image) {
	
	$path_parts = pathinfo($image);
	$imagename = substr($path_parts['basename'], 0, -(strlen($path_parts['extension']) + ($path_parts['extension'] == '' ? 0 : 1)));
	
	$imagename = str_replace("_"," ", RemoveOrderNumber($imagename) );	
	return $imagename;
}
	
function RemoveOrderNumber($foldername) {
	Global $ordernumber_separator;
	
	if(strpos($foldername,$ordernumber_separator)) {
		$displayfoldername = substr($foldername, (strpos($foldername,$ordernumber_separator)+2));
	} else {
		$displayfoldername = $foldername;
	}
	return $displayfoldername;
}

function getCommentsFileName($getimage) {
	$path_parts = pathinfo($getimage);
	$path_parts['basename_we'] = substr($path_parts['basename'], 0, -(strlen($path_parts['extension']) + ($path_parts['extension'] == '' ? 0 : 1)));
	$commentsfile = $path_parts['dirname']."/".$path_parts['basename_we'].".txt";
	return $commentsfile;
}

function getCommentsText($commentsfile,$removeNL) {
	$fp = fopen($commentsfile, "r");
	$comments = fread($fp, filesize($commentsfile));
	fclose($fp);
	
	if($removeNL) {
		$comments = str_replace("\n", " ", $comments);
		$comments = str_replace("\r", " ", $comments);
	}
	
	$comments = htmlentities($comments);
	
	return $comments;
}


function endsWithSlash($s) {
	return (strrpos($s,'/') == strlen($s) - 1);
}  

function outputJS($js) {
	if ($js == "lytebox"){
		LyteBoxJS(); die();
	} else if ($js == "") {
		
	} 
}


/////////////////////////////////////////////////////////////////
// ENCODED IMAGES
/////////////////////////////////////////////////////////////////

function getEncodedImage($img) {

$images = array(
"blank"=> array("image/gif", "R0lGODlhAQABAID/AMDAwAAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw=="),
"close"=> array("image/gif", "R0lGODlhGgAaAPcAAFZWVpqamoWFhZubm6GhoZaWlouLi6CgoJWVlWpqamFhYZ6eno6OjqampmhoaKOjo5SUlICAgFpaWqWlpTU1NZKSkoSEhI+Pj5+fn6urq4eHh52dnU1NTbS0tC4uLlxcXKqqqk9PT/39/XFxceHh4YKCgqKioqioqJiYmHV1dVNTU2RkZK+vr0pKSsPDw4iIiI2NjUBAQNzc3G9vbzAwMD8/Py0tLTw8PHp6ejs7O0hISEFBQXl5eUJCQoODg7e3t0lJSWdnZzY2Nq6urnd3d76+vnBwcDQ0NLa2tn19fS8vLzo6Ojg4OGlpaaysrEVFRTk5OTMzM3R0dGtra8HBwWxsbFFRUUtLSz4+PrKysmVlZYqKiltbW7q6ukxMTLi4uENDQzExMcDAwNDQ0MbGxnt7e6enp1VVVbW1tSwsLL29va2trbCwsH9/f6mpqcTExFBQUFhYWJGRkTIyMmJiYry8vMXFxWZmZnZ2dnx8fERERFdXV7+/vzc3N4mJiYyMjHNzc3Jycn5+fkZGRpycnP///wAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAACH5BAAAAAAALAAAAAAaABoAAAj/AAsJHDjQhQAAPdL0ACDAhQiCECESaMFhBANCTgigKKPCA4GIEKkkCPEHw4EDBEw0cDOkQwMuCaiALHSCRiAEAQjp1LkAg0oWXyBEORERTZQUFwoEGLCz6QITIJA0YJKFIAkPTfzghBHhYlMEETRgOPHjgBISA1+oiFAhgIM+T5ZI2KmAghUdEjCA6NLmhUAZFIxsyblEigYEcRQQmkEByRs7NRgQGKKmhoxCAjjggMB0SYIkMAqAcUDhgBgyRYQg2DDhhw8BImwosJCTkIMcI0oggHAkQpfTOBQTOsACiYcxTIIYYKpTQg8iBjZgYPGDjwEAFXRuOFGHQ4YaCbzu/wRwRjcBEB1Q6LDQdMKXIIR2VLnQlFASGilgEGrAAsuM+g+gMQIhMSRA304WhGCFFAJAcAAIA+zA3k4PdGBEBjesYMBOFQCwAxdV5GEBCg+sUQJ2FHagxRgUfCBAbRJAcQUAK2ghBA8BTDCEEXPpNEEHV8QGBw8FEMIADYMAocIHR8wQhgaETOAEBxEQsoAZGXiAWQzKDTBFDi14oQIFiilwgwELTDDFCsNlgIMAhcgwxxk8QACBEmAAAUWPhCigBxEpHFHBBg9kgMVlhbxQAx0+FOADADrgUV8SIQBgwQYHmAGIXwKRYAMQDghQAHP1OWUCDGcRlAUNHDRRQlukllG6wAVCsBFRAx60sAIRGsih1ADABoBCAQWUEcYDMxXhwA17JJBCWAZcwIABWwgSggNFzDTQRDl48cEdI8yQwAdP2PCRthAZBEAMacTAkEMgBQQAOw=="),
"loading"=> array("image/gif", "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"),
"prev"=> array("image/gif", "R0lGODlhLQAtAPebAOrq6nt7e319fdfX19XV1UVFRbOzs6+vr8bGxru7u7+/v7CwsHd3d7W1tX9/f8LCwicnJ8fHx3p6eigoKHx8fLy8vL29vaysrKioqLGxsXh4eKGhoSoqKjg4OLKysq6urr6+vmRkZKOjo5aWlqCgoLi4uHZ2dn5+fqKiojAwMKampoiIiI2Nja2trcjIyM7OzpCQkEZGRpiYmFJSUsPDw83NzbS0tIKCgpqamsnJyWZmZikpKYSEhCsrK7a2tqqqqmFhYW9vb7m5uU9PT2lpaV1dXbq6ulZWVldXV8TExHJycv39/aenp8/Pz/v7+0BAQKmpqTU1NZOTky8vL5+fn2tra0hISDMzM0tLS05OToyMjG5ubp2dnaWlpcHBwUlJSczMzGhoaImJiVFRUS4uLj09PYCAgG1tbXV1dTw8PLe3t0dHR8DAwJycnF5eXqurq0NDQ2xsbIqKimBgYEpKSjk5OXFxcZKSksXFxYeHh3l5eWpqaltbW01NTVNTU0JCQkFBQZubmz8/P1lZWcvLy6SkpNjY2Do6OiwsLHNzc8rKyjY2No6OjlhYWIuLiy0tLTQ0NFpaWmJiYo+Pj4WFhZSUlJWVlXR0dDIyMmNjY////////wAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAACH5BAEAAJsALAAAAAAtAC0AAAj/ADUJHEiwoMGDCA9u2pSwocOHAhlCnEixosWLGDNq3Mhx4IAVmUKKHJmJCAsAHQkOIMlyZAAnKTWtzCSABIYDaiw8QOAiRwQECgJkGpByJoMRJKBkEKIgSQQXLiJ4Efqy40wljkZs+GEgAZskCMJ6oZApAEqOM7fc0IJDRIsGXh/QoBG07NmNM6tI4AGDi4oDPhKAUKCgAtW7GmcSQXNCjFYoCxqUMJKghAS7VkOGUbIXRpsuFxYYaODDgAbMaEPqCMLADAscKDC0WJAhwwcGqPGGDFElkQDHGzBcOED8jYnciUMCCXGGAQ8pVJjMXnDAOPKMM4vwCbGFgpY2Kj4Y/7Bh4ABus5kzIRlyBEgQM1JQXDBQQkiD0+hTZ5oBx8oYN0HcIIMKGZRQQQKHpTdEB4cI8sUgcdyAAwY2VGABWflx5EEmQ2AyRQqLADKGDifIgAFcGCK20QKZYNHDDhyQ0YEVRTBwhwoNVECWACpqREBIMUAAwQ5TlDFDHCts4IECNAhAU48Z/ZhJARBMwMEVBbghgQwXJIBADic8GZOUVE6ASB1Z7LGCCDY8QEgNDoiZEplVpvBEI2hU8oMRCLzQRJw8jhkSlRxAsgYQDnBxgAUR+AkolBiROUEPHfQRBiUkZACCCy+88KigU05ARhoznCEHCktGUAOccnZE5g52HqJhBwsiGAACAmCAEWagcw56JRyRXDJJIR5YgIciOTjJq6uD9hBFDEWYAIMICyTwwE/KQnoRmY90QMccGsCAwgdqgPDAA0Ity5GUMaRQRhaS6MEIFVwJYUEFl6m7kZRrRPGHHyFoIEcgXbRggA/30bQEqFik8QUSOjCQhyUkMDHcB8cZEpMmUrbEkgALb0wASB6HZJK2G6es8sostyxRywktFBAAOw=="),
"next"=> array("image/gif", "R0lGODlhLQAtAPeeAOrq6n19fdfX19XV1Xt7e2FhYX9/f5+fn0ZGRo2NjXNzc0lJSYCAgLq6ultbW1lZWWlpaV9fX8/Pz0VFRUtLS3p6esvLy15eXnl5eWRkZFZWVmxsbMXFxVBQUHx8fM7Ozp6enp2dnX5+ftPT09DQ0JycnJCQkMbGxrS0tJOTk2pqai0tLUxMTE1NTXFxcaKiolNTU1RUVEFBQcjIyMrKytLS0r6+vrGxsaSkpMDAwLa2tsnJyXd3d6urq0NDQ9HR0aWlpXh4eMHBwa6urs3NzXV1dW5ubpmZmVpaWnR0dG9vbzg4ONTU1Jqamjo6Oqenp6ysrJaWll1dXU5OTouLi8LCwsPDw7Ozs09PT5SUlJiYmEhISKGhoYODg2tra7i4uKqqqpKSkq+vr4yMjKmpqWVlZYSEhI+Pj3JycomJiYeHh2JiYsTExFdXV46OjqOjo4iIiLu7u9bW1lFRUb+/v7CwsGZmZlJSUqioqFhYWIWFhWdnZzIyMjAwMEdHR9jY2Dw8PJubm6ampr29vWBgYG1tbbW1tUBAQIKCglVVVUpKSrm5uURERKCgoHZ2dsfHx/v7+1xcXIqKij4+PszMzJWVlS8vLzc3N5eXl7KysmhoaP39/WNjY////////wAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAACH5BAEAAJ4ALAAAAAAtAC0AAAj/AD156kSwoMGDCBMqTDhwocOHECNKnEixosWLGDNqjDgADqePIENygpAAwMaDA0SqDBnA5EmCdUZuMOICQ5czJYAM0WFDiAdOLV92SsmpwIUCe1yISBACz5U4VjgEAOryJFEkczRc8FIhwYEeOnI8miGCqlCiMRCwaGMHgxsuQxqwsWChbNCXRLFMQnCHEwYTb24MOkGEiAGzeD9SuHRoSgEMYYCgoLPjw4fDd61+3MIHEIvHWZ7oqEKDhATMVTcSRWDJCYUCFaKQ+TL3BwnUZz9OWLFEUYEATaDEIVzjBwPEmjntXkIhA6IDdWzMkMCkxvHMqnWvcNJhAxUchqpY//gxYMT11BqJTugj4wGPKD0anPgwQo555Nk5+VnSQpOaF1fkUBoTctSAW2KcUIBABAQcAUUDHFBCwghMSGDXJrlxEoMDRZggyGQnWPCBBBLsMNUfQgnwkQYOKJDAC2I0kAMHM+xAg1ScCPCSipzAgEUBjnjVAwoN9GSFT5wQAMlJPCbSwgMQeHDGAXiIgcIXDSxSQZLoXcQjEjA8UEYSZqQAAhBgDHHDDUFwyeRHFzgQwR5oGDCGFge88QQYZPDg5kY8EhJBBl4kYYAkKQRywAs44FDEnxrxWEABdmxQhAFphHFECAdw0UgSkGbEYwQXrKGCAgGoYQImJYBwAAgKhLGKEY9SPCBFBkYE0UUCWTQRAgghxEpAlxbx6AAMGkRShhIV6OFGJU2UUIKwxFbE4wMtdLBhBsyaMUYKWhyBhqxefpTHAguwMMcDa2zAAwNpmJCCC+QWa+4WCCCwwBQaRACBAh7oQYUS9Vr7ERILTOCDDwhQcIcDuAbBQCEFV5QJJxd0sIAPMsjAyAIdtFHAqSpUbPBKKw0rFEECeITyRyRVu/LMNNdss0EN3eyQQDo7FBAAOw=="),
"play"=> array("image/gif", "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"),
"pause"=> array("image/gif", "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"),
"folder1"=> array("image/png", "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"),
"folder_up1"=> array("image/png", "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"),
"folder2"=> array("image/png", "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"),
"folder_up2"=> array("image/png", "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"),
"page_bg"=> array("image/gif", "R0lGODlhAQBpAOYAAKGhocjIyNra2pqZmejo6J2dnaurq62trbe3uKioqbCwsLKysp2endzc3aGgoOTl5Onp6cPDw8XFxcTExM7OztLS0tTU1NbW1tnZ2d7e3p6fn+Pj47a2t6inqM/Pz7m5utvc29HR0bW2tbS0tLi5uL6/v9XV1erp6ry8vJ+fn+Dg4OTj46ampufn5qSkpJqamqqqquXm5ufn55ycm6ysrMDAwMLCwtPT09fX19/f356enuLi4sHBwZycnM3NzaOjor2+vru7vK6urqenppubm6+vr93d3bq6urGxsdjY2MrLysbGx+jo58vMzNDQ0LOzs+Xl5ampqeHi4uDh4aSjo8rJyqSlperq6pmZmQAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAACH5BAAAAAAALAAAAAABAGkAAAdlgFeCg4InEAQETDItMVAPKxs7UlMqORlGDSACAhhJOBcmFjcVIU4eFD5NSlUBAUsSExE2PDUlQChBRx8kCBwiI08LSApFQgc0BjBRCR1DLFYuVD8AAA4pGjoMBT0zRC8DA1ji4oEAOw=="),
"photo_bg"=> array("image/gif", "R0lGODlhAQA/ANUAAH9/fp6enZycnICAgISEhLu6u4iIiKurq7a3t6mpqbCwsLW1tYqKirS0tI2NjI2Ojrm5uKGioZGSkqGgoJGQka6trpWWlq2trZSUlZiZmJubm31+foKCgqSkpbi3uIGAgYaGhqqqq7GxsaioqLOztKOiorq5uaemp6alpbm6uouMjImJiYWFhYODgra2tn9/gK+vr5eXl56fn5qZmZOTk4+Pj4eHh7Kysn19fXx8fLu7uwAAAAAAAAAAAAAAAAAAACH5BAAAAAAALAAAAAABAD8AAAY4wFwOh9sAXoMPp0VggWyGFUPleNQoEhrGEsvMNIKAbBIpdVCnUSJ0uFRgCtGN1Fi4EB6IKVXQ+YMAOw=="),
"gallery_bg"=> array("image/gif", "R0lGODlhAQCKANUAAObm5sjIyO7u7snJyd7e3uvr6+3t7eLi4vLy8tLS0tXV1cTExM3Nzby8vOfn59DQ0PHx8dvb29fX1/n5+cvLy/f39/X19fPz8/T09PDw8N3d3cfHx8/Pz9jY2NPT0+Tk5MXFxePj4+Hh4eDg4O/v7+jo6MzMzN/f387OzsbGxsPDw729veXl5dra2tzc3Orq6unp6dnZ2ezs7NTU1Pv7+/j4+L6+vsLCwvb29sDAwPr6+vz8/P39/f7+/v///wAAACH5BAAAAAAALAAAAAABAIoAAAZnwEZjZSvmjrekarEApTaBwGBAoZgYDBTn8UgkPDOFQiLpxFqRiEtDIJxGosMh9GEBAA5HCfYqFGQGBgICJBkZEBAICBcXGBgWFjiSFRU1lhMTOpo6NJ07n588oqM9paanPqmqq6ypQQA7"),
"header_bg"=> array("image/gif", "R0lGODlhAQAoAMQAAP39/fv8/Pf4+MDBwfT09Orq6ufn6Pb29vDw8O3t7c3NzdDQ0MPEw+Tk5NTU1NjX2Nrb2t7e3sfGxr6+vvLy8vr6+/z8/MnKyvn5+eLh4f7+/ry8vP///wAAAAAAAAAAACH5BAAAAAAALAAAAAABACgAAAUdICeOnKYBVlBhwkFQSFIYTRZBj7Mol8QMk41QGAIAOw=="),
"footer_bg"=> array("image/gif", "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"),
"first_page"=> array("image/png", "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"),
"next_page"=> array("image/png", "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"),
"previous_page"=> array("image/png", "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"),
"last_page"=> array("image/png", "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"),
"x-x-x" => array("image/gif", "")
); 
	
header("Content-type: ".$images[$img][0] );
header("Cache-control: public");
echo base64_decode( $images[$img][1] ); 

}






/////////////////////////////////////////////////////////////////
// PAGING FUNCTION
/////////////////////////////////////////////////////////////////

function display_paging( $total, $limit, $pagenumber, $baseurl ){

/************************************************************************
display_paging() function v1.0

Function to perform paging link creation
Copyright (c) 2007 Joonas Viljanen http://www.jv2design.com

*************************************************************************/
	Global $showpages; Global $self;
	
	// set up icons to be used
	$icon_path =	$self.'img=';
	$icon_param =	' style="border:0px;" ';
	$icon_first=	'<img '.$icon_param.' src="'.$icon_path.'first_page" alt="first" title="first" />';
	$icon_last=	'<img '.$icon_param.' src="'.$icon_path.'last_page" alt="last" title="last" />';
	$icon_previous=	'<img '.$icon_param.' src="'.$icon_path.'previous_page" alt="previous" title="previous" />';
	$icon_next=	'<img '.$icon_param.' src="'.$icon_path.'next_page" alt="next" title="next" />';
	///////////////////
	///////////////////
	
	
	// do calculations
	$pages = ceil($total / $limit);
	$offset = ($pagenumber * $limit) - $limit;
	$end = $offset + $limit;

	// prepare paging links
	$html .= '<div id="pageLinks">';
	// if first link is needed
	if($pagenumber > 1) { $previous = $pagenumber -1;
		$html .= '<a href="'.$baseurl.'1">'.$icon_first.'</a> ';
	}
	// if previous link is needed
	if($pagenumber > 2) {    $previous = $pagenumber -1;
		$html .= '<a href="'.$baseurl.''.$previous.'">'.$icon_previous.'</a> ';
	}
	// print page numbers
	if ($pages>=2) { $p=1;
		$html .= "| Page: ";
		$pages_before = $pagenumber - 1;
		$pages_after = $pages - $pagenumber;
		$show_before = floor($showpages / 2);
		$show_after = floor($showpages / 2);
		if ($pages_before < $show_before){
			$dif = $show_before - $pages_before;
			$show_after = $show_after + $dif;
		}
		if ($pages_after < $show_after){
			$dif = $show_after - $pages_after;
			$show_before = $show_before + $dif;
		}   
		$minpage = $pagenumber - ($show_before+1);
		$maxpage = $pagenumber + ($show_after+1);

		if ($pagenumber > ($show_before+1) && $showpages > 0) {
			$html .= " ... ";
		}
		while ($p <= $pages) {
			if ($p > $minpage && $p < $maxpage) {
				if ($pagenumber == $p) {
			    		$html .= " <b>".$p."</b>";
				} else {
			    	$html .= ' <a href="'.$baseurl.$p.'">'.$p.'</a>';
				}
			}
			$p++;
		}
		if ($maxpage-1 < $pages && $showpages > 0) {
			$html .= " ... ";
		}
	}
	// if next link is needed
	if($end < $total) { $next = $pagenumber +1;
		if ($next != ($p-1)) {
			$html .= ' | <a href="'.$baseurl.$next.'">'.$icon_next.'</a>';
		} else {$html .= ' | ';}
	}
	// if last link is needed
	if($end < $total) { $last = $p -1;
		$html .= ' <a href="'.$baseurl.$last.'">'.$icon_last.'</a>';
	}
	$html .= '</div>';
	// return paging links
	return $html;
}












/////////////////////////////////////////////////////////////////
// LyteBox
/////////////////////////////////////////////////////////////////

function LyteBoxJS() {
	
Global $lytebox_hideFlash;
Global $lytebox_outerBorder;	
Global $lytebox_resizeSpeed;
Global $lytebox_maxOpacity;
Global $lytebox_navType;
Global $lytebox_autoResize;
Global $lytebox_doAnimations;
Global $lytebox_borderSize;
Global $lytebox_slideInterval;
Global $lytebox_showNavigation;
Global $lytebox_showClose;
Global $lytebox_showDetails;
Global $lytebox_showPlayPause;
Global $lytebox_autoEnd;
Global $lytebox_pauseOnNextClick;
Global $lytebox_pauseOnPrevClick;	
	
?>

//***********************************************************************************************************************************/
//	LyteBox v3.22
//
//	 Author: Markus F. Hay
//  Website: http://www.dolem.com/lytebox
//	   Date: October 2, 2007
//	License: Creative Commons Attribution 3.0 License (http://creativecommons.org/licenses/by/3.0/)
// Browsers: Tested successfully on WinXP with the following browsers (using no DOCTYPE and Strict/Transitional/Loose DOCTYPES):
//				* Firefox: 2.0.0.7, 1.5.0.12
//				* Internet Explorer: 7.0, 6.0 SP2, 5.5 SP2
//				* Opera: 9.23
//
// Releases: For up-to-date and complete release information, visit http://www.dolem.com/forum/showthread.php?tid=62
//				* v3.22 (10/02/07)
//				* v3.21 (09/30/07)
//				* v3.20 (07/12/07)
//				* v3.10 (05/28/07)
//				* v3.00 (05/15/07)
//				* v2.02 (11/13/06)
//
//   Credit: LyteBox was originally derived from the Lightbox class (v2.02) that was written by Lokesh Dhakar. For more
//			 information please visit http://huddletogether.com/projects/lightbox2/
//***********************************************************************************************************************************/
Array.prototype.removeDuplicates = function () { for (var i = 1; i < this.length; i++) { if (this[i][0] == this[i-1][0]) { this.splice(i,1); } } }
Array.prototype.empty = function () { for (var i = 0; i <= this.length; i++) { this.shift(); } }
String.prototype.trim = function () { return this.replace(/^\s+|\s+$/g, ''); }

function LyteBox() {
	/*** Start Global Configuration ***/
		
		this.hideFlash			= <?php echo $lytebox_hideFlash; ?>;	
		this.outerBorder		= <?php echo $lytebox_outerBorder; ?>;	
		this.resizeSpeed		= <?php echo $lytebox_resizeSpeed; ?>;
		this.maxOpacity			= <?php echo $lytebox_maxOpacity; ?>;	
		this.navType			= <?php echo $lytebox_navType; ?>;
		this.autoResize			= <?php echo $lytebox_autoResize; ?>;	
		this.doAnimations		= <?php echo $lytebox_doAnimations; ?>;	
		this.borderSize			= <?php echo $lytebox_borderSize; ?>;	
	/*** End Global Configuration ***/
	
	/*** Configure Slideshow Options ***/
		this.slideInterval		= <?php echo $lytebox_slideInterval; ?>;		
		this.showNavigation		= <?php echo $lytebox_showNavigation; ?>;	
		this.showClose			= <?php echo $lytebox_showClose; ?>;	
		this.showDetails		= <?php echo $lytebox_showDetails; ?>;	
		this.showPlayPause		= <?php echo $lytebox_showPlayPause; ?>;	
		this.autoEnd			= <?php echo $lytebox_autoEnd; ?>;	
		this.pauseOnNextClick		= <?php echo $lytebox_pauseOnNextClick; ?>;
		this.pauseOnPrevClick 		= <?php echo $lytebox_pauseOnPrevClick; ?>;	
	/*** End Slideshow Configuration ***/
	
	this.theme			= 'grey';
	
	if(this.resizeSpeed > 10) { this.resizeSpeed = 10; }
	if(this.resizeSpeed < 1) { resizeSpeed = 1; }
	this.resizeDuration = (11 - this.resizeSpeed) * 0.15;
	this.resizeWTimerArray		= new Array();
	this.resizeWTimerCount		= 0;
	this.resizeHTimerArray		= new Array();
	this.resizeHTimerCount		= 0;
	this.showContentTimerArray	= new Array();
	this.showContentTimerCount	= 0;
	this.overlayTimerArray		= new Array();
	this.overlayTimerCount		= 0;
	this.imageTimerArray		= new Array();
	this.imageTimerCount		= 0;
	this.timerIDArray			= new Array();
	this.timerIDCount			= 0;
	this.slideshowIDArray		= new Array();
	this.slideshowIDCount		= 0;
	this.imageArray	 = new Array();
	this.activeImage = null;
	this.slideArray	 = new Array();
	this.activeSlide = null;
	this.frameArray	 = new Array();
	this.activeFrame = null;
	this.checkFrame();
	this.isSlideshow = false;
	this.isLyteframe = false;
	/*@cc_on
		/*@if (@_jscript)
			this.ie = (document.all && !window.opera) ? true : false;
		/*@else @*/
			this.ie = false;
		/*@end
	@*/
	this.ie7 = (this.ie && window.XMLHttpRequest);	
	this.initialize();
}
LyteBox.prototype.initialize = function() {
	this.updateLyteboxItems();
	var objBody = this.doc.getElementsByTagName("body").item(0);	
	if (this.doc.getElementById('lbOverlay')) {
		objBody.removeChild(this.doc.getElementById("lbOverlay"));
		objBody.removeChild(this.doc.getElementById("lbMain"));
	}
	var objOverlay = this.doc.createElement("div");
		objOverlay.setAttribute('id','lbOverlay');
		objOverlay.setAttribute((this.ie ? 'className' : 'class'), this.theme);
		if ((this.ie && !this.ie7) || (this.ie7 && this.doc.compatMode == 'BackCompat')) {
			objOverlay.style.position = 'absolute';
		}
		objOverlay.style.display = 'none';
		objBody.appendChild(objOverlay);
	var objLytebox = this.doc.createElement("div");
		objLytebox.setAttribute('id','lbMain');
		objLytebox.style.display = 'none';
		objBody.appendChild(objLytebox);
	var objOuterContainer = this.doc.createElement("div");
		objOuterContainer.setAttribute('id','lbOuterContainer');
		objOuterContainer.setAttribute((this.ie ? 'className' : 'class'), this.theme);
		objLytebox.appendChild(objOuterContainer);
	var objIframeContainer = this.doc.createElement("div");
		objIframeContainer.setAttribute('id','lbIframeContainer');
		objIframeContainer.style.display = 'none';
		objOuterContainer.appendChild(objIframeContainer);
	var objIframe = this.doc.createElement("iframe");
		objIframe.setAttribute('id','lbIframe');
		objIframe.setAttribute('name','lbIframe');
		objIframe.style.display = 'none';
		objIframeContainer.appendChild(objIframe);
	var objImageContainer = this.doc.createElement("div");
		objImageContainer.setAttribute('id','lbImageContainer');
		objOuterContainer.appendChild(objImageContainer);
	var objLyteboxImage = this.doc.createElement("img");
		objLyteboxImage.setAttribute('id','lbImage');
		objImageContainer.appendChild(objLyteboxImage);
	var objLoading = this.doc.createElement("div");
		objLoading.setAttribute('id','lbLoading');
		objOuterContainer.appendChild(objLoading);
	var objDetailsContainer = this.doc.createElement("div");
		objDetailsContainer.setAttribute('id','lbDetailsContainer');
		objDetailsContainer.setAttribute((this.ie ? 'className' : 'class'), this.theme);
		objLytebox.appendChild(objDetailsContainer);
	var objDetailsData =this.doc.createElement("div");
		objDetailsData.setAttribute('id','lbDetailsData');
		objDetailsData.setAttribute((this.ie ? 'className' : 'class'), this.theme);
		objDetailsContainer.appendChild(objDetailsData);
	var objDetails = this.doc.createElement("div");
		objDetails.setAttribute('id','lbDetails');
		objDetailsData.appendChild(objDetails);
	var objCaption = this.doc.createElement("span");
		objCaption.setAttribute('id','lbCaption');
		objDetails.appendChild(objCaption);
	var objHoverNav = this.doc.createElement("div");
		objHoverNav.setAttribute('id','lbHoverNav');
		objImageContainer.appendChild(objHoverNav);
	var objBottomNav = this.doc.createElement("div");
		objBottomNav.setAttribute('id','lbBottomNav');
		objDetailsData.appendChild(objBottomNav);
	var objPrev = this.doc.createElement("a");
		objPrev.setAttribute('id','lbPrev');
		objPrev.setAttribute((this.ie ? 'className' : 'class'), this.theme);
		objPrev.setAttribute('href','#');
		objHoverNav.appendChild(objPrev);
	var objNext = this.doc.createElement("a");
		objNext.setAttribute('id','lbNext');
		objNext.setAttribute((this.ie ? 'className' : 'class'), this.theme);
		objNext.setAttribute('href','#');
		objHoverNav.appendChild(objNext);
	var objNumberDisplay = this.doc.createElement("span");
		objNumberDisplay.setAttribute('id','lbNumberDisplay');
		objDetails.appendChild(objNumberDisplay);
	var objNavDisplay = this.doc.createElement("span");
		objNavDisplay.setAttribute('id','lbNavDisplay');
		objNavDisplay.style.display = 'none';
		objDetails.appendChild(objNavDisplay);
	var objClose = this.doc.createElement("a");
		objClose.setAttribute('id','lbClose');
		objClose.setAttribute((this.ie ? 'className' : 'class'), this.theme);
		objClose.setAttribute('href','#');
		objBottomNav.appendChild(objClose);
	var objPause = this.doc.createElement("a");
		objPause.setAttribute('id','lbPause');
		objPause.setAttribute((this.ie ? 'className' : 'class'), this.theme);
		objPause.setAttribute('href','#');
		objPause.style.display = 'none';
		objBottomNav.appendChild(objPause);
	var objPlay = this.doc.createElement("a");
		objPlay.setAttribute('id','lbPlay');
		objPlay.setAttribute((this.ie ? 'className' : 'class'), this.theme);
		objPlay.setAttribute('href','#');
		objPlay.style.display = 'none';
		objBottomNav.appendChild(objPlay);
};
LyteBox.prototype.updateLyteboxItems = function() {	
	var anchors = (this.isFrame) ? window.parent.frames[window.name].document.getElementsByTagName('a') : document.getElementsByTagName('a');
	for (var i = 0; i < anchors.length; i++) {
		var anchor = anchors[i];
		var relAttribute = String(anchor.getAttribute('rel'));
		if (anchor.getAttribute('href')) {
			if (relAttribute.toLowerCase().match('lytebox')) {
				anchor.onclick = function () { myLytebox.start(this, false, false); return false; }
			} else if (relAttribute.toLowerCase().match('lyteshow')) {
				anchor.onclick = function () { myLytebox.start(this, true, false); return false; }
			} else if (relAttribute.toLowerCase().match('lyteframe')) {
				anchor.onclick = function () { myLytebox.start(this, false, true); return false; }
			}
		}
	}
};
LyteBox.prototype.start = function(imageLink, doSlide, doFrame) {
	if (this.ie && !this.ie7) {	this.toggleSelects('hide');	}
	if (this.hideFlash) { this.toggleFlash('hide'); }
	this.isLyteframe = (doFrame ? true : false);
	var pageSize	= this.getPageSize();
	var objOverlay	= this.doc.getElementById('lbOverlay');
	var objBody		= this.doc.getElementsByTagName("body").item(0);
	objOverlay.style.height = pageSize[1] + "px";
	objOverlay.style.display = '';
	this.appear('lbOverlay', (this.doAnimations ? 0 : this.maxOpacity));
	var anchors = (this.isFrame) ? window.parent.frames[window.name].document.getElementsByTagName('a') : document.getElementsByTagName('a');
	if (this.isLyteframe) {
		this.frameArray = [];
		this.frameNum = 0;
		if ((imageLink.getAttribute('rel') == 'lyteframe')) {
			var rev = imageLink.getAttribute('rev');
			this.frameArray.push(new Array(imageLink.getAttribute('href'), imageLink.getAttribute('title'), (rev == null || rev == '' ? 'width: 400px; height: 400px; scrolling: auto;' : rev)));
		} else {
			if (imageLink.getAttribute('rel').indexOf('lyteframe') != -1) {
				for (var i = 0; i < anchors.length; i++) {
					var anchor = anchors[i];
					if (anchor.getAttribute('href') && (anchor.getAttribute('rel') == imageLink.getAttribute('rel'))) {
						var rev = anchor.getAttribute('rev');
						this.frameArray.push(new Array(anchor.getAttribute('href'), anchor.getAttribute('title'), (rev == null || rev == '' ? 'width: 400px; height: 400px; scrolling: auto;' : rev)));
					}
				}
				this.frameArray.removeDuplicates();
				while(this.frameArray[this.frameNum][0] != imageLink.getAttribute('href')) { this.frameNum++; }
			}
		}
	} else {
		this.imageArray = [];
		this.imageNum = 0;
		this.slideArray = [];
		this.slideNum = 0;
		if ((imageLink.getAttribute('rel') == 'lytebox')) {
			this.imageArray.push(new Array(imageLink.getAttribute('href'), imageLink.getAttribute('title')));
		} else {
			if (imageLink.getAttribute('rel').indexOf('lytebox') != -1) {
				for (var i = 0; i < anchors.length; i++) {
					var anchor = anchors[i];
					if (anchor.getAttribute('href') && (anchor.getAttribute('rel') == imageLink.getAttribute('rel'))) {
						this.imageArray.push(new Array(anchor.getAttribute('href'), anchor.getAttribute('title')));
					}
				}
				this.imageArray.removeDuplicates();
				while(this.imageArray[this.imageNum][0] != imageLink.getAttribute('href')) { this.imageNum++; }
			}
			if (imageLink.getAttribute('rel').indexOf('lyteshow') != -1) {
				for (var i = 0; i < anchors.length; i++) {
					var anchor = anchors[i];
					if (anchor.getAttribute('href') && (anchor.getAttribute('rel') == imageLink.getAttribute('rel'))) {
						this.slideArray.push(new Array(anchor.getAttribute('href'), anchor.getAttribute('title')));
					}
				}
				this.slideArray.removeDuplicates();
				while(this.slideArray[this.slideNum][0] != imageLink.getAttribute('href')) { this.slideNum++; }
			}
		}
	}
	var object = this.doc.getElementById('lbMain');
		object.style.top = (this.getPageScroll() + (pageSize[3] / 15)) + "px";
		object.style.display = '';
	if (!this.outerBorder) {
		this.doc.getElementById('lbOuterContainer').style.border = 'none';
		this.doc.getElementById('lbDetailsContainer').style.border = 'none';
	} else {
		this.doc.getElementById('lbOuterContainer').style.borderBottom = '';
		this.doc.getElementById('lbOuterContainer').setAttribute((this.ie ? 'className' : 'class'), this.theme);
	}
	this.doc.getElementById('lbOverlay').onclick = function() { myLytebox.end(); return false; }
	this.doc.getElementById('lbMain').onclick = function(e) {
		var e = e;
		if (!e) {
			if (window.parent.frames[window.name] && (parent.document.getElementsByTagName('frameset').length <= 0)) {
				e = window.parent.window.event;
			} else {
				e = window.event;
			}
		}
		var id = (e.target ? e.target.id : e.srcElement.id);
		if (id == 'lbMain') { myLytebox.end(); return false; }
	}
	this.doc.getElementById('lbClose').onclick = function() { myLytebox.end(); return false; }
	this.doc.getElementById('lbPause').onclick = function() { myLytebox.togglePlayPause("lbPause", "lbPlay"); return false; }
	this.doc.getElementById('lbPlay').onclick = function() { myLytebox.togglePlayPause("lbPlay", "lbPause"); return false; }	
	this.isSlideshow = doSlide;
	this.isPaused = (this.slideNum != 0 ? true : false);
	if (this.isSlideshow && this.showPlayPause && this.isPaused) {
		this.doc.getElementById('lbPlay').style.display = '';
		this.doc.getElementById('lbPause').style.display = 'none';
	}
	if (this.isLyteframe) {
		this.changeContent(this.frameNum);
	} else {
		if (this.isSlideshow) {
			this.changeContent(this.slideNum);
		} else {
			this.changeContent(this.imageNum);
		}
	}
};
LyteBox.prototype.changeContent = function(imageNum) {
	if (this.isSlideshow) {
		for (var i = 0; i < this.slideshowIDCount; i++) { window.clearTimeout(this.slideshowIDArray[i]); }
	}
	this.activeImage = this.activeSlide = this.activeFrame = imageNum;
	if (!this.outerBorder) {
		this.doc.getElementById('lbOuterContainer').style.border = 'none';
		this.doc.getElementById('lbDetailsContainer').style.border = 'none';
	} else {
		this.doc.getElementById('lbOuterContainer').style.borderBottom = '';
		this.doc.getElementById('lbOuterContainer').setAttribute((this.ie ? 'className' : 'class'), this.theme);
	}
	this.doc.getElementById('lbLoading').style.display = '';
	this.doc.getElementById('lbImage').style.display = 'none';
	this.doc.getElementById('lbIframe').style.display = 'none';
	this.doc.getElementById('lbPrev').style.display = 'none';
	this.doc.getElementById('lbNext').style.display = 'none';
	this.doc.getElementById('lbIframeContainer').style.display = 'none';
	this.doc.getElementById('lbDetailsContainer').style.display = 'none';
	this.doc.getElementById('lbNumberDisplay').style.display = 'none';
	if (this.navType == 2 || this.isLyteframe) {
		object = this.doc.getElementById('lbNavDisplay');
		object.innerHTML = '&nbsp;&nbsp;&nbsp;<span id="lbPrev2_Off" style="display: none;" class="' + this.theme + '">&laquo; prev</span><a href="#" id="lbPrev2" class="' + this.theme + '" style="display: none;">&laquo; prev</a> <b id="lbSpacer" class="' + this.theme + '">||</b> <span id="lbNext2_Off" style="display: none;" class="' + this.theme + '">next &raquo;</span><a href="#" id="lbNext2" class="' + this.theme + '" style="display: none;">next &raquo;</a>';
		object.style.display = 'none';
	}
	if (this.isLyteframe) {
		var iframe = myLytebox.doc.getElementById('lbIframe');
		var styles = this.frameArray[this.activeFrame][2];
		var aStyles = styles.split(';');
		for (var i = 0; i < aStyles.length; i++) {
			if (aStyles[i].indexOf('width:') >= 0) {
				var w = aStyles[i].replace('width:', '');
				iframe.width = w.trim();
			} else if (aStyles[i].indexOf('height:') >= 0) {
				var h = aStyles[i].replace('height:', '');
				iframe.height = h.trim();
			} else if (aStyles[i].indexOf('scrolling:') >= 0) {
				var s = aStyles[i].replace('scrolling:', '');
				iframe.scrolling = s.trim();
			} else if (aStyles[i].indexOf('border:') >= 0) {
				// Not implemented yet, as there are cross-platform issues with setting the border (from a GUI standpoint)
				//var b = aStyles[i].replace('border:', '');
				//iframe.style.border = b.trim();
			}
		}
		this.resizeContainer(parseInt(iframe.width), parseInt(iframe.height));
	} else {
		imgPreloader = new Image();
		imgPreloader.onload = function() {
			var imageWidth = imgPreloader.width;
			var imageHeight = imgPreloader.height;
			if (myLytebox.autoResize) {
				var pagesize = myLytebox.getPageSize();
				var x = pagesize[2] - 150;
				var y = pagesize[3] - 150;
				if (imageWidth > x) {
					imageHeight = Math.round(imageHeight * (x / imageWidth));
					imageWidth = x; 
					if (imageHeight > y) { 
						imageWidth = Math.round(imageWidth * (y / imageHeight));
						imageHeight = y; 
					}
				} else if (imageHeight > y) { 
					imageWidth = Math.round(imageWidth * (y / imageHeight));
					imageHeight = y; 
					if (imageWidth > x) {
						imageHeight = Math.round(imageHeight * (x / imageWidth));
						imageWidth = x;
					}
				}
			}
			var lbImage = myLytebox.doc.getElementById('lbImage')
			lbImage.src = (myLytebox.isSlideshow ? myLytebox.slideArray[myLytebox.activeSlide][0] : myLytebox.imageArray[myLytebox.activeImage][0]);
			lbImage.width = imageWidth;
			lbImage.height = imageHeight;
			myLytebox.resizeContainer(imageWidth, imageHeight);
			imgPreloader.onload = function() {};
		}
		imgPreloader.src = (this.isSlideshow ? this.slideArray[this.activeSlide][0] : this.imageArray[this.activeImage][0]);
	}
};
LyteBox.prototype.resizeContainer = function(imgWidth, imgHeight) {
	this.wCur = this.doc.getElementById('lbOuterContainer').offsetWidth;
	this.hCur = this.doc.getElementById('lbOuterContainer').offsetHeight;
	this.xScale = ((imgWidth  + (this.borderSize * 2)) / this.wCur) * 100;
	this.yScale = ((imgHeight  + (this.borderSize * 2)) / this.hCur) * 100;
	var wDiff = (this.wCur - this.borderSize * 2) - imgWidth;
	var hDiff = (this.hCur - this.borderSize * 2) - imgHeight;
	if (!(hDiff == 0)) {
		this.hDone = false;
		this.resizeH('lbOuterContainer', this.hCur, imgHeight + this.borderSize*2, this.getPixelRate(this.hCur, imgHeight));
	} else {
		this.hDone = true;
	}
	if (!(wDiff == 0)) {
		this.wDone = false;
		this.resizeW('lbOuterContainer', this.wCur, imgWidth + this.borderSize*2, this.getPixelRate(this.wCur, imgWidth));
	} else {
		this.wDone = true;
	}
	if ((hDiff == 0) && (wDiff == 0)) {
		if (this.ie){ this.pause(250); } else { this.pause(100); } 
	}
	this.doc.getElementById('lbPrev').style.height = imgHeight + "px";
	this.doc.getElementById('lbNext').style.height = imgHeight + "px";
	this.doc.getElementById('lbDetailsContainer').style.width = (imgWidth + (this.borderSize * 2) + (this.ie && this.doc.compatMode == "BackCompat" && this.outerBorder ? 2 : 0)) + "px";
	this.showContent();
};
LyteBox.prototype.showContent = function() {
	if (this.wDone && this.hDone) {
		for (var i = 0; i < this.showContentTimerCount; i++) { window.clearTimeout(this.showContentTimerArray[i]); }
		if (this.outerBorder) {
			this.doc.getElementById('lbOuterContainer').style.borderBottom = 'none';
		}
		this.doc.getElementById('lbLoading').style.display = 'none';
		if (this.isLyteframe) {
			this.doc.getElementById('lbIframe').style.display = '';
			this.appear('lbIframe', (this.doAnimations ? 0 : 100));
		} else {
			this.doc.getElementById('lbImage').style.display = '';
			this.appear('lbImage', (this.doAnimations ? 0 : 100));
			this.preloadNeighborImages();
		}
		if (this.isSlideshow) {
			if(this.activeSlide == (this.slideArray.length - 1)) {
				if (this.autoEnd) {
					this.slideshowIDArray[this.slideshowIDCount++] = setTimeout("myLytebox.end('slideshow')", this.slideInterval);
				}
			} else {
				if (!this.isPaused) {
					this.slideshowIDArray[this.slideshowIDCount++] = setTimeout("myLytebox.changeContent("+(this.activeSlide+1)+")", this.slideInterval);
				}
			}
			this.doc.getElementById('lbHoverNav').style.display = (this.showNavigation && this.navType == 1 ? '' : 'none');
			this.doc.getElementById('lbClose').style.display = (this.showClose ? '' : 'none');
			this.doc.getElementById('lbDetails').style.display = (this.showDetails ? '' : 'none');
			this.doc.getElementById('lbPause').style.display = (this.showPlayPause && !this.isPaused ? '' : 'none');
			this.doc.getElementById('lbPlay').style.display = (this.showPlayPause && !this.isPaused ? 'none' : '');
			this.doc.getElementById('lbNavDisplay').style.display = (this.showNavigation && this.navType == 2 ? '' : 'none');
		} else {
			this.doc.getElementById('lbHoverNav').style.display = (this.navType == 1 && !this.isLyteframe ? '' : 'none');
			if ((this.navType == 2 && !this.isLyteframe && this.imageArray.length > 1) || (this.frameArray.length > 1 && this.isLyteframe)) {
				this.doc.getElementById('lbNavDisplay').style.display = '';
			} else {
				this.doc.getElementById('lbNavDisplay').style.display = 'none';
			}
			this.doc.getElementById('lbClose').style.display = '';
			this.doc.getElementById('lbDetails').style.display = '';
			this.doc.getElementById('lbPause').style.display = 'none';
			this.doc.getElementById('lbPlay').style.display = 'none';
		}
		this.doc.getElementById('lbImageContainer').style.display = (this.isLyteframe ? 'none' : '');
		this.doc.getElementById('lbIframeContainer').style.display = (this.isLyteframe ? '' : 'none');
		try {
			this.doc.getElementById('lbIframe').src = this.frameArray[this.activeFrame][0];
		} catch(e) { }
	} else {
		this.showContentTimerArray[this.showContentTimerCount++] = setTimeout("myLytebox.showContent()", 200);
	}
};
LyteBox.prototype.updateDetails = function() {
	var object = this.doc.getElementById('lbCaption');
	var sTitle = (this.isSlideshow ? this.slideArray[this.activeSlide][1] : (this.isLyteframe ? this.frameArray[this.activeFrame][1] : this.imageArray[this.activeImage][1]));
	object.style.display = '';
	object.innerHTML = (sTitle == null ? '' : sTitle);
	this.updateNav();
	this.doc.getElementById('lbDetailsContainer').style.display = '';
	object = this.doc.getElementById('lbNumberDisplay');
	if (this.isSlideshow && this.slideArray.length > 1) {
		object.style.display = '';
		object.innerHTML = "Image " + eval(this.activeSlide + 1) + " of " + this.slideArray.length;
		this.doc.getElementById('lbNavDisplay').style.display = (this.navType == 2 && this.showNavigation ? '' : 'none');
	} else if (this.imageArray.length > 1 && !this.isLyteframe) {
		object.style.display = '';
		object.innerHTML = "Image " + eval(this.activeImage + 1) + " of " + this.imageArray.length;
		this.doc.getElementById('lbNavDisplay').style.display = (this.navType == 2 ? '' : 'none');
	} else if (this.frameArray.length > 1 && this.isLyteframe) {
		object.style.display = '';
		object.innerHTML = "Page " + eval(this.activeFrame + 1) + " of " + this.frameArray.length;
		this.doc.getElementById('lbNavDisplay').style.display = '';
	} else {
		this.doc.getElementById('lbNavDisplay').style.display = 'none';
	}
	this.appear('lbDetailsContainer', (this.doAnimations ? 0 : 100));
};
LyteBox.prototype.updateNav = function() {
	if (this.isSlideshow) {
		if (this.activeSlide != 0) {
			var object = (this.navType == 2 ? this.doc.getElementById('lbPrev2') : this.doc.getElementById('lbPrev'));
				object.style.display = '';
				object.onclick = function() {
					if (myLytebox.pauseOnPrevClick) { myLytebox.togglePlayPause("lbPause", "lbPlay"); }
					myLytebox.changeContent(myLytebox.activeSlide - 1); return false;
				}
		} else {
			if (this.navType == 2) { this.doc.getElementById('lbPrev2_Off').style.display = ''; }
		}
		if (this.activeSlide != (this.slideArray.length - 1)) {
			var object = (this.navType == 2 ? this.doc.getElementById('lbNext2') : this.doc.getElementById('lbNext'));
				object.style.display = '';
				object.onclick = function() {
					if (myLytebox.pauseOnNextClick) { myLytebox.togglePlayPause("lbPause", "lbPlay"); }
					myLytebox.changeContent(myLytebox.activeSlide + 1); return false;
				}
		} else {
			if (this.navType == 2) { this.doc.getElementById('lbNext2_Off').style.display = ''; }
		}
	} else if (this.isLyteframe) {
		if(this.activeFrame != 0) {
			var object = this.doc.getElementById('lbPrev2');
				object.style.display = '';
				object.onclick = function() {
					myLytebox.changeContent(myLytebox.activeFrame - 1); return false;
				}
		} else {
			this.doc.getElementById('lbPrev2_Off').style.display = '';
		}
		if(this.activeFrame != (this.frameArray.length - 1)) {
			var object = this.doc.getElementById('lbNext2');
				object.style.display = '';
				object.onclick = function() {
					myLytebox.changeContent(myLytebox.activeFrame + 1); return false;
				}
		} else {
			this.doc.getElementById('lbNext2_Off').style.display = '';
		}		
	} else {
		if(this.activeImage != 0) {
			var object = (this.navType == 2 ? this.doc.getElementById('lbPrev2') : this.doc.getElementById('lbPrev'));
				object.style.display = '';
				object.onclick = function() {
					myLytebox.changeContent(myLytebox.activeImage - 1); return false;
				}
		} else {
			if (this.navType == 2) { this.doc.getElementById('lbPrev2_Off').style.display = ''; }
		}
		if(this.activeImage != (this.imageArray.length - 1)) {
			var object = (this.navType == 2 ? this.doc.getElementById('lbNext2') : this.doc.getElementById('lbNext'));
				object.style.display = '';
				object.onclick = function() {
					myLytebox.changeContent(myLytebox.activeImage + 1); return false;
				}
		} else {
			if (this.navType == 2) { this.doc.getElementById('lbNext2_Off').style.display = ''; }
		}
	}
	this.enableKeyboardNav();
};
LyteBox.prototype.enableKeyboardNav = function() { document.onkeydown = this.keyboardAction; };
LyteBox.prototype.disableKeyboardNav = function() { document.onkeydown = ''; };
LyteBox.prototype.keyboardAction = function(e) {
	var keycode = key = escape = null;
	keycode	= (e == null) ? event.keyCode : e.which;
	key		= String.fromCharCode(keycode).toLowerCase();
	escape  = (e == null) ? 27 : e.DOM_VK_ESCAPE;
	if ((key == 'x') || (key == 'c') || (keycode == escape)) {
		myLytebox.end();
	} else if ((key == 'p') || (keycode == 37)) {
		if (myLytebox.isSlideshow) {
			if(myLytebox.activeSlide != 0) {
				myLytebox.disableKeyboardNav();
				myLytebox.changeContent(myLytebox.activeSlide - 1);
			}
		} else if (myLytebox.isLyteframe) {
			if(myLytebox.activeFrame != 0) {
				myLytebox.disableKeyboardNav();
				myLytebox.changeContent(myLytebox.activeFrame - 1);
			}
		} else {
			if(myLytebox.activeImage != 0) {
				myLytebox.disableKeyboardNav();
				myLytebox.changeContent(myLytebox.activeImage - 1);
			}
		}
	} else if ((key == 'n') || (keycode == 39)) {
		if (myLytebox.isSlideshow) {
			if(myLytebox.activeSlide != (myLytebox.slideArray.length - 1)) {
				myLytebox.disableKeyboardNav();
				myLytebox.changeContent(myLytebox.activeSlide + 1);
			}
		} else if (myLytebox.isLyteframe) {
			if(myLytebox.activeFrame != (myLytebox.frameArray.length - 1)) {
				myLytebox.disableKeyboardNav();
				myLytebox.changeContent(myLytebox.activeFrame + 1);
			}
		} else {
			if(myLytebox.activeImage != (myLytebox.imageArray.length - 1)) {
				myLytebox.disableKeyboardNav();
				myLytebox.changeContent(myLytebox.activeImage + 1);
			}
		}
	}
};
LyteBox.prototype.preloadNeighborImages = function() {
	if (this.isSlideshow) {
		if ((this.slideArray.length - 1) > this.activeSlide) {
			preloadNextImage = new Image();
			preloadNextImage.src = this.slideArray[this.activeSlide + 1][0];
		}
		if(this.activeSlide > 0) {
			preloadPrevImage = new Image();
			preloadPrevImage.src = this.slideArray[this.activeSlide - 1][0];
		}
	} else {
		if ((this.imageArray.length - 1) > this.activeImage) {
			preloadNextImage = new Image();
			preloadNextImage.src = this.imageArray[this.activeImage + 1][0];
		}
		if(this.activeImage > 0) {
			preloadPrevImage = new Image();
			preloadPrevImage.src = this.imageArray[this.activeImage - 1][0];
		}
	}
};
LyteBox.prototype.togglePlayPause = function(hideID, showID) {
	if (this.isSlideshow && hideID == "lbPause") {
		for (var i = 0; i < this.slideshowIDCount; i++) { window.clearTimeout(this.slideshowIDArray[i]); }
	}
	this.doc.getElementById(hideID).style.display = 'none';
	this.doc.getElementById(showID).style.display = '';
	if (hideID == "lbPlay") {
		this.isPaused = false;
		if (this.activeSlide == (this.slideArray.length - 1)) {
			this.end();
		} else {
			this.changeContent(this.activeSlide + 1);
		}
	} else {
		this.isPaused = true;
	}
};
LyteBox.prototype.end = function(caller) {
	var closeClick = (caller == 'slideshow' ? false : true);
	if (this.isSlideshow && this.isPaused && !closeClick) { return; }
	this.disableKeyboardNav();
	this.doc.getElementById('lbMain').style.display = 'none';
	this.fade('lbOverlay', (this.doAnimations ? this.maxOpacity : 0));
	this.toggleSelects('visible');
	if (this.hideFlash) { this.toggleFlash('visible'); }
	if (this.isSlideshow) {
		for (var i = 0; i < this.slideshowIDCount; i++) { window.clearTimeout(this.slideshowIDArray[i]); }
	}
	if (this.isLyteframe) {
		 this.initialize();
	}
};
LyteBox.prototype.checkFrame = function() {
	if (window.parent.frames[window.name] && (parent.document.getElementsByTagName('frameset').length <= 0)) {
		this.isFrame = true;
		this.lytebox = "window.parent." + window.name + ".myLytebox";
		this.doc = parent.document;
	} else {
		this.isFrame = false;
		this.lytebox = "myLytebox";
		this.doc = document;
	}
};
LyteBox.prototype.getPixelRate = function(cur, img) {
	var diff = (img > cur) ? img - cur : cur - img;
	if (diff >= 0 && diff <= 100) { return 10; }
	if (diff > 100 && diff <= 200) { return 15; }
	if (diff > 200 && diff <= 300) { return 20; }
	if (diff > 300 && diff <= 400) { return 25; }
	if (diff > 400 && diff <= 500) { return 30; }
	if (diff > 500 && diff <= 600) { return 35; }
	if (diff > 600 && diff <= 700) { return 40; }
	if (diff > 700) { return 45; }
};
LyteBox.prototype.appear = function(id, opacity) {
	var object = this.doc.getElementById(id).style;
	object.opacity = (opacity / 100);
	object.MozOpacity = (opacity / 100);
	object.KhtmlOpacity = (opacity / 100);
	object.filter = "alpha(opacity=" + (opacity + 10) + ")";
	if (opacity == 100 && (id == 'lbImage' || id == 'lbIframe')) {
		try { object.removeAttribute("filter"); } catch(e) {}	/* Fix added for IE Alpha Opacity Filter bug. */
		this.updateDetails();
	} else if (opacity >= this.maxOpacity && id == 'lbOverlay') {
		for (var i = 0; i < this.overlayTimerCount; i++) { window.clearTimeout(this.overlayTimerArray[i]); }
		return;
	} else if (opacity >= 100 && id == 'lbDetailsContainer') {
		try { object.removeAttribute("filter"); } catch(e) {}	/* Fix added for IE Alpha Opacity Filter bug. */
		for (var i = 0; i < this.imageTimerCount; i++) { window.clearTimeout(this.imageTimerArray[i]); }
		this.doc.getElementById('lbOverlay').style.height = this.getPageSize()[1] + "px";
	} else {
		if (id == 'lbOverlay') {
			this.overlayTimerArray[this.overlayTimerCount++] = setTimeout("myLytebox.appear('" + id + "', " + (opacity+20) + ")", 1);
		} else {
			this.imageTimerArray[this.imageTimerCount++] = setTimeout("myLytebox.appear('" + id + "', " + (opacity+10) + ")", 1);
		}
	}
};
LyteBox.prototype.fade = function(id, opacity) {
	var object = this.doc.getElementById(id).style;
	object.opacity = (opacity / 100);
	object.MozOpacity = (opacity / 100);
	object.KhtmlOpacity = (opacity / 100);
	object.filter = "alpha(opacity=" + opacity + ")";
	if (opacity <= 0) {
		try {
			object.display = 'none';
		} catch(err) { }
	} else if (id == 'lbOverlay') {
		this.overlayTimerArray[this.overlayTimerCount++] = setTimeout("myLytebox.fade('" + id + "', " + (opacity-20) + ")", 1);
	} else {
		this.timerIDArray[this.timerIDCount++] = setTimeout("myLytebox.fade('" + id + "', " + (opacity-10) + ")", 1);
	}
};
LyteBox.prototype.resizeW = function(id, curW, maxW, pixelrate, speed) {
	if (!this.hDone) {
		this.resizeWTimerArray[this.resizeWTimerCount++] = setTimeout("myLytebox.resizeW('" + id + "', " + curW + ", " + maxW + ", " + pixelrate + ")", 100);
		return;
	}
	var object = this.doc.getElementById(id);
	var timer = speed ? speed : (this.resizeDuration/2);
	var newW = (this.doAnimations ? curW : maxW);
	object.style.width = (newW) + "px";
	if (newW < maxW) {
		newW += (newW + pixelrate >= maxW) ? (maxW - newW) : pixelrate;
	} else if (newW > maxW) {
		newW -= (newW - pixelrate <= maxW) ? (newW - maxW) : pixelrate;
	}
	this.resizeWTimerArray[this.resizeWTimerCount++] = setTimeout("myLytebox.resizeW('" + id + "', " + newW + ", " + maxW + ", " + pixelrate + ", " + (timer+0.02) + ")", timer+0.02);
	if (parseInt(object.style.width) == maxW) {
		this.wDone = true;
		for (var i = 0; i < this.resizeWTimerCount; i++) { window.clearTimeout(this.resizeWTimerArray[i]); }
	}
};
LyteBox.prototype.resizeH = function(id, curH, maxH, pixelrate, speed) {
	var timer = speed ? speed : (this.resizeDuration/2);
	var object = this.doc.getElementById(id);
	var newH = (this.doAnimations ? curH : maxH);
	object.style.height = (newH) + "px";
	if (newH < maxH) {
		newH += (newH + pixelrate >= maxH) ? (maxH - newH) : pixelrate;
	} else if (newH > maxH) {
		newH -= (newH - pixelrate <= maxH) ? (newH - maxH) : pixelrate;
	}
	this.resizeHTimerArray[this.resizeHTimerCount++] = setTimeout("myLytebox.resizeH('" + id + "', " + newH + ", " + maxH + ", " + pixelrate + ", " + (timer+.02) + ")", timer+.02);
	if (parseInt(object.style.height) == maxH) {
		this.hDone = true;
		for (var i = 0; i < this.resizeHTimerCount; i++) { window.clearTimeout(this.resizeHTimerArray[i]); }
	}
};
LyteBox.prototype.getPageScroll = function() {
	if (self.pageYOffset) {
		return this.isFrame ? parent.pageYOffset : self.pageYOffset;
	} else if (this.doc.documentElement && this.doc.documentElement.scrollTop){
		return this.doc.documentElement.scrollTop;
	} else if (document.body) {
		return this.doc.body.scrollTop;
	}
};
LyteBox.prototype.getPageSize = function() {	
	var xScroll, yScroll, windowWidth, windowHeight;
	if (window.innerHeight && window.scrollMaxY) {
		xScroll = this.doc.scrollWidth;
		yScroll = (this.isFrame ? parent.innerHeight : self.innerHeight) + (this.isFrame ? parent.scrollMaxY : self.scrollMaxY);
	} else if (this.doc.body.scrollHeight > this.doc.body.offsetHeight){
		xScroll = this.doc.body.scrollWidth;
		yScroll = this.doc.body.scrollHeight;
	} else {
		xScroll = this.doc.getElementsByTagName("html").item(0).offsetWidth;
		yScroll = this.doc.getElementsByTagName("html").item(0).offsetHeight;
		xScroll = (xScroll < this.doc.body.offsetWidth) ? this.doc.body.offsetWidth : xScroll;
		yScroll = (yScroll < this.doc.body.offsetHeight) ? this.doc.body.offsetHeight : yScroll;
	}
	if (self.innerHeight) {
		windowWidth = (this.isFrame) ? parent.innerWidth : self.innerWidth;
		windowHeight = (this.isFrame) ? parent.innerHeight : self.innerHeight;
	} else if (document.documentElement && document.documentElement.clientHeight) {
		windowWidth = this.doc.documentElement.clientWidth;
		windowHeight = this.doc.documentElement.clientHeight;
	} else if (document.body) {
		windowWidth = this.doc.getElementsByTagName("html").item(0).clientWidth;
		windowHeight = this.doc.getElementsByTagName("html").item(0).clientHeight;
		windowWidth = (windowWidth == 0) ? this.doc.body.clientWidth : windowWidth;
		windowHeight = (windowHeight == 0) ? this.doc.body.clientHeight : windowHeight;
	}
	var pageHeight = (yScroll < windowHeight) ? windowHeight : yScroll;
	var pageWidth = (xScroll < windowWidth) ? windowWidth : xScroll;
	return new Array(pageWidth, pageHeight, windowWidth, windowHeight);
};
LyteBox.prototype.toggleFlash = function(state) {
	var objects = this.doc.getElementsByTagName("object");
	for (var i = 0; i < objects.length; i++) {
		objects[i].style.visibility = (state == "hide") ? 'hidden' : 'visible';
	}
	var embeds = this.doc.getElementsByTagName("embed");
	for (var i = 0; i < embeds.length; i++) {
		embeds[i].style.visibility = (state == "hide") ? 'hidden' : 'visible';
	}
	if (this.isFrame) {
		for (var i = 0; i < parent.frames.length; i++) {
			try {
				objects = parent.frames[i].window.document.getElementsByTagName("object");
				for (var j = 0; j < objects.length; j++) {
					objects[j].style.visibility = (state == "hide") ? 'hidden' : 'visible';
				}
			} catch(e) { }
			try {
				embeds = parent.frames[i].window.document.getElementsByTagName("embed");
				for (var j = 0; j < embeds.length; j++) {
					embeds[j].style.visibility = (state == "hide") ? 'hidden' : 'visible';
				}
			} catch(e) { }
		}
	}
};
LyteBox.prototype.toggleSelects = function(state) {
	var selects = this.doc.getElementsByTagName("select");
	for (var i = 0; i < selects.length; i++ ) {
		selects[i].style.visibility = (state == "hide") ? 'hidden' : 'visible';
	}
	if (this.isFrame) {
		for (var i = 0; i < parent.frames.length; i++) {
			try {
				selects = parent.frames[i].window.document.getElementsByTagName("select");
				for (var j = 0; j < selects.length; j++) {
					selects[j].style.visibility = (state == "hide") ? 'hidden' : 'visible';
				}
			} catch(e) { }
		}
	}
};
LyteBox.prototype.pause = function(numberMillis) {
	var now = new Date();
	var exitTime = now.getTime() + numberMillis;
	while (true) {
		now = new Date();
		if (now.getTime() > exitTime) { return; }
	}
};
if (window.addEventListener) {
	window.addEventListener("load",initLytebox,false);
} else if (window.attachEvent) {
	window.attachEvent("onload",initLytebox);
} else {
	window.onload = function() {initLytebox();}
}
function initLytebox() { myLytebox = new LyteBox(); }


<?php	
}
?>

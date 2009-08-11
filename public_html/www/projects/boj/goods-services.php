<?php
$pageTitle = "Goods & Services";
$graphictop = "goods-services_02.jpg";
$graphicbottom = "goods-services_03.jpg";

$tagline = "In an age where burgers form the basis of a quick meal, it's good to know exactly where you can find a cracking good one - and a whole lot more gourmet treats.";

$valid = false;

if($_COOKIES['goodsservices'] == 1)
{
	$pageContent = 'Sorry, you can only submit this ballot once';
}else{
	if($_POST)
	{
		//check the form
		//if valid set to true,.. + set the cookie
		//setcookie('goodsservices','1', 2592000+time());
		//else append the pageContent
	}
	
	if(!$valid){
		//set our page content
	}
}
	require_once('tmpl.html');
	?>
	
<?php
$IGnotemplate = 1;
include('core/lib/IGcore.php');

$pageTitle = "Eats and Drinks";
$graphictop = "eats-drinks_01.jpg";
$graphicbottom = "eats-drinks_02.jpg";

$tagline = "In an age where burgers form the basis of a quick meal, it's good to know exactly where you can find a cracking good one - and a whole lot more gourmet treats.";

$valid = false;

if($_COOKIES['eatsdrinks'] == 1)
{
	$pageContent = 'Sorry, you can only submit this ballot once';
}else{
	if($_POST)
	{
		foreach($_POST as $key=>$value)
		{
			$post[$key] = addslashes($value);
			
		}
		$res = IGquery("Select count(email) as cnt from eats_2009 where email='" . $post['email'] . "'");
		if($res[0]['cnt'] > 0)
		{
			setcookie('eatsdrinks','1', 2592000+time());
			$pageContent = 'Sorry, you can only submit this ballot once';
			$valid = true;
		}else{
			//Ok form filled out, lets save it.
			$query = "Insert into eats_2009 (name,email,phone_number,Bakery,Beer,Homeindustry,Pizza,Biltong,Bottlestore,Foodmarket,Butcher,Slapchips,CoffeeBar,Readymade,Continental,Takeaway,Chocolate,TeaGarden,FishDeli,Greengrocer,Hamburger,createdate) values('{$post['name']}','{$post['email']}','{$post['phone_number']}','{$post['Bakery']}','{$post['Beer']}','{$post['Homeindustry']}','{$post['Pizza']}','{$post['Biltong']}','{$post['Bottlestore']}','{$post['Foodmarket']}','{$post['Butcher']}','{$post['Slapchips']}','{$post['CoffeeBar']}','{$post['Readymade']}','{$post['Continental']}','{$post['Takeaway']}','{$post['Chocolate']}','{$post['TeaGarden']}','{$post['FishDeli']}','{$post['Greengrocer']}','{$post['Hamburger']}','" . DATE("Y-m-d H:i:s") . "')";
			IGquery($query);
			$pageContent = 'Thank you for your submission';
			$valid = true;
			setcookie('eatsdrinks','1', 2592000+time());
			
		}
		//check the form
		//if valid set to true,.. + set the cookie
		//setcookie('eatsdrinks','1', 2592000+time());
		//else append the pageContent
	}

	if(!$valid){
   
    $js = '
	var dosubmit = false;
	var errormsgs = "";
	var filled = 0;
	
	if(document.getElementById("Bakery").value.length > 2)
	{
	  filled += 1;	
	}
	
	if(document.getElementById("Beer").value.length > 2)
	{
		filled += 1;	
	}
	
	if(document.getElementById("Homeindustry").value.length > 2)
	{
		filled += 1;	
	}
	
	if(document.getElementById("Pizza").value.length > 2)
	{
		filled += 1;	
		}
	if(document.getElementById("Biltong").value.length > 2)
	{
		filled += 1;	
	}
		if(document.getElementById("Bottlestore").value.length > 2)
		{
		filled += 1;	
		}
		
		if(document.getElementById("Foodmarket").value.length > 2)
		{
			filled += 1;	
		}
		
		if(document.getElementById("Butcher").value.length > 2)
		{
			filled += 1;	
		}
		
		if(document.getElementById("Slapchips").value.length > 2)
		{
			filled += 1;	
		}
		
		if(document.getElementById("CoffeeBar").value.length > 2)
		{
			filled += 1;	
		}
		
		if(document.getElementById("Readymade").value.length > 2)
		{
			filled += 1;	
		}
		
		if(document.getElementById("Continental").value.length > 2)
		{
			filled += 1;	
		}
		
		if(document.getElementById("Takeaway").value.length > 2)
		{
			filled += 1;	
		}
		
		if(document.getElementById("Chocolate").value.length > 2)
		{
		filled += 1;	
		}
		
		if(document.getElementById("TeaGarden").value.length > 2)
		{
			filled += 1;	
		}
		
		if(document.getElementById("FishDeli").value.length > 2)
		{
			filled += 1;	
			}
			
			if(document.getElementById("Greengrocer").value.length > 2)
			{
				filled += 1;	
			}
	
		if(document.getElementById("Hamburger").value.length > 2)
		{
			filled += 1;	
		}
		
	if(filled >= 14)
	{
		dosubmit = true;
	}else{
		errormsgs = "please complete at least 80% of the form"+ filled;
	}
		
	if(document.getElementById("name").value.length < 5 ||	document.getElementById("email").value.length < 5 || document.getElementById("phone_number").value.length < 5)
	{
		dosumit = false;
		errormsgs = "Please supply your name, email and phone number";
	}
		
	';
   
    //set our page content
	$pageContent .= '<form action="eatsdrinks.php" method="post" name="ballotform" id="ballotform">';
	$pageContent .= '<table width="100%">
	
	<tr>
		<td width="48%"><span class="inputlabel">Bakery</span></td>
		<td width="5%"></td>
		<td width="47%"></td>
	</tr>
	<tr>
		<td width="48%"><span class="inputtagline">Whether its simple, crusty rolls or prim and proper petit forus, where do you go?</span></td>
		<td width="5%"></td>
		<td width="47%"></td>
	</tr>
	<tr>
		<td width="48%"><input type="text" name="Bakery" id="Bakery"></td>
		<td width="5%"></td>
		<td width="47%"></td>
	</tr>
	
	
	<tr>
	<td width="48%"><span class="inputlabel">Beer to drink while watching the game</span></td>
	<td width="5%"></td>
	<td width="47%"><span class="inputlabel">Home industry Shop</span></td>
	</tr>
	<tr>
	<td width="48%"><span class="inputtagline">We\'re the Wold Cup Holders so there\'s always lots to celebrate on Saturdays!</span></td>
	<td width="5%"></td>
	<td width="47%"><span class="inputtagline">You need never to DIY if you top off here for a made-it-myself delish dish</span></td>
	</tr>
	<tr>
	<td width="48%"><input type="text" name="Beer" id="Beer"></td>
	<td width="5%"></td>
	<td width="47%"><input type="text" name="Homeindustry" id="Homeindustry"></td>
	</tr>
	
	<tr>
	<td width="48%"><span class="inputlabel">Biltong</span></td>
	<td width="5%"></td>
	<td width="47%"><span class="inputlabel">Pizza</span></td>
	</tr>
	<tr>
	<td width="48%"><span class="inputtagline">Peri peri or plain, there\'s nothing we would miss more if we didn\'t live in SA</span></td>
	<td width="5%"></td>
	<td width="47%"><span class="inputtagline">The base must be crispy, the mozzarella melted, the tomato perfect</span></td>
	</tr>
	<tr>
	<td width="48%"><input type="text" name="Biltong" id="Biltong"></td>
	<td width="5%"></td>
	<td width="47%"><input type="text" name="Pizza" id="Pizza"></td>
	</tr>
	
	<tr>
	<td width="48%"><span class="inputlabel">Bottle store</span></td>
	<td width="5%"></td>
	<td width="47%"><span class="inputlabel">Food market</span></td>
	</tr>
	<tr>
	<td width="48%"><span class="inputtagline">Pick up a cocktail mix or a bottle of findest reserve - at drinkable prices</span></td>
	<td width="5%"></td>
	<td width="47%"><span class="inputtagline">Fall in line with global trends and go green and always organic</span></td>
	</tr>
	<tr>
	<td width="48%"><input type="text" name="Bottlestore" id="Bottlestore"></td>
	<td width="5%"></td>
	<td width="47%"><input type="text" name="Foodmarket" id="Foodmarket"></td>
	</tr>
	
	<tr>
	<td width="48%"><span class="inputlabel">Butcher</span></td>
	<td width="5%"></td>
	<td width="47%"><span class="inputlabel">Slap chips</span></td>
	</tr>
	<tr>
	<td width="48%"><span class="inputtagline">Bag a top-notch weekend braai or a sophisticated dinner for the boss</span></td>
	<td width="5%"></td>
	<td width="47%"><span class="inputtagline">The best things in life aren\'t free ... of calories! Load \'em with salt and vinegar</span></td>
	</tr>
	<tr>
	<td width="48%"><input type="text" name="Butcher" id="Butcher"></td>
	<td width="5%"></td>
	<td width="47%"><input type="text" name="Slapchips" id="Slapchips"></td>
	</tr>
	
	<tr>
	<td width="48%"><span class="inputlabel">Coffee Bar</span></td>
	<td width="5%"></td>
	<td width="47%"><span class="inputlabel">Store to order ready-made meals for informal entertaining</span></td>
	</tr>
	<tr>
	<td width="48%"><span class="inputtagline">For early morning refuelling, you just can\'t beat the caffeine rush at...</span></td>
	<td width="5%"></td>
	<td width="47%"><span class="inputtagline">Raise the standard of a casual long-day-at-work supper by stopping off here</span></td>
	</tr>
	<tr>
	<td width="48%"><input type="text" name="CoffeeBar" id="CoffeeBar"></td>
	<td width="5%"></td>
	<td width="47%"><input type="text" name="Readymade" id="Readymade"></td>
	</tr>
	
	<tr>
	<td width="48%"><span class="inputlabel">Continental deli</span></td>
	<td width="5%"></td>
	<td width="47%"><span class="inputlabel">Takeaway (adult food)</span></td>
	</tr>
	<tr>
	<td width="48%"><span class="inputtagline">You\'ve read all about it, now find exotic ingredients, like squid ink and gold leaf</span></td>
	<td width="5%"></td>
	<td width="47%"><span class="inputtagline">Even grown-ups have to stop off for a q	uick munch on the run. Where do you go?</span></td>
	</tr>
	<tr>
	<td width="48%"><input type="text" name="Continental" id="Continental"></td>
	<td width="5%"></td>
	<td width="47%"><input type="text" name="Takeaway" id="Takeaway"></td>
	</tr>
	
	<tr>
	<td width="48%"><span class="inputlabel">Chocolate</span></td>
	<td width="5%"></td>
	<td width="47%"><span class="inputlabel">Tea garden</span></td>
	</tr>
	<tr>
	<td width="48%"><span class="inputtagline">What\'s the chocolate bar that defies your best attempts at dieting?</span></td>
	<td width="5%"></td>
	<td width="47%"><span class="inputtagline">It\'s the perfect spot for children to run and mother-in-laws to be impressed</span></td>
	</tr>
	<tr>
	<td width="48%"><input type="text" name="Chocolate" id="Chocolate"></td>
	<td width="5%"></td>
	<td width="47%"><input type="text" name="TeaGarden" id="TeaGarden"></td>
	</tr>
	
	<tr>
	<td width="48%"><span class="inputlabel">Fish deli</span></td>
	<td width="5%"></td>
	<td width="47%"><span class="inputlabel"></span></td>
	</tr>
	<tr>
	<td width="48%"><span class="inputtagline">OD on Omega 3 and load up with everyghing from simple hake to oysters</span></td>
	<td width="5%"></td>
	<td width="47%"><span class="inputtagline"></span></td>
	</tr>
	<tr>
	<td width="48%"><input type="text" name="FishDeli" id="FishDeli"></td>
	<td width="5%"></td>
	<td width="47%"></td>
	</tr>
	
	<tr>
	<td width="48%"><span class="inputlabel">Greengrocer</span></td>
	<td width="5%"></td>
	<td width="47%"><span class="inputlabel"></span></td>
	</tr>
	<tr>
	<td width="48%"><span class="inputtagline">Be eco-friendly and watch your food miles by buying seasonal, local produce</span></td>
	<td width="5%"></td>
	<td width="47%"><span class="inputtagline"></span></td>
	</tr>
	<tr>
	<td width="48%"><input type="text" name="Greengrocer" id="Greengrocer"></td>
	<td width="5%"></td>
	<td width="47%"></td>
	</tr>
	
	
	<tr>
	<td width="48%"><span class="inputlabel">Hamburger</span></td>
	<td width="5%"></td>
	<td width="47%"><span class="inputlabel"></span></td>
	</tr>
	<tr>
	<td width="48%"><span class="inputtagline">Forget processed and think pure beef, loaded with unusual toppings</span></td>
	<td width="5%"></td>
	<td width="47%"><span class="inputtagline"></span></td>
	</tr>
	<tr>
	<td width="48%"><input type="text" name="Hamburger" id="Hamburger"></td>
	<td width="5%"></td>
	<td width="47%"></td>
	</tr>

	
	';
	
	
	$pageContent .= '</table>';
   }
}
require_once('tmpl.html');
?>

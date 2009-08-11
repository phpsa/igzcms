<?php

class Block_Login
{
	
	public function __construct()
	{
	}
	
	public function drawBlock($params, $block)
	{
		if($params->user->id < 1)
		{
			return $this->drawLogin($params, $block);
		}else{
			return $this->drawLogout($params,$block);
		}
	}
	
	public function drawLogout($params, $block)
	{
		$user = $params->user;
		$html = '';
		$html .= '<div class="' . strtolower($block) . '_block"><ul>
		Welcome ' . $user->username . '</li>
		<li><a href="/user/profile/edit">Edit Profile</a></li>
		<li><A href="/user/profile/logout" >Logout</a></li></ul></div>';
		return $html;
	}
	
	public function drawLogin($params, $block)
	{
		$html = '';
		$html .= '<div class="' . strtolower($block) . '_block"><Form action="/user/login/" method="post">Username:<br /><input type="text" name="username"><br />Password:<br /><input type="password" name="password"><br /><input type="submit" value="Login"></form>
		<ul><li><a href="/user/profile/forgot">Forgot Password</a></li>
		<li><a href="/user/profile/register">Create Account</a></li></ul></div>';
		return $html;
	}
}
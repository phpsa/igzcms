<?php

Class Form_Register extends Ig_Form
{
	public function init()
	{
		$this->setAction('/user/profile/register')
		->setMethod('post');
		$this->setAttrib('name', 'register');
		
		$this->addElement(
			'validateText',
			'name',
			array(
				'label' 		=> $this->translate('fullName'),
				'formId'		=> 'register',
				'trim'			=> true,
				'required' 		=> true,
				'validators'  	=> array(
					array(
						'validator' 	=> 'StringLength', 
						'options' 		=> array(5, 15)
					)
				),
				'class'	=>	'required',
				'js'	=> array(
					'minlength'	=> '5',
					'maxlength'	=>	'15',
					'class'	=> 'required input',
					
					
				),
			)
		);
		
		$this->addElement(
			'validateText',
			'username',
			array(
				'label' 		=> $this->translate('userName'),
				'formId'		=> 'register',
				'trim'			=> true,
				'required' 		=> true,
				'validators'  	=> array(
					array(
						'validator' 	=> 'StringLength', 
						 'options' 		=> array(5, 15)
					)
				),
				'js'	=> array(
					'minlength'	=> '5',
					'maxlength'	=>	'15',
					'class'	=> 'required',
					'remote'	=> '/user/ajax/checkuser/',
				),
			)
		);
		
		$this->addElement(
			'validateText',
			'email',
			array(
				'label' 		=> $this->translate('Email Address'),
				'formId'		=> 'register',
				'trim'			=> true,
				'required' 		=> true,
				'validators'  	=> array(
					array(
					'EmailAddress'	=> 'EmailAddress'
					 )
				),
				'js'	=> array(
					'class'	=> 'required email',
					//'remote'	=> '/user/ajax/checkemail/',
					 ),
				)
		);
		
		$this->addElement(
			'validateText',
			'confirm_email',
			array(
				'label' 		=> $this->translate('Confirm Email Address'),
				'formId'		=> 'register',
				'trim'			=> true,
				'required' 		=> true,
				'validators'  	=> array(
					array(
					'EmailAddress'	=> 'EmailAddress',
					)
				),
				'js'	=> array(
					'class'	=> 'required email',
					'equalTo' => '#email'
				),
			)
		);
		
		$this->addElement(
			'validateText',
			'password',
			array(
				'label' 		=> 'Select Password',
				'formId'		=> 'register',
				'trim'			=> true,
				'required' 		=> true,
				'password'	=> true,
				'validators'  	=> array(
					array(
						'validator' 	=> 'StringLength', 
						'options' 		=> array(5, 15)
					 )
				),
				'js'	=> array(
					'class'	=> 'required password',
					'minlength' => 5,
					'maxlength' => 15,
					 ),
				)
		);
		
		$this->addElement(
			'validateText',
			'confirm_password',
			array(
				'label' 		=> 'Confirm Password',
				'formId'		=> 'register',
				'trim'			=> true,
				'required' 		=> true,
				'password'	=> true,
				'validators'  	=> array(
					array(
						'validator' 	=> 'StringLength', 
						'options' 		=> array(5, 15)
					 )
				),
				'js'	=> array(
					'class'	=> 'required password',
					'minlength' => 5,
					'maxlength' => 15,
					'equalTo' => '#password',
					 ),
				)
		);
		
		 $captcha = new Zend_Form_Element_Captcha(  
		         'captcha', 
		         array('label' => 'Write the chars to the field',  
					'captcha' => array( 
						'captcha' => 'Image',
						'wordLen' => 6,
						'timeout' => 300,
						'font' => '/var/Library/Application/_fonts/Vera.ttf',
						'imgDir' => BASE_PATH .'/images/captcha/',
						),
					'class' => 'required padmetop',
					'minlength' => 6,
					)
				);  
		        
		 
		$this->addElement($captcha);
		/*
		# $captcha = new Zend_Form_Element_Captcha(  
		#         'captcha', // This is the name of the input field  
		#         array('label' => 'Write the chars to the field',  
		#         'captcha' => array( // Here comes the magic...  
		#         // First the type...  
		#         'captcha' => 'Image',  
		#         // Length of the word...  
		#         'wordLen' => 6, ver 
		#         // Captcha timeout, 5 mins  
		#         'timeout' => 300,  
		#         // What font to use...  
		#         'font' => '/path/to/font/FontName.ttf',  
		#         // Where to put the image  
		#         'imgDir' => '/var/www/project/public/captcha/',  
		#         // URL to the images  
		#         // This was bogus, here's how it should be... Sorry again :S  
		#         'imgUrl' => 'http://project.com/captcha/',  
		# )));  
		*/
										 
		$this->addElement('submit','submit',array('label'=>'Register'));
		
	}
	
}

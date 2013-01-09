<?php
class Telephone_Mail_Emailer extends Zend_Mail
{
  	public function init()
  	{    
	}
	
	public function sendMail($values)
	{	$view = new Zend_View();						
		$html_body = $this->getMailContent($values);
        $this->setBodyHtml($html_body);       					
		$this->setFrom('incomingtelephone@mediaversa.nl', 'Incoming Telephone');
		$this->addTo($values['Telephone_Model_Users']['username']); 
		$this->setSubject('New Incoming Telephone from: '.$values['who_called_name']);
		$this->send();
	}  
	
	public function getMailContent($values) 
	{   // create a view renderer and set it to app_path/emails/
		$renderer = new Zend_View();
		$renderer->setScriptPath(realpath(APPLICATION_PATH . '/emails/'));
	 
		// create a layout object and set it to app_path/emails/layouts
		$layout = new Zend_Layout(APPLICATION_PATH . '/emails/layouts/');
		$layout->setView($renderer); 
	 
		// assign all values to the view renderer		
	 	$renderer->item = $values;	 	
		
		// render html assign to output
		$layout->content = $renderer->render('email.phtml');
		$layout->setLayout('email');
		$output = $layout->render();
	 
		// all done, return output
		return $output;	 
	}
}
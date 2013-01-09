<?php
class Telephone_Form_IncomingCreate extends Zend_Form
{
  public function init()
  {
    // initialize form
    $this->setAction('/incoming/create')
         ->setMethod('post');
    
	 
	// create text input for who called name 
    $who_called_name = new Zend_Form_Element_Text('who_called_name');
    $who_called_name->setLabel('Who Called Name:')
         ->setOptions(array('size' => '35'))
         ->setRequired(true)
         ->addValidator('Regex', false, array(
            'pattern' => '/^[a-zA-Z]+[A-Za-z\'\-\. ]{1,200}$/'
           ))            
         ->addFilter('HtmlEntities')            
         ->addFilter('StringTrim');  
	
	// create text input for who called company
    $who_called_company = new Zend_Form_Element_Textarea('who_called_company');
    $who_called_company->setLabel('Who Called Company:')        
         ->setRequired(true)        
		 ->setAttrib('COLS', '40')
    	->setAttrib('ROWS', '4')            
         ->addFilter('HtmlEntities')            
         ->addFilter('StringTrim');    
		           
		           
    // create text input for description
	$description = new Zend_Form_Element_Textarea('description');
    $description->setLabel('Description:')         
         ->setRequired(true)
		  ->setAttrib('COLS', '40')
    	->setAttrib('ROWS', '4')                   
         ->addFilter('HtmlEntities')            
         ->addFilter('StringTrim');  
		   
	//create selec  for priority
	$priority = new Zend_Form_Element_Select('priority');
    $priority->setLabel('Priority:')  
					   ->setRequired(true)       
					  ->addFilter('HtmlEntities')            
                      ->addFilter('StringTrim');                        
      $priority->addMultiOptions(array('*'=>'*', '**'=>'**','***'=>'***','Urgent'=>'Urgent'));      	        
                         
    // create select input for item follow_up_action
    $follow_up_action = new Zend_Form_Element_Select('follow_up_action');
    $follow_up_action->setLabel('Follow up action:')
            ->setRequired(true)    
            ->addValidator('Int')            
            ->addFilter('HtmlEntities')            
            ->addFilter('StringTrim')            
            ->addFilter('StringToUpper'); 
    foreach ($this->getFollowUpAction() as $f) {
      $follow_up_action->addMultiOption($f['followupaction_id'], $f['name']);      
    }        

	 // create text input for follow_up_action_text
	$follow_up_action_text = new Zend_Form_Element_Textarea('follow_up_action_text');
    $follow_up_action_text->setLabel('Follow Up Text:')         
         ->setRequired(true)        
		 ->setAttrib('COLS', '40')
    	->setAttrib('ROWS', '4')            
         ->addFilter('HtmlEntities')            
         ->addFilter('StringTrim');  

 // create select input for item action for
    $action_for = new Zend_Form_Element_Select('action_for_user');
    $action_for->setLabel('Action For:')
            ->setRequired(true)    
            ->addValidator('Int')            
            ->addFilter('HtmlEntities')            
            ->addFilter('StringTrim')            
            ->addFilter('StringToUpper'); 
    foreach ($this->getUsers() as $f) {
      $action_for->addMultiOption($f['userid'], $f['name']);      
    }        
          
    // create submit button
    $submit = new Zend_Form_Element_Submit('submit');
    $submit->setLabel('Submit Entry')
           ->setOrder(100)
           ->setOptions(array('class' => 'submit'));
                
    // attach elements to form    
	$this->addElement($who_called_name)
		->addElement($who_called_company)
        ->addElement($description)
		->addElement($priority)		 
		->addElement($follow_up_action)
		->addElement($follow_up_action_text)
		->addElement($action_for);
         
    // create display group for incoming telephone
    $this->addDisplayGroup(array('who_called_name', 'who_called_company', 'description', 'priority', 'follow_up_action', 'follow_up_action_text', 'action_for_user'), 'newcall');
    $this->getDisplayGroup('newcall')
         ->setOrder(10)
         ->setLegend('Incoming Telephone');         
         
    // attach element to form    
    $this->addElement($submit);    
  }
  
  public function getUsers() {
    $q = Doctrine_Query::create()
         ->from('Telephone_Model_Users u');   
    return $q->fetchArray();
  }

  public function getFollowUpAction() {
    $q = Doctrine_Query::create()
         ->from('Telephone_Model_FollowUpAction f');   
    return $q->fetchArray();
  }

      
}

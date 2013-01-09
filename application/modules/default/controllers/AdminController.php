<?php
class AdminController extends Zend_Controller_Action
{    
  public function init() 
  {
    $this->view->doctype('XHTML1_STRICT');
  }

  // action to handle admin URLs
  public function preDispatch() 
  {
    // set admin layout
    // check if user is authenticated
    // if not, redirect to login page
    $url = $this->getRequest()->getRequestUri();
    $this->_helper->layout->setLayout('admin');          
    if (!Zend_Auth::getInstance()->hasIdentity()) {
      $session = new Zend_Session_Namespace('telephone.auth');
      $session->requestURL = $url;
      $this->_redirect('/login');
    }
  }
  
  // action to display list of incoming telephones
  public function indexAction()
  {	$page = $this->getRequest()->getParam('page');
  	if($page=='history'){
		$donevar=1; 
		$this->view->theaction = 'incoming-update-doneundone'; // set as form action the route for done, because in history there's cheecked and checked		
	}
	else{
		$donevar=0;	
		$this->view->theaction = 'incoming-update-done'; // set as form action the route for doneundone
	}
	$this->view->page = $page;
    $q = Doctrine_Query::create()
          ->from('Telephone_Model_IncomingTelephone i')
		  ->leftJoin('i.Telephone_Model_FollowUpAction f')
          ->leftJoin('i.Telephone_Model_Users u')
		  ->where('i.done = '.$donevar);;
    $result = $q->fetchArray();
    $this->view->records = $result; 
	
	// this is used to show status messages at overview
	if ($this->_helper->getHelper('FlashMessenger')->getMessages()) {
      $this->view->messages = $this->_helper->getHelper('FlashMessenger')->getMessages();    
    }
	else
		$this->view->messages = array();	
  }
  
  // action to delete items after being confirmed
  public function deleteconfirmedAction()
  {	
    
    $filters = array(
        'id' => array('HtmlEntities', 'StripTags', 'StringTrim')
      );          
      $validators = array(
        'id' => array('NotEmpty', 'Int')
      );  
      $input = new Zend_Filter_Input($filters, $validators);
      $input->setData($this->getRequest()->getParams()); 
    
    // test if input is valid
    // read array of record identifiers
    // delete records from database
    if ($input->isValid()) {
      $q = Doctrine_Query::create()
            ->delete('Telephone_Model_IncomingTelephone i')
            ->where('i.incomingtelephone_id =?', $input->id);
      $result = $q->execute();               
      $this->_helper->getHelper('FlashMessenger')->addMessage('The record were successfully deleted.');
      $this->_redirect('/incoming/overview/index');
    } else {
      throw new Zend_Controller_Action_Exception('Invalid input');              
    }
  }
  //all in one update done
   public function updatedoneAction()
  {
    // set filters and validators for POST input
    $filters = array(
      'ids' => array('HtmlEntities', 'StripTags', 'StringTrim')
    );    
    $validators = array(
      'ids' => array('NotEmpty', 'Int')
    );
    $input = new Zend_Filter_Input($filters, $validators);
    $input->setData($this->getRequest()->getParams());
    
    // test if input is valid
    // read array of record identifiers
    // delete records from database
    if ($input->isValid()) {
      if($input->ids){	  		
			$q = Doctrine_Query::create()	
            ->update('Telephone_Model_IncomingTelephone i')
			->set('i.done','1')
            ->whereIn('i.incomingtelephone_id', $input->ids);			
      		$result = $q->execute(); 
			$this->_helper->getHelper('FlashMessenger')->addMessage('The records were successfully set as done.');
      		$this->_redirect('/incoming/overview/index');     
	  	}		         	        
    } else {
      throw new Zend_Controller_Action_Exception('Invalid input');              
    }
  }
  //all in one update undone and done because in history there's allready some checked as done.
   public function updatedoneundoneAction()
  {
    // set filters and validators for POST input
    $filters = array(
      'ids' => array('HtmlEntities', 'StripTags', 'StringTrim')
    );    
    $validators = array(
      'ids' => array('NotEmpty', 'Int')
    );
    $input = new Zend_Filter_Input($filters, $validators);
    $input->setData($this->getRequest()->getParams());
    
    // test if input is valid
    // read array of record identifiers
    // delete records from database
    if ($input->isValid()) {
      if($input->ids){
	  		$q = Doctrine_Query::create()
	  		->update('Telephone_Model_IncomingTelephone i')
			->set('i.done','0')
            ->whereNotIn('i.incomingtelephone_id', $input->ids);
			$result = $q->execute(); 
			$q = Doctrine_Query::create()	
            ->update('Telephone_Model_IncomingTelephone i')
			->set('i.done','1')
            ->whereIn('i.incomingtelephone_id', $input->ids);			
      		$result = $q->execute(); 
			$this->_helper->getHelper('FlashMessenger')->addMessage('The records were successfully updated.');
      		$this->_redirect('/incoming/overview/index');     
	  	}		         	        
    } else {
      throw new Zend_Controller_Action_Exception('Invalid input');              
    }
  }
  //set as done from email
   public function setdoneemailAction()
  {
    // set filters and validators for POST input
    $filters = array(
      'id' => array('HtmlEntities', 'StripTags', 'StringTrim')
    );    
    $validators = array(
      'id' => array('NotEmpty', 'Int')
    );
    $input = new Zend_Filter_Input($filters, $validators);
    $input->setData($this->getRequest()->getParams());
    
    // test if input is valid
    // read id
    // delete record from database
    if ($input->isValid()) {
      if($input->id){
	  		$q = Doctrine_Query::create()
	  		->update('Telephone_Model_IncomingTelephone i')
			->set('i.done','1')
            ->where('i.incomingtelephone_id =?', $input->id);
			$result = $q->execute(); 			    
	  	}		
	  
      $this->_helper->getHelper('FlashMessenger')->addMessage('The record was successfully updated.');
      $this->_redirect('/incoming/overview/index');
    } else {
      throw new Zend_Controller_Action_Exception('Invalid input');              
    }
  }
  
  // action to modify an individual item
  public function updateAction()
  {
    // generate input form
    $form = new Telephone_Form_IncomingUpdate;
    $this->view->form = $form;    
    
    if ($this->getRequest()->isPost()) { 
      // if POST request
      // test if input is valid
      // retrieve current record
      // update values and replace in database
      $postData = $this->getRequest()->getPost();
      $postData['date'] = sprintf('%04d-%02d-%02d', 
        $this->getRequest()->getPost('date_year'), 
        $this->getRequest()->getPost('date_month'), 
        $this->getRequest()->getPost('date_day')
      );
      if ($form->isValid($postData)) {
        $input = $form->getValues();
        $item = Doctrine::getTable('Telephone_Model_IncomingTelephone')->find($input['incoming_thisID']);		
        $item->fromArray($input);       
        $item->save();		
        $this->_helper->getHelper('FlashMessenger')->addMessage('The record was successfully updated.');
        $this->_redirect('/incoming/overview/index');        
      }      
    } else {    
      // if GET request
      // set filters and validators for GET input
      // test if input is valid
      // retrieve requested record
      // pre-populate form
      $filters = array(
        'id' => array('HtmlEntities', 'StripTags', 'StringTrim')
      );          
      $validators = array(
        'id' => array('NotEmpty', 'Int')
      );  
      $input = new Zend_Filter_Input($filters, $validators);
      $input->setData($this->getRequest()->getParams());      
      if ($input->isValid()) {
        $q = Doctrine_Query::create()
           	->from('Telephone_Model_IncomingTelephone i')
		  	->leftJoin('i.Telephone_Model_FollowUpAction f')
          	->leftJoin('i.Telephone_Model_Users u')
           	->where('i.incomingtelephone_id = ?', $input->id);
        $result = $q->fetchArray();        
        if (count($result) == 1) {
          // perform adjustment for date selection lists
          $date = $result[0]['date'];
          $result[0]['date_day'] = date('d', strtotime($date));
          $result[0]['date_month'] = date('m', strtotime($date));
          $result[0]['date_year'] = date('Y', strtotime($date));
		  $result[0]['incoming_thisID'] = $result[0]['incomingtelephone_id'];
          $this->view->form->populate($result[0]);                
        } else {
          throw new Zend_Controller_Action_Exception('Page not found', 404);        
        }        
      } else {
        throw new Zend_Controller_Action_Exception('Invalid input');                
      }              
    }
  }  
  
  // action to confirm if should delete
  	public function deleteAction()
  	{	// generate delete confirmation question   	
		$filters = array(
        'id' => array('HtmlEntities', 'StripTags', 'StringTrim')
      );          
      $validators = array(
        'id' => array('NotEmpty', 'Int')
      );  
      $input = new Zend_Filter_Input($filters, $validators);
      $input->setData($this->getRequest()->getParams());
	  $this->view->deleteId=$input->id;	
    	$this->updateAction(); // this includes edit options instead of deleting
		
	}
  
  // action to display an individual item
  public function displayAction()
  {
    // set filters and validators for GET input
    $filters = array(
      'id' => array('HtmlEntities', 'StripTags', 'StringTrim')
    );    
    $validators = array(
      'id' => array('NotEmpty', 'Int')
    );
    $input = new Zend_Filter_Input($filters, $validators);
    $input->setData($this->getRequest()->getParams());

    // test if input is valid
    // retrieve requested record
    // attach to view
    if ($input->isValid()) {
      $q = Doctrine_Query::create()
           	->from('Telephone_Model_IncomingTelephone i')
		  	->leftJoin('i.Telephone_Model_FollowUpAction f')
          	->leftJoin('i.Telephone_Model_Users u')
           	->where('i.incomingtelephone_id = ?', $input->id);
      $result = $q->fetchArray();
      if (count($result) == 1) {
        $this->view->item = $result[0];               
      } else {
        throw new Zend_Controller_Action_Exception('Page not found', 404);        
      }
    } else {
      throw new Zend_Controller_Action_Exception('Invalid input');              
    }
  }      

   public function createAction()
  {
    // generate input form
    $form = new Telephone_Form_IncomingCreate;
    $this->view->form = $form;
    
    // test for valid input
    // if valid, populate model
    // assign default values for some fields
    // save to database
    if ($this->getRequest()->isPost()) {
      if ($form->isValid($this->getRequest()->getPost())) {
        $item = new Telephone_Model_IncomingTelephone;
        $item->fromArray($form->getValues());      
        $item->date = date('Y-m-d', mktime());
		$item->time = date('H:i:s', mktime());               
        $item->save();
        $id = $item->incomingtelephone_id;  
		///get all data for email
		$q = Doctrine_Query::create()
           	->from('Telephone_Model_IncomingTelephone i')
		  	->leftJoin('i.Telephone_Model_FollowUpAction f')
          	->leftJoin('i.Telephone_Model_Users u')
           	->where('i.incomingtelephone_id = ?', $id);
      $result = $q->fetchArray();
      if (count($result) == 1) {       						
		$mail = new Telephone_Mail_Emailer();
		$mail->sendMail($result[0]);					      		               	
	  }
        $this->_helper->getHelper('FlashMessenger')->addMessage('New incoming telephone recorded as item #' . $id);
        $this->_redirect('/incoming/overview/index');
      }   
    } 
  }

  
    
}

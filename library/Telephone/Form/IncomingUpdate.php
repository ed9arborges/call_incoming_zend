<?php
class Telephone_Form_IncomingUpdate extends Telephone_Form_IncomingCreate
{
  public function init()
  {
    // get parent form
    parent::init();
    
    // set form action (set to false for current URL)
    $this->setAction('/incoming/update');

         
    // create hidden input for item ID
    $id = new Zend_Form_Element_Hidden('incoming_thisID');
    $id->addValidator('Int')            
       ->addFilter('HtmlEntities')            
       ->addFilter('StringTrim');
	   
	  // create hidden input for item date
    $date = new Zend_Form_Element_Hidden('date');
    $date->addValidator('Date')
                 ->addFilter('HtmlEntities')            
                 ->addFilter('StringTrim');            
    
    // create select inputs for item date
    $dateDay = new Zend_Form_Element_Select('date_day');
    $dateDay->addValidator('Int')            
                    ->addFilter('HtmlEntities')            
                    ->addFilter('StringTrim')            
                    ->addFilter('StringToUpper')
                    ->setDecorators(array(
                          array('ViewHelper'),
                          array('HtmlTag', 
                            array(
                              'tag' => 'div', 
                              'closeOnly' => true
                            )
                          ),
                       ));                   
    for($x=1; $x<=31; $x++) {
      $dateDay->addMultiOption($x, sprintf('%02d', $x));      
    }  
    
    $dateMonth = new Zend_Form_Element_Select('date_month');
    $dateMonth->addValidator('Int')            
                      ->addFilter('HtmlEntities')            
                      ->addFilter('StringTrim')            
                      ->setDecorators(array(
                          array('ViewHelper')
                        ));                      
    for($x=1; $x<=12; $x++) {
      $dateMonth->addMultiOption($x, date('M', mktime(1,1,1,$x,1,1)));      
    }  
    
    $dateYear = new Zend_Form_Element_Select('date_year');
    $dateYear->setLabel('Date:')
                    ->addValidator('Int')            
                     ->addFilter('HtmlEntities')            
                     ->addFilter('StringTrim')                                 
					   ->setDecorators(array(
                        array('ViewHelper'),
                        array('Label', array('tag' => 'dt')),
                        array('HtmlTag', 
                          array(
                            'tag' => 'div', 
                            'openOnly' => true, 
                            'id' => 'divDate', 
                            'placement' => 'prepend'
                          )
                        ),
                      ));
    for($x=2009; $x<=2013; $x++) {
      $dateYear->addMultiOption($x, $x);      
    }  
    
	// create text input for time 
    $time = new Zend_Form_Element_Text('time');
    $time->setLabel('Time:')
         ->setOptions(array('size' => '15'))
         ->setRequired(true)
         ->addValidator(new Zend_Validate_Date(array("format" => 'H:i')))            
         ->addFilter('HtmlEntities')            
         ->addFilter('StringTrim'); 	 	                     
    
     $this->addElement($id)
	 	->addElement($date)
		->addElement($dateYear)		
		->addElement($dateMonth)
		->addElement($dateDay)
         ->addElement($time);
		 
	$this->addDisplayGroup(array('date_year', 'date_month', 'date_day', 'time'), 'datetime');
    $this->getDisplayGroup('datetime')
         ->setOrder(9)
         ->setLegend('Change Date/Time');         
  }
}

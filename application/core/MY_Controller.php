<?php

/**
 * core/MY_Controller.php
 *
 * Default application controller
 *
 * @author		JLP
 * @copyright           2010-2013, James L. Parry
 * ------------------------------------------------------------------------
 */
class Application extends CI_Controller {

    protected $data = array();      // parameters for view components
    protected $id;                  // identifier for our content

    /**
     * Constructor.
     * Establish view parameters & load common helpers
     */

    function __construct() {
        parent::__construct();
        $this->data = array();
        $this->data['title'] = "Top Secret Government Site";    // our default title
        $this->errors = array();
        $this->data['pageTitle'] = 'welcome';   // our default page
    }

    /**
     * Render this page
     */
    function render() {
        $this->data['menubar'] = $this->parser->parse('_menubar', $this->makemenu(),true);
        $this->data['content'] = $this->parser->parse($this->data['pagebody'], $this->data, true);
		$this->data['sessionid'] = session_id();
        // finally, build the browser page!
        $this->data['data'] = &$this->data;
        $this->parser->parse('_template', $this->data);
    }
	
	function restrict($roleNeeded = null) {
		$userRole = $this->session->userdata('userRole');
		if ($roleNeeded != null) {
			if (is_array($roleNeeded)) {
				if (!in_array($userRole, $roleNeeded)) {
					redirect("/");
					return;
				}
			} else if ($userRole != $roleNeeded) {
				redirect("/");
				return;
			}
		}
	}
	
	function makemenu() {
		//get role & name from session
		$userRole = $this->session->userdata('userRole');
		$userName = $this->session->userdata('name');
		
		// make array, with menu choice for alpha
		$choices['menu_choices'] = array(
			'menudata' => array(
				array('name' => "Alpha", 'link' => '/alpha')
				//array('name' => "Beta", 'link' => '/beta'),
				//array('name' => "Gamma", 'link' => '/gamma'),
				//array('name' => "Login", 'link' => '/auth'),
				//array('name' => "Logout", 'link' => '/auth/logout')
			)
		);

		// if user, add menu choice for beta and logout
		if ($userRole == 'user') {
			array_push($choices['menu_choices']['menudata'], array('name' => "Beta", 'link' => '/beta'));
		}
		
		// if admin, add menu choices for beta, gamma and logout
		if ($userRole == 'admin') {
			array_push($choices['menu_choices']['menudata'], array('name' => "Beta", 'link' => '/beta'));
			array_push($choices['menu_choices']['menudata'], array('name' => "Gamma", 'link' => '/gamma'));
		}
		
		// add login or logout
		if ($userRole == NULL)
			array_push($choices['menu_choices']['menudata'], array('name' => "Login", 'link' => '/auth'));
		else
			array_push($choices['menu_choices']['menudata'], array('name' => "Logout", 'link' => '/auth/logout'));
		
		// return the choices array
		return $choices['menu_choices'];
	}
}

/* End of file MY_Controller.php */
/* Location: application/core/MY_Controller.php */
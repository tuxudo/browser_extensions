<?php 

/**
 * browser_extensions module class
 *
 * @package munkireport
 * @author tuxudo
 **/
class Browser_extensions_controller extends Module_controller
{

	/*** Protect methods with auth! ****/
	function __construct()
	{
		// Store module path
		$this->module_path = dirname(__FILE__);
	}

	/**
	 * Default method
	 * @author tuxudo
	 *
	 **/
	function index()
	{
		echo "You've loaded the browser_extensions module!";
	}

	/**
     * Retrieve data in json format
     *
     **/
    public function get_data($serial_number)
    {
        jsonView(
            Browser_extensions_model::selectRaw('name, version, extension_id, user, browser, date_installed, developer, enabled, extension_path, description')
                ->where('browser_extensions.serial_number', $serial_number)
                ->filter()
                ->get()
                ->toArray()
        );
   }
} // End class Browser_extensions_controller
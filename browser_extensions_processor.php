<?php

use CFPropertyList\CFPropertyList;
use munkireport\processors\Processor;

class Browser_extensions_processor extends Processor
{

    /**
     * Process data sent by postflight
     *
     * @param string data
     * @author abn290
     **/
    public function run($plist)
    {    
        // Add local config
        configAppendFile(__DIR__ . '/config.php');

        // Check if we have data
		if ( ! $plist){
			throw new Exception("Error Processing Request: No property list found", 1);
		}

        // Delete previous set
        Browser_extensions_model::where('serial_number', $this->serial_number)->delete();

        // Build list of extension IDs to ignore
        $extension_id_ignorelist = is_array(conf('browser_extension_id_ignorelist')) ? conf('browser_extension_id_ignorelist') : array();
        $regex_id = '/^'.implode('|', $extension_id_ignorelist).'$/';

        // Build list of extension names to ignore
        $extension_name_ignorelist = is_array(conf('browser_extension_name_ignorelist')) ? conf('browser_extension_name_ignorelist') : array();
        $regex_name = '/^'.implode('|', $extension_name_ignorelist).'$/';

		$parser = new CFPropertyList();
        $parser->parse($plist, CFPropertyList::FORMAT_XML);

        // Get fillable items
        $fillable = array_fill_keys((new Browser_extensions_model)->getFillable(), null);
        $fillable['serial_number'] = $this->serial_number;

        $save_list = [];
        foreach ($parser->toArray() as $extension) {

            // Check if we should skip this extension based on extension ID
            if (preg_match($regex_id, $extension['extension_id'])) {
                continue;
            }     

            // Check if we should skip this extension based on extension name
            if (preg_match($regex_name, $extension['name'])) {
                continue;
            }

            $save_list[] = array_replace($fillable, array_intersect_key($extension, $fillable));
        }

        Browser_extensions_model::insertChunked($save_list);
    }
}
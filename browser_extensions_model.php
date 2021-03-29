<?php

use munkireport\models\MRModel as Eloquent;

class Browser_extensions_model extends Eloquent
{
    protected $table = 'browser_extensions';

    protected $fillable = [
		'serial_number',
		'name',
		'extension_id',
		'version',
		'description',
		'browser',
		'date_installed',
		'developer',
		'enabled',
		'user',
    ];

    public $timestamps = false;
}

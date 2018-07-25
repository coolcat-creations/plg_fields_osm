<?php
/**
 * @package     Joomla.Plugin
 * @subpackage  Fields.Osm
 *
 * @copyright   Copyright (C) 2017 Elisa Foltyn.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use Joomla\CMS\Plugin\PluginHelper;

defined('_JEXEC') or die;

JLoader::import('components.com_fields.libraries.fieldsplugin', JPATH_ADMINISTRATOR);

class PlgFieldsOsm extends FieldsPlugin
{
	public function onCustomFieldsPrepareField($context, $item, $field)
	{
		// Check if the field should be processed by us
		if (!$this->isTypeSupported($field->type))
		{
			return;
		}
		// Merge the params from the plugin and field which has precedence
		$fieldParams = clone $this->params;
		$fieldParams->merge($field->fieldparams);

		$mapheight    = $field->fieldparams['mapheight'];
		$plugin       = PluginHelper::getPlugin('fields', 'osm');
		$pluginparams = json_decode($plugin->params);
		$key = $pluginparams->opencageapi;

		/* Fill in the gaps in adress to make a proper call in the url */
		$address = JFilterOutput::stringURLSafe($field->rawvalue);

		/* setting up the call url */
		$url = "https://api.opencagedata.com/geocode/v1/json?q=$address&key=$key&pretty=1";

		/* Getting the JSON DATA TO GET LAT & LONG */

		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_TIMEOUT, 5);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4 );

		$curl_response = curl_exec($ch);
		$jsonobj = json_decode($curl_response);

		$lat = $jsonobj->results[0]->geometry->lat;
		$lon = $jsonobj->results[0]->geometry->lng;

		// Get the path for the layout file
		$path = JPluginHelper::getLayoutPath('fields', $field->type, $field->type);
		// Render the layout
		ob_start();
		include $path;
		$output = ob_get_clean();
		// Return the output
		return $output;
	}
}

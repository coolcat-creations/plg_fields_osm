<?php
/**
 * @package     Joomla.Plugin
 * @subpackage  Fields.Osm
 *
 * @copyright   Copyright (C) 2017 Elisa Foltyn.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use Joomla\CMS\Factory;
use Joomla\CMS\Plugin\PluginHelper;

defined('_JEXEC') or die;

if (!$field->value || $field->value == '-1')
{
	return;
}

$value = $field->rawvalue;

/* get the document */
$doc = Factory::getDocument();

/* get the style for the leaflet map */
$doc->addStyleSheet('https://cdn.jsdelivr.net/leaflet/1/leaflet.css');
$doc->addStyleDeclaration('#map {height: ' . $mapheight . 'px; }');

/* get the script for the leaflet map */
$doc->addScript('https://cdn.jsdelivr.net/leaflet/1/leaflet.js');

?>


<?php /* Building the mapcontainer */ ?>
<div id="map">
</div>


<?php /* We add the script at the end - We get the first latitute and longitute entry from the list */ ?>

<script>

	(function ($) {

		var map;
		var marker;

		$(function () {
			// Initialize the map
			// This variable map is inside the scope of the jQuery function.

			// Now map reference the global map declared in the first line
			map = L.map('map').setView([<?php echo $lat . ',' . $lon; ?>], 12);
			marker = L.marker([<?php echo $lat . ',' . $lon; ?>]).addTo(map);

			L.tileLayer('http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
				attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>',
				maxZoom: 18
			}).addTo(map);

		});

	})(jQuery);

</script>


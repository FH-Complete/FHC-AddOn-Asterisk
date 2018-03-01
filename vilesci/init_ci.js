/*
 This program is free software: you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation, either version 3 of the License, or
 (at your option) any later version.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
/**
 * javascript file for Click2Dial on infocenterDetails page
 */

$(document).ready(
	function ()
	{
		InitiateCallButton();
	}
);

function AsteriskAnrufInfoCenter(telNumber)
{
	// replace one or more '+' with exactly '00'
	telNumber = telNumber.replace(/\++/,'00');
	$.ajax({url:FHC_ADDON_DATA_STORAGE_OBJECT.app_root+"/addons/asterisk/vilesci/asterisk_anruf.php?nummer="+telNumber});
}

function InitiateCallButton()
{
	var kontakttypen = ['mobil', 'telefon'];
	for (typ in kontakttypen)
	{
		var buttons = document.getElementsByClassName(kontakttypen[typ]);

		for (var i = buttons.length - 1; i >= 0; i--)
		{
			var buttonElem = buttons[i];
			var telNum = buttonElem.innerHTML;
			buttonElem.innerHTML = telNum+'<input type="button" class="btn btn-default" value="Anruf" onclick="AsteriskAnrufInfoCenter(\''+telNum+'\')">';
		}
	}
}

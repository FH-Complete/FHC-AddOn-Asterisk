<?php
/* Copyright (C) 2013 fhcomplete.org
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as
 * published by the Free Software Foundation; either version 2 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307, USA.
 *
 * Authors: Andreas Oesterreicher <andreas.oesterreicher@technikum-wien.at>
 * 			Gerald Raab <gerald.raab@technikum-wien.at>
 */
/**
 * Initialisierung des Addons
 */
?>


var show_call_menu = 0;

addon.push( 
{
	init: function() 
	{
		// Diese Funktion wird nach dem Laden des FAS aufgerufen

		$.ajax({dataType: "json", url: "../addons/asterisk/vilesci/asterisk_get_numbers.php",
		success: function(data)
		{
		if (data == 1)
			{				
				show_call_menu = 1;
				writeCallMenu();
			}
			
		}
		});
		

	},
	selectMitarbeiter: function(person_id, mitarbeiter_uid)
	{
		
		if (show_call_menu ==1)
		{		
			var anrufmenue = document.getElementById("addons-asterisk-anrufmenu");
			while (anrufmenue.firstChild) {
	    		anrufmenue.removeChild(anrufmenue.firstChild);
			}
			
			var menuentry = document.createElement("menupopup");
			menuentry.setAttribute("id","addons-asterisk-menupopup");
			anrufmenue.appendChild(menuentry);
		
			$.ajax({dataType: "json", url: "../addons/asterisk/vilesci/asterisk_get_numbers.php?uid="+mitarbeiter_uid+"&person_id="+person_id,
			success: function(data)
				{
					
					for (var i=0; i<data.length; i++)
					{		
								
						var tel = data[i];		
						var anrufmenue = document.getElementById("addons-asterisk-menupopup");
						var menuentry = document.createElement("menuitem");
						menuentry.setAttribute("id","addons-asterisk-anruf-"+data[i]);
						menuentry.setAttribute("label",data[i]);
						menuentry.setAttribute("tel",tel);
						menuentry.addEventListener("command",AsteriskAnruf, true);
							
						anrufmenue.appendChild(menuentry);
					}
				}
			}
			);	
		}
		
	},
	selectStudent: function(person_id, prestudent_id, student_uid)
	{
		if (show_call_menu ==1)
		{		
			var anrufmenue = document.getElementById("addons-asterisk-anrufmenu-stud");
			while (anrufmenue.firstChild) {
	    		anrufmenue.removeChild(anrufmenue.firstChild);
			}
			
			var menuentry = document.createElement("menupopup");
			menuentry.setAttribute("id","addons-asterisk-menupopup-stud");
			anrufmenue.appendChild(menuentry);
		
			$.ajax({dataType: "json", url: "../addons/asterisk/vilesci/asterisk_get_numbers.php?person_id="+person_id,
			success: function(data)
				{
					
					for (var i=0; i<data.length; i++)
					{				
						var tel = data[i];		
						var anrufmenue = document.getElementById("addons-asterisk-menupopup-stud");
						var menuentry = document.createElement("menuitem");
						menuentry.setAttribute("id","addons-asterisk-anruf-"+data[i]);
						menuentry.setAttribute("label",data[i]);
						menuentry.setAttribute("tel",tel);
						menuentry.addEventListener("command",AsteriskAnruf, true);
							
						anrufmenue.appendChild(menuentry);
					}
				}
			}
			);	
		}


	},
	selectVerband: function(item)
	{
	},
	selectInstitut: function(institut)
	{
	},
	selectLektor: function(lektor)
	{
	}
});

function writeCallMenu()
{
			anrufmenue = document.getElementById("mitarbeiter-tree-popup");
			var menuentry = document.createElement("menu");
			menuentry.setAttribute("id","addons-asterisk-anrufmenu");
			menuentry.setAttribute("label","Anruf");
			//menuentry.addEventListener("command",AsteriskAnrufIntern, true);
			anrufmenue.appendChild(menuentry);
			
			anrufmenue = document.getElementById("student-tree-popup");
			var menuentry = document.createElement("menu");
			menuentry.setAttribute("id","addons-asterisk-anrufmenu-stud");
			menuentry.setAttribute("label","Anruf");
			//menuentry.addEventListener("command",AsteriskAnrufIntern, true);
			anrufmenue.appendChild(menuentry);
}

function AsteriskAnruf(event)
{
	tel=event.target.getAttribute('tel');
	$.ajax({url:"../addons/asterisk/vilesci/asterisk_anruf.php?nummer="+tel});
}




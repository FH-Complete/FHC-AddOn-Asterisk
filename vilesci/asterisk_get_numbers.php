<?php
/* Copyright (C) 2006 Technikum-Wien
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
 * Authors: Gerald Raab <gerald.raab@technikum-wien.at>,
 *          Andreas Oesterreicher <andreas.oesterreicher@technikum-wien.at>
 */
require_once('../config.inc.php');
require_once('../../../config/vilesci.config.inc.php');
require_once('../../../include/functions.inc.php');
require_once('../../../include/person.class.php');
require_once('../../../include/benutzerberechtigung.class.php');
require_once('../../../include/mitarbeiter.class.php');
require_once('../../../include/kontakt.class.php');
require_once('../../../include/benutzer.class.php');

error_reporting(E_ALL);

if (isset($_REQUEST["uid"]) && isset($_REQUEST["person_id"]))
{
	$user = $_REQUEST["uid"];
	$person_id = $_REQUEST["person_id"];


	$ma = new mitarbeiter();
	$ma->load($user);

	$nummern = array();

	if ($ma->telefonklappe && $ma->standort_id!='')
	{
		$klappe_intern = $ma->telefonklappe;
		$standortkontakt = new kontakt();
		$standortkontakt->load_standort($ma->standort_id);
		foreach ($standortkontakt->result as $sk)
		{
			if ($sk->kontakttyp == 'telefon' && $sk->kontakt != ASTERISK_KOPFNUMMER_INTERN)
				$klappe_intern = $sk->kontakt.$ma->telefonklappe;
		}
		$nummern[] = array('typ' => 'Intern', 'nummer' => $klappe_intern);
	}

	$kontakt = new kontakt();
	$typen = unserialize(ASTERISK_KONTAKT_TYPEN);
	foreach ($typen as $typ)
	{
		$kontakt->load_persKontakttyp($person_id, $typ);
	}
	$kontakttypen = new kontakt();
	$kontakttypen->getKontakttyp();
	$kontakttypen_arr = array();
	foreach ($kontakttypen->result AS $kontakttyp)
		$kontakttypen_arr[$kontakttyp->kontakttyp] = $kontakttyp->beschreibung;

	foreach ($kontakt->result as $k)
	{
		$nummern[] = array('typ' => $kontakttypen_arr[$k->kontakttyp], 'nummer' => $k->kontakt);
	}

	$jsonstring = json_encode($nummern);

	echo $jsonstring;

	die();
}
elseif (isset($_REQUEST["person_id"]))
{
	$person_id = $_REQUEST["person_id"];
	$nummern = array();
	$kontakt = new kontakt();
	$typen = unserialize(ASTERISK_KONTAKT_TYPEN);
	foreach ($typen as $typ)
	{
		$kontakt->load_persKontakttyp($person_id, $typ);
	}
	$kontakttypen = new kontakt();
	$kontakttypen->getKontakttyp();
	$kontakttypen_arr = array();
	foreach ($kontakttypen->result AS $kontakttyp)
		$kontakttypen_arr[$kontakttyp->kontakttyp] = $kontakttyp->beschreibung;
	
	foreach ($kontakt->result as $k)
	{
		$nummern[] = array('typ' => $kontakttypen_arr[$k->kontakttyp], 'nummer' => $k->kontakt);
	}

	$jsonstring = json_encode($nummern);

	echo $jsonstring;

	die();

}
else
{
	$user = get_uid();
	$ma = new mitarbeiter();
	$ma->load($user);
	$standort = '';

	$standortkontakt = new kontakt();
	$standortkontakt->load_standort($ma->standort_id);
	foreach ($standortkontakt->result as $sk)
	{
		if ($sk->kontakttyp == 'telefon' && $sk->kontakt == ASTERISK_KOPFNUMMER_INTERN)
			$standort = 'intern';
	}
	$test_user = unserialize(ASTERISK_TEST_MODE);

	if ($ma->telefonklappe && $standort == 'intern' && (in_array($ma->telefonklappe, $test_user) || count($test_user) == 0))
		echo json_encode(1);
	else
		echo json_encode(0);
	die();
}

?>
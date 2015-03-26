<?php
// SSH-connection to Asterisk Server
define('ASTERSIK_SSH_USER','USER');
define('ASTERSIK_SSH_PWD','PASS');
define('ASTERISK_SERVER_IP','192.168.1.1');
define('ASTERISK_SERVER_PORT', 22);

//Kontakttypen die angezeigt werden sollen
define('ASTERISK_KONTAKT_TYPEN',serialize(array('mobil', 'firmenhandy', 'so.tel', 'telefon')));

//Interne Anrufe direkt verbinden wenn Standorttelefonnummer = Kopfnummer_intern 
define('ASTERISK_KOPFNUMMER_INTERN','+43 1 XXX');

//For testing: nur fuer diese EXTs freischalten, wenn array leer -> freigeschaltet fuer alle
define('ASTERISK_TEST_MODE',serialize(array()));

?>

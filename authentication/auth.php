<?php 
	// initialize phpCAS
	phpCAS::client(CAS_VERSION_2_0,'cas-auth.rpi.edu',443,'/cas/');
	
	// no SSL validation for the CAS server
	phpCAS::setNoCasServerValidation();	
?>
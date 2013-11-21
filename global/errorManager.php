<?php
global $ERRORS;
foreach ($ERRORS as $error) {
	$error->publishError();
}
?>
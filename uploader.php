<?php

print_r( $_POST );
print_r( $_FILES );

// Should check size, security, etc.
if ( move_uploaded_file( $_FILES['file']['tmp_name'], '/var/www/dev.coderagora.com/crispy-data/' . $_FILES['file']['name'] ) ) {
	echo "Success! Maybe?";
} else if  ( ! ( is_dir( '/var/www/dev.coderagora.com/crispy-data/' ) && is_writable( '/var/www/dev.coderagora.com/crispy-data/' ) ) ) {
	die( "Write error" );
} else {
	die( "There was an unexpected error" );
}

<?php

print_r( $_POST );
print_r( $_FILES );

// Should check size, security, etc.
if ( move_uploaded_file( $_FILES['tmp_name'], '/var/www/dev.coderagora.com/crispy-data/' . $_FILES['name'] ) ) {
	echo "Success! Maybe?";
} else {
	die( "There was an unexpected error" );
}

<?php

print_r( $_POST );
print_r( $_FILES );

// Check size, security, etc.
move_uploaded_file( $_FILES['tmp_name'], '/var/www/dev.coderagora.com/crispy-data' );

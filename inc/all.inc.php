<?php

require __DIR__ . '/autoload.inc.php';
// require __DIR__ . '/db-connect.inc.php';
require __DIR__ . '/functions.inc.php';

/*
 * This file is used to include all necessary files for the CMS. It is included in all pages of the CMS. It is used to autoload classes, connect to the database, and include functions.
 */

// The explanation of the above code in plain English, what the code does, step by step. Highlighting the purpose of each key part (methods, variables, classes, loops, etc.). 
// 1. The first line is a PHP opening tag, which indicates that the following code is written in PHP.
// 2. The second line uses the `require` statement to include the `autoload.inc.php` file, which is responsible for autoloading classes in the CMS. This means that whenever a class is used in the CMS, it will automatically include the necessary file for that class without needing to manually include it.
// 3. The third line is commented out, which means that it is not currently being executed. This line would include the `db-connect.inc.php` file, which is responsible for connecting to the database. It is likely commented out because the database connection is being handled elsewhere or is not needed at this point in the code.
// 4. The fourth line uses the `require` statement to include the `functions.inc.php` file, which contains various functions that are used throughout the CMS. These functions could be for things like handling user input, formatting data, or performing other common tasks that are needed in multiple places in the CMS.
// 5. The comment block at the end of the file explains the purpose of this file, which is to include all necessary files for the CMS. It is included in all pages of the CMS to ensure that the autoloading, database connection, and functions are available wherever they are needed in the CMS. This helps to keep the code organized and reduces the need for repetitive includes in each individual page of the CMS.
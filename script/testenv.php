<?php

$env = parse_ini_file('.env');
$TEST = $env["TEST"];

echo $TEST;
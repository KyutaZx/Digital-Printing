<?php
$output = shell_exec('cd "d:\\digiprint web\\Digital-Printing\\golang-api" && go run test_api.go 2>&1');
echo "<pre>$output</pre>";

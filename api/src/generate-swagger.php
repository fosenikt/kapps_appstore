<?php
require_once 'vendor/autoload.php';

use OpenApi\Generator;

// Specify the directories or files to scan for OpenAPI annotations
// It's important to include all relevant source files or directories here.
$directoriesToScan = ['index.php', 'app']; // Add or modify paths as necessary

// Scan the files for OpenAPI annotations
$openapi = Generator::scan($directoriesToScan);

// Output directory for the generated Swagger specification
$outputDir = __DIR__ . '/swagger';
if (!file_exists($outputDir)) {
    mkdir($outputDir, 0755, true);
}

// Save the OpenAPI specification to a file in JSON format
file_put_contents($outputDir . '/swagger.json', $openapi->toJson());

echo "Swagger specification generated successfully!\n";

// Optionally, also save the specification in YAML format for better readability
file_put_contents($outputDir . '/swagger.yaml', $openapi->toYaml());

echo "YAML specification also generated successfully!\n";

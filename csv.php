<?php
function extract_domain_ip($data) {
    $result = array();
    $regex = '/(https?:\/\/)?(www\.)?([a-zA-Z0-9.-]+)(?::\d+)?/';
    preg_match_all($regex, $data, $matches);
    foreach ($matches[0] as $match) {
        if (!empty($match)) {
            $result[] = $match;
        }
    }
    return $result;
}
if ($argc != 2) {
    echo "Usage: php csv.php <file.csv>\n";
    exit(1);
}
$input_file = $argv[1];
$input = file_get_contents($input_file);
if ($input === false) {
    //file input
    echo "Failed to read file.\n";
    exit(1);
}
$lines = explode("\n", $input);
$extract = array();
foreach ($lines as $line) {
    $csv = str_getcsv($line);
    if (count($csv) == 2) {
        $extract = array_merge($extract, extract_domain_ip($csv[0]));
        $extract = array_merge($extract, extract_domain_ip($csv[1]));
    }
}
//filter duplikat https://www.php.net/manual/en/function.array-unique.php
$filter = array_unique($extract);
$output_file = "dom-ip.txt";
$output = fopen($output_file, "w");
if ($output === false) {
    //file output
    echo "Failed to open file.\n";
    exit(1);
}
foreach ($filter as $item) {
    fwrite($output, $item . "\n");
}
fclose($output);
?>

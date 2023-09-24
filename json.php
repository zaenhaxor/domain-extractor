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
    echo "Usage: php json.php <file.json>\n";
    exit(1);
}
$input_file = $argv[1];
$input_data = file_get_contents($input_file);
if ($input_data === false) {
  //file input
    echo "Failed to read file.\n";
    exit(1);
}
$lines = explode("\n", $input_data);
$extract = array();
foreach ($lines as $line) {
    if (!empty($line)) {
        $json = json_decode($line, true);
        if (isset($json['host'])) {
            $extract = array_merge($extract, extract_domain_ip($json['host']));
        }
    }
}
$output_file = "dom-ip.txt";
$output = fopen($output_file, "w");
if ($output === false) {
  //file output
    echo "Failed to open file.\n";
    exit(1);
}
foreach ($extract as $item) {
    fwrite($output, $item . "\n");
}
fclose($output);
?>

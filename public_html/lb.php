<?php
$file = fopen('lb.txt', 'r');
$scores = fread($file, filesize('lb.txt'));
fclose($file);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $level = intval($_GET['level']);
  $hash = $_GET['hash'];
  $nextLevel = $level + 1;

  // ensure they actually are on a level
  if (!file_exists("$nextLevel/$hash.html")) {
    echo "Aborted";
    exit;
  }

  $id = $_GET['id'];
  $name = $_GET['name'];
  $attempts = intval($_GET['attempts']);

  $lines = explode("\n", $scores);
  $added = false;
  foreach ($lines as &$line) {
    $list = explode(',', $line);
    if ($list[0] == $id) {
      if (intval($list[2]) < $level) {
        $list[1] = $name;
        $list[2] = $level;
        $list[3] = intval($list[3]) + $attempts;
        $line = join(',', $list);
      }
      $added = true;
      break;
    }
  }
  unset($line);

  if (!$added) {
    array_push($lines, "$id,$name,$level,$attempts");
  }

  $output = join("\n", $lines);

  $file = fopen('lb.txt', 'w');
  fwrite($file, $output);
  fclose($file);

  echo "OK!";
} else {
  echo $scores;
}
?>

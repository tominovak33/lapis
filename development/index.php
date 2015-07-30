<?php
$hook_payload = $_POST['payload'];
$payload_object = json_decode($_POST['payload']);
$hook_file = fopen("payload.txt", "w");
fwrite($hook_file, $hook_payload);
fclose($hook_file);
echo "Lapis webhook";

if ($payload_object->action == 'opened') {
  $pull_request = [];
  $pull_request['number'] = $payload_object->number;
  $pull_request['branch'] = $payload_object->pull_request->head->ref;

  $pr_text = serialize($pull_request);

  $pr_file = fopen("pr.txt", "w");
  fwrite($pr_file, $pull_request['number']);
  fwrite($pr_file, '\n');
  fwrite($pr_file, $pull_request['branch']);

  fclose($pr_file);
}


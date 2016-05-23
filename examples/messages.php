<?php
session_start();
require_once '../vendor/autoload.php';
require_once('config.php');

use GmailWrapper\Authenticate;
use GmailWrapper\Messages;

if (!isset($_SESSION['tokens'])) {
    header('Location:login.php');
    exit;
}
$authenticate = Authenticate::getInstance(CLIENT_ID, CLIENT_SECRET, APPLICATION_NAME, DEVELOPER_KEY);
if (!$authenticate->isTokenValid($_SESSION['tokens'])) {
    header('Location:login.php');
    exit;
}
$pageToken = isset($_GET['pageToken']) ? $_GET['pageToken'] : null;
$msgs = new Messages($authenticate);
$messageList = $msgs->getMessages([], $pageToken);
if(!$messageList['status']) {
    echo $messageList['message'];
    exit;
}
foreach ($messageList['data'] as $key => $value) {
    $msgId = $value->getId();
    echo '<a href="message_details.php?messageId='.$msgId.'">'.$msgId.'</a><br/>';
}
$nextToken = $messageList['nextToken'];
echo '<p><a href="messages.php?pageToken='.$nextToken.'">Next</a></p>';

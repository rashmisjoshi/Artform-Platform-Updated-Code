<?php

/*

CometChat
Copyright (c) 2014 Inscripts

CometChat ('the Software') is a copyrighted work of authorship. Inscripts
retains ownership of the Software and any copies of it, regardless of the
form in which the copies may exist. This license is not a sale of the
original Software or any copies.

By installing and using CometChat on your server, you agree to the following
terms and conditions. Such agreement is either on your own behalf or on behalf
of any corporate entity which employs you or which you represent
('Corporate Licensee'). In this Agreement, 'you' includes both the reader
and any Corporate Licensee and 'Inscripts' means Inscripts (I) Private Limited:

CometChat license grants you the right to run one instance (a single installation)
of the Software on one web server and one web site for each license purchased.
Each license may power one instance of the Software on one domain. For each
installed instance of the Software, a separate license is required.
The Software is licensed only to you. You may not rent, lease, sublicense, sell,
assign, pledge, transfer or otherwise dispose of the Software in any form, on
a temporary or permanent basis, without the prior written consent of Inscripts.

The license is effective until terminated. You may terminate it
at any time by uninstalling the Software and destroying any copies in any form.

The Software source code may be altered (at your risk)

All Software copyright notices within the scripts must remain unchanged (and visible).

The Software may not be used for anything that would represent or is associated
with an Intellectual Property violation, including, but not limited to,
engaging in any activity that infringes or misappropriates the intellectual property
rights of others, including copyrights, trademarks, service marks, trade secrets,
software piracy, and patents held by individuals, corporations, or other entities.

If any of the terms of this Agreement are violated, Inscripts reserves the right
to revoke the Software license at any time.

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.

*/

include_once(dirname(__FILE__).DIRECTORY_SEPARATOR."cometchat_init.php");

$response = array();
$messages = array();

$status['available'] = $language[30];
$status['busy'] = $language[31];
$status['offline'] = $language[32];
$status['invisible'] = $language[33];
$status['away'] = $language[34];

if (!empty($_REQUEST['userid'])) {
	$fetchid = $_REQUEST['userid'];
} else {
	$fetchid = $userid;
}

$fetchid = intval($fetchid);
$time = getTimeStamp();
$sql = getUserDetails($fetchid);

if ($guestsMode && $fetchid >= 10000000) {
	$sql = getGuestDetails($fetchid);
}

$query = mysqli_query($GLOBALS['dbh'],$sql);

if (defined('DEV_MODE') && DEV_MODE == '1') { echo mysqli_error($GLOBALS['dbh']); }

$chat = mysqli_fetch_assoc($query);

if ((($time-processTime($chat['lastactivity'])) < ONLINE_TIMEOUT || $chat['isdevice'] == 1) && $chat['status'] != 'invisible' && $chat['status'] != 'offline') {
	if ($chat['status'] != 'busy' && $chat['status'] != 'away') {
		$chat['status'] = 'available';
	}
} else {
	$chat['status'] = 'offline';
}

if ($chat['message'] == null) {
	$chat['message'] = $status[$chat['status']];
}

$link = fetchLink($chat['link']);
$avatar = getAvatar($chat['avatar']);

if(empty($chat['ch'])) {
	if( defined('KEY_A') && defined('KEY_B') && defined('KEY_C') ){
		$key = KEY_A.KEY_B.KEY_C;
	}
	$chat['ch'] = md5($chat['userid'].$key);
} 

if (function_exists('processName')) {
	$chat['username'] = processName($chat['username']);
}

$response =  array('id' => $chat['userid'], 'n' => $chat['username'], 'l' => $link, 'd' => $chat['isdevice'],'a' => $avatar, 's' => $chat['status'], 'm' => $chat['message'], 'ch' => $chat['ch'], 'ls' => $chat['lastseen'], 'lstn' => $chat['lastseensetting']);

header('Content-type: application/json; charset=utf-8');
if (!empty($_GET['callback'])) {
	echo $_GET['callback'].'('.json_encode($response).')';
} else {
	echo json_encode($response);
}
exit;
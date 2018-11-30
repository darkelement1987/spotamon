<?php
NameSpace Spotamon;

Class Mail
{
    Public $error;
    Public $messages;
    public $result;

    Public function __construct() {

        $this->error = array();

    }

    protected function getMessages($uname, $box = null, $options = array()) {
/*
$uname = current user
$box = 'inbox' | 'outbox' | 'trash' | 'conversation'   which thread the messages are being requested for
$options = [$otheruser, $since]
$otheruser = for outbox, the recipient, for convo the other user, for trash either when specifying
$since = used to call updates to messages since the last one already given
*/
        global $conn;

        $stmt[] = "SELECT a.id as mid, a.subject, a.message, a.to_user, a.from_user, a.del_in, a.del_out, a.unread, a.date as sent, a.updated, b.url as tourl, c.url as fromurl FROM messages a JOIN users b JOIN users c ON a.to_user = b.uname AND a.from_user = c.uname WHERE";
        $param = array();
        $input = array();

        switch ($box) {
            case 'inbox':
                $stmt[] = "to_user = ? AND del_in='0'";
                $param[] = "s";
                $input[] = $uname;
            break;
            case 'trash':
                $stmt[] = "(to_user = ? AND del_in = '1') OR (from_user = ? AND del_out = '1')";
                $param[] = "ss";
                array_push($input, $uname, $uname);
            break;
            case 'outbox':
                $stmt[] = "from_user = ? AND del_out = '0'";
                $param[] = "s";
                $input[] = "$uname";
            break;
            case 'conversation':
            if (!$options['otheruser']) {
                $this->error[] = "both users not declared";
                return false;
            }
                $stmt[] = "(to_user = ? AND from_user = ? AND del_in = '0') OR (to_user = ? AND from_user = ? AND del_out = '0')";
                $param[] = 'ssss';
                array_push($input, $uname, $options['otheruser'], $options['otheruser'], $uname);
            break;
            case null:
                $stmt[] = "to_user = ?";
                $param[] = "s";
                $input[] = $uname;
            break;
            default:
                $this->error[] = "No reason for messages was given";
            break;
        }
        if (!empty($options['since'])) {
            $stmt[] = "AND unix_timestamp(updated) >= ?";
            $param[] = 'i';
            $input[] = $options['since'];
        }
        $sql = join(" ", $stmt) . ';';
        $param = join('', $param);
        $sql = $conn->prepare($sql);
        $sql->bind_param($param, ...$input);
        if ($sql->execute()) {
            $messages = array();
            $sql = $sql->get_result();
            while ($row = $sql->fetch_assoc()) {
                $messages[] = $row;
            }
            $sql->close();
            return $messages;
        } else {
            $this->error[] = 'Error getting messages';
            return false;
        }
    }
    public function getInbox($uname, $since = null) {
        $messages = array();
        if (!empty($since)) {
            $result = $this->getMessages($uname, 'inbox', ['since' => $since]);
        } else {
            $result = $this->getMessages($uname, 'inbox');
        }
        foreach ($result as $k) {
            $data = new \stdclass();
            $data->id = $k['mid'];
            $data->subject = $k['subject'];
            $data->message = $k['message'];
            $data->unread = $k['unread'];
            $data->sent = $k['sent'];
            $data->from_user = $k['from_user'];
            $data->avatar = $k['fromurl'];
            array_push($messages, $data);
        }
        return $messages;
        }

    public function getOutbox($uname, $since = null) {
        $messages = array();
        if (!empty($since)) {
            $result = $this->getMessages($uname, 'outbox', ['since' => $since]);
        } else {
            $result = $this->getMessages($uname, 'outbox');
        }
        foreach ($result as $k) {
            $data = new \stdclass();
            $data->id = $k['mid'];
            $data->subject = $k['subject'];
            $data->message = $k['message'];
            $data->sent = $k['sent'];
            $data->to_user = $k['to_user'];
            $data->avatar = $k['tourl'];
            array_push($messages, $data);
        }
        return $messages;
    }

    public function getConvo($uname, $to, $since = null) {
        if (!empty($since)) {
            $result = $this->getMessages($uname, 'conversation', ['otheruser' => $to, 'since' => $since]);
            return $result;
        } else {
            $result = $this->getMessages($uname, 'conversation', ['otheruser' => $to]);
            return $result;
        }
    }
    public function sendMessage($uname, $to, $subject, $message) {
        global $conn;

        $sql = "INSERT INTO messages (subject, to_user, from_user, message)
                VALUES (?,?,?,?);";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ssss', $subject, $to, $uname, $message);
        $result = $stmt->execute();
        if (!$result) {
            $this->error[] = $conn->error;
            return false;
        }
        $id = $conn->insert_id;
        $this->result = $id;
        return true;
    }
    public function read($id) {
        global $conn;
        $sql = 'Update messages set unread = 0 where id = ?;';
        $sql = $conn->prepare($sql);
        if (is_array($id)) {
            foreach ($id as $k) {
                $sql->bind_param('i',$k);
                $sql->execute();
            }
        } else {
        $sql->bind_param('i',$id);
        $sql->execute(); }
        $sql->close();
        return true;
    }
    public function getTrash($uname, $since = null) {
        $messages = array();
        if (empty($since)) {
        $result = $this->getMessages($uname, 'trash');
        } else {
            $result = $this->getMessages($uname, 'trash',['since' => $since]);
        }
        foreach ($result as $k) {
            $data = new \stdclass();
            $data->id = $k['mid'];
            $data->subject = $k['subject'];
            $data->message = $k['message'];
            $data->sent = $k['sent'];
            if ($k['to_user'] == $uname){
                $data->tofrom = 'from';
                $data->user = $k['from_user'];
                $data->avatar = $k['fromurl'];

            } else {
                $data->tofrom = 'to';
                $data->user = $k['to_user'];
                $data->avatar = $k['tourl'];
            }
            array_push($messages, $data);
        }
        return $messages;
    }
    public function deleteMessage($mId, $box) {
        global $conn;
        if ($box == 'inbox') {
            $sql = 'UPDATE messages set del_in = 1 WHERE id = ?;';
        } else if ($box == 'outbox') {
            $sql = 'UPDATE messages set del_out = 1 WHERE id = ?;';
        }
        $del = $conn->prepare($sql);
        if (is_array($mId)) {
            foreach($mId as $id) {
                $del->bind_param('i', $id);
                $del->execute();
            }
        } else {
            $del->bind_param('i', $mId);
            $del->execute();
        }
        $del->close();
        return true;
    }


    }
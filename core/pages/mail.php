<?php
require_once 'initiate.php';
$uname = $Validate->getSession('uname', null);
$logged_in = $Validate->getSession('logged_in', false);
if (empty($uname) || $logged_in == false) { ?>
    <div style='margin-top:10px;'>
    Login to read your messages
    <br />
    <br />
    <a href="#" id="login-link" data-toggle="modal" data-target="#auth-modal">
        Login Here</a>
    </div>
    <?php

} else {
    $csrf = csrf();
$mquery = $conn->prepare('Select count(a.message) as count, b.url as url from messages a join users b on a.to_user = b.uname where a.to_user = ? and a.unread = 1 and a.del_in = 0;');
$mquery->bind_param('s', $uname);
if ($mquery->execute()) {
    $results = $mquery->get_result();
    while ( $result = $results->fetch_assoc()) {
        $mcount = $result['count'];
        $url = $result['url'];
    }
}
$mquery->close();
$userlist = array();
$userquery = $conn->query("Select uname from users;");
while ($row = $userquery->fetch_array()) {
    $userlist[] = $row[0];
}
$userquery->close();
?>

<script>
    var csrf = '<?=$session->getCsrfToken()->getValue()?>';
    </script>
    <div class="container">
        <div class="row d-flex align-items-baseline p-5 ">
        <img class="img img-responsive my-auto border rounded-circle" src="<?=$url?>" height="50px" weight="50px" >
<h1 class="title ml-2 my-auto"><?=ucfirst($uname)?>'s Mail</h1>
</div>
        <div class="row">
            <div class="col-3 col-lg-2">
            <div class="dropdown">
                    <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Actions
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <a href="#" id="read-button" class="dropdown-item">Mark Read</a>
                        <a href="#" id="delete-button" class="dropdown-item">Delete</a>
                        <a href="#" id="select-button" class="dropdown-item">Select All</a>
                    </div>
                    </div>
            </div>
            <div class="col-9 col-lg-10 d-flex justify-content-start align-items-baseline">
                <!-- Split button -->
                <button type="button" on-click="refreshBox();return false;" class="btn btn-primary mx-1" data-toggle="tooltip" title="Refresh"><i class="fas fa-sync-alt"></i>
                </button>
                <div class="form-inline d-inline-flex mx-1">
                    <input class="mail-search form-control" placeholder="Search" name="mailsearch" id="mailsearch" type="text">
                </div>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-md-3 d-none d-md-block col-lg-2">
                <a href="#" class="btn btn-danger btn-sm btn-block" data-toggle="modal"
                    data-target="#compose-modal" role="button">COMPOSE</a>
                <hr>
                <ul class="nav nav-pills flex-column">
                    <li class="active nav-item"><a id="inbox-pill" href="#inbox" class="nav-link active" data-toggle="pill" aria-controls="inbox" aria-selected="true"><span class="badge mcount float-right"><?=$mcount?></span>
                            Inbox </a>
                    </li>
                    <li class="nav-item"><a href="#outbox" id="outbox-pill" class="nav-link" data-toggle="pill" aria-controls="outbox" aria-selected="false">Outbox</a>
                    </li>
                    <li class="nav-item"><a href="#trash" id="trash-pill" class="nav-link" data-toggle="pill" aria-controls="trash" aria-selected="false">Trash</a>
                    </li>
                </ul>
            </div>
            <div class="col-md-9 col-lg-10">
            <ul class="nav nav-tabs">
                <li class="nav-item"><a href="#inbox" id="active-tab" class="nav-link active" data-toggle="tab">inbox</a></li>
                <li class="nav-item d-none"><a href="#conversation" id="conversation-tab" class="nav-link" data-toggle="tab">
                    Conversation</a></li>
            </ul>
                <!-- Tab panes -->
                <div class="tab-content mail-content">
                    <div class="tab-pane fade show active" id="inbox"  role="tabpanel" aria-labelledby="inbox-pill">
                        <ul class="inbox-list mail-list list-group" role="tablist">
                        </ul>
                        <nav aria-label="page inbox">
                        <ul class="inbox-pagination pagination text-muted"></ul>
                        </nav>
                    </div>
                    <div class="tab-pane fade" id="conversation"  role="tabpanel" aria-labelledby="conversation-tab">
                        <ul class="conversation-list mail-list list-group" role="tablist">
                        </ul>
                        <nav aria-label="page conversation">
                        <ul class="conversation-pagination paginiation text-muted"></ul>
                        </nav>
                    </div>
                    <div class="tab-pane fade" role="tabpanel" aria-labelledby="outbox-pill" id="outbox">
                        <ul class="outbox-list mail-list list-group" role="tablist">
                        </ul>
                        <nav aria-label="page outbox">
                        <ul class="outbox-pagination pagination text-muted"></ul>
                        </nav>
                    </div>
                    <div class="tab-pane fade" id="trash" role="tabpanel" aria-labelledby="trash-pill">
                        <ul class="trash-list mail-list list-group" role="tablist">
                        </ul>
                        <nav aria-label="page trash">
                        <ul class="trash-pagination pagination text-muted">
                        </ul>
                        </nav>
                    </div>
                </div>

            </div>
        </div>
    </div>


    <div class="modal fade" id="compose-modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog mail modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-dark text-light">
                    <span id="send-header">New Message</span>
                    <button type="button" class="close text-light" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="post" action="<?=W_FUNCTIONS?>mail.php" id="compose-form" >
                        <div class="form-group">
                            <label for="to_user">To:</label>
                            <input type="text" list="userlist" name="to_user" class="form-control" id="to_user" />
                            <datalist id="userlist">
                    <?php foreach ($userlist as $u) { ?>
                                <option value="<?=$u?>">
                    <?php } ?>
                            </datalist>
                        </div>
                        <div class="form-group">
                            <label for="subject">Subject:</label>
                            <input type="text" name="subject" class="form-control" id="subject" />
                        </div>
                        <div class="form-group">
                            <textarea name="message" placeholder="Message" class="form-control"></textarea>
                        </div>
                        <input type="hidden" name="action" value="send" />
                        <?=csrf()?>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" form="compose-form" id="send-button" class="btn btn-primary">Send</button>
                </div>
            </div>
        </div>
    </div>
</div>


<?php
}

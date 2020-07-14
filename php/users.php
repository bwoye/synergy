<?php
//session_start();
include_once "../include/sesscheck.php";
include_once "../include/connector.php";

$conn = Singleton::getInstance();

$response = array('error' => true);
if (!$conn) {
    $response['errmsg'] = "Unable to log into databases";
} else if (isset($_POST['newuser'])) {

    $fulname = $_POST['newuser'];
    $kpass = crypt($_POST['kpass'], $conn->getSalt());
    $userid = $_POST['userid'];
    $utype = $_POST['utype'];

    //find out if id is already taken
    $kl = $conn->run("SELECT userid FROM users WHERE userid=?", [$userid]);
    if ($kl->rowCount() > 0) {
        $response['error'] = true;
        $response['errmsg'] = "UserId is already taken";
    } else if ($_SESSION['utype'] != 'Ad') {
        $response['error'] = true;
        $response['errmsg'] = "You are not authorised to add new users";
    } else {
        /*
        +--------+-------------+------+-----+---------+----------------+
        | Field  | Type        | Null | Key | Default | Extra          |
        +--------+-------------+------+-----+---------+----------------+
        | idno   | int(11)     | NO   | PRI | NULL    | auto_increment |
        | userid | varchar(10) | YES  | MUL | NULL    |                |
        | mypage | varchar(15) | YES  |     | NULL    |                |
        | perms  | varchar(3)  | YES  |     | no      |                |
        +--------+-------------+------+-----+---------+----------------+
         */

        $conn->run("INSERT INTO users VALUES(?,?,?,?)", [$userid, $fulname, $kpass, $utype]);
        $response['error'] = false;
        $response['errmsg'] = "User added";
        $response['userid'] = $userid;
        $response['fulname'] = $fulname;
        $response['utype'] = $utype;

        $conn->run("INSERT INTO userperm VALUES(?,?,?,?)", [NULL, $userid, 'assess', 'no']);
        $conn->run("INSERT INTO userperm VALUES(?,?,?,?)", [NULL, $userid, 'budget', 'no']);
        $conn->run("INSERT INTO userperm VALUES(?,?,?,?)", [NULL, $userid, 'entities', 'no']);
        $conn->run("INSERT INTO userperm VALUES(?,?,?,?)", [NULL, $userid, 'enrol', 'no']);
        $conn->run("INSERT INTO userperm VALUES(?,?,?,?)", [NULL, $userid, 'district', 'no']);
        $conn->run("INSERT INTO userperm VALUES(?,?,?,?)", [NULL, $userid, 'skillarea', 'no']);
        $conn->run("INSERT INTO userperm VALUES(?,?,?,?)", [NULL, $userid, 'delall', 'no']);

    }

} else if (isset($_POST['delete'])) {
    if ($_SESSION['utype'] != 'Ad') {
        $response['error'] = true;
        $response['errmsg'] = "You are not allowed to delete users";
    } else {

      
        $kk = $conn->run("SELECT * FROM users WHERE userid=?", [$_POST['delete']]);

        $fd = $kk->fetch();
        if ($fd->userid == $_SESSION['userid']) {
            $response['error'] = true;
            $response['errmsg'] = "You cannot delete Yourself";
        } else {
            $rm = $conn->run("SELECT COUNT(*) FROM users WHERE utype=?", ['Ad']);
            $fx = $rm->fetch(PDO::FETCH_NUM);
            if ($fx[0] < 2 && $_POST['mytype'] == 'Ad') {
                $response['error'] = true;
                $response['errmsg'] = "You cannot delete the only administrator";
            } else {
                $conn->run("DELETE FROM users WHERE userid=?", [$_POST['delete']]);
                $response['error'] = false;
                $response['errmsg'] = "User Deleted";
            }

        }
    }
} else if (isset($_POST['change'])) {
    $kpass = crypt($_POST['kpass'], $conn->getSalt());
    $userid = $_POST['change'];

    if ($_SESSION['utype'] == 'Ad') {
        $conn->run("UPDATE users SET kpass=:kpass WHERE userid=:userid", ['kpass' => $kpass, 'userid' => $userid]);
        $response['error'] = true;
        $response['errmsg'] = "Password updated successfully";
    } else {
        $conn->run("UPDATE users SET kpass=:kpass WHERE userid=:userid", ['kpass' => $kpass, 'userid' => $_SESSION['userid']]);
        $response['error'] = false;
        $response['errmsg'] = "your password updated successfuly";
    }
} else if (isset($_POST['perms'])) {
    $perms = $_POST['perms'];
    $idno = $_POST['idno'];

    $conn->run("UPDATE userperm SET perms=:perms WHERE idno=:idno", ['perms' => $perms, 'idno' => $idno]);
} else {
    //echo $_SESSION['userid'];
    //echo '<br>'.$_SESSION['utype'];
    //exit();
    //if($_SESSION['utype'] == 'Ad'){

    $uarr = array('Ad' => 'Admin', 'Del' => 'Delegate');
    $db = $conn->run("SELECT * FROM users");
    $response['error'] = false;
    for ($j = 0; $v = $db->fetch(); $j++) {
        $response['users'][$j]['userid'] = $v->userid;
        $response['users'][$j]['fulname'] = $v->fulname;
        $response['users'][$j]['utype'] = $uarr[$v->utype];
        $response['users'][$j]['ucode'] = $v->utype;
    }
    $response['curruser'] = $_SESSION['userid'];
    $response['currlevel'] = $_SESSION['utype'];
    $response['currfulname'] = $_SESSION['fulname'];
    //$response['currlevel'] = $_SESSION['utype'];
    //}

    $cc = $conn->run("SELECT * FROM userperm");

    for ($j = 0; $df = $cc->fetch(); $j++) {
        $response['perms'][$j]['idno'] = $df->idno;
        $response['perms'][$j]['mypage'] = $df->mypage;
        $response['perms'][$j]['perms'] = $df->perms;
        $response['perms'][$j]['userid'] = $df->userid;
    }
}
echo json_encode($response);

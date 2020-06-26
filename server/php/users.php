<?php
// Be adviced - SQL database MUST have 'userid' INT, AUTO_INCREMENT field as first column
// All othed fields MUST have default values ('null' at least), if they have not, use 'ALTER TABLE users ALTER field SET DEFAULT 'null';
//

// NEW! get and convert $_REQUEST superglobal variable from index.php JSON to array to read cmd: instruction
$_REQUEST = json_decode(file_get_contents('php://input'), true);

require("w2db.php");
require("w2lib.php");

$db = new dbConnection("mysql");
// Put your database parameters here (host, login, pass, dbname, port). Remove it, or replace by "XXXXX" if you share the code.
$db->connect("localhost", "testuser", "XXXPASSXXX", "TESTTABLE", "3306");
// put your data table name
$tablename = 'users';

switch ($_REQUEST['cmd']) {

    case 'get':
        if (array_key_exists('recid', $_REQUEST)){  // if true , then is a 'get' only one record with recid
            /*$sql = "SELECT userid, fname, lname, email, login, password FROM users WHERE userid = ".$_REQUEST['recid'];*/
            $sql = "SELECT * FROM ".$tablename." WHERE userid = ".$_REQUEST['recid'];
            $res = $w2grid->getRecord($sql);
        }
        else{        
            $sql  = "SELECT * FROM ".$tablename." WHERE ~search~ ORDER BY ~sort~";
        $res = $w2grid->getRecords($sql, null, $_REQUEST);
        
        }        
        $w2grid->outputJSON($res);
        return $w2grid;   
        break;

    case 'delete':
        $res = $w2grid->deleteRecords($tablename, "userid", $_REQUEST);
        $w2grid->outputJSON($res);
        break;

    case 'save':
        $res = $w2grid->saveRecord($tablename, 'userid', $_REQUEST);
        $w2grid->outputJSON($res);
        break;

    default:
        $res = Array();
        $res['status']  = 'error';
        $res['message'] = 'Command "'.$_REQUEST['cmd'].'" is not recognized.';
        $res['postData']= $_REQUEST;
        $w2grid->outputJSON($res);
        break;
}

?>
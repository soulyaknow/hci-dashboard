<?php

require_once('dbcon.php');
class backend
{
    public function doLogin($email, $password)
    {
        return self::login($email, $password);
    }
    public function doRegister($username,$email,$password,$cpassword)
    {
        return self::register($username,$email,$password,$cpassword);
    }
    public function viewData()
    {
        return self::recentRegister();
    }
    public function isOnline($flag,$email)
    {
        return self::onStatus($flag,$email);
    }
    public function isOffline($flag)
    {
        return self::offStatus($flag);
    }

    private function checkLoginCredentials($user, $pass)
    {
        if ($user != "" && $pass != "") {
            return true;
        } else {
            return false;
        }
    }

    private function checker($username, $email,$password,$cpassword)
    {
        if ($username != "" && $email != "" && $password != "" && $cpassword != "") {
            return true;
        } else {
            return false;
        }
    }

    private function login($email, $password)
    {
        try {
            if ($this->checkLoginCredentials($email, $password)) {
                $conn = new database();
                $tmp = md5($password);
                if ($conn->getStatus()) {
                    $stmt = $conn->getCon()->prepare($this->loginQuery());
                    $stmt->execute(array($email, $tmp));
                    $result = $stmt->fetch();
                    $type = null;
                    $isdisabled = null;
                    $pass = null;
                    if ($result > 0) {
                        $_SESSION['email'] = $email;
                        $_SESSION['password'] = $tmp;
                        $type = $result['type'];
                        $isdisabled = $result['isdisabled'];
                        $pass = $result['password'];
                        $conn->dbClose();
                        return $this->userConfirmation($type, $isdisabled, $pass, $password);
                    } else {
                        $conn->dbClose();
                        return 404;
                    }
                } else {
                    return 403;
                }
            } else {
                return 403;
            }
        } catch (PDOException $th) {
            return 501;
        }
    }
    private function register($username, $email, $password, $cpassword)
    {
        try {
            if ($this->checker($username, $email,$password,$cpassword)) {
                $conn = new database();
                if ($conn->getStatus()) {
                    $stmt = $conn->getCon()->prepare($this->registerQuery());
                    $stmt->execute(array($username, $email, md5($password), md5($cpassword), $this->getDate(), 1, "User", 0, 0));
                    $res = $stmt->fetch();
                    if (!$res) {
                        $conn->dbClose();
                        return 200;
                    } else {
                        $conn->dbClose();
                        return 404;
                    }
                } else {
                    return 403;
                }
            } else {
                return 403;
            }
        } catch (PDOException $th) {
            return $th;
        }
    }
    private function recentRegister()
    {
        try {
                $conn = new database();
                if ($conn->getStatus()) {
                    $stmt = $conn->getCon()->prepare($this->viewDataQuery());
                    $stmt->execute(array());
                    $result = $stmt->fetchAll();
                    $conn->dbClose();
                    return json_encode($result);
                } else {
                    return "403";
                }
            }catch (PDOException $th) {
            return "501";
        }
    }

    private function onStatus($flag,$email)
    {
        try {
                $conn = new database();
                if ($conn->getStatus()) {
                    $stmt = $conn->getCon()->prepare($this->statusQuery());
                    $stmt->execute(array($flag,$email));
                    $result = $stmt->fetch(PDO::FETCH_ASSOC);
                    if (!$result) {
                        $conn->dbClose();
                        return 1;
                    } else {
                        $conn->dbClose();
                        return 0;
                    }
                }
        } catch (PDOException $th) {
            return $th;
        }
    }

    private function offStatus($flag)
    {
        try {
            $conn = new database();
            if ($conn->getStatus()) {
                $stmt = $conn->getCon()->prepare($this->offStatusQuery());
                $stmt->execute(array($flag,$this->getId()));
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                if (!$result) {
                    $conn->dbClose();
                    return 1;
                } else {
                    $conn->dbClose();
                    return 0;
                }
            }
    } catch (PDOException $th) {
        return $th;
    }
    }

    private function getId()
    {
        try {
            $db = new database();
            if ($db->getStatus()) {
                $stmt = $db->getCon()->prepare($this->loginQuery());
                $stmt->execute(array($_SESSION['email'], $_SESSION['password']));
                $tmp = null;
                $row = $stmt->fetch();
                if ($row > 0) {
                    $tmp = $row['id'];
                }
                return $tmp;
            }
        } catch (PDOException $th) {
            echo $th;
        }
    }

    //customize function
    private function getDate()
    {
        return date("m / d / y");
    }

    private function userConfirmation($type,  $isdisabled, $pass, $password)
    {
        if ($type == "Admin"  && $isdisabled == 0 && md5($password) == $pass) {
            //admin
            return 1;
        } elseif ($type == "User" && $isdisabled == 0 && md5($password) == $pass) {
            //user
            return 2;
        } elseif ($type == "User" && $isdisabled == 1 && md5($password) == $pass) {
            //lock account
            return 3;
        } else {
            return "error";
        }
    }

    private function loginQuery()
    {
        return "SELECT * FROM user WHERE email = ? AND `password` = ?";
    }
    private function registerQuery()
    {
        return "INSERT INTO user(`username`, `email`, `password`, `cpassword`, `joined`, `recent`, `type`, `status`, `isdisabled`) VALUES (?,?,?,?,?,?,?,?,?)";
    }
    private function statusQuery()
    {
        return "UPDATE user SET `status` = ? WHERE `email` = ? ";
    }
    private function offStatusQuery()
    {
        return "UPDATE user SET `status` = ? WHERE `id` = ? ";
    }
    private function viewDataQuery()
    {
        return "SELECT * FROM user WHERE recent = 1";
    }
}
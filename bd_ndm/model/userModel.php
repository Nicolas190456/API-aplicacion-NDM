<?php
require_once "conDB.php";
class UserModel{

    static public function createuser($data){
        $cantMail=self::getMail($data["use_mail"]);
        if ($cantMail==0) {
            $query="";
            $query= "INSERT INTO users (use_id,use_mail,use_pss,use_dateCreate,us_identifier,us_key,us_status) VALUES (NULL,:use_mail,:use_pss,:use_dateCreate,:us_identifier,:us_key,:us_status);";            
            $status="0";
            $statement = connection::connecction()->prepare($query);
            $statement-> bindParam(":use_mail",$data["use_mail"],PDO::PARAM_STR);
            $statement-> bindParam(":use_pss",$data["use_pss"],PDO::PARAM_STR);
            $statement-> bindParam(":use_dataCreate",$data["use_dataCreate"],PDO::PARAM_STR);
            $statement-> bindParam(":us_identifier",$data["us_identifier"],PDO::PARAM_STR);
            $statement-> bindParam(":us_key",$data["us_key"],PDO::PARAM_STR);
            $statement-> bindParam(":us_status",$status,PDO::PARAM_STR);
            $message= $statement ->execute() ? "ok":connection::connecction()->errorInfo;
            $statement->closeCursor();
            $statement=null;
            $query="";
        }else{
            $message="usuario ya registrado";
        }
        return $message;

        
    }
    static private function getMail($mail){
        $query="";

        $query="SELECT use_mail FROM users WHERE use_mail='$mail';";
        $statement = connection::connecction()->prepare($query);
        $statement ->execute();
        $result=$statement->rowCount();
        return $result;
    }
    static function getUsers($id){
        $query="";

        $id= is_numeric($id) ? $id : 0;
        $query="SELECT use_id,use_mail,use_dataCreate FROM users";
        $query.=($id>0)?"WHERE users.use_id='$id'AND ":"";
        $query.=($id>0)?"us_status'1';":" WHERE us_status ='!';";
        $statement = connection::connecction()->prepare($query);
        $statement ->execute();
        $result=$statement->fetchAll(PDO::FETCH_ASSOC);
        return $result;


    }
    static public function updateUser($id, $data) {
        $query = "UPDATE users SET use_mail=:use_mail, use_pss=:use_pss WHERE use_id=:use_id";
        $statement = connection::connecction()->prepare($query);
        $statement->bindParam(":use_mail", $data["use_mail"], PDO::PARAM_STR);
        $statement->bindParam(":use_pss", $data["use_pss"], PDO::PARAM_STR);
        $statement->bindParam(":use_id", $id, PDO::PARAM_INT);

        $message = $statement->execute() ? "ok" : connection::connecction()->errorInfo;
        $statement->closeCursor();
        $statement = null;
        return $message;
    }

    static public function deleteUser($id) {
        $query = "DELETE FROM users WHERE use_id=:use_id";
        $statement = connection::connecction()->prepare($query);
        $statement->bindParam(":use_id", $id, PDO::PARAM_INT);

        $message = $statement->execute() ? "ok" : connection::connecction()->errorInfo;
        $statement->closeCursor();
        $statement = null;
        return $message;
    }
    static public function login($data){
        $query="";

        $user=$data['use_mail'];
        $pss=md5($data['use_pss']);
        if(!empty($user)&&!empty($pss)){
            $query= "SELECT us_identifier,us_key,use_id FROM users WHERE use_mail='$user'
            and use_pss='$pss'and us_status= '1'";
            $statement = connection::connecction()->prepare($query);
            $statement ->execute();
            $result=$statement->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        }else{
            $mensaje = array(
                "COD"=> "000",
                "MENSAJE"=>("ERROR EN CREDENCIALES")
            );
            return $mensaje;

        }

    }
    static public function getUserAuth(){
        $query="";
        $query="SELECT us_identifier,us_key,use_id FROM users WHERE us_status= '1'";
        $statement = connection::connecction()->prepare($query);
        $statement ->execute();
        $result=$statement->fetchAll(PDO::FETCH_ASSOC);
        return $result;
        


    }







}
?>
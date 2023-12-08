<?php
    class userController{
        private $_method;
        private $_complement;
        private $_data;

        function __construct($method,$complement,$data){
            $this->_method=$method;
            $this->_complement=$complement==null ? 0:$complement;
            $this->_data = $data !=0 ? $data :"";
            // echo($this->_method);
            // echo($this->_complement);
        }
        public function index(){

            switch($this->_method){
                case 'GET':
                if($this->_complement==0){
                    $user= UserModel::getUsers(0);
                    $json=$user;
                    echo json_encode($json,true);
                    return;
                }else{
                    $user= UserModel::getUsers($this->_complement);
                    $json=$user;
                    echo json_encode($json,true);
                    return;

                }   
                case "POST":

                    $createUser=UserModel::createuser($this->generateSalting());
                    $json=array(
                        "response: "=>createuser
                    );
                    echo json_encode($json,true);
                    return;
                case 'UPDATE':
                    $updateUser = UserModel::updateUser($this->_complement, $this->generateSalting());
                    $json = array("response" => $updateUser);
                    echo json_encode($json, true);
                    return;
                case 'delete':
                    $deleteUser = UserModel::deleteUser($this->_complement);
                    $json = array("response" => $deleteUser);
                    echo json_encode($json, true);
                    return;
                default:
                    $json=array(
                    "response: "=>"not found"
                    );
                    echo json_encode($json,true);
                    return; 


            }
        
        }
        private function generateSalting(){
            $trimmedData="";
            if(($this->_data != "" ) ||(!empty($this->_da))){
                $trimmedData= array_map('trim',$this->_data);
                $trimmedData['use_pss']=md5($trimmedData['use_pss']);
                $identifier=str_replace("$","ue3",crypt($trimmedData["use_mail"],'ue56'));
                $key=str_replace("$","ue3",crypt($trimmedData["use_mail"],'ue56'));
                $trimmedData['us_identifier']=$identifier;
                $trimmedData['us_key']=$key;
                return $trimmedData;

            }
        }
    }

    








?>
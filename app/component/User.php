<?php

namespace uranium\component;

use uranium\model\UserAttributeModel;
use uranium\model\UserModel;
use uranium\model\NotificationModel;

class User{
    private $_user_id;
    private $_userData = null;

    public function __construct($user_id){
        $this->_user_id = $user_id;
    }

    public function getUserId(){
        return $this->_user_id;
    }

    public function getSessionCount(){
        $sessions = UserHandler::getAuthenticatedUserSessions();
        return count($sessions);
    }

    public function getNotifications(){
        $notificationModel = new NotificationModel();
        $notifications = $notificationModel->where("userId", $this->_user_id)->get()->getResults();
        return $notifications;
    }

    public function getNotificationCount(){
        $notifications = $this->getNotifications();
        return count($notifications);
    }

    public function getUsername():String {
        $data = $this->getUserData();
        return $data["username"];
    }

    public function getUserData():Array {
        if(!is_null($this->_userData)){
            return $this->_userData;
        };
        $userModel = new userModel();
        $result = $userModel->where("id", $this->_user_id)
                            ->get()
                            ->getResults();
        if(count($result) > 0){
            $this->_userData = array_pop($result);
            return $this->_userData;
        }else{
            return [];
        };
    }

    public function isActive(){
    
    }

    public function getAttribute(String $key) {
        $attributeModel = new UserAttributeModel();
        $selectors = [
            "userId" => $this->_user_id,
            "attrName" => $key
        ];
        $results = $attributeModel->whereAnd($selectors)
                                  ->get()
                                  ->getResults();

        if(count($results) > 0){
            return array_pop($results)["attrValue"];
        }else{
            return null;
        };
    }
};

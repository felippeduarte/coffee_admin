<?php
class RequireLogin extends CBehavior
{
    const LOGIN_PAGE = '/bulebar/site/login';

    public function attach($owner)
    {
        $owner->attachEventHandler('onBeginRequest', array($this, 'handleBeginRequest'));
    }
    
    public function handleBeginRequest($event)
    {
        if (Yii::app()->user->isGuest && !in_array($_SERVER['REQUEST_URI'],array(self::LOGIN_PAGE))) {
            Yii::app()->user->loginRequired();
        }
    }
}
?>
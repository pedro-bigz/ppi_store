<?php namespace Core\Auth;

class Auth
{
    private $auth;
    private $session;
    private $user;

    public function __construct($session)
    {
        $this->session = $session;
    }

    public static function user()
    {
        return new self($_SESSION['auth']);
    }

    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }

    public function getUserId()
    {
        return $this->user['id'];
    }

    public function getAuthIdentifier()
    {
        if (!$this->auth = base64_decode($this->session)) {
            return null;
        }
        if (!$this->auth = json_decode($this->auth, true)) {
            return null;
        }
        return $this->setUser($this->auth)->getUserId();
    }
}
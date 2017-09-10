<?php namespace Entity;
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../core/DbManager.php';

class User
{
    const TABLE_NAME = "user";

    private $id;
    private $name;
    private $profile;
    private $token;
    private $is_active;
    private $createdAt;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->is_active = 1;
    }

    public function selfSave()
    {
        global $dbConfig;
        $sql = "Insert into ". self::TABLE_NAME ." (id, profile_pic, `name`, token, is_active) VALUES ('', ?, ?, ?, ?);";
        $dbm = new \core\DbManager();
        return $dbm->executeQuery($sql, [$this->profile, $this->name, $this->token, $this->is_active]);
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getProfile()
    {
        return $this->profile;
    }

    /**
     * @param mixed $profile
     */
    public function setProfile($profile)
    {
        $this->profile = $profile;
    }

    /**
     * @return mixed
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @param mixed $token
     */
    public function setToken($token)
    {
        $this->token = $token;
    }

    /**
     * @return mixed
     */
    public function getisActive()
    {
        return $this->is_active;
    }

    /**
     * @param mixed $is_active
     */
    public function setIsActive($is_active)
    {
        $this->is_active = $is_active;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param mixed $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }


}
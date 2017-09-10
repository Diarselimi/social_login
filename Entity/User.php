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
    private $userId;

    private $databaseManager;

    public function __construct($id = null)
    {
        $this->databaseManager = new \core\DbManager();

        if($id) {
            $sql = "SELECT * FROM ". self::TABLE_NAME ." WHERE user_id = :user_id";
            $data = $this->databaseManager->findOne($sql, $id);
            $this->id = $data["id"];
            $this->name = $data["name"];
            $this->profile = $data["profile_pic"];
            $this->token = $data["token"];
            $this->is_active = $data["is_active"];
            $this->createdAt = $data["created_at"];
            $this->userId = $data["user_id"];
            return $this;
        }
        $this->createdAt = new \DateTime();
        $this->is_active = 1;
    }

    public function selfSave()
    {
        if ($this->doesUserExists($this->userId)) {
            $sql = "Update table ". self::TABLE_NAME ." set profile_pic = ?, name = ?, is_active = ?, token = ?, user_id = ? ;";
            return $this->databaseManager->executeQuery($sql, [$this->profile, $this->name, $this->is_active, $this->token, $this->userId]);
        }
        $sql = "Insert into ". self::TABLE_NAME ." (id, profile_pic, `name`, token, is_active, user_id) VALUES (?, ?, ?, ?, ?, ?);";

        return $this->databaseManager
            ->executeQuery($sql, [$this->id, $this->profile, $this->name, $this->token, $this->is_active, $this->userId]);
    }

    public function doesUserExists($id)
    {
        return $this->databaseManager
            ->findOne("Select * from ". self::TABLE_NAME." where user_id = :user_id", $id);
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

    /**
     * @return mixed
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @param mixed $userId
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;
    }
}

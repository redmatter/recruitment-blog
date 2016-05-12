<?php

namespace RedMatter\InterestingBlog\Repository;

use RedMatter\InterestingBlog\Entity\User;

class UserRepository
{
    /** @var \PDO */
    private $database;

    public function __construct()
    {
        $this->database = new \PDO('mysql:dbname=blog;host=127.0.0.1', 'root', '');
    }

    /**
     * @return User[]
     */
    public function getAll()
    {
        $results = $this->database->query("SELECT * FROM User")->fetchAll();
        foreach ($results as $row) {
            $users[] = $this->getById($row['UserId']);
        }
        return $users;
    }

    /**
     * @param int $id
     *
     * @return User
     */
    public function getById($id)
    {
        $result = $this->database->query("SELECT * FROM User WHERE UserId = $id")[0];

        $user = new User();
        $user->id = $id;
        $user->email_address = $result['EmailAddress'];
        $user->password = $result['Password'];
        $user->setEnabled($result['Status'] == User::STATUS_ENABLED);

        return $user;
    }

    public function save(User $user)
    {
        $sql = "INSERT INTO User VALUES ($user->id, '$user->email_address', '$user->password', '" . ($user->isEnabled() ? User::STATUS_ENABLED : User::STATUS_SUSPENDED) . "') ON DUPLICATE KEY UPDATE EmailAddress = '$user->email_address', Password = '$user->password', Status = '" . ($user->isEnabled() ? User::STATUS_ENABLED : User::STATUS_SUSPENDED) . "'";
        $rows_affected = $this->database->exec($sql);

        if ($rows_affected === 1) {
            return true;
        }
        return false;
    }
}

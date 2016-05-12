<?php

namespace RedMatter\InterestingBlog\Repository;

use RedMatter\InterestingBlog\Entity\BlogPost;

class BlogPostRepository
{
    /** @var \PDO */
    private $database;

    /** @var UserRepository */
    private $user_repository;

    public function __construct()
    {
        $this->database = new \PDO('mysql:dbname=blog;host=127.0.0.1', 'root', '');
        $this->user_repository = new UserRepository();
    }

    /**
     * @return BlogPost[]
     */
    public function getAll() {
        $posts = array();
        $results = $this->database->query("SELECT * FROM BlogPost")->fetchAll();
        foreach ($results as $row) {
            $posts[] = $this->getById($row['BlogPostId']);
        }
        return $posts;
    }

    public function getById($id) {
        $result = $this->database->query("SELECT * FROM BlogPost WHERE BlogPostId = $id")[0];

        $post = new BlogPost();
        $post->id = $result['BlogPostId'];
        $post->user = $this->user_repository->getById($result['UserId']);
        $post->subject = $result['Subject'];
        $post->message = $result['Message'];
        $post->created = $result['Created'];

        return $post;
    }

    /**
     * @param $post
     * @return bool
     */
    public function save(BlogPost $post)
    {
        $sql = "INSERT INTO BlogPost VALUES ($post->id, {$post->user->id}, '$post->subject', '$post->message', 'NOW()') ON DUPLICATE KEY UPDATE UserId = {$post->user->id}, Subject = '$post->subject', Message = '$post->message'";
        $rows_affected = $this->database->exec($sql);

        if ($rows_affected === 1) {
            return true;
        }
        return false;
    }
}

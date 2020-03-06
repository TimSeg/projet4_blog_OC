<?php

namespace App\Model;

use App\Model\Factory\PDOFactory;

/**
 * Class CommentsModel
 * Manages Comments Data
 * @package App\Model
 */
class CommentsModel extends MainModel
{

    /**
     * Lists selected post from the id or another key
     * @param string $value
     * @param string $key
     * @return array|mixed
     */


    public function getPost($postId)
    {

        $query = 'SELECT * FROM posts WHERE id = ?';
        return $this->database->getData($query);
    }



    public function getCommentsFromPost($postId)
    {
        $query = 'SELECT * FROM comments WHERE post_id = ? ORDER BY created_date DESC';
        return $this->database->getAllData($query);
    }


}
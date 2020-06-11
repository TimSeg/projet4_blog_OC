<?php


namespace App\Model;

use App\Model\Factory\PdoFactory;

class ArticlesModel extends MainModel
{
    /**
     * @param string $title
     * @param string $content
     * @return bool
     */
    public function createIt(string $title, string $content)
    {
        $req = PdoFactory::getPdo()->prepare('INSERT INTO Articles(title,content) VALUES(?,?)');
        return $req->execute(array($title,$content));
    }

    /**
     * @param int $id
     * @param string $title
     * @param string $content
     */
    public function modifyIt(int $id, string $title ,string $content)
    {
        $content = html_entity_decode($content);
        $req = PdoFactory::getPdo()->prepare('UPDATE Articles SET title = ? , content = ? WHERE id = ?');
        $req->execute(array($title,$content,$id));
    }

    /**
     * Lists last post from the id or another key
     * @param string $value
     * @param string $key
     * @return array|mixed
     */

    public function getLastArticle()
    {
        $query = 'SELECT * FROM Articles ORDER BY id DESC LIMIT 1';

        return $this->database->getAllData($query);
    }

}
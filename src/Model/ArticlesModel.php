<?php


namespace App\Model;

use App\Model\Factory\PDOFactory;

class ArticlesModel extends MainModel
{
    /**
     * @param string $title
     * @param string $content
     * @return bool
     */
    public function createIt(string $title, string $content)
    {
        $req = PDOFactory::getPDO()->prepare('INSERT INTO articles(title,content) VALUES(?,?)');
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
        $req = PDOFactory::getPDO()->prepare('UPDATE articles SET title = ? , content = ? WHERE id = ?');
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
        $query = 'SELECT * FROM articles ORDER BY id DESC LIMIT 1';

        return $this->database->getAllData($query);
    }




}
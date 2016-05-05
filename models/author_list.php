<?php
class AuthorList extends Object
{
        private $authors;

        public function __construct() {

        }

        public function loadValues() {
            $db = Loader::db();
            Loader::model('author');

            $akID = $db->query("SELECT akID FROM AttributeKeys WHERE akHandle = 'regular_author'");
            while($row=$akID->fetchrow()){
                    $akIDc = $row['akID'];
            }
            $akv = $db->execute("SELECT value FROM atSelectOptions WHERE akID = $akIDc");

            $this->authors = array();
            while($row=$akv->fetchrow()){
                    $author = new Author();
                    $author->setFromCompiledString($row['value']);
                    $this->authors[] = $author;
            }
            if (empty($values)){
                    $values = array();
            }
        }

        public function getNames()
        {
            $names = array();
            foreach ($this->authors as $author) {
                $names[]=$author->getName();
            }
            return $names;
        }

        public function getEmailByName($name) {
            foreach ($this->authors as $author) {
                if ($author->getName() == $name) {
                    return $author->getEmail();
                }
            }
        }


}

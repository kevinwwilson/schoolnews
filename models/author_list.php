<?php
class AuthorList extends Object
{
        public $authors;

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
                    $author->setFromCompiledString($row);
            }
            if (empty($values)){
                    $values = array();
            }
        }

        public function findByName($name) {

        }


}

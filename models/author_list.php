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

        /*
        Requires that the attribute has one element that is defined as "Default" showing the e-mail address
        that will be shown in the case of an author or combination of authors that is not specifically registered
        */
        public function getEmailByName($name) {
            foreach ($this->authors as $author) {
                if ($author->getName() == $name) {
                    return $author->getEmail();
                }
            }
            //if the specific name of the author is not defined, then return the default
            return $this->getEmailByName('Default');
        }


}

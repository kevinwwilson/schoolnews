<?php
class Author extends Object
{
        private $name;
        private $email;

        public function getName() {
            return $name;
        }

        public function getEmail() {
            return $email;
        }

        public function setFromCompiledString($authorDetails) {
            $parts = explode(',', $authorDetails);
            $this->name = $parts[0];
            $this->email = $parts[1];
        }
}

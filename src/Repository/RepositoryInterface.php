<?php

// Supporting classes...

namespace RedMatter\InterestingBlog\Repository {

    use RedMatter\InterestingBlog\Entity\User;

    interface RepositoryInterface
    {
        /**
         * @return mixed[]
         */
        public function getAll();

        /**
         * @return User
         */
        public function getById($id);
    }
}

<?php

namespace WG\FileBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * TagRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class FileRepository extends EntityRepository {

    public function find($id) {
        $query = $this->getEntityManager()->createQuery(
                'SELECT f FROM WGFileBundle:File f
                 WHERE f.id = :id
                 AND f.state = 1');
        $query->setParameter('id', $id);
        return $query->getSingleResult();
    }

    
    public function getFilesWithTags(\WG\UserBundle\Entity\User $user, $tags, $limit = 0) {
        //TODO
        $filesIdLoaded = array();

        $out = array();

        //AUTHOR
        $query = 'SELECT f FROM WGFileBundle:File f
                  JOIN f.author a';
        foreach ($tags as $key => $tag) {
            $query .= ' JOIN f.tags t' . $key;
        }
        $query .= ' WHERE f.state = 1
                    AND a.id = :userId';
        foreach ($tags as $key => $tag) {
            $query .= ' AND t' . $key . '.id = ' . $tag;
        }
        $query = $this->getEntityManager()->createQuery($query);
        if ($limit > 0) {
            $query->setMaxResults($limit - sizeof($filesIdLoaded));
        }
        $query->setParameter('userId', $user->getId());

        $files_author = $query->getResult();

        foreach ($files_author as $file) {
            $filesIdLoaded[] = $file->getId();
        }

        $out = array_merge($out, $files_author);

        //Membre projet
        if (sizeof($filesIdLoaded) < $limit || $limit == 0) {

            $query = 'SELECT f FROM WGFileBundle:File f
                     JOIN f.project p
                     JOIN p.users u';
            foreach ($tags as $key => $tag) {
                $query .= ' JOIN f.tags t' . $key;
            }
            $query .= ' WHERE u.id = :userId
                     AND f.state = 1';
            foreach ($tags as $key => $tag) {
                $query .= ' AND t' . $key . '.id = ' . $tag;
            }

            if (sizeof($filesIdLoaded) > 0) {
                $query .= ' AND f.id NOT IN ' . $this->arrayToString($filesIdLoaded);
            }

            $query = $this->getEntityManager()->createQuery($query);
            if ($limit > 0) {
                $query->setMaxResults($limit - sizeof($filesIdLoaded));
            }
            $query->setParameter('userId', $user->getId());

            $files_project = $query->getResult();

            foreach ($files_project as $file) {
                $filesIdLoaded[] = $file->getId();
            }

            $out = array_merge($out, $files_project);
        }

        //Sans projet, sans groupe
        if (sizeof($filesIdLoaded) < $limit || $limit == 0) {

            $query = 'SELECT f FROM WGFileBundle:File f';
            foreach ($tags as $key => $tag) {
                $query .= ' JOIN f.tags t' . $key;
            }
            $query .= ' WHERE f.project is null
                     AND f.state = 1';
            foreach ($tags as $key => $tag) {
                $query .= ' AND t' . $key . '.id = ' . $tag;
            }

            if (sizeof($filesIdLoaded) > 0) {
                $query .= ' AND f.id NOT IN ' . $this->arrayToString($filesIdLoaded);
            }

            $query = $this->getEntityManager()->createQuery($query);

            if ($limit > 0) {
                $query->setMaxResults($limit - sizeof($filesIdLoaded));
            }
            $files_public = $query->getResult();

            $Size_files_public = sizeof($files_public);
            for ($i = 0; $i < $Size_files_public; $i++) {
                if (sizeof($files_public[$i]->getUsersgroups()) > 0) {
                    unset($files_public[$i]);
                }
            }

            foreach ($files_public as $file) {
                $filesIdLoaded[] = $file->getId();
            }

            $out = array_merge($out, $files_public);
        }

        //Sans projet, membre groupe
        if (sizeof($filesIdLoaded) < $limit || $limit == 0) {

            $query = 'SELECT f FROM WGFileBundle:File f
                     JOIN f.usersgroups ug
                     JOIN ug.users u';
            foreach ($tags as $key => $tag) {
                $query .= ' JOIN f.tags t' . $key;
            }
            $query .= ' WHERE f.project is null
                     AND u.id = :userId
                     AND f.state = 1';
            foreach ($tags as $key => $tag) {
                $query .= ' AND t' . $key . '.id = ' . $tag;
            }

            if (sizeof($filesIdLoaded) > 0) {
                $query .= ' AND f.id NOT IN ' . $this->arrayToString($filesIdLoaded);
            }

            $query = $this->getEntityManager()->createQuery($query);

            $query->setParameter('userId', $user->getId());
            if ($limit > 0) {
                $query->setMaxResults($limit - sizeof($filesIdLoaded));
            }
            $files_group = $query->getResult();

            foreach ($files_group as $file) {
                $filesIdLoaded[] = $file->getId();
            }

            $out = array_merge($out, $files_group);
        }

        $out = $this->usort_filesOnDate($out);

        return $out;
    }

    public function getProjectFiles($project, $user) {
        return array();
    }

    public function getGroupFiles($usersgroup, $user) {
        return array();
    }

    public function getFiles(\WG\UserBundle\Entity\User $user, $limit = 0) {
        $filesIdLoaded = array();

        $out = array();

        //AUTHOR
        $query = 'SELECT f FROM WGFileBundle:File f
                 JOIN f.author a
                 WHERE a.id = :userId
                 AND f.state = 1';
        $query = $this->getEntityManager()->createQuery($query);
        if ($limit > 0) {
            $query->setMaxResults($limit - sizeof($filesIdLoaded));
        }
        $query->setParameter('userId', $user->getId());

        $files_author = $query->getResult();
        foreach ($files_author as $file) {
            $filesIdLoaded[] = $file->getId();
        }

        $out = array_merge($out, $files_author);

        //Membre projet
        if (sizeof($filesIdLoaded) < $limit || $limit == 0) {

            $query = 'SELECT f FROM WGFileBundle:File f
                     JOIN f.project p
                     JOIN p.users u
                     WHERE u.id = :userId
                     AND f.state = 1';

            if (sizeof($filesIdLoaded) > 0) {
                $query .= ' AND f.id NOT IN ' . $this->arrayToString($filesIdLoaded);
            }

            $query = $this->getEntityManager()->createQuery($query);
            if ($limit > 0) {
                $query->setMaxResults($limit - sizeof($filesIdLoaded));
            }
            $query->setParameter('userId', $user->getId());

            $files_project = $query->getResult();

            foreach ($files_project as $file) {
                $filesIdLoaded[] = $file->getId();
            }

            $out = array_merge($out, $files_project);
        }

        //Sans projet, sans groupe
        if (sizeof($filesIdLoaded) < $limit || $limit == 0) {

            $query = 'SELECT f FROM WGFileBundle:File f
                     WHERE f.project is null
                     AND f.state = 1';

            if (sizeof($filesIdLoaded) > 0) {
                $query .= ' AND f.id NOT IN ' . $this->arrayToString($filesIdLoaded);
            }

            $query = $this->getEntityManager()->createQuery($query);

            if ($limit > 0) {
                $query->setMaxResults($limit - sizeof($filesIdLoaded));
            }
            $files_public = $query->getResult();

            $Size_files_public = sizeof($files_public);
            for ($i = 0; $i < $Size_files_public; $i++) {
                if (sizeof($files_public[$i]->getUsersgroups()) > 0) {
                    unset($files_public[$i]);
                }
            }

            foreach ($files_public as $file) {
                $filesIdLoaded[] = $file->getId();
            }

            $out = array_merge($out, $files_public);
        }

        //Sans projet, membre groupe
        if (sizeof($filesIdLoaded) < $limit || $limit == 0) {

            $query = 'SELECT f FROM WGFileBundle:File f
                     JOIN f.usersgroups ug
                     JOIN ug.users u
                     WHERE f.project is null
                     AND u.id = :userId
                     AND f.state = 1';

            if (sizeof($filesIdLoaded) > 0) {
                $query .= ' AND f.id NOT IN ' . $this->arrayToString($filesIdLoaded);
            }

            $query = $this->getEntityManager()->createQuery($query);

            $query->setParameter('userId', $user->getId());
            if ($limit > 0) {
                $query->setMaxResults($limit - sizeof($filesIdLoaded));
            }
            $files_group = $query->getResult();

            foreach ($files_group as $file) {
                $filesIdLoaded[] = $file->getId();
            }

            $out = array_merge($out, $files_group);
        }

        $out = $this->usort_filesOnDate($out);

        return $out;
    }

    private function arrayToString($array) {
        return '(' . implode(',', $array) . ')';
    }

    /**
     * The most recent first.
     * @param type $files
     * @return type
     */
    private function usort_filesOnDate($files) {
        usort($files, array('WG\FileBundle\Entity\FileRepository', 'cmp_file_date'));
        return $files;
    }

    /**
     * The older one first.
     * @param type $files
     * @return type
     */
    private function usort_filesOnDateInversed($files) {
        usort($files, array('WG\FileBundle\Entity\FileRepository', 'cmp_file_date_inversed'));
        return $files;
    }

    public static function cmp_file_date($a, $b) {
        if ($a->getEditedAt() === $b->getEditedAt()) {
            return 0;
        }

        return ($a->getEditedAt() > $b->getEditedAt()) ? -1 : 1;
    }

    public static function cmp_file_date_inversed($a, $b) {
        if ($a->getEditedAt() === $b->getEditedAt()) {
            return 0;
        }

        return ($a->getEditedAt() < $b->getEditedAt()) ? -1 : 1;
    }

}

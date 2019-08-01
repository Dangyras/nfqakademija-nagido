<?php

namespace App\Repository;

use App\Entity\Document;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Document|null find($id, $lockMode = null, $lockVersion = null)
 * @method Document|null findOneBy(array $criteria, array $orderBy = null)
 * @method Document[]    findAll()
 * @method Document[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DocumentRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Document::class);
    }

    /**
     * @param $value
     * @param $user
     * @return mixed
     */
    public function search($value, $user)
    {
        return $this->createQueryBuilder("document")
            ->where('document.documentName LIKE :value')
            ->setParameter('value', "%".$value."%")
            ->andWhere('document.user = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult()
            ;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function reminderDates($id)
    {
        return $this->createQueryBuilder("document")
            ->where('document.documentReminder IS NOT NULL AND document.user = :user')
            ->setParameter('user', $id)
            ->getQuery()
            ->getResult()
            ;
    }

    public function categoryFiles($category, $user)
    {
        return $this->createQueryBuilder("document")
            ->where('document.category = :category AND document.user = :user')
            ->setParameter('user', $user)
            ->setParameter('category', $category)
            ->getQuery()
            ->getResult()
            ;
    }

    public function tagFiles($tag, $user)
    {
        return $this->createQueryBuilder("document")
            ->leftJoin('document.tag', 'c')
            ->where('c.id = :tag')
            ->setParameter('tag', $tag)
            ->andWhere('document.user = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult()
            ;
    }

    public function documentTags()
    {
        return $this->createQueryBuilder("document")
            ->leftJoin('document.tag', 'tag')
            ->where('tag.id = 168')
            ->getQuery()
            ->getResult()
            ;

    }

    public function countDocuments($user, $reminder)
    {
        if ($reminder) {
            return $this->createQueryBuilder("document")
                ->select('count(document.id)')
                ->where('document.documentReminder IS NOT NULL AND document.user = :user')
                ->setParameter('user', $user)
                ->getQuery()
                ->getResult()
                ;
        } else {
            return $this->createQueryBuilder("document")
                ->select('count(document.id)')
                ->where('document.user = :user')
                ->setParameter('user', $user)
                ->getQuery()
                ->getResult()
                ;
        }
    }
}

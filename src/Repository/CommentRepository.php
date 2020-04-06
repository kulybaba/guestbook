<?php

namespace App\Repository;

use App\Entity\Comment;
use App\Entity\Conference;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * @method Comment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Comment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Comment[]    findAll()
 * @method Comment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Comment::class);
    }

    public function getCommentsPaginator(Conference $conference, int $offset)
    {
        $query = $this->createQueryBuilder('c')
            ->andWhere('c.conference = :conference')
            ->orderBy('c.created', 'DESC')
            ->setFirstResult($offset)
            ->setMaxResults(Comment::COMMENTS_LIMIT)
            ->setParameter('conference', $conference)
            ->getQuery();

        return new Paginator($query);
    }
}

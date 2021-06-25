<?php
/**
 * Advert Repository.
 */

namespace App\Repository;

use App\Entity\Advert;
use App\Entity\Category;
use App\Entity\Status;
use App\Entity\Tag;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Class Advert Repository.
 *
 * @method Advert|null find($id, $lockMode = null, $lockVersion = null)
 * @method Advert|null findOneBy(array $criteria, array $orderBy = null)
 * @method Advert[]    findAll()
 * @method Advert[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AdvertRepository extends ServiceEntityRepository
{
    /**
     * Items per page.
     *
     * Use constants to define configuration options that rarely change instead
     * of specifying them in app/config/config.yml.
     * See https://symfony.com/doc/current/best_practices.html#configuration
     *
     * @constant int
     */
    const PAGINATOR_ITEMS_PER_PAGE = 10;

    /**
     * AdvertRepository constructor.
     *
     * @param ManagerRegistry $registry Manager registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Advert::class);
    }

    /**
     * Query all.
     *
     * @param Status $status
     * @param array  $filters
     *
     * @return QueryBuilder
     */
    public function queryAll(Status $status, array $filters = []): QueryBuilder
    {
        $queryBuilder = $this->getOrCreateQueryBuilder()
            ->select(
                'partial advert.{id, title, content, date, updatedAt}',
                'partial category.{id, name}',
                'partial tags.{id, content}',
                'partial status.{id, title}'
            )
            ->join('advert.category', 'category')
            ->leftjoin('advert.tags', 'tags')
            ->join('advert.status', 'status')
            ->where('advert.status= :status')
            ->setParameter('status', $status)
            ->orderBy('advert.updatedAt', 'DESC');
        $queryBuilder = $this->applyFiltersToList($queryBuilder, $filters);

        return $queryBuilder;
    }

    /**
     * Query adverts by author.
     *
     * @param User   $user
     * @param Status $status
     * @param array  $filters
     *
     * @return QueryBuilder
     */
    public function queryByAuthor(User $user, Status $status, array $filters = []): QueryBuilder
    {
        $queryBuilder = $this->queryAll($status, $filters);

        $queryBuilder->andWhere('advert.author = :author')
            ->setParameter('author', $user);

        return $queryBuilder;
    }

    /**
     * Save record.
     *
     * @param Advert $advert Advert entity
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function save(Advert $advert): void
    {
        $this->_em->persist($advert);
        $this->_em->flush($advert);
    }

    /**
     * Delete record.
     *
     * @param Advert $advert Advert entity
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function delete(Advert $advert): void
    {
        $this->_em->remove($advert);
        $this->_em->flush($advert);
    }

    /**
     * Apply filters to paginated list.
     *
     * @param QueryBuilder $queryBuilder Query builder
     * @param array        $filters      Filters array
     *
     * @return QueryBuilder Query builder
     */
    private function applyFiltersToList(QueryBuilder $queryBuilder, array $filters = []): QueryBuilder
    {
        if (isset($filters['category']) && $filters['category'] instanceof Category) {
            $queryBuilder->andWhere('category = :category')
                ->setParameter('category', $filters['category']);
        }

        if (isset($filters['tag']) && $filters['tag'] instanceof Tag) {
            $queryBuilder->andWhere('tags IN (:tag)')
                ->setParameter('tag', $filters['tag']);
        }

        return $queryBuilder;
    }

    /**
     * Get or create new query builder.
     *
     * @param QueryBuilder|null $queryBuilder Query builder
     *
     * @return QueryBuilder Query builder
     */
    private function getOrCreateQueryBuilder(QueryBuilder $queryBuilder = null): QueryBuilder
    {
        return $queryBuilder ?? $this->createQueryBuilder('advert');
    }
}

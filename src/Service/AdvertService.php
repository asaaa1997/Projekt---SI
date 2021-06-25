<?php
/**
 * Advert service.
 */

namespace App\Service;

use App\Entity\Advert;
use App\Entity\Status;
use App\Repository\AdvertRepository;
use App\Repository\StatusRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class AdvertService.
 */
class AdvertService
{
    /**
     * Advert repository.
     *
     * @var AdvertRepository
     */
    private $advertRepository;

    /**
     * Status Repository.
     *
     * @var StatusRepository
     */
    private $statusRepository;

    /**
     * Paginator.
     *
     * @var PaginatorInterface
     */
    private $paginator;

    /**
     * Category service.
     *
     * @var CategoryService
     */
    private $categoryService;

    /**
     * Tag service.
     *
     * @var TagService
     */
    private $tagService;

    /**
     * AdvertService constructor.
     *
     * @param AdvertRepository   $advertRepository
     * @param StatusRepository   $statusRepository
     * @param PaginatorInterface $paginator
     * @param CategoryService    $categoryService
     * @param TagService         $tagService
     */
    public function __construct(AdvertRepository $advertRepository, StatusRepository $statusRepository, PaginatorInterface $paginator, CategoryService $categoryService, TagService $tagService)
    {
        $this->advertRepository = $advertRepository;
        $this->statusRepository = $statusRepository;
        $this->paginator = $paginator;
        $this->categoryService = $categoryService;
        $this->tagService = $tagService;
    }

    /**
     * Create paginated list of adverts.
     *
     * @param int    $page
     * @param Status $status
     * @param array  $filters
     *
     * @return PaginationInterface
     */
    public function createPaginatedList(int $page, Status $status, array $filters = []): PaginationInterface
    {
        $filters = $this->prepareFilters($filters);

        return $this->paginator->paginate(
            $this->advertRepository->queryAll($status, $filters),
            $page,
            AdvertRepository::PAGINATOR_ITEMS_PER_PAGE
        );
    }

    /**
     * Create paginated list of adverts by author.
     *
     * @param int           $page
     * @param UserInterface $user
     * @param Status        $status
     * @param array         $filters
     *
     * @return PaginationInterface
     */
    public function createAuthorPaginatedList(int $page, UserInterface $user, Status $status, array $filters = []): PaginationInterface
    {
        $filters = $this->prepareFilters($filters);

        return $this->paginator->paginate(
            $this->advertRepository->queryByAuthor($user, $status, $filters),
            $page,
            AdvertRepository::PAGINATOR_ITEMS_PER_PAGE
        );
    }

    /**
     * Save advert.
     *
     * @param Advert $advert Advert entity
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function save(Advert $advert): void
    {
        $this->advertRepository->save($advert);
    }

    /**
     * Delete advert.
     *
     * @param Advert $advert Advert entity
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function delete(Advert $advert): void
    {
        $this->advertRepository->delete($advert);
    }

    /**
     * Prepare filters for the adverts list.
     *
     * @param array $filters Raw filters from request
     *
     * @return array Result array of filters
     */
    private function prepareFilters(array $filters): array
    {
        $resultFilters = [];
        if (isset($filters['category']) && is_numeric($filters['category'])) {
            $category = $this->categoryService->findOneById(
                $filters['category']
            );
            if (null !== $category) {
                $resultFilters['category'] = $category;
            }
        }

        if (isset($filters['tag']) && is_numeric($filters['tag'])) {
            $tag = $this->tagService->findOneById($filters['tag']);
            if (null !== $tag) {
                $resultFilters['tag'] = $tag;
            }
        }

        return $resultFilters;
    }
}

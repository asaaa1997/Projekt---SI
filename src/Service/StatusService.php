<?php
/**
 * Status service.
 */

namespace App\Service;

use App\Entity\Status;
use App\Repository\StatusRepository;
use Doctrine\ORM\NonUniqueResultException;

/**
 * Class StatusService.
 */
class StatusService
{
    /**
     * Status repository.
     *
     * @var StatusRepository
     */
    private $statusRepository;

    /**
     * StatusService constructor.
     *
     * @param StatusRepository $statusRepository
     */
    public function __construct(StatusRepository $statusRepository)
    {
        $this->statusRepository = $statusRepository;
    }

    /**
     * Find one by title.
     *
     * @param string $value
     *
     * @return Status
     *
     * @throws NonUniqueResultException
     */
    public function findOneByTitle(string $value): Status
    {
        return $this->statusRepository->findOneByTitle($value);
    }
}

<?php
/**
 * Advert controller.
 */

namespace App\Controller;

use App\Entity\Advert;
use App\Form\AdvertType;
use App\Service\AdvertService;
use App\Service\StatusService;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

/**
 * Class AdvertController.
 *
 * @Route("/")
 */
class AdvertController extends AbstractController
{
    /**
     * Security.
     *
     * @var Security
     */
    private $security;

    /**
     * StatusService.
     *
     * @var StatusService
     */
    private $statusService;

    /**
     * AdvertService.
     *
     * @var AdvertService
     */
    private $advertService;

    /**
     * AdvertController constructor.
     *
     * @param Security      $security
     * @param AdvertService $advertService
     * @param StatusService $statusService
     */
    public function __construct(Security $security, AdvertService $advertService, StatusService $statusService)
    {
        $this->security = $security;
        $this->advertService = $advertService;
        $this->statusService = $statusService;
    }

    /**
     * Index action.
     *
     * @param Request $request HTTP request
     *
     * @return Response HTTP response
     *
     * @Route(
     *     "/",
     *     name="advert_index",
     * )
     *
     * @throws NonUniqueResultException
     */
    public function index(Request $request): Response
    {
        if ($this->security->isGranted('IS_AUTHENTICATED_ANONYMOUSLY')) {
            $page = $request->query->getInt('page', 1);
            $filters = $request->query->getAlnum('filters', []);
            $status = $this->statusService->findOneByTitle('ACTIVE');
            $pagination = $this->advertService->createPaginatedList($page, $status, $filters);
        }

        if ($this->security->isGranted('ROLE_USER')) {
            $page = $request->query->getInt('page', 1);
            $user = $this->getUser();
            $filters = $request->query->getAlnum('filters', []);
            $status = $this->statusService->findOneByTitle('ACTIVE');
            $pagination = $this->advertService->createAuthorPaginatedList($page, $user, $status, $filters);
        }

        if ($this->security->isGranted('ROLE_ADMIN')) {
            $page = $request->query->getInt('page', 1);
            $filters = $request->query->getAlnum('filters', []);
            $status = $this->statusService->findOneByTitle('ACTIVE');
            $pagination = $this->advertService->createPaginatedList($page, $status, $filters);
        }

        return $this->render(
            'advert/index.html.twig',
            ['pagination' => $pagination]
        );
    }

    /**
     * Inactive adverts action.
     *
     * @param Request $request HTTP request
     *
     * @return Response HTTP response
     *
     * @Route(
     *     "/inactive",
     *     methods={"GET"},
     *     name="advert_inactive"
     * )
     * @IsGranted("ROLE_ADMIN")
     *
     * @throws NonUniqueResultException
     */
    public function inactive(Request $request): Response
    {
        $page = $request->query->getInt('page', 1);
        $filters = $request->query->getAlnum('filters', []);
        $status = $this->statusService->findOneByTitle('INACTIVE');
        $pagination = $this->advertService->createPaginatedList($page, $status, $filters);

        return $this->render(
            'advert/inactive.html.twig',
            ['pagination' => $pagination]
        );
    }

    /**
     * Show action.
     *
     * @param Advert $advert Advert entity
     *
     * @return Response HTTP response
     *
     * @Route(
     *     "/{id}",
     *     methods={"GET"},
     *     name="advert_show",
     *     requirements={"id": "[1-9]\d*"},
     * )
     *
     * @IsGranted(
     *     "VIEW",
     *     subject="advert",
     * )
     */
    public function show(Advert $advert): Response
    {
        return $this->render(
            'advert/show.html.twig',
            ['advert' => $advert]
        );
    }

    /**
     * Create action.
     *
     * @param Request $request HTTP request
     *
     * @return Response HTTP response
     *
     * @throws ORMException
     * @throws OptimisticLockException
     *
     * @Route(
     *     "/create",
     *     methods={"GET", "POST"},
     *     name="advert_create",
     * )
     */
    public function create(Request $request): Response
    {
        $advert = new Advert();
        $form = $this->createForm(AdvertType::class, $advert);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($this->security->isGranted('ROLE_USER')) {
                $advert->setAuthor($this->getUser());
            }

            $status = $this->statusService->findOneByTitle('INACTIVE');
            $advert->setStatus($status);

            $this->advertService->save($advert);

            $this->addFlash('success', 'message_must_be_accepted');

            return $this->redirectToRoute('advert_index');
        }

        return $this->render(
            'advert/create.html.twig',
            ['form' => $form->createView()]
        );
    }

    /**
     * Edit action.
     *
     * @param Request $request HTTP request
     * @param Advert  $advert  Advert entity
     *
     * @return Response HTTP response
     *
     * @throws ORMException
     * @throws OptimisticLockException
     *
     * @Route(
     *     "/{id}/edit",
     *     methods={"GET", "PUT"},
     *     requirements={"id": "[1-9]\d*"},
     *     name="advert_edit",
     * )
     *
     * @IsGranted(
     *     "EDIT",
     *     subject="advert",
     * )
     */
    public function edit(Request $request, Advert $advert): Response
    {
        $form = $this->createForm(AdvertType::class, $advert, ['method' => 'PUT']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (!($this->security->isGranted('ROLE_ADMIN'))) {
                $status = $this->statusService->findOneByTitle('INACTIVE');
                $advert->setStatus($status);

                $this->advertService->save($advert);

                $this->addFlash('success', 'message_must_be_accepted');
            }

            if ($this->security->isGranted('ROLE_ADMIN')) {
                $status = $this->statusService->findOneByTitle('ACTIVE');
                $advert->setStatus($status);

                $this->advertService->save($advert);

                $this->addFlash('success', 'message_updated_successfully');
            }

            return $this->redirectToRoute('advert_index');
        }

        return $this->render(
            'advert/edit.html.twig',
            [
                'form' => $form->createView(),
                'advert' => $advert,
            ]
        );
    }

    /**
     * Delete action.
     *
     * @param Request $request HTTP request
     * @param Advert  $advert  Advert entity
     *
     * @return Response HTTP response
     *
     * @throws ORMException
     * @throws OptimisticLockException
     *
     * @Route(
     *     "/{id}/delete",
     *     methods={"GET", "DELETE"},
     *     requirements={"id": "[1-9]\d*"},
     *     name="advert_delete",
     * )
     *
     * @IsGranted(
     *     "DELETE",
     *     subject="advert",
     * )
     */
    public function delete(Request $request, Advert $advert): Response
    {
        $form = $this->createForm(FormType::class, $advert, ['method' => 'DELETE']);
        $form->handleRequest($request);

        if ($request->isMethod('DELETE') && !$form->isSubmitted()) {
            $form->submit($request->request->get($form->getName()));
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $status = $this->statusService->findOneByTitle('INACTIVE');
            $advert->setStatus($status);

            if ($this->security->isGranted('ROLE_ADMIN')) {
                $this->advertService->delete($advert);
                $this->addFlash('success', 'message_deleted_successfully');
            }

            if (!($this->security->isGranted('ROLE_ADMIN'))) {
                $this->advertService->save($advert);
                $this->addFlash('success', 'message_must_be_accepted');
            }

            return $this->redirectToRoute('advert_index');
        }

        return $this->render(
            'advert/delete.html.twig',
            [
                'form' => $form->createView(),
                'advert' => $advert,
            ]
        );
    }
}

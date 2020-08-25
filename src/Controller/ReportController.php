<?php
declare(strict_types=1);

namespace App\Controller;

use App\Form\ReportType;
use App\Service\ExceptionService;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\Document\Report;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class ReportController
 * @package App\Controller
 */
class ReportController
{
    /** @var FormFactoryInterface */
    private $formFactory;

    /** @var ExceptionService */
    private $exceptionService;

    /**
     * ProductController constructor.
     * @param FormFactoryInterface $formFactory
     * @param ExceptionService $exceptionService
     */
    public function __construct(FormFactoryInterface $formFactory, ExceptionService $exceptionService)
    {
        $this->formFactory = $formFactory;
        $this->exceptionService = $exceptionService;
    }

    /**
     * @Route("/report", methods={"POST"})
     * @param DocumentManager $dm
     * @param Request $request
     * @return JsonResponse
     * @throws \Doctrine\ODM\MongoDB\MongoDBException
     */
    public function create(DocumentManager $dm, Request $request)
    {
        $report = new Report();
        $form = $this->formFactory->createNamed('report', ReportType::class, $report);
        $form->submit($request->request->all());

        if ($form->isValid()) {

            $dm->persist($report);
            $dm->flush();

            $response = new JsonResponse(
                [
                    'message' => 'Successfully added new product',
                    'success' => true,
                ],
                201
            );

        } else {
            $errors = $this->exceptionService->getFormErrors($form);
            $response = new JsonResponse(
                [
                    'message' => $errors,
                    'success' => false
                ],
                400);
        }
        return $response;
    }
}
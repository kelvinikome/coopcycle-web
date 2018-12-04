<?php

namespace AppBundle\Action\Task;

use Doctrine\Common\Persistence\ManagerRegistry;
use AppBundle\Entity\Task;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;

class UnassignedTasks
{
    private $taskRepository;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->taskRepository = $doctrine->getRepository(Task::class);
    }

    public function __invoke(Request $request)
    {
        $tasks = $this->taskRepository->findUnassigned(new \DateTime($request->get('date')));

        return $tasks;
    }
}

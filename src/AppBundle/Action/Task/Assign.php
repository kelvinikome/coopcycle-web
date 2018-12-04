<?php

namespace AppBundle\Action\Task;

use AppBundle\Entity\Task;
use AppBundle\Exception\PreviousTaskNotCompletedException;
use AppBundle\Exception\TaskCancelledException;
use FOS\UserBundle\Model\UserManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;

class Assign
{
    private $userManager;

    public function __construct(UserManagerInterface $userManager)
    {
        $this->userManager = $userManager;
    }

    public function __invoke(Task $data, Request $request)
    {
        $task = $data;

        // var_dump($request->request->get('username'));

        $payload = [];

        $content = $request->getContent();
        if (!empty($content)) {
            $payload = json_decode($content, true);
        }

        $user = $this->userManager->findUserByUsername($payload['username']);

        // try {
        //     $this->taskManager->markAsFailed($task, $this->getNotes($request));
        // } catch (PreviousTaskNotCompletedException $e) {
        //     throw new BadRequestHttpException($e->getMessage());
        // } catch (TaskCancelledException $e) {
        //     throw new BadRequestHttpException($e->getMessage());
        // }

        // var_dump('YO');

        $task->assignTo($user);

        return $task;
    }
}

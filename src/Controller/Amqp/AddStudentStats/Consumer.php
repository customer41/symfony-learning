<?php

namespace App\Controller\Amqp\AddStudentStats;

use App\Application\RabbitMq\AbstractConsumer;
use App\Controller\Amqp\AddStudentStats\Input\Message;
use App\Controller\DTO\AmqpMessageInterface;
use App\Domain\Enum\TaskStatus;
use App\Domain\Event\StudentStatsIsAddedEvent;
use App\Domain\Exception\EntityNotFoundException;
use App\Domain\Factory\ModelFactory;
use App\Domain\Model\CreateStudentStatsModel;
use App\Domain\Model\UpdateStudentTaskModel;
use App\Domain\Service\StudentStatsService;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class Consumer extends AbstractConsumer
{
    public function __construct(
        /** @var ModelFactory<CreateStudentStatsModel> */
        private readonly ModelFactory $modelFactory,
        private readonly StudentStatsService $studentStatsService,
        private readonly EventDispatcherInterface $eventDispatcher,
    ) {
    }

    /**
     * @param Message $message
     */
    protected function handle(AmqpMessageInterface $message): int
    {
        try {
            $createStudentStatsModel = $this->modelFactory->makeModel(
                CreateStudentStatsModel::class,
                $message->studentId,
                $message->skillIds,
            );
            $this->studentStatsService->addStudentStats($createStudentStatsModel);
        } catch (EntityNotFoundException $e) {
            return $this->reject($e->getDefaultMessage());
        }

        $this->eventDispatcher->dispatch(
            new StudentStatsIsAddedEvent(
                new UpdateStudentTaskModel(
                    $message->studentTaskId,
                    TaskStatus::from($message->taskStatus),
                    $message->taskMinRequirements,
                ),
            ),
        );

        return self::MSG_ACK;
    }

    protected function getMessageClass(): string
    {
        return Message::class;
    }
}

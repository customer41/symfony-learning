<?php

namespace App\Application\RabbitMq;

use App\Controller\DTO\AmqpMessageInterface;
use Doctrine\ORM\EntityManagerInterface;
use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Contracts\Service\Attribute\Required;

abstract class AbstractConsumer implements ConsumerInterface
{
    private readonly EntityManagerInterface $entityManager;
    private readonly ValidatorInterface $validator;
    private readonly SerializerInterface $serializer;

    abstract protected function handle(AmqpMessageInterface $message): int;
    abstract protected function getMessageClass(): string;

    #[Required]
    public function setEntityManager(EntityManagerInterface $entityManager): void
    {
        $this->entityManager = $entityManager;
    }

    #[Required]
    public function setValidator(ValidatorInterface $validator): void
    {
        $this->validator = $validator;
    }

    #[Required]
    public function setSerializer(SerializerInterface $serializer): void
    {
        $this->serializer = $serializer;
    }

    public function execute(AMQPMessage $msg): int
    {
        try {
            $message = $this->serializer->deserialize($msg->getBody(), $this->getMessageClass(), 'json');
            $errors = $this->validator->validate($message);
            if ($errors->count() > 0) {
                return $this->reject((string) $errors);
            }

            return $this->handle($message);
        } catch (\Throwable $e) {
            return $this->reject($e->getMessage());
        } finally {
            $this->entityManager->clear();
            $this->entityManager->getConnection()->close();
        }
    }

    protected function reject(string $error): int
    {
        echo "Incorrect message: $error";

        return self::MSG_REJECT;
    }
}

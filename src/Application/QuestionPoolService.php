<?php
declare(strict_types = 1);

namespace srag\asq\QuestionPool\Application;

use ILIAS\Data\UUID\Uuid;
use srag\CQRS\Command\CommandBus;
use srag\CQRS\Command\CommandConfiguration;
use srag\CQRS\Command\Access\OpenAccess;
use srag\asq\Application\Command\CreateQuestionCommand;
use srag\asq\Application\Command\CreateQuestionCommandHandler;
use srag\asq\Application\Service\ASQService;
use srag\asq\QuestionPool\Application\Command\AddQuestionCommand;
use srag\asq\QuestionPool\Application\Command\AddQuestionCommandHandler;
use srag\asq\QuestionPool\Application\Command\RemoveQuestionCommand;
use srag\asq\QuestionPool\Application\Command\RemoveQuestionCommandHandler;
use srag\asq\QuestionPool\Domain\Model\QuestionPool;
use srag\asq\QuestionPool\Domain\Model\QuestionPoolRepository;
use ILIAS\Data\UUID\Factory;
use srag\asq\QuestionPool\Application\Command\CreatePoolCommand;
use srag\asq\QuestionPool\Application\Command\CreatePoolCommandHandler;

/**
 * Class QuestionPoolService
 *
 * @package srag\asq\QuestionPool
 *
 * @author studer + raimann ag - Team Core 2 <al@studer-raimann.ch>
 */

class QuestionPoolService extends ASQService
{
    /**
     * @var CommandBus
     */
    private $command_bus;

    /**
     * @var QuestionPoolRepository
     */
    private $repo;

    public function __construct()
    {
        $this->command_bus = new CommandBus();

        $this->command_bus->registerCommand(new CommandConfiguration(
            CreatePoolCommand::class,
            new CreatePoolCommandHandler(),
            new OpenAccess()
        ));

        $this->command_bus->registerCommand(new CommandConfiguration(
            AddQuestionCommand::class,
            new AddQuestionCommandHandler(),
            new OpenAccess()
        ));

        $this->command_bus->registerCommand(new CommandConfiguration(
            RemoveQuestionCommand::class,
            new RemoveQuestionCommandHandler(),
            new OpenAccess()
        ));

        $this->repo = new QuestionPoolRepository();
    }

    /**
     * @return Uuid
     */
    public function createQuestionPool() : Uuid
    {
        $uuid_factory = new Factory();
        $uuid = $uuid_factory->uuid4();

        // CreateQuestion.png
        $this->command_bus->handle(
            new CreatePoolCommand(
                $uuid,
                $this->getActiveUser()
            )
        );

        return $uuid;
    }

    /**
     * @param Uuid $pool_id
     * @param Uuid $question_id
     */
    public function addQuestion(Uuid $pool_id, Uuid $question_id) : void
    {
        $this->command_bus->handle(
            new AddQuestionCommand(
                $pool_id,
                $this->getActiveUser(),
                $question_id
            )
        );
    }

    /**
     * @param Uuid $pool_id
     * @param Uuid $question_id
     */
    public function removeQuestion(Uuid $pool_id, Uuid $question_id) : void
    {
        $this->command_bus->handle(
            new RemoveQuestionCommand(
                $pool_id,
                $this->getActiveUser(),
                $question_id
            )
        );
    }

    /**
     * @param Uuid $pool_id
     * @return Uuid[]
     */
    public function getQuestionsOfPool(Uuid $pool_id) : array
    {
        /** @var $pool QuestionPool */
        $pool = $this->repo->getAggregateRootById($pool_id);

        return $pool->getQuestions();
    }
}
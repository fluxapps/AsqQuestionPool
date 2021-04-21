<?php
declare(strict_types = 1);

namespace srag\asq\QuestionPool\Domain\Model;

use srag\CQRS\Aggregate\AbstractAggregateRoot;
use srag\CQRS\Event\Standard\AggregateCreatedEvent;
use ILIAS\Data\UUID\Uuid;
use ilDateTime;
use srag\asq\Application\Exception\AsqException;
use srag\asq\QuestionPool\Domain\Event\QuestionAddedEvent;
use srag\asq\QuestionPool\Domain\Event\QuestionRemovedEvent;

/**
 * Class QuestionPool
 *
 * @package srag\asq\QuestionPool
 *
 * @author studer + raimann ag - Team Core 2 <al@studer-raimann.ch>
 */
class QuestionPool extends AbstractAggregateRoot
{
    /**
     * @var Uuid[]
     */
    protected $questions = [];

    /**
     * @param Uuid $uuid
     * @param int $initiating_user_id
     * @return QuestionPool
     */
    public static function create(
        Uuid $uuid,
        int $initiating_user_id
    ) : QuestionPool {
        $pool = new QuestionPool();
        $pool->ExecuteEvent(
            new AggregateCreatedEvent(
                $uuid,
                new ilDateTime(time(), IL_CAL_UNIX),
                $initiating_user_id
                )
            );

        return $pool;
    }

    /**
     * @param Uuid $question_id
     */
    public function addQuestion(Uuid $question_id, int $user_id) : void
    {
        if (!in_array($question_id, $this->questions)) {
            $this->ExecuteEvent(
                new QuestionAddedEvent(
                    $this->aggregate_id,
                    new ilDateTime(time(), IL_CAL_UNIX),
                    $user_id,
                    $question_id)
                );
        }
        else {
            throw new AsqException('Section is already part of Test');
        }
    }

    /**
     * @param QuestionAddedEvent $event
     */
    protected function applyQuestionAddedEvent(QuestionAddedEvent $event) : void
    {
        $this->questions[] = $event->getQuestionId();
    }

    /**
     * @param Uuid $question_id
     */
    public function removeQuestion(Uuid $question_id, int $user_id) : void
    {
        if (in_array($question_id, $this->questions)) {
            $this->ExecuteEvent(
                new QuestionRemovedEvent(
                    $this->aggregate_id,
                    new ilDateTime(time(), IL_CAL_UNIX),
                    $user_id,
                    $question_id)
                );
        }
        else {
            throw new AsqException('Section is not part of Test');
        }
    }

    /**
     * @param QuestionRemovedEvent $event
     */
    protected function applyQuestionRemovedEvent(QuestionRemovedEvent $event) : void
    {
        $this->questions = array_diff($this->questions, [$event->getQuestionId()]);
    }

    /**
     * @return Uuid[]
     */
    public function getQuestions() : array
    {
        return $this->questions;
    }
}
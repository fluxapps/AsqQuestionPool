<?php
declare(strict_types = 1);

namespace srag\asq\Test\Domain\Test\Event;

use ILIAS\Data\UUID\Uuid;
use ilDateTime;
use srag\CQRS\Event\AbstractDomainEvent;
use ILIAS\Data\UUID\Factory;

/**
 * Class QuestionAddedEvent
 *
 * @package srag\asq\QuestionPool
 *
 * @author studer + raimann ag - Team Core 2 <al@studer-raimann.ch>
 */
class QuestionAddedEvent extends AbstractDomainEvent
{
    /**
     * @var Uuid
     */
    protected $question_id;

    /**
     * @param Uuid $aggregate_id
     * @param ilDateTime $occured_on
     * @param int $initiating_user_id
     * @param Uuid $data
     */
    public function __construct(
        Uuid $aggregate_id,
        ilDateTime $occured_on,
        int $initiating_user_id,
        Uuid $question_id = null
        ) {
            $this->question_id = $question_id;
            parent::__construct($aggregate_id, $occured_on, $initiating_user_id);
    }

    /**
     * @return Uuid
     */
    public function getQuestionId() : Uuid
    {
        return $this->question_id;
    }

    /**
     * {@inheritDoc}
     * @see \srag\CQRS\Event\AbstractDomainEvent::getEventBody()
     */
    public function getEventBody() : string
    {
        return $this->question_id->toString();
    }

    /**
     * {@inheritDoc}
     * @see \srag\CQRS\Event\AbstractDomainEvent::restoreEventBody()
     */
    protected function restoreEventBody(string $event_body) : void
    {
        $factory = new Factory();
        $this->question_id = $factory->fromString($event_body);
    }

    /**
     * @return int
     */
    public static function getEventVersion() : int
    {
        // initial version 1
        return 1;
    }
}

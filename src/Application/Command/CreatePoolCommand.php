<?php
declare(strict_types = 1);

namespace srag\asq\QuestionPool\Application\Command;

use ILIAS\Data\UUID\Uuid;
use srag\CQRS\Command\AbstractCommand;

/**
 * Class CreatePoolCommand
 *
 * @package srag\asq\QuestionPool
 *
 * @author studer + raimann ag - Team Core 2 <al@studer-raimann.ch>
 */
class CreatePoolCommand extends AbstractCommand
{
    /**
     * @var Uuid
     */
    protected $uuid;

    /**
     * @param Uuid $uuid
     * @param int $user_id
     */
    public function __construct(Uuid $uuid, int $user_id)
    {
        $this->uuid = $uuid;
        parent::__construct($user_id);
    }

    /**
     * @return Uuid
     */
    public function getId() : Uuid
    {
        return $this->uuid;
    }
}

<?php

use srag\asq\QuestionPool\Application\QuestionPoolPlugin;
use srag\asq\QuestionPool\UI\QuestionListGUI;
use srag\Plugins\AsqQuestionPool\Utils\AsqQuestionPoolTrait;
use srag\asq\Application\Service\AuthoringContextContainer;
use srag\asq\Application\Service\ASQDIC;
use srag\DIC\AsqQuestionPool\DICTrait;
use srag\asq\QuestionPool\Application\QuestionPoolService;
use ILIAS\Data\UUID\Factory;
use ILIAS\Data\UUID\Uuid;
use srag\asq\Application\Service\AsqServices;
use srag\asq\Application\Service\IAuthoringCaller;
use srag\asq\Domain\QuestionDto;
use srag\asq\Infrastructure\Helpers\PathHelper;

/**
 * Class ilObjAsqQuestionPoolGUI
 *
 * Generated by SrPluginGenerator v2.8.1
 *
 * @author studer + raimann ag - Team Custom 2 <support-custom2@studer-raimann.ch>
 * @author studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 *
 * @ilCtrl_isCalledBy ilObjAsqQuestionPoolGUI: ilRepositoryGUI
 * @ilCtrl_isCalledBy ilObjAsqQuestionPoolGUI: ilObjPluginDispatchGUI
 * @ilCtrl_isCalledBy ilObjAsqQuestionPoolGUI: ilAdministrationGUI
 * @ilCtrl_Calls      ilObjAsqQuestionPoolGUI: ilPermissionGUI
 * @ilCtrl_Calls      ilObjAsqQuestionPoolGUI: ilInfoScreenGUI
 * @ilCtrl_Calls      ilObjAsqQuestionPoolGUI: ilObjectCopyGUI
 * @ilCtrl_Calls      ilObjAsqQuestionPoolGUI: ilCommonActionDispatcherGUI
 * @ilCtrl_Calls      ilObjAsqQuestionPoolGUI: AsqQuestionAuthoringGUI
 * @ilCtrl_Calls      ilObjAsqQuestionPoolGUI: ilObjTaxonomyGUI
 */
class ilObjAsqQuestionPoolGUI extends ilObjectPluginGUI
{
    use PathHelper;
    use DICTrait;
    use AsqQuestionPoolTrait;

    const CMD_PERMISSIONS = "perm";
    const CMD_SETTINGS = "settings";
    const CMD_SETTINGS_STORE = "settingsStore";
    const LANG_MODULE_OBJECT = "object";
    const LANG_MODULE_SETTINGS = "settings";
    const PLUGIN_CLASS_NAME = ilAsqQuestionPoolPlugin::class;
    const TAB_CONTENTS = "contents";
    const TAB_PERMISSIONS = "perm_settings";
    const TAB_SETTINGS = "settings";
    const TAB_SHOW_QUESTIONS = "show_questions";



    /**
     * @var ilObjAsqQuestionPool
     */
    public $object;

    /**
     * @var Uuid
     */
    private $pool_id;

    /**
     * @var QuestionPoolService
     */
    private $pool_service;

    /**
     * @var AsqServices
     */
    private $asq_service;

    /**
     * @var Factory
     */
    private $uuid_factory;

    private QuestionPoolPlugin $pool;

    /**
     * @inheritDoc
     */
    protected function afterConstructor() : void
    {
        global $DIC, $ASQDIC;

        ASQDIC::initiateASQ($DIC);
        $this->asq_service = $ASQDIC->asq();
        $this->pool_service = new QuestionPoolService();
        $this->uuid_factory = new Factory();

        $this->loadPool();
    }

    private function loadPool() : void
    {
        if (!is_null($this->object)) {
            $raw_pool_id = $this->object->getData();

            if (is_null($raw_pool_id)) {
                $this->pool_id = $this->uuid_factory->uuid4();
                $this->object->setData($this->pool_id->toString());
                $this->object->doUpdate();
                $this->pool = QuestionPoolPlugin::create(
                    $this->pool_id,
                    $this->object->getTitle(),
                    $this->object->getDescription());
            }
            else {
                $this->pool_id = $this->uuid_factory->fromString($raw_pool_id);
                $this->pool = QuestionPoolPlugin::load($this->pool_id);
            }
        }
    }


    /**
     * @return string
     */
    public static function getStartCmd() : string
    {
        return QuestionPoolPlugin::getInitialCommand();
    }


    /**
     * @inheritDoc
     *
     * @param ilObjAsqQuestionPool $a_new_object
     */
    public function afterSave(/*ilObjAsqQuestionPool*/ ilObject $a_new_object) : void
    {
        parent::afterSave($a_new_object);
    }


    /**
     * @inheritDoc
     */
    public function getAfterCreationCmd() : string
    {
        return self::getStartCmd();
    }


    /**
     * @inheritDoc
     */
    public function getStandardCmd() : string
    {
        return self::getStartCmd();
    }


    /**
     * @inheritDoc
     */
    public final function getType() : string
    {
        return ilAsqQuestionPoolPlugin::PLUGIN_ID;
    }


    /**
     * @inheritDoc
     */
    public function initCreateForm(/*string*/ $a_new_type) : ilPropertyFormGUI
    {
        $form = parent::initCreateForm($a_new_type);

        return $form;
    }


    /**
     * @param string $cmd
     */
    public function performCommand(string $cmd) : void
    {
        self::dic()->help()->setScreenIdComponent(ilAsqQuestionPoolPlugin::PLUGIN_ID);
        self::dic()->ui()->mainTemplate()->setPermanentLink(ilAsqQuestionPoolPlugin::PLUGIN_ID, $this->object->getRefId());

        $next_class = self::dic()->ctrl()->getNextClass($this);

        switch ($cmd) {
            case self::CMD_SETTINGS:
            case self::CMD_SETTINGS_STORE:
                // Write commands
                if (!ilObjAsqQuestionPoolAccess::hasWriteAccess()) {
                    ilObjAsqQuestionPoolAccess::redirectNonAccess($this);
                }

                $this->{$cmd}();
                break;

            default:
                if ($next_class === '') {
                    $this->pool->executeCommand($cmd);
                }
                else {
                    $this->pool->handleTransfer($next_class);
                }
                $this->show();
                break;
        }
    }

    /**
     * @param string $html
     */
    protected function show()/*: void*/
    {
        foreach ($this->pool->ui()->getTabs() as $tab) {
            self::dic()->tabs()->addTab(
                $tab->getId(),
                $tab->getText(),
                self::dic()->ctrl()->getLinkTarget($this, $tab->getLink())
            );

            if($tab->isActive()) {
                self::dic()->tabs()->activateTab($tab->getId());
            }
        }

        foreach ($this->pool->ui()->getToolbar() as $tool) {
            self::dic()->toolbar()->addComponent($tool);
        }

        self::dic()->ui()->mainTemplate()->setTitle($this->pool->ui()->getTitle());

        self::dic()->ui()->mainTemplate()->setDescription($this->pool->ui()->getDescription());

        self::output()->output($this->pool->ui()->getContent());
    }
}

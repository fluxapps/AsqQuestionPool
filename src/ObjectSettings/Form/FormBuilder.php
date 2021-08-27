<?php

namespace srag\Plugins\AsqQuestionPool\ObjectSettings\Form;

use srag\Plugins\AsqQuestionPool\Utils\AsqQuestionPoolTrait;
use ilAsqQuestionPoolPlugin;
use ilObjAsqQuestionPool;
use ilObjAsqQuestionPoolGUI;
use srag\CustomInputGUIs\AsqQuestionPool\FormBuilder\AbstractFormBuilder;

/**
 * Class FormBuilder
 *
 * @package srag\Plugins\AsqQuestionPool\ObjectSettings\Form
 *
 * @author studer + raimann ag - Team Custom 2 <support-custom2@studer-raimann.ch>
 * @author studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class FormBuilder extends AbstractFormBuilder
{

    use AsqQuestionPoolTrait;

    const PLUGIN_CLASS_NAME = ilAsqQuestionPoolPlugin::class;
    /**
     * @var ilObjAsqQuestionPool
     */
    protected $object;


    /**
     * @inheritDoc
     *
     * @param ilObjAsqQuestionPoolGUI $parent
     * @param ilObjAsqQuestionPool    $object
     */
    public function __construct(ilObjAsqQuestionPoolGUI $parent, ilObjAsqQuestionPool $object)
    {
        $this->object = $object;

        parent::__construct($parent);
    }


    /**
     * @inheritDoc
     */
    protected function getButtons() : array
    {
        $buttons = [
            ilObjAsqQuestionPoolGUI::CMD_SETTINGS_STORE  => self::plugin()->translate("save", ilObjAsqQuestionPoolGUI::LANG_MODULE_SETTINGS),
         ];

        return $buttons;
    }


    /**
     * @inheritDoc
     */
    protected function getData() : array
    {
        $data = [
            "title"       => $this->object->getTitle(),
            "description" => $this->object->getLongDescription(),
            "online"      => $this->object->isOnline()
        ];

        return $data;
    }


    /**
     * @inheritDoc
     */
    protected function getFields() : array
    {
        $fields = [
            "title"       => self::dic()->ui()->factory()->input()->field()->text(self::plugin()->translate("title", ilObjAsqQuestionPoolGUI::LANG_MODULE_SETTINGS))->withRequired(true),
            "description" => self::dic()->ui()->factory()->input()->field()->textarea(self::plugin()->translate("description", ilObjAsqQuestionPoolGUI::LANG_MODULE_SETTINGS)),
            "online"      => self::dic()->ui()->factory()->input()->field()->checkbox(self::plugin()->translate("online", ilObjAsqQuestionPoolGUI::LANG_MODULE_SETTINGS))
        ];

        return $fields;
    }


    /**
     * @inheritDoc
     */
    protected function getTitle() : string
    {
        return self::plugin()->translate("settings", ilObjAsqQuestionPoolGUI::LANG_MODULE_SETTINGS);
    }


    /**
     * @inheritDoc
     */
    protected function storeData(array $data) : void
    {
        $this->object->setTitle(strval($data["title"]));
        $this->object->setDescription(strval($data["description"]));
        $this->object->setOnline(boolval($data["online"]));

        $this->object->update();
    }
}

<?php

namespace srag\Plugins\AsqQuestionPool\Config\Form;

use srag\Plugins\AsqQuestionPool\Config\ConfigCtrl;
use srag\Plugins\AsqQuestionPool\Utils\AsqQuestionPoolTrait;
use ilAsqQuestionPoolPlugin;
use srag\CustomInputGUIs\AsqQuestionPool\FormBuilder\AbstractFormBuilder;

/**
 * Class FormBuilder
 *
 * @package srag\Plugins\AsqQuestionPool\Config\Form
 *
 * @author studer + raimann ag - Team Custom 2 <support-custom2@studer-raimann.ch>
 * @author studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class FormBuilder extends AbstractFormBuilder
{

    use AsqQuestionPoolTrait;

    const KEY_SOME = "some";
    const PLUGIN_CLASS_NAME = ilAsqQuestionPoolPlugin::class;


    /**
     * @inheritDoc
     *
     * @param ConfigCtrl $parent
     */
    public function __construct(ConfigCtrl $parent)
    {
        parent::__construct($parent);
    }


    /**
     * @inheritDoc
     */
    protected function getButtons() : array
    {
        $buttons = [
            ConfigCtrl::CMD_UPDATE_CONFIGURE => self::plugin()->translate("save", ConfigCtrl::LANG_MODULE)
        ];

        return $buttons;
    }


    /**
     * @inheritDoc
     */
    protected function getData() : array
    {
        $data = [
            self::KEY_SOME => self::asqQuestionPool()->config()->getValue(self::KEY_SOME)
        ];

        return $data;
    }


    /**
     * @inheritDoc
     */
    protected function getFields() : array
    {
        $fields = [
            self::KEY_SOME => self::dic()->ui()->factory()->input()->field()->text(self::plugin()->translate(self::KEY_SOME, ConfigCtrl::LANG_MODULE))->withRequired(true)
        ];

        return $fields;
    }


    /**
     * @inheritDoc
     */
    protected function getTitle() : string
    {
        return self::plugin()->translate("configuration", ConfigCtrl::LANG_MODULE);
    }


    /**
     * @inheritDoc
     */
    protected function storeData(array $data) : void
    {
        self::asqQuestionPool()->config()->setValue(self::KEY_SOME, strval($data[self::KEY_SOME]));
    }
}

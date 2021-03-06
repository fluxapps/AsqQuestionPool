<?php

namespace srag\Plugins\AsqQuestionPool\ObjectSettings;

use srag\Plugins\AsqQuestionPool\AsqQuestionPoolTrait;
use srag\Plugins\AsqQuestionPool\ObjectSettings\Form\FormBuilder;
use ilAsqQuestionPoolPlugin;
use ilObjAsqQuestionPool;
use ilObjAsqQuestionPoolGUI;
use srag\DIC\AsqQuestionPool\DICTrait;

/**
 * Class Factory
 *
 * Generated by SrPluginGenerator v2.8.1
 *
 * @package srag\Plugins\AsqQuestionPool\ObjectSettings
 *
 * @author studer + raimann ag - Team Custom 2 <support-custom2@studer-raimann.ch>
 * @author studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
final class Factory
{

    use DICTrait;
    use AsqQuestionPoolTrait;

    const PLUGIN_CLASS_NAME = ilAsqQuestionPoolPlugin::class;
    /**
     * @var self|null
     */
    protected static $instance = null;


    /**
     * Factory constructor
     */
    private function __construct()
    {

    }


    /**
     * @return self
     */
    public static function getInstance() : self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }


    /**
     * @param ilObjAsqQuestionPoolGUI $parent
     * @param ilObjAsqQuestionPool    $object
     *
     * @return FormBuilder
     */
    public function newFormBuilderInstance(ilObjAsqQuestionPoolGUI $parent, ilObjAsqQuestionPool $object) : FormBuilder
    {
        $form = new FormBuilder($parent, $object);

        return $form;
    }


    /**
     * @return ObjectSettings
     */
    public function newInstance() : ObjectSettings
    {
        $object_settings = new ObjectSettings();

        return $object_settings;
    }
}

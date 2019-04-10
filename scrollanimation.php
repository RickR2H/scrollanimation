<?php
/**
 * @copyright   Copyright (c) 2019  R2H BV (httpwwwr2hnl). All rights reserved.
 * @license     http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

// no direct access
defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Form\Form;

jimport('joomla.plugin.plugin');

/**
 * Content -  scrollanimation Plugin.
 *
 * @package Joomla.Plugin.
 * @subpakage R2H B.V. scrollanimation.
 */
class PlgSystemScrollanimation extends JPlugin
{
    /**
     * @var    boolean $autoloadLanguage Autoload the language.
     * @access protected
     */
    protected $autoloadLanguage = true;

    /**
     * @var    object $app Application object.
     * @access protected
     */
    protected $app = null;

    /**
     * Plugin Event onContentPrepareForm.
     * @param  Form  $form The form reference.
     * @param  mixed $data Dataset.
     * @access public
     * @return void
     */
    public function onContentPrepareForm(Form $form, $data)
    {
        // Retrun when on the frontend
        if (!$this->app->isClient('administrator')) {
            return;
        }

        // Only activate on module display
        if ($form->getName() !== 'com_modules.module' && $form->getName() !== 'com_advancedmodules.module') {
            return;
        }

        // Add the Form path for the sub form
        Form::addFormPath(__DIR__ . '/form');

        // Load the sub form
        $form->loadFile('scrollsettings', false);
    }

    /**
     * Plugin Event onBeforeCompileHead.
     * @access public
     * @return void
     */
    public function onBeforeCompileHead()
    {

        if (!$this->app->isClient('site')) {
            return;
        }

        // Add Stylesheet file.
        \Joomla\CMS\HTML\HTMLHelper::_(
            'stylesheet',
            'plg_system_scrollanimation/style.css',
            array('version' => 'auto', 'relative' => true)
        );

        // Add Script file.
        \Joomla\CMS\HTML\HTMLHelper::_(
            'script',
            'plg_system_scrollanimation/script.js',
            array('version' => 'auto', 'relative' => true),
            ['defer' => 'true']
        );

        $document = Factory::getDocument();

        $document->addScriptOptions('plg_system_scrollanimation', array(
            'aossettings' => array(
                'disable' => $disableon = $this->params->get('disableon', 'false')
            ),
        ));
    }

    /**
     * Plugin Event onAfterRenderModule.
     * @param  mixed  $module  Module content.
     * @param  object $attribs Module positions and module chrome / style.
     * @access public
     * @return void
     */
    public function onAfterRenderModule($module, $attribs)
    {
        // Disable on the backend and return.
        if (!$this->app->isClient('site')) {
            return;
        }

        // Get the parameters from the module.
        $mod_params = json_decode($module->params);

        // If the parameter 'animationtype' does not exist or is 'none' then return.
        if (!isset($mod_params->animationtype) || $mod_params->animationtype == 'none') {
            return;
        }

        // Create an empty string to hold the data attributes.
        $aos_attributes = '';

        // Set the animation type.
        if (isset($mod_params->animationtype)) {
            // Add the data attribute.
            $aos_attributes .= 'data-aos="' . $mod_params->animationtype . '"';
        }

        // Set the duration of the animation.
        if (isset($mod_params->duration) && $mod_params->duration !== "400") {
            // Add the data attribute.
            $aos_attributes .= ' data-aos-duration="' . $mod_params->duration . '"';
        }

        // set the easing of the animation.
        if (isset($mod_params->easing) && $mod_params->easing !== 'ease') {
            // Add the data attribute.
            $aos_attributes .= ' data-aos-easing="' . $mod_params->easing . '"';
        }

        // Set the offset of the animation.
        if (isset($mod_params->offset) && $mod_params->offset !== "120") {
            // Add the data attribute.
            $aos_attributes .= ' data-aos-offset="' . $mod_params->offset . '"';
        }

        // Set the delay of the animation.
        if (isset($mod_params->delay) && $mod_params->delay !== "0") {
            // Add the data attribute.
            $aos_attributes .= ' data-aos-delay="' . $mod_params->delay . '"';
        }

        // Set the anchor placement of the window (desktop).
        if (isset($mod_params->anchorplacement) && $mod_params->anchorplacement !== 'top-bottom') {
            // Add the data attribute.
            $aos_attributes .= ' data-aos-anchor-placement="' . $mod_params->anchorplacement . '"';
        }

        // Animation once or multiple times.
        if (isset($mod_params->animateonce) && $mod_params->animateonce !== 0) {
            // Add the data attribute.
            $aos_attributes .= ' data-aos-once="true"';
        }

        // Mirror the animation.
        if (isset($mod_params->annimationinverse) && $mod_params->annimationinverse !== 0) {
            // Add the data attribute.
            $aos_attributes .= ' data-aos-mirror="true"';
        }

        // Get the first html element from module and split at the first > character.
        // Then insert the data attributes.
        if (!empty($module->content)) {
            $splitToArray = explode(">", $module->content, 2);
            $module->content = $splitToArray[0] . ' ' . $aos_attributes . '>' . $splitToArray[1];
        }
    }
}

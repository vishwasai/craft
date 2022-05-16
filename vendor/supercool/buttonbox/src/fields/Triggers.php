<?php
/**
 * ButtonBox plugin for Craft CMS 3.x
 *
 * ButtonBox
 *
 * @link      http://supercooldesign.co.uk
 * @copyright Copyright (c) 2017 Supercool
 */

namespace supercool\buttonbox\fields;

use supercool\buttonbox\ButtonBox as ButtonBoxPlugin;
use supercool\buttonbox\assetbundles\buttonbox\ButtonBoxAsset;

use Craft;
use craft\base\ElementInterface;
use craft\fields\BaseOptionsField;
use craft\helpers\Db;
use yii\db\Schema;
use craft\helpers\Json;
use craft\helpers\Template;

/**
 *
 * @author    Supercool
 * @package   TableMaker
 * @since     1.0.0
 */

class Triggers extends BaseOptionsField
{
    // Public Properties
    // =========================================================================


    // Static Methods
    // =========================================================================
    
    public $options;
    public $displayAsGraphic;
    public $displayFullwidth;

    /**
     * Returns the display name of this class.
     *
     * @return string The display name of this class.
     */
    public static function displayName(): string
    {
        return Craft::t('buttonbox', 'Button Box - Triggers');
    }

    /**
     * Returns whether this field has a column in the content table.
     *
     * @return bool
     */
    public static function hasContentColumn(): bool
    {
        return false;
    }

    // Public Methods
    // =========================================================================

    /**
     * Returns the component’s settings HTML.
     *
     * @return string|null
     */
    public function getSettingsHtml()
    {
        $options = $this->translatedOptions();

        if (!$options)
        {
            // Give it a default row
            $options = array(
                array(
                    'label' => '',
                    'showLabel' => false,
                    'imageUrl' => '',
                    'type' => '',
                    'link' => '',
                    'newWindow' => false,
                )
            );
        }

        $table =  Craft::$app->getView()->renderTemplateMacro('_includes/forms', 'editableTableField', array(
            array(
                'label'        => $this->optionsSettingLabel(),
                'instructions' => Craft::t('buttonbox', 'Image urls can be relative e.g. /admin/resources/buttonbox/images/align-left.png'),
                'id'           => 'options',
                'name'         => 'options',
                'addRowLabel'  => Craft::t('buttonbox', 'Add a trigger'),
                'cols'         => array(
                  'label' => array(
                    'heading'      => Craft::t('buttonbox', 'Option Label'),
                    'type'         => 'singleline'
                    ),
                  'showLabel' => array(
                    'heading'      => Craft::t('buttonbox', 'Show Label?'),
                    'type'         => 'checkbox',
                    'class'        => 'thin'
                    ),
                  'imageUrl' => array(
                    'heading'      => Craft::t('buttonbox', 'Image URL'),
                    'type'         => 'singleline'
                    ),
                  'type' => array(
                    'heading' => Craft::t('buttonbox', 'Trigger Type'),
                    'class'   => 'thin triggerType',
                    'type'    => 'select',
                    'options' => array(
                      'link' => 'Link',
                      'js'   => 'JavaScript'
                      ),
                    ),
                  'value' => array(
                    'heading'      => Craft::t('buttonbox', 'HREF or Custom JS'),
                    'type'         => 'singleline',
                    'class'        => 'code triggerValue'
                    ),
                  'newWindow' => array(
                    'heading'      => Craft::t('buttonbox', 'New window?'),
                    'type'         => 'checkbox',
                    'class'        => 'thin newWindow'
                    ),
                  ),
                'rows' => $options
            )
        ));

        $displayAsGraphic = Craft::$app->getView()->renderTemplateMacro('_includes/forms', 'checkboxField', array(
          array(
            'label' => Craft::t('buttonbox', 'Display as graphic'),
            'instructions' => Craft::t('buttonbox', 'This will take the height restrictions off the buttons to allow for larger images.'),
            'id' => 'displayAsGraphic',
            'name' => 'displayAsGraphic',
            'class' => 'displayAsGraphic',
            'value' => 1,
            'checked' => $this->displayAsGraphic
            )
        ));

        $displayFullwidth = Craft::$app->getView()->renderTemplateMacro('_includes/forms', 'checkboxField', array(
          array(
            'label' => Craft::t('buttonbox', 'Display full width'),
            'instructions' => Craft::t('buttonbox', 'Allow the button group to be fullwidth, useful for allowing larger graphics to be more responsive.'),
            'id' => 'displayFullwidth',
            'name' => 'displayFullwidth',
            'class' => 'displayFullwidth',
            'value' => 1,
            'checked' => $this->displayFullwidth
            )
        ));

        return $displayAsGraphic . $displayFullwidth . $table;

    }

    /**
     * Returns the field’s input HTML.
     *
     * @param ElementInterface|null $element The element the field is associated with, if there is one
     *
     * @return string The input HTML.
     */
    public function getInputHtml($value, ElementInterface $element = null): string
    {
        $name = $this->handle;
        $options = $this->translatedOptions();

        // If this is a new entry, look for a default option
        if ($this->isFresh($element))
        {
            $value = $this->defaultValue();
        }

        Craft::$app->getView()->registerAssetBundle(ButtonBoxAsset::class);
        Craft::$app->getView()->registerJs('new Craft.ButtonBoxButtons("'.Craft::$app->getView()->namespaceInputId($name).'");');

        // Parse element tags in links
        foreach ($options as $i => $opt) {
            $options[$i]['value'] = Craft::$app->getView()->renderObjectTemplate($opt['value'], $element);
        }

        return Craft::$app->getView()->renderTemplate('buttonbox/_components/fields/triggers/input', [
            'name'    => $name,
            'value'   => $value,
            'options' => $options,
            'displayAsGraphic' => $this->displayAsGraphic,
            'displayFullwidth' => $this->displayFullwidth
        ]);

    }

    // Protected Methods
    // =========================================================================
    
    protected function optionsSettingLabel() : string
    {
        return Craft::t('buttonbox', 'Triggers Options');
    }

    /**
     * Override this method to add cssColour and default value to the options
     * 
     * @return array 
     */
    protected function translatedOptions(): array
    {
        $translatedOptions = [];

        foreach ($this->options as $option) {
            $translatedOptions[] = [
                'label'     => Craft::t('site', $option['label']),
                'value'     => $option['value'],
                'showLabel' => $option['showLabel'],
                'imageUrl'  => $option['imageUrl'],
                'type'      => $option['type'],
                'newWindow' => $option['newWindow']
            ];
        }

        return $translatedOptions;
    }

}

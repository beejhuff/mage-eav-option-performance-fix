<?php
/**
 * George Babarus extension for Magento
 *
 * Long description of this file (if any...)
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade
 * the Bb Eav module to newer versions in the future.
 * If you wish to customize the Bb Eav module for your needs
 * please refer to http://www.magentocommerce.com for more information.
 *
 * @category   Bb
 * @package    Bb_Eav
 * @copyright  Copyright (C) 2014 http://www.babarus.ro
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Fix a performance issue for Magento community and enterprise related to 
 * attributes get option text by option id
 *
 * 
 *
 * @category   Bb
 * @package    Bb_Eav
 * @subpackage Model
 * @author     George Babarus <george.babarus@gmail.com>
 */

class Bb_Eav_Model_Entity_Attribute_Frontend_Default extends Mage_Eav_Model_Entity_Attribute_Frontend_Default
{


    /**
     * Retreive attribute value
     *
     * @param $object
     * @return mixed
     */
    public function getValue(Varien_Object $object)
    {
        $value = $object->getData($this->getAttribute()->getAttributeCode());
        if (in_array($this->getConfigField('input'), array('select','boolean'))) {
            $valueOption = $this->getOption($value);
            if (!$valueOption) {
                $opt     = Mage::getModel('eav/entity_attribute_source_boolean');
                $options = $opt->getAllOptions();
                if ($options) {
                    foreach ($options as $option) {
                        if ($option['value'] == $value) {
                            $valueOption = $option['label'];
                        }
                    }
                }
            }
            $value = $valueOption;
        } elseif ($this->getConfigField('input') == 'multiselect') {
            $value = $this->getOption($value);
            if (is_array($value)) {
                $value = implode(', ', $value);
            }
        }

        return $value;
    }



}

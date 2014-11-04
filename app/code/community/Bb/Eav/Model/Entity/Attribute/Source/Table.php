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
 * Issue was fixed by replacing the way to find option label in getOptionText method 
 *
 * @category   Bb
 * @package    Bb_Eav
 * @subpackage Model
 * @author     George Babarus <george.babarus@gmail.com>
 */

class Bb_Eav_Model_Entity_Attribute_Source_Table extends Mage_Eav_Model_Entity_Attribute_Source_Table
{


    /**
     * return an array of selected option label and value of attribute for product
     *
     * @param $optionsIds
     * @return mixed
     */
    public function getOptionTextByOptionId($optionsIds){
        if(!is_array($optionsIds)){
            return array();
        }

        $collection = Mage::getResourceModel('eav/entity_attribute_option_collection')
            ->setPositionOrder('asc')
            ->setAttributeFilter($this->getAttribute()->getId())
            ->setStoreFilter($this->getAttribute()->getStoreId());


        $collection->getSelect()
            ->where('main_table.option_id IN (?)',$optionsIds);

        $collection->load();

        return $collection->toOptionArray();
    }

    /**
     * Get a text for option value
     *
     * @param string|integer $value
     * @return string
     */
    public function getOptionText($value)
    {
        $isMultiple = false;
        if (strpos($value, ',')) {
            $isMultiple = true;
            $value = explode(',', $value);
        } else {
            if (!empty($value)) {
                $value = array($value);
            }
        }

        $options = $this->getOptionTextByOptionId($value);

        if ($isMultiple) {
            $values = array();
            foreach ($options as $item) {
                if (in_array($item['value'], $value)) {
                    $values[] = $item['label'];
                }
            }
            return $values;
        }

        foreach ($options as $item) {
            if (in_array($item['value'], $value)) {
                return $item['label'];
            }
        }
        return false;
    }



}

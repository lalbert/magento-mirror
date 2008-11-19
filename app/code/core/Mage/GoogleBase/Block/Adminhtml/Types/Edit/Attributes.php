<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category   Mage
 * @package    Mage_GoogleBase
 * @copyright  Copyright (c) 2008 Irubin Consulting Inc. DBA Varien (http://www.varien.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Attributes box for Google Base attributes mapping
 *
 * @category    Mage
 * @package     Mage_GoogleBase
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Mage_GoogleBase_Block_Adminhtml_Types_Edit_Attributes extends Mage_Adminhtml_Block_Widget_Form_Renderer_Fieldset_Element
{
    public function __construct()
    {
        $this->setTemplate('googlebase/types/edit/attributes.phtml');
    }

    protected function _prepareLayout()
    {
        $this->setChild('add_button',
            $this->getLayout()->createBlock('adminhtml/widget_button')
                ->setData(array(
                    'label' => Mage::helper('googlebase')->__('Add New Attribute'),
                    'class' => 'add',
                    'id'    => 'add_new_attribute',
                    'on_click' => 'gBaseAttribute.add()'
                ))
        );
        $this->setChild('delete_button',
            $this->getLayout()->createBlock('adminhtml/widget_button')
                ->setData(array(
                    'label' => Mage::helper('googlebase')->__('Remove'),
                    'class' => 'delete delete-product-option',
                    'on_click' => 'gBaseAttribute.remove(event)'
                ))
        );

        parent::_prepareLayout();
    }

    public function getFieldId()
    {
        return 'gbase_attribute';
    }

    public function getFieldName ()
    {
        return 'attributes';
    }

    public function getGbaseAttributesSelectHtml()
    {
        $options = array('' => $this->__('Custom attribute, no mapping'));

        $attributes = Mage::getModel('googlebase/service_feed')
            ->getAttributes($this->getGbaseItemtype());
        foreach ($attributes as $attr) {
            $options[$attr->getId()] = $attr->getName();
        }

        $select = $this->getLayout()->createBlock('adminhtml/html_select')
            ->setId($this->getFieldId().'_{{index}}_gattribute')
            ->setName($this->getFieldName().'[{{index}}][gbase_attribute]')
            ->setOptions($options);
        return $select->getHtml();
    }

    public function getAttributesSelectHtml()
    {
        $select = $this->getLayout()->createBlock('adminhtml/html_select')
            ->setId($this->getFieldId().'_{{index}}_attribute')
            ->setName($this->getFieldName().'[{{index}}][attribute_id]')
            ->setOptions($this->_getAttributes($this->getAttributeSetId()));
        return $select->getHtml();
    }

    public function getAddButtonHtml()
    {
        return $this->getChildHtml('add_button');
    }

    public function getDeleteButtonHtml()
    {
        return $this->getChildHtml('delete_button');
    }

    public function _getAttributes($setId)
    {
        $attributes = Mage::getModel('googlebase/attribute')->getAllowedAttributes($setId);
        $result = array();

        foreach ($attributes as $attribute) {
            /* @var $attribute Mage_Catalog_Model_Resource_Eav_Attribute */
            $result[$attribute->getAttributeId()] = $attribute->getFrontendLabel();
        }
        return $result;
    }

    protected function _toJson($data)
    {
        return Zend_Json::encode($data);
    }
}
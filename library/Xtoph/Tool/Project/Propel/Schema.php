<?php

/**
 * Xtoph Framework
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://framework.zend.com/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@zend.com so we can send you a copy immediately.
 *
 * @category   Xtoph
 * @package    Xtoph_Tool
 * @subpackage Framework
 * @copyright  Christophe Sicard (http://christophe.plom.net)
 * @license    http://christophe.plom.net/license/new-bsd     New BSD License
 * @version    1.0
 */
class Xtoph_Tool_Project_Propel_SchemaException
    extends Zend_Exception
{
   //put your code here
}

/**
 * @category   Xtoph
 * @package    Xtoph_Tool
 * @copyright  Christophe Sicard (http://christophe.plom.net)
 * @license    http://christophe.plom.net/license/new-bsd     New BSD License
 */
class Xtoph_Tool_Project_Propel_Schema
{
   const XPATH_TABLE = "/database/table[@name='%s']";
   const XPATH_COLUMN = "column[@name='%s']";
   const XPATH_VALIDATOR = "validator[@column='%s']";
   const XPATH_VALIDATORRULE = "rule[@name='%s']";

   const TYPE_VARCHAR = 'VARCHAR';
   const TYPE_INTEGER = 'INTEGER';

   const COLUMN_ATTRIBUTE_NAME = 'name';
   const COLUMN_ATTRIBUTE_PHPNAME = 'phpName';
   const COLUMN_ATTRIBUTE_TYPE = 'type';
   const COLUMN_ATTRIBUTE_PRIMARYKEY = 'primaryKey';
   const COLUMN_ATTRIBUTE_AUTOINCREMENT = 'autoIncrement';
   const COLUMN_ATTRIBUTE_REQUIRED = 'required';
   const COLUMN_ATTRIBUTE_SIZE = 'size';
   const COLUMN_ATTRIBUTE_DEFAULTVALUE = 'defaultValue';

   const VALIDATOR_RULE_CLASS = 'class';
   const VALIDATOR_RULE_MINLENGTH = 'minLength';
   const VALIDATOR_RULE_MAXLENGTH = 'maxLength';
   const VALIDATOR_RULE_REQUIRED = 'required';
   const VALIDATOR_RULE_TYPE = 'type';

   /**
    * @var SimpleXMLElement
    */
   protected $_xml = null;

   /**
    * @todo Complete default validator rule message
    * @param string $rule
    * @param string $value
    * @param string $message
    * @param string $column
    * @param string $table
    * @return string 
    */
   public static function getValidatorRuleMessage($rule, $value, $message, $column, $table)
   {
      if (empty($message)) {
         switch ($rule) {
            case self::VALIDATOR_RULE_MINLENGTH:
               $message = sprintf("The '%s.%s' field must be %s characters long.", $table, $column, $value);
               break;
            case self::VALIDATOR_RULE_MAXLENGTH:
               $message = sprintf("The '%s.%s' field must be not longer than %s characters.", $table, $column, $value);
               break;
            case self::VALIDATOR_RULE_REQUIRED:
               $message = sprintf("The '%s.%s' field is required.", $table, $column);
               break;
            case self::VALIDATOR_RULE_TYPE:
               $message = sprintf("The '%s.%s' field must be an %s type.", $table, $column, $value);
               break;
         }
      }
      return $message;
   }

   public static function isValidatorRuleValid($rule, $value)
   {
      if (!in_array($rule,
              array(
              self::VALIDATOR_RULE_CLASS,
              self::VALIDATOR_RULE_MINLENGTH,
              self::VALIDATOR_RULE_MAXLENGTH,
              self::VALIDATOR_RULE_REQUIRED,
              self::VALIDATOR_RULE_TYPE
          ))) {
         throw new Xtoph_Tool_Project_Propel_SchemaException("Validator rule '$rule' is not valid");
      }
      return true;
   }

   public static function isColumnAttributeValid($name, $value)
   {
      if (!in_array($name,
              array(
              self::COLUMN_ATTRIBUTE_AUTOINCREMENT,
              self::COLUMN_ATTRIBUTE_NAME,
              self::COLUMN_ATTRIBUTE_PHPNAME,
              self::COLUMN_ATTRIBUTE_PRIMARYKEY,
              self::COLUMN_ATTRIBUTE_REQUIRED,
              self::COLUMN_ATTRIBUTE_SIZE,
              self::COLUMN_ATTRIBUTE_TYPE,
              self::COLUMN_ATTRIBUTE_DEFAULTVALUE
          ))) {
         throw new Xtoph_Tool_Project_Propel_SchemaException("Attributes '$name' is not valid");
      }
      switch ($name) {
         case self::COLUMN_ATTRIBUTE_TYPE:
            if (!in_array($value,
                    array(
                    self::TYPE_INTEGER,
                    self::TYPE_VARCHAR
                ))) {
               throw new Xtoph_Tool_Project_Propel_SchemaException("Value '$value' is not valid for '$name' attribute");
            }
            break;
         case self::COLUMN_ATTRIBUTE_SIZE:
            if (!is_numeric($value)) {
               throw new Xtoph_Tool_Project_Propel_SchemaException("Value '$value' is not valid for '$name' attribute");
            }
            break;
         case self::COLUMN_ATTRIBUTE_PRIMARYKEY:
         case self::COLUMN_ATTRIBUTE_REQUIRED:
            if (!in_array($value,
                    array(
                    'true',
                    'false'
                ))) {
               throw new Xtoph_Tool_Project_Propel_SchemaException("Value '$value' is not valid for '$name' attribute");
            }
            break;
      }
      return true;
   }

   protected static function _normalize($name)
   {
      return str_replace(' ', '', ucwords(str_replace('_', ' ', $name)));
   }

   public function getXml()
   {
      if (!is_null($this->_xml)) {
         return $this->_xml->asXML();
      }
   }

   public function getSimpleXMLElement()
   {
      return $this->_xml;
   }

   public function loadFromFile($filename)
   {
      $this->_xml = simplexml_load_file($filename);
   }

   public function addTable($name)
   {
      $xml = $this->_xml;
      $table = $xml->addChild('table');
      $table['name'] = $name;
      $table['phpName'] = ucwords($name);
      return $table;
   }

   public function hasTable($name)
   {
      return $this->_hasNode($this->getTable($name));
   }

   public function removeTable($name)
   {
      $this->_removeNode($this->getTable($name));
   }

   public function getTable($name, $first = false)
   {
      $a = $this->_xpath(sprintf(self::XPATH_TABLE, $name));
      if ($first) {
         return $a[0];
      } else {
         return $a;
      }
   }

   /**
    * @param string $name
    * @param string $table
    * @return SimpleXMLElement
    */
   public function addColumn($name, $table)
   {
      $xml = $this->getTable($table, true);
      $column = $xml->addChild('column');
      $column['name'] = $name;
      $column['phpName'] = self::_normalize($name);
      return $column;
   }

   public function hasColumn($name, $table)
   {
      if (!$this->hasTable($table)) {
         return new Xtoph_Tool_Project_Propel_SchemaException("Table '$table' doesn't exists");
      }
      return $this->_hasNode($this->getColumn($name, $table));
   }

   public function removeColumn($name, $table)
   {
      $this->_removeNode($this->getColumn($name, $table));
   }

   public function getColumn($name, $table, $first = false)
   {
      $a = $this->_xpath(sprintf(
              self::XPATH_TABLE . '/' . self::XPATH_COLUMN, $table, $name
          ));
      if ($first) {
         return $a[0];
      } else {
         return $a;
      }
   }

   /**
    * @param string $name
    * @param string $value
    * @param string $column
    * @param string $table
    * @return SimpleXMLElement Column node
    */
   public function setColumnAttribute($name, $value, $column, $table)
   {
      self::isColumnAttributeValid($name, $value);
      $column = $this->getColumn($column, $table, true);
      $column[$name] = $value;
      return $column;
   }

   /**
    * @param string $column
    * @param string $table
    * @return SimpleXMLElement
    */
   public function addValidator($column, $table)
   {
      $xml = $this->getTable($table, true);
      $validator = $xml->addChild('validator');
      $validator['column'] = $column;
      return $validator;
   }

   /**
    * @param string $rule
    * @param string $value
    * @param string $message
    * @param string $column
    * @param string $table
    * @param SimpleXMLElement $validator
    * @return SimpleXMLElement validator node
    */
   public function setValidatorRule($rule, $value, $message, $column, $table, SimpleXMLElement $validator = null)
   {
      self::isValidatorRuleValid($rule, $value);
      if (is_null($validator)) {
         $validator = $this->getValidator($column, $table, true);
      }
      $this->removeValidatorRule($validator, $rule);
      $message = self::getValidatorRuleMessage($rule, $value, $message, $column, $table);
      $this->addValidatorRule($validator, $rule, $value, $message);
      return $validator;
   }

   public function hasValidator($column, $table)
   {
      if (!$this->hasColumn($column, $table)) {
         return new Xtoph_Tool_Project_Propel_SchemaException("Column '$table.$column' doesn't exists");
      }
      return $this->_hasNode($this->getValidator($column, $table));
   }

   /**
    * @param string $column
    * @param string $table
    * @param boolean $first
    * @return mixed array|SimpleXMLElement
    */
   public function getValidator($column, $table, $first = false)
   {
      $a = $this->_xpath(sprintf(
              self::XPATH_TABLE . '/' . self::XPATH_VALIDATOR, $table, $column
          ));
      if ($first) {
         return $a[0];
      } else {
         return $a;
      }
   }

   /**
    * @param SimpleXMLElement $validator
    * @param string $rule
    * @param boolean $first
    * @return mixed array|SimpleXMLElement
    */
   public function getValidatorRule(SimpleXMLElement $validator, $rule,
       $first = false)
   {
      $a = $this->_xpath(sprintf(
              self::XPATH_VALIDATORRULE, $rule
          ),
          $validator);
      if ($first) {
         return $a[0];
      } else {
         return $a;
      }
   }

   /**
    * @param SimpleXMLElement $validator
    * @param string $rule 
    */
   public function removeValidatorRule(SimpleXMLElement $validator, $rule)
   {
      $this->_removeNode($this->getValidatorRule($validator, $rule));
   }
   
   /**
    * @param string $column
    * @param string $table
    */
   public function removeValidator($column, $table)
   {
      $this->_removeNode($this->getValidator($column, $table));
   }

   public function addValidatorRule(SimpleXMLElement $validator, $name, $value,
       $message)
   {
      $rule = $validator->addChild('rule');
      $rule['name'] = $name;
      if (!empty($value)) {
         $rule['value'] = $value;
      }
      $rule['message'] = $message;
      return $rule;
   }

   /**
    * @param string $xpath
    * @param SimpleXMLElement $simpleXMLElement
    * @return array
    * @throws Xtoph_Tool_Project_Propel_SchemaException if xpath gives an error
    */
   protected function _xpath($xpath, $simpleXMLElement = null)
   {
      if (is_null($simpleXMLElement)) {
         $simpleXMLElement = $this->_xml;
      }
      $a = $simpleXMLElement->xpath((string) $xpath);
      if ($a === false) {
         throw new Xtoph_Tool_Project_Propel_SchemaException('Xpath expression gives an error');
      }
      return $a;
   }

   protected function _hasNode(array $a)
   {
      if (!is_array($a)
          || count($a) == 0) {
         return false;
      }
      return true;
   }

   protected function _removeNode(array $a)
   {
      if ($this->_hasNode($a)) {
         //SimpleXML
         unset($a[0][0]);
         //Via Dom
//         $domRef = dom_import_simplexml($a[0]);
//         $domRef->parentNode->removeChild($domRef);
      }
   }

}
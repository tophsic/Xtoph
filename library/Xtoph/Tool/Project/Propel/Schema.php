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

   /**
    * @var SimpleXMLElement
    */
   protected $_xml = null;

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
    * @param string $xpath
    * @return array
    * @throws Xtoph_Tool_Project_Propel_SchemaException if xpath gives an error
    */
   protected function _xpath($xpath)
   {
      $a = $this->_xml->xpath((string) $xpath);
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
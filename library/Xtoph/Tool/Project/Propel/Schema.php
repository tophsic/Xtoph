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

   /**
    * @var SimpleXMLElement
    */
   protected $_xml = null;

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

   public function getDatabase($database)
   {
      
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
      $a = $this->getTable($name);
      if ($a === false) {
         throw new Xtoph_Tool_Project_Propel_SchemaException();
      } else if (!is_array($a)
          || count($a) == 0) {
         return false;
      }
      return true;
   }

   public function removeTable($name)
   {
      $a = $this->getTable($name);
      if (is_array($a)
          && count($a) >= 1) {
         //SimpleXML
         unset($a[0][0]);
         //Via Dom
//         $domRef = dom_import_simplexml($a[0]);
//         $domRef->parentNode->removeChild($domRef);
      }
   }

   public function getTable($name)
   {
      return $this->_xml->xpath("/database/table[@name='$name']");
   }

   public function filterColumn($column)
   {
      
   }

}
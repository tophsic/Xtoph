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
/**
 * @see Xtoph_Tool_Project_Context_Propel_Interface
 */
require_once 'Xtoph/Tool/Project/Context/Propel/Interface.php';

/**
 * @category   Xtoph
 * @package    Xtoph_Tool
 * @copyright  Christophe Sicard (http://christophe.plom.net)
 * @license    http://christophe.plom.net/license/new-bsd     New BSD License
 */
class Xtoph_Tool_Project_Context_Propel_ActiveValues
    implements Xtoph_Tool_Project_Context_Propel_Interface
{

   /**
    * @var string
    */
   protected $_schema;

   /**
    * @var string
    */
   protected $_table;

   /**
    * @var string
    */
   protected $_column;

   /**
    * @var Zend_Tool_Project_Profile_Resource
    */
   protected $_resource = null;

   /**
    * getName()
    *
    * @return string
    */
   public function getName()
   {
      return 'ActiveValues';
   }

   /**
    * getPersistentAttributes
    *
    * @return array
    */
   public function getPersistentAttributes()
   {
      $a = array(
          'schema' => $this->_schema,
          'table' => $this->_table,
          'column' => $this->_column
      );
      return $a;
   }

   public function init()
   {
      $this->_schema = $this->_resource->getAttribute('schema');
      $this->_table = $this->_resource->getAttribute('table');
      $this->_column = $this->_resource->getAttribute('column');
   }

   /**
    * setResource()
    *
    * @param Zend_Tool_Project_Profile_Resource $resource
    * @return Zend_Tool_Project_Context_Filesystem_Abstract
    */
   public function setResource(Zend_Tool_Project_Profile_Resource $resource)
   {
      $this->_resource = $resource;
      return $this;
   }

   public function setSchema($schema)
   {
      $this->_schema = (string) $schema;
      return $this;
   }

   public function setTable($table)
   {
      Zend_Debug::dump($table);
      $this->_table = (string) $table;
      return $this;
   }

   public function setColumn($column)
   {
      $this->_column = (string) $column;
      return $this;
   }

   public function getSchema()
   {
      return $this->_schema;
   }

   public function getTable()
   {
      return $this->_table;
   }

   public function getColumn()
   {
      return $this->_column;
   }

}
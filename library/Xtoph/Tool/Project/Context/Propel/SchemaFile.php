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
 * @see Zend_Tool_Project_Context_Filesystem_Directory
 */
require_once 'Zend/Tool/Project/Context/Filesystem/Directory.php';
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
class Xtoph_Tool_Project_Context_Propel_SchemaFile
    extends Zend_Tool_Project_Context_Filesystem_File
    implements Xtoph_Tool_Project_Context_Propel_Interface
{

   protected $_schemaFile = 'schema.xml';
   protected $_database = 'db';

   /**
    * @var string
    */
   protected $_filesystemName = 'schema.xml';

   /**
    * init()
    *
    */
   public function init()
   {
      $this->_schemaFile = $this->_resource->getAttribute('file');
      $this->_database = $this->_resource->getAttribute('database');
      $this->_filesystemName = $this->_schemaFile;
      parent::init();
   }

   /**
    * getPersistentAttributes()
    *
    * @return array
    */
   public function getPersistentAttributes()
   {
      return array(
          'file' => $this->_schemaFile
      );
   }

   /**
    * getName()
    *
    * @return string
    */
   public function getName()
   {
      return 'SchemaFile';
   }

   public function setSchemaFile($schemaFile)
   {
      $this->_schemaFile = (string) $schemaFile;
   }

   public function getSchemaFile()
   {
      return $this->_schemaFile;
   }
   
   public function getContents()
   {
      $schema = <<<EOT
<?xml version="1.0" encoding="utf-8"?>
<database name="{$this->_database}" defaultIdMethod="native">
</database>

EOT;
      return $schema;
   }

}
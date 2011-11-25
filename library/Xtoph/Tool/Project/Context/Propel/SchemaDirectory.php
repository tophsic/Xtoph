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
class Xtoph_Tool_Project_Context_Propel_SchemaDirectory
    extends Zend_Tool_Project_Context_Filesystem_Directory
    implements Xtoph_Tool_Project_Context_Propel_Interface
{

   protected $_schemaName = 'default';

   /**
    * @var string
    */
   protected $_filesystemName = 'schema';

   /**
    * init()
    *
    */
   public function init()
   {
      $this->_schemaName = $this->_resource->getAttribute('schemaName');
      $this->_filesystemName = $this->_schemaName;
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
          'schemaName' => $this->_schemaName
      );
   }

   /**
    * getName()
    *
    * @return string
    */
   public function getName()
   {
      return 'SchemaDirectory';
   }

   public function setSchemaName($schemaName)
   {
      $this->_schemaName = (string) $schemaName;
   }

   public function getSchemaName()
   {
      return $this->_schemaName;
   }

}
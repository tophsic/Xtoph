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
class Xtoph_Tool_Project_Context_Propel_PropertiesFile
    extends Zend_Tool_Project_Context_Filesystem_File
    implements Xtoph_Tool_Project_Context_Propel_Interface
{

   protected $_propertiesFile = 'build.properties';

   /**
    * @var string
    */
   protected $_filesystemName = 'build.properties';

   /**
    * init()
    *
    */
   public function init()
   {
      $this->_propertiesFile = $this->_resource->getAttribute('file');
      $this->_filesystemName = $this->_propertiesFile;
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
          'file' => $this->_propertiesFile
      );
   }

   /**
    * getName()
    *
    * @return string
    */
   public function getName()
   {
      return 'PropertiesFile';
   }

   public function setPropertiesFile($propertiesFile)
   {
      $this->_propertiesFile = (string) $propertiesFile;
   }

   public function getPropertiesFile()
   {
      return $this->_propertiesFile;
   }

   public function getContents()
   {
      parent::getContents();
   }

}
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
class Xtoph_Tool_Project_Context_Propel_RuntimeConfigFile
    extends Zend_Tool_Project_Context_Filesystem_File
    implements Xtoph_Tool_Project_Context_Propel_Interface
{

   protected $_project = null;
   
   protected $_runtimeConfigName = 'runtime-conf.xml';

   /**
    * @var string
    */
   protected $_filesystemName = 'runtime-conf.xml';

   /**
    * init()
    *
    */
   public function init()
   {
      $this->_runtimeConfigName = $this->_resource->getAttribute('file');
      $this->_project = $this->_resource->getAttribute('project');
      $this->_filesystemName = $this->_runtimeConfigName;
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
          'file' => $this->_runtimeConfigName
      );
   }

   /**
    * getName()
    *
    * @return string
    */
   public function getName()
   {
      return 'RuntimeConfigFile';
   }

   public function setRuntimeConfigName($runtimeConfigName)
   {
      $this->_runtimeConfigName = (string) $runtimeConfigName;
   }

   public function getRuntimeConfigName()
   {
      return $this->_runtimeConfigName;
   }

   public function getContents()
   {
      $conf = <<<EOT
<?xml version="1.0" encoding="UTF-8"?>
<config>
  <propel>
    <datasources default="{$this->_project}">
      <datasource id="{$this->_project}">
        <adapter>sqlite</adapter>
        <connection>
          <dsn>sqlite:</dsn>
          <user/>
          <password/>
        </connection>
      </datasource>
    </datasources>
  </propel>
</config>

EOT;
      return $conf;
   }

}
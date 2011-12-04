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
class Xtoph_Tool_Project_Context_Propel_ConnectionConfigFile
    extends Zend_Tool_Project_Context_Filesystem_File
    implements Xtoph_Tool_Project_Context_Propel_Interface
{

   protected $_project = null;
   protected $_connectionConfigName = 'runtime-conf.xml';

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
      $this->_connectionConfigName = $this->_resource->getAttribute('file');
      $this->_project = $this->_resource->getAttribute('project');
      $this->_filesystemName = $this->_connectionConfigName;
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
          'file' => $this->_connectionConfigName
      );
   }

   /**
    * getName()
    *
    * @return string
    */
   public function getName()
   {
      return 'ConnectionConfigFile';
   }

   public function setConnectionConfigName($runtimeConfigName)
   {
      $this->_connectionConfigName = (string) $runtimeConfigName;
   }

   public function getConnectionConfigName()
   {
      return $this->_connectionConfigName;
   }

   public function getContents()
   {
      switch ($this->_resource->getAttribute('adapter')) {
         case 'sqlite':
            $conf = $this->_getSqliteContents($this->_project);
            break;
         case 'mysql':
            $conf = $this->_getMysqlContents($this->_project);
            break;
         default:
            throw new Zend_Tool_Project_Context_Exception('Unknown adapter');
            break;
      }
      return $conf;
   }

   protected function _getSqliteContents($project)
   {
      $path = $this->getBaseDirectory() . '/../../data/';
      $path = realpath($path);
      $path.= '/' . $project . '.db3';
      return <<<EOT
<?xml version="1.0" encoding="UTF-8"?>
<config>
  <propel>
    <datasources default="{$project}">
      <datasource id="{$project}">
        <adapter>sqlite</adapter>
        <connection>
          <dsn>sqlite:{$path}</dsn>
          <user/>
          <password/>
        </connection>
      </datasource>
    </datasources>
  </propel>
</config>

EOT;
   }

   protected function _getMysqlContents($project, $user = "root", $password = "")
   {
      return <<<EOT
<?xml version="1.0" encoding="UTF-8"?>
<config>
  <propel>
    <datasources default="{$project}">
      <datasource id="{$project}">
        <adapter>mysql</adapter>
        <connection>
          <dsn>mysql:host=localhost;dbname=$project</dsn>
          <user>{$user}</user>
          <password>{$password}</password>
        </connection>
      </datasource>
    </datasources>
  </propel>
</config>

EOT;
   }

}
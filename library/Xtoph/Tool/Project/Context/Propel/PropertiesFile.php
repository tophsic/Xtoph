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

   protected $_project        = null;
   protected $_propertiesFile = 'build.properties';

   /**
    * @var string
    */
   protected $_filesystemName = 'build.properties';

   protected function _getSqliteContents($project)
   {
      return <<<EOT
# General Build Settings
propel.project                         = {$project}
propel.schema.validate                 = true

# Database Settings
propel.database                        = sqlite
propel.database.url                    = sqlite:\${propel.output.dir}/db.sq3
#propel.database.user                   = 
#propel.database.password               = 

# Customizing Generated Object Model
propel.addValidateMethod               = true
#propel.basePrefix                      = 
#propel.classPrefix                     = 
propel.addIncludes                     = true

# Directories
propel.output.dir                      = \${propel.project.dir}/../../application/models/
propel.php.dir                         = \${propel.output.dir}/
propel.phpconf.dir                     = \${propel.project.dir}/../../application/configs/
propel.sql.dir                         = \${propel.project.dir}/sql/

# Migrations
propel.migration.table                 = propel_migration
propel.migration.caseInsensitive       = true
propel.migration.dir                   = \${propel.project.dir}/migrations

EOT;
   }

   protected function _getMysqlContents($project, $user = 'root', $password = '')
   {
      return <<<EOT
# General Build Settings
propel.project                         = {$project}
propel.schema.validate                 = true

# Database Settings
propel.database                        = mysql
propel.database.url                    = mysql:host=localhost;dbname={$project}
propel.database.user                   = {$user}
propel.database.password               = {$password}

# Customizing Generated Object Model
propel.addValidateMethod               = true
#propel.basePrefix                      = 
#propel.classPrefix                     = 
propel.addIncludes                     = true

# Mysql-specific Settings
propel.mysql.tableType                 = MyIsam

# Directories
propel.output.dir                      = \${propel.project.dir}/../../application/models/
propel.php.dir                         = \${propel.output.dir}/
propel.phpconf.dir                     = \${propel.project.dir}/../../application/configs/
propel.sql.dir                         = \${propel.project.dir}/sql/

# Migrations
propel.migration.table                 = propel_migration
propel.migration.caseInsensitive       = true
propel.migration.dir                   = \${propel.project.dir}/migrations

EOT;
   }

   /**
    * init()
    *
    */
   public function init()
   {
      $this->_propertiesFile = $this->_resource->getAttribute('file');
      $this->_project = $this->_resource->getAttribute('project');
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
      switch ($this->_resource->getAttribute('adapter')) {
         case 'sqlite':
            $properties = $this->_getSqliteContents($this->_project);
            break;
         case 'mysql':
            $properties = $this->_getMysqlContents($this->_project);
            break;
         default:
            throw new Zend_Tool_Project_Context_Exception('Unknown exception');
            break;
      }
      return $properties;
   }

}
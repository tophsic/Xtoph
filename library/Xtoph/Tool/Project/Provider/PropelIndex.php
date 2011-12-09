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
require_once 'Xtoph/Tool/Project/Provider/Abstract.php';
require_once 'Zend/Tool/Project/Provider/Exception.php';

/**
 * @category   Xtoph
 * @package    Xtoph_Tool
 * @copyright  Christophe Sicard (http://christophe.plom.net)
 * @license    http://christophe.plom.net/license/new-bsd     New BSD License
 */
class Xtoph_Tool_Project_Provider_PropelIndex
    extends Xtoph_Tool_Project_Provider_PropelAbstract
    implements Zend_Tool_Framework_Provider_Pretendable
{

   protected function _createIndex($name, $columns, $table,
       Xtoph_Tool_Project_Propel_Schema $schema)
   {
      $columns = explode(',', $columns);
      foreach ($columns as $column) {
         if ($schema->hasColumn($column, $table) !== true) {
            throw new Zend_Tool_Project_Provider_Exception("Column '$column' does not exist in table '$table'");
         }
      }
      $index = null;
      if ($schema->hasIndex($name, $table) === true) {
         throw new Zend_Tool_Project_Provider_Exception("Column '$table.$column' has already a index named '$name'");
      }
      $index = $schema->addIndex($name, $columns, $table);
      return $index;
   }

   protected function _deleteIndex($name, $table,
       Xtoph_Tool_Project_Propel_Schema $schema)
   {
      $index = null;
      if ($schema->hasIndex($name, $table) !== true) {
         throw new Zend_Tool_Project_Provider_Exception("Table '$table' does not have a index named '$name'");
      }
      $index = $schema->getIndex($name, $table);
      $schema->removeIndex($name, $table);
      return $index;
   }

   public function create($name = '', $columns = array(), $table = '',
       $schema = '')
   {
      $this->_loadProfile(self::NO_PROFILE_THROW_EXCEPTION);

      $request = $this->_registry->getRequest();
      $response = $this->_registry->getResponse();

      $schema = Xtoph_Tool_Project_Provider_Propel::getActiveSchema($this->_loadedProfile,
              $schema);
      $table = Xtoph_Tool_Project_Provider_Propel::getActiveTable($this->_loadedProfile,
              $table);
      $column = Xtoph_Tool_Project_Provider_Propel::getActiveColumn($this->_loadedProfile,
              '');

      if (!empty($schema)
          && !empty($table)) {

         if (!$this->initializeSchema($schema)) {
            throw new Zend_Tool_Project_Provider_Exception("Schema '$schema' could not be initialized");
         }

         $unique = $this->_createIndex($name, $columns, $table,
             $this->_loadedSchema);

         Xtoph_Tool_Project_Provider_Propel::setActiveValues($this->_loadedProfile,
             $schema, $table, $column);

         if (!is_null($unique)) {
            if ($request->isPretend()) {
               $response->appendContent("Would create index '$name' in table '$table'");
            } else {
               $response->appendContent("Creating index '$name' in table '$table'");
               $this->_storeSchema();
               $this->_storeProfile();
            }
         } else {
            throw new Zend_Tool_Project_Profile_Exception('Index was not created');
         }
      } else {
         throw new Zend_Tool_Project_Profile_Exception('Schema, table names should be provided');
      }
   }

   public function delete($name = '', $table = '', $schema = '')
   {
      $this->_loadProfile(self::NO_PROFILE_THROW_EXCEPTION);

      $request = $this->_registry->getRequest();
      $response = $this->_registry->getResponse();

      $schema = Xtoph_Tool_Project_Provider_Propel::getActiveSchema($this->_loadedProfile,
              $schema);
      $table = Xtoph_Tool_Project_Provider_Propel::getActiveTable($this->_loadedProfile,
              $table);
      $column = Xtoph_Tool_Project_Provider_Propel::getActiveColumn($this->_loadedProfile,
              '');

      if (!empty($schema)
          && !empty($table)) {

         if (!$this->initializeSchema($schema)) {
            throw new Zend_Tool_Project_Provider_Exception("Schema '$schema' could not be initialized");
         }

         $unique = $this->_deleteIndex($name, $table, $this->_loadedSchema);

         Xtoph_Tool_Project_Provider_Propel::setActiveValues($this->_loadedProfile,
             $schema, $table, $column);

         if (!is_null($unique)) {
            if ($request->isPretend()) {
               $response->appendContent("Would delete index '$name' in table '$table'");
            } else {
               $response->appendContent("Deleting index '$name' in table '$table'");
               $this->_storeSchema();
               $this->_storeProfile();
            }
         } else {
            throw new Zend_Tool_Project_Profile_Exception('Index was not deleted');
         }
      } else {
         throw new Zend_Tool_Project_Profile_Exception('Schema, table names should be provided');
      }
   }

}
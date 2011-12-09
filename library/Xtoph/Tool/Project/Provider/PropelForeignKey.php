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
class Xtoph_Tool_Project_Provider_PropelForeignKey
    extends Xtoph_Tool_Project_Provider_PropelAbstract
    implements Zend_Tool_Framework_Provider_Pretendable
{

   protected function _createForeignKey($key, $foreignTable, $name, $column,
       $table, Xtoph_Tool_Project_Propel_Schema $schema)
   {
      if ($schema->hasColumn($key, $foreignTable) !== true) {
         throw new Zend_Tool_Project_Provider_Exception("Column '$key' does not exist in table '$foreignTable'");
      }
      if ($schema->hasColumn($column, $table) !== true) {
         throw new Zend_Tool_Project_Provider_Exception("Column '$column' does not exist in table '$table'");
      }
      $foreignKey = null;
      $name = Xtoph_Tool_Project_Propel_Schema::getForeignKeyName($name, $foreignTable, $column);
      if ($schema->hasForeignKey($name, $table) === true) {
         throw new Zend_Tool_Project_Provider_Exception("Column '$table.$column' has already a foreign-key");
      }
      $foreignKey = $schema->addForeignKey($key, $foreignTable, $name, $column,
          $table);
      return $foreignKey;
   }

   public function create($key, $foreignTable, $name = '', $column = '',
       $table = '', $schema = '')
   {
      $this->_loadProfile(self::NO_PROFILE_THROW_EXCEPTION);

      $request = $this->_registry->getRequest();
      $response = $this->_registry->getResponse();

      $schema = Xtoph_Tool_Project_Provider_Propel::getActiveSchema($this->_loadedProfile,
              $schema);
      $table = Xtoph_Tool_Project_Provider_Propel::getActiveTable($this->_loadedProfile,
              $table);

      $column = Xtoph_Tool_Project_Provider_Propel::getActiveColumn($this->_loadedProfile,
              $column);

      if (!empty($schema)
          && !empty($table)
          && !empty($column)) {

         if (!$this->initializeSchema($schema)) {
            throw new Zend_Tool_Project_Provider_Exception("Schema '$schema' could not be initialized");
         }

         $foreignKey = $this->_createForeignKey($key, $foreignTable, $name,
             $column, $table, $this->_loadedSchema);

         Xtoph_Tool_Project_Provider_Propel::setActiveValues($this->_loadedProfile,
             $schema, $table, $column);

         if (!is_null($foreignKey)) {
            if ($request->isPretend()) {
               $response->appendContent("Would create foreign-key '$foreignTable.$key' for column '$table.$column'");
            } else {
               $response->appendContent("Creating foreign-key '$foreignTable.$key' for column '$table.$column'");
               $this->_storeSchema();
               $this->_storeProfile();
            }
         } else {
            throw new Zend_Tool_Project_Profile_Exception('Foreign key was not created');
         }
      } else {
         throw new Zend_Tool_Project_Profile_Exception('Schema, table and column names should be provided');
      }
   }

   public function delete()
   {
      $this->_registry->getResponse()->appendContent('TODO: delete action in propel-behavior provider');
   }

}
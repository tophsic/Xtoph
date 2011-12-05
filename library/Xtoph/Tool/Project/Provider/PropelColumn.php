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
class Xtoph_Tool_Project_Provider_PropelColumn
    extends Xtoph_Tool_Project_Provider_PropelAbstract
    implements Zend_Tool_Framework_Provider_Pretendable
{
   const TYPE_VARCHAR = 'VARCHAR';
   const TYPE_INTEGER = 'INTEGER';

   protected $_specialties = array(
       'PrimaryKey',
       'Required',
       'Size',
       'Type'
   );

   protected function _setColumnAttributes($name, $value, $column, $table,
       Xtoph_Tool_Project_Propel_Schema $schema)
   {
      if (!$schema->hasColumn($column, $table)) {
         throw new Zend_Tool_Project_Provider_Exception("Column '$column' does not exists in table '$table'");
      }
      $attribute = $schema->setColumnAttribute($name, $value, $column, $table);
      return $attribute;
   }

   protected function _createColumn($name, $table, $force,
       Xtoph_Tool_Project_Propel_Schema $schema)
   {
      $column = null;
      if ($schema->hasColumn($name, $table) && $force === false) {
         throw new Zend_Tool_Project_Provider_Exception("Column '$name' already exists in table '$table'");
      } else {
         if ($force == true) {
            $schema->removeColumn($name, $table);
         }
         $column = $schema->addColumn($name, $table);
      }
      return $column;
   }

   public function create($name, $table = null, $schema = null, $force = false)
   {
      $this->_loadProfile(self::NO_PROFILE_THROW_EXCEPTION);

      $request = $this->_registry->getRequest();
      $response = $this->_registry->getResponse();

      $schema = Xtoph_Tool_Project_Provider_Propel::getActiveSchema($this->_loadedProfile,
              $schema);
      $table = Xtoph_Tool_Project_Provider_Propel::getActiveTable($this->_loadedProfile,
              $table);

      if (!is_null($schema) && !is_null($table)) {

         if (!$this->initializeSchema($schema)) {
            throw new Zend_Tool_Project_Provider_Exception("Schema '$schema' could not be initialized");
         }

         $column = $this->_createColumn($name, $table, $force,
             $this->_loadedSchema);
         Xtoph_Tool_Project_Provider_Propel::setActiveValues($this->_loadedProfile,
             $schema, $table, $name);

         if (!is_null($column)) {
            if ($request->isPretend()) {
               $response->appendContent("Would create column '$name' in table '$table'");
            } else {
               $response->appendContent("Creating column '$name' in table '$table'");
               $this->_storeSchema();
               $this->_storeProfile();
            }
         }
      } else {
         throw new Zend_Tool_Project_Profile_Exception('Schema and table names should be provided');
      }
   }

   public function setAttribute($name, $value, $column = null, $table = null,
       $schema = null)
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

      if (!is_null($schema)
          && !is_null($table)
          && !is_null($column)) {

         $this->initializeSchema($schema);

         $attributes = $this->_setColumnAttributes($name, $value, $column,
             $table, $this->_loadedSchema);

         if (!is_null($attributes)) {
            if ($request->isPretend()) {
               $response->appendContent("Would create column attribute '$name' in column '$table.$column'");
            } else {
               $response->appendContent("Creating column attribute '$name' in column '$table.$column'");
               $this->_storeSchema();
            }
         } else {
            throw new Zend_Tool_Project_Profile_Exception('Column attribute creation failed');
         }
      } else {
         throw new Zend_Tool_Project_Profile_Exception('Schema, table and column names should be provided');
      }
   }

   public function setAttributeRequired($value = true)
   {
      
   }

   public function setAttributePrimaryKey($value = true)
   {
      
   }

   public function setAttributeSize($value = '0')
   {
      
   }

   public function setAttributeType($value = self::TYPE_VARCHAR)
   {
      
   }

   public function delete()
   {
      $this->_registry->getResponse()->appendContent('TODO: delete action in propel-column provider');
   }

}
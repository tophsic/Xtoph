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
class Xtoph_Tool_Project_Provider_PropelValidator
    extends Xtoph_Tool_Project_Provider_PropelAbstract
    implements Zend_Tool_Framework_Provider_Pretendable
{

   protected $_specialties = array(
       'Class',
       'MinLength',
       'MaxLength',
       'Required',
       'Type'
   );

   public function _createValidator($rule, $value, $message, $column, $table,
       Xtoph_Tool_Project_Propel_Schema $schema)
   {
      if (!$schema->hasColumn($column, $table)) {
         throw new Zend_Tool_Project_Provider_Exception("Column '$column' does not exists in table '$table'");
      }
      $validator = null;
      if (!$schema->hasValidator($column, $table)) {
         $validator = $schema->addValidator($column, $table);
      }
      $validator = $schema->setValidatorRule($rule, $value, $message, $column,
          $table, $validator);
      return $validator;
   }

   public function _deleteValidator($rule, $column, $table,
       Xtoph_Tool_Project_Propel_Schema $schema)
   {
      if (!$schema->hasColumn($column, $table)) {
         throw new Zend_Tool_Project_Provider_Exception("Column '$column' does not exists in table '$table'");
      }
      $validator = null;
      if ($schema->hasValidator($column, $table)) {
         /* @var $validator SimpleXMLElement */
         $validator = $schema->getValidator($column, $table, true);
         $schema->removeValidatorRule($validator, $rule);
         if ($validator->count() == 0) {
            $schema->removeValidator($column, $table);
         }
      }
      return $validator;
   }

   public function create($rule, $value = "", $message = "", $column = "",
       $table = "", $schema = "")
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

         if (!$this->initializeSchema($schema)) {
            throw new Zend_Tool_Project_Provider_Exception("Schema '$schema' could not be initialized");
         }

         $validator = $this->_createValidator($rule, $value, $message, $column,
             $table, $this->_loadedSchema);
         Xtoph_Tool_Project_Provider_Propel::setActiveValues($this->_loadedProfile,
             $schema, $table, $column);

         if (!is_null($validator)) {
            if ($request->isPretend()) {
               $response->appendContent("Would create validator '$rule' for column '$table.$column'");
            } else {
               $response->appendContent("Creating validator '$rule' for column '$table.$column'");
               $this->_storeSchema();
               $this->_storeProfile();
            }
         }
      } else {
         throw new Zend_Tool_Project_Profile_Exception('Schema, table and column names should be provided');
      }
   }

   public function createRequired()
   {
      $this->create(Xtoph_Tool_Project_Propel_Schema::VALIDATOR_RULE_REQUIRED);
   }

   public function createMaxLength($value)
   {
      $this->create(Xtoph_Tool_Project_Propel_Schema::VALIDATOR_RULE_MAXLENGTH,
          $value);
   }

   public function createMinLength($value)
   {
      $this->create(Xtoph_Tool_Project_Propel_Schema::VALIDATOR_RULE_MINLENGTH,
          $value);
   }

   public function createType($type)
   {
      $this->create(Xtoph_Tool_Project_Propel_Schema::VALIDATOR_RULE_TYPE, $type);
   }

   public function createClass($class)
   {
      $this->create(Xtoph_Tool_Project_Propel_Schema::VALIDATOR_RULE_CLASS,
          $class);
   }

   public function delete($rule, $column = "", $table = "", $schema = "")
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

         if (!$this->initializeSchema($schema)) {
            throw new Zend_Tool_Project_Provider_Exception("Schema '$schema' could not be initialized");
         }

         $validator = $this->_deleteValidator($rule, $column, $table,
             $this->_loadedSchema);
         Xtoph_Tool_Project_Provider_Propel::setActiveValues($this->_loadedProfile,
             $schema, $table, $column);

         if (!is_null($validator)) {
            if ($request->isPretend()) {
               $response->appendContent("Would delete validator '$rule' for column '$table.$column'");
            } else {
               $response->appendContent("Deleting validator '$rule' for column '$table.$column'");
               $this->_storeSchema();
               $this->_storeProfile();
            }
         }
      } else {
         throw new Zend_Tool_Project_Profile_Exception('Schema, table and column names should be provided');
      }
   }

}
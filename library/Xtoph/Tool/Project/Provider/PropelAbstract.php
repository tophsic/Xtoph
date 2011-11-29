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
 * @see Zend_Tool_Project_Provider_Abstract
 */
require_once 'Xtoph/Tool/Project/Provider/Abstract.php';

/**
 * @category   Xtoph
 * @package    Xtoph_Tool
 * @copyright  Christophe Sicard (http://christophe.plom.net)
 * @license    http://christophe.plom.net/license/new-bsd     New BSD License
 */
abstract class Xtoph_Tool_Project_Provider_PropelAbstract
    extends Xtoph_Tool_Project_Provider_Abstract
{
   const NO_SCHEMA_THROW_EXCEPTION = true;
   const NO_SCHEMA_RETURN_FALSE = false;

   /**
    * @var Xtoph_Tool_Project_Propel_Schema
    */
   protected $_loadedSchema = null;
   protected $_loadedSchemaResource = null;
   protected static $_isSchemaInitialized = false;

   protected function _hasSchemaDirectory($pathToSchemaFile)
   {
      if (!file_exists($pathToSchemaFile)) {
         return false;
      }

      return true;
   }

   protected function _getSchemaResource($schema)
   {
      $profile = $this->_getProfile();
      $matchSearchConstraints = array(
          'propelDirectory',
          'schemaDirectory' => array('schemaName' => $schema),
          'schemaFile'
      );
      $schemaResource = $profile->search($matchSearchConstraints);
      return $schemaResource;
      return false;
   }

   public function initializeSchema($schema)
   {
      if (!self::$_isSchemaInitialized) {
         $schemaResource = $this->_getSchemaResource($schema);
         if ($schemaResource == false) {
            return false;
         }
         if ($this->_hasSchemaDirectory($schemaResource->getContext()->getPath())) {
            $schema = $this->_loadSchema(self::NO_SCHEMA_THROW_EXCEPTION,
                $schemaResource);
         }

         self::$_isSchemaInitialized = true;
      }
   }

   protected function _loadSchema($loadSchemaFlag = self::NO_SCHEMA_THROW_EXCEPTION,
       $schemaResource = null)
   {
//      $this->_registry->getResponse()->appendContent('Load schema');

      $schema = new Xtoph_Tool_Project_Propel_Schema();

      if (!is_null($schemaResource)) {
         $schema->loadFromFile($schemaResource->getContext()->getPath());
      }
      
      $this->_loadedSchemaResource = $schemaResource;
      $this->_loadedSchema = $schema;
      
      return $schema;
   }

   protected function _getSchema($loadSchemaFlag = self::NO_SCHEMA_THROW_EXCEPTION)
   {
      if (!$this->_loadedSchema) {
         if (($this->_loadSchema($loadSchemaFlag) === false) && ($loadSchemaFlag === self::NO_SCHEMA_RETURN_FALSE)) {
            return false;
         }
      }

      return $this->_loadedSchema;
   }

   protected function _storeSchema()
   {
      $schemaFile = $this->_loadedSchemaResource;

      $name = $schemaFile->getContext()->getPath();

      $this->_registry->getResponse()->appendContent('Updating schema \'' . $name . '\'');

      $schemaFile->getContext()->save($this->_loadedSchema);
   }

}

<?php

require_once 'Xtoph/Tool/Project/Provider/Abstract.php';
require_once 'Zend/Tool/Project/Provider/Exception.php';

class Xtoph_Tool_Project_Provider_Propel
    extends Xtoph_Tool_Project_Provider_Abstract
    implements Zend_Tool_Framework_Provider_Pretendable
{

   public static function setActiveValues(Zend_Tool_Project_Profile $profile,
       $schema = '', $table = '', $column = '')
   {
      if (!$activeValuesResource = self::getActiveValuesResources($profile)) {
         $activeValuesResource = self::_createActiveValuesResource($profile);
      }
      $activeValuesResource->setAttributes(array(
          'schema' => $schema,
          'table' => $table,
          'column' => $column
      ));
      $context = $activeValuesResource->getContext();
      $context->init();
   }

   protected static function _createActiveValuesResource(Zend_Tool_Project_Profile $profile)
   {
      $propelDirectory = self::_getPropelDirectoryResource($profile);
      $resource = $propelDirectory->createResource('ActiveValues');
      return $resource;
   }

   protected static function _createPropelResource(Zend_Tool_Project_Profile $profile)
   {
      $projectDirectory = $profile->search(array('projectDirectory'));
      $resource = $projectDirectory->createResource('PropelDirectory',
          array(
          'enabled' => 'false'
          ));
      return $resource;
   }

   public static function getActiveValuesResources(Zend_Tool_Project_Profile $profile)
   {
      $profileSearchParams = array('propelDirectory', 'activeValues');
      return $profile->search($profileSearchParams);
   }

   public static function getActiveSchema(Zend_Tool_Project_Profile $profile, $schema)
   {
      $resource = Xtoph_Tool_Project_Provider_Propel::getActiveValuesResources($profile);
      $activeSchema = $resource->getContext()->getSchema();
      if (!empty($activeSchema)) {
         $schema = $activeSchema;
      }
      return $schema;
   }
   public static function getActiveTable(Zend_Tool_Project_Profile $profile, $table)
   {
      $resource = Xtoph_Tool_Project_Provider_Propel::getActiveValuesResources($profile);
      $activeTAble = $resource->getContext()->getTable();
      if (!empty($activeTAble)) {
         $table = $activeTAble;
      }
      return $table;
   }
   public static function getActiveColumn(Zend_Tool_Project_Profile $profile,
       $column)
   {
      $resource = Xtoph_Tool_Project_Provider_Propel::getActiveValuesResources($profile);
      $activeColumn = $resource->getContext()->getColumn();
      if (!empty($activeColumn)) {
         $column = $activeColumn;
      }
      return $column;
   }

   protected static function _getPropelDirectoryResource(Zend_Tool_Project_Profile $profile)
   {
      $profileSearchParams = array('propelDirectory');
      return $profile->search($profileSearchParams);
   }

   public function enable()
   {
      $this->_loadProfile(self::NO_PROFILE_THROW_EXCEPTION);

      $response = $this->_registry->getResponse();

      if (!$propelDirectoryResource = self::_getPropelDirectoryResource($this->_loadedProfile)) {
         try {
            $propelDirectoryResource = self::_createPropelResource($this->_loadedProfile);
            self::setActiveValues($this->_loadedProfile);
         } catch (Exception $e) {
            $response->appendContent('Create propel resource in project failed');
            throw $e;
         }
      } else {
         $response->appendContent('Propel resource already exists in project profile');
      }
      if ($propelDirectoryResource->isEnabled()) {
         throw new Zend_Tool_Project_Provider_Exception('This project already has propel enabled.');
      } else {
         if ($this->_registry->getRequest()->isPretend()) {
            $this->_registry->getResponse()->appendContent("Would enable propel directory at '" . $propelDirectoryResource->getContext()->getPath() . "'");
         } else {
            $this->_registry->getResponse()->appendContent("Enable propel directory at '" . $propelDirectoryResource->getContext()->getPath() . "'");
            $propelDirectoryResource->setEnabled(true);
            $propelDirectoryResource->create();
            $this->_storeProfile();
         }
      }
   }

   public function setAv($schema = null, $table = null, $column = null)
   {
      $this->_loadProfile(self::NO_PROFILE_THROW_EXCEPTION);

      $response = $this->_registry->getResponse();
      self::setActiveValues($this->_loadedProfile, $schema, $table, $column);
      if ($this->_registry->getRequest()->isPretend()) {
         $this->_registry->getResponse()->appendContent("Would set active values");
      } else {
         $this->_registry->getResponse()->appendContent("Set active values: $schema, $table, $column");
         $this->_storeProfile();
      }
   }

}
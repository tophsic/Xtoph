<?php

require_once 'Xtoph/Tool/Project/Provider/Abstract.php';
require_once 'Zend/Tool/Project/Provider/Exception.php';

class Xtoph_Tool_Project_Provider_Propel
    extends Xtoph_Tool_Project_Provider_Abstract
{

   protected static function _createPropelResource(Zend_Tool_Project_Profile $profile)
   {
      $projectDirectory = $profile->search(array('projectDirectory'));
      $resource = $projectDirectory->createResource('PropelDirectory',
          array(
          'Enabled' => 'false'
          ));
      return $resource;
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
   public function config($schema = null, $database = null, $table = null)
   {
      
   }
}
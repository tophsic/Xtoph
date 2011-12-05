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
class Xtoph_Tool_Project_Provider_PropelSchema
    extends Xtoph_Tool_Project_Provider_Abstract
    implements Zend_Tool_Framework_Provider_Pretendable
{

   protected function _createFilesResources(
   Zend_Tool_Project_Profile_Resource $schemaResource, $database, $adapter)
   {
      if (!in_array($adapter, array('sqlite', 'mysql'))) {
         throw new Zend_Tool_Project_Provider_Exception('Unknown adapter');
      }
      $resources = array(
          $schemaResource->createResource('SchemaFile',
              array(
              'file' => 'schema.xml',
              'database' => $database
          )),
          $schemaResource->createResource('PropertiesFile',
              array(
              'file' => 'build.properties',
              'project' => $schemaResource->getSchemaName(),
              'adapter' => $adapter
          )),
          $schemaResource->createResource('ConnectionConfigFile',
              array(
              'file' => 'runtime-conf.xml',
              'project' => $schemaResource->getSchemaName(),
              'adapter' => $adapter
          )),
          $schemaResource->createResource('ConnectionConfigFile',
              array(
              'file' => 'builtime-conf.xml',
              'project' => $schemaResource->getSchemaName(),
              'adapter' => $adapter
          ))
      );
      if (!$this->_registry->getRequest()->isPretend()) {
         foreach ($resources as $resource) {
            $resource->create();
         }
      }
      return $resources;
   }

   public static function createResource($profile, $schema)
   {
      $propelDirectory = $profile->search(array('propelDirectory'));
      $schemaDirectory = $propelDirectory->createResource('SchemaDirectory',
          array(
          'schemaName' => $schema
          ));
      $schemaDirectory->setFileSystemName($schema);
      return $schemaDirectory;
   }

   protected static function _getPropelDirectory($profile, $schema)
   {
      $profileSearchParams = array();
      $profileSearchParams[] = 'propelDirectory';
      return $profile->search($profileSearchParams);
   }

   public static function hasResource(Zend_Tool_Project_Profile $profile,
       $schema)
   {
      $propelDirectory = self::_getPropelDirectory($profile, $schema);
      if (!$propelDirectory
          || !$propelDirectory instanceof Zend_Tool_Project_Profile_Resource
          || !$propelDirectory->isEnabled()) {
         throw new Zend_Tool_Project_Provider_Exception('Propel is not yet enabled for this project.');
      }
      return ($propelDirectory && ($propelDirectory->search(array('schemaDirectory' => array('schemaName' => $schema)))) instanceof Zend_Tool_Project_Profile_Resource);
   }

   public function create($schema, $filesIncluded = true, $database = 'db', $adapter = "sqlite")
   {
      $this->_loadProfile(self::NO_PROFILE_THROW_EXCEPTION);

      // get request & response
      $request = $this->_registry->getRequest();
      $response = $this->_registry->getResponse();

      $schema = (string) $schema;
      if (self::hasResource($this->_loadedProfile, $schema)) {
         throw new Zend_Tool_Project_Provider_Exception('This project has already a Propel schema named ' . $schema);
      }
      try {
         $schemaResource = self::createResource($this->_loadedProfile, $schema);
         Xtoph_Tool_Project_Provider_Propel::setActiveValues($this->_loadedProfile, $schema);
         if ($filesIncluded) {
            $resources = $this->_createFilesResources($schemaResource, $database, $adapter);
         }
      } catch (Exception $e) {
         $response->setException($e);
         return;
      }
      if ($request->isPretend()) {
         $response->appendContent('Would create schema ' . $schema . ' at ' .
             $schemaResource->getParentResource()->getContext()->getPath());
         if (isset($resources)) {
            $response->appendContent('Would create default files for schema ' . $schema);
            $response->appendContent('`-- schema.xml');
            $response->appendContent('`-- build.properties');
            $response->appendContent('`-- runtime-conf.xml');
            $response->appendContent('`-- buildtime-conf.xml');
         }
      } else {
         $response->appendContent('Creating schema ' . $schema . ' at ' .
             $schemaResource->getParentResource()->getContext()->getPath());
         if (isset($resources)) {
            $response->appendContent('Creating default files for schema ' . $schema);
            $response->appendContent('`-- schema.xml');
            $response->appendContent('`-- build.properties');
            $response->appendContent('`-- runtime-conf.xml');
            $response->appendContent('`-- buildtime-conf.xml');
         }
         $schemaResource->create();
         $this->_storeProfile();
      }
   }

   public function delete($schema)
   {
      $this->_registry->getResponse()->appendContent('TODO: delete action in propel-schema provider');
   }

   public function show($schema)
   {
      $this->_registry->getResponse()->appendContent('TODO: show action in propel-schema provider');
   }

}
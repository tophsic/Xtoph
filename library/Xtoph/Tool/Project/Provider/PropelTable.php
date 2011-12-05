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
class Xtoph_Tool_Project_Provider_PropelTable
    extends Xtoph_Tool_Project_Provider_PropelAbstract
    implements Zend_Tool_Framework_Provider_Pretendable
{

   protected function _createTable($name,
       Xtoph_Tool_Project_Propel_Schema $schema, $force = false)
   {
      $table = null;
      if ($schema->hasTable($name) && $force === false) {
         throw new Zend_Tool_Project_Profile_Exception("Table '$name' already exists");
      } else {
         if ($force == true) {
            $schema->removeTable($name);
         }
         $table = $schema->addTable($name);
      }
      return $table;
   }

   public function create($name, $schema = null,
       $force = false)
   {
      $this->_loadProfile(self::NO_PROFILE_THROW_EXCEPTION);

      $request = $this->_registry->getRequest();
      $response = $this->_registry->getResponse();

      
      $schema = Xtoph_Tool_Project_Provider_Propel::getActiveSchema($this->_loadedProfile, $schema);

      if (!is_null($schema)) {
         if (!$this->initializeSchema($schema)) {
            throw new Zend_Tool_Project_Provider_Exception("Schema '$schema' could not be initialized");
         }
         
         $table = $this->_createTable($name, $this->_loadedSchema, $force);
         Xtoph_Tool_Project_Provider_Propel::setActiveValues($this->_loadedProfile, $schema, $name);

         if (!is_null($table)) {
            if ($request->isPretend()) {
               $response->appendContent("Would create table '$name'");
            } else {
               $response->appendContent("Creating table '$name'");
               $this->_storeSchema();
               $this->_storeProfile();
            }
         }
      } else {
         throw new Zend_Tool_Project_Profile_Exception('Schema name should be provided');
      }
      
   }

   public function delete()
   {
      $this->_registry->getResponse()->appendContent('TODO: delete action in propel-table provider');
   }

}
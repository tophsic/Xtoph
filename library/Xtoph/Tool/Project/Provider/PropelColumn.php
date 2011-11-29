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

   protected function _createColumn($name, $table, $force,
       Xtoph_Tool_Project_Propel_Schema $schema)
   {
      $column = null;
      if ($schema->hasColumn($name, $table) && $force === false) {
         $this->_registry->getResponse()->appendContent("Column '$name' already exists in table '$table'");
      } else {
         if ($force == true) {
            $schema->removeColumn($name, $table);
         }
         $column = $schema->addColumn($name, $table);
      }
      return $column;
   }

   public function create($name, $table = null, $schema = null,
       $force = false)
   {
      $request = $this->_registry->getRequest();
      $response = $this->_registry->getResponse();

      if (!is_null($schema)) {

         $this->initializeSchema($schema);

         $column = $this->_createColumn($name, $table, $force, $this->_loadedSchema);

         if (!is_null($column)) {
            if ($request->isPretend()) {
               $response->appendContent("Would create column '$name' in table '$table'");
            } else {
               $response->appendContent("Creating column '$name' in table '$table'");
               $this->_storeSchema();
            }
         }
      }
   }
   
   public function setAttribute($attribute, $value, $column = null, $table = null, $schema = null, $force = false)
   {
      
   }

   public function delete()
   {
      $this->_registry->getResponse()->appendContent('TODO: delete action in propel-column provider');
   }

}
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
require_once 'Zend/Tool/Project/Provider/Abstract.php';

/**
 * @category   Xtoph
 * @package    Xtoph_Tool
 * @copyright  Christophe Sicard (http://christophe.plom.net)
 * @license    http://christophe.plom.net/license/new-bsd     New BSD License
 */
abstract class Xtoph_Tool_Project_Provider_Abstract
    extends Zend_Tool_Project_Provider_Abstract
{

   public function initialize()
   {
      parent::initialize();

      $contextRegistry = Zend_Tool_Project_Context_Repository::getInstance();
      if (!$contextRegistry->hasContext('PropelDirectory')) {
         $contextRegistry->addContextsFromDirectory(
             dirname(dirname(__FILE__)) . '/Context/Propel/',
             'Xtoph_Tool_Project_Context_Propel_'
         );
      }
   }

}

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
 * @see Zend_Tool_Framework_Manifest_ProviderManifestable
 */
require_once 'Zend/Tool/Framework/Manifest/ProviderManifestable.php';

/**
 * @category   Xtoph
 * @package    Xtoph_Tool
 * @copyright  Christophe Sicard (http://christophe.plom.net)
 * @license    http://christophe.plom.net/license/new-bsd     New BSD License
 */
class PropelManifest
    implements
Zend_Tool_Framework_Manifest_ProviderManifestable
{

   /**
    * getProviders()
    *
    * @return array Array of Providers
    */
   public function getProviders()
   {
      // the order here will represent what the output will look like when iterating a manifest

      Zend_Loader_Autoloader::getInstance()->registerNamespace('Xtoph_');

      return array(
          'Xtoph_Tool_Project_Provider_Propel',
          'Xtoph_Tool_Project_Provider_PropelSchema',
          'Xtoph_Tool_Project_Provider_PropelDatabase',
          'Xtoph_Tool_Project_Provider_PropelTable',
          'Xtoph_Tool_Project_Provider_PropelColumn',
          'Xtoph_Tool_Project_Provider_PropelBehavior',
          'Xtoph_Tool_Project_Provider_PropelValidator'
      );
   }

}

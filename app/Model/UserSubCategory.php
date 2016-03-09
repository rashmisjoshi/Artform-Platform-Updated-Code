<?php
/**
 * Application model for CakePHP.
 *
 * This file is application-wide model file. You can put all
 * application-wide model-related methods here.
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Model
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

App::uses('Model', 'Model');

/**
 * Application model for Cake.
 *
 * Add your application-wide methods in the class below, your models
 * will inherit them.
 *
 * @package       app.Model
 */
class UserSubCategory extends Model {
	var $useTable = 'usersubcategories';
	var $name = 'usersubcategory';
	
	
   public function saveusersubcategory($subcatdata = array())
   {
       $strQuery = "insert into  appap14_usersubcategories (user_id,subcategory_id) values( '".$subcatdata['user_id']."','".$subcatdata['subcategory_id']."')";
	
	 
	   $usersubcategory_id = $this->query($strQuery);
	   return $usersubcategory_id;
	
   }

}
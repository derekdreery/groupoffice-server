<?php

namespace Intermesh\Modules\DevTools\Controller;

use Intermesh\Core\Controller\AbstractController;
use Intermesh\Core\Db\Column;
use Intermesh\Core\Db\Criteria;
use Intermesh\Core\Db\Finder;
use Intermesh\Core\Db\Query;
use Intermesh\Modules\Contacts\Model\Contact;

class ModelController extends AbstractController {

	public function actionDatabaseToProperties($modelName) {

		header('Content-Type:text/plain;charset=utf-8;');

		$columns = $modelName::getColumns();

		/* @var  $column Column */
		
		$parts = explode('\\', $modelName);

		echo "/**";
		
		echo "\n * The ".array_pop($parts)." model\n *";
		foreach ($columns as $name => $column) {

			if ($name == 'ownerUserId') {
				echo "\n * @property int \$ownerUserId";
				echo "\n * @property \Intermesh\Modules\Auth\Model\User \$owner";
			} else {
				switch ($column->dbType) {
					case 'int':
					case 'tinyint':
					case 'bigint':
						$type = $column->length == 1 ? 'boolean' : 'int';
						break;
					default:
						$type = 'string';
						break;
				}

				echo "\n * @property " . $type . " \$" . $name;
			}
		}
		
		echo "\n *";
		
		 echo "\n * @copyright (c) ".date('Y').", Intermesh BV http://www.intermesh.nl".
				"\n * @author Merijn Schering <mschering@intermesh.nl>".
				"\n * @license https://www.gnu.org/licenses/lgpl.html LGPLv3";

		echo "\n */";
	}
	
	
	public function actionColumns(){
		var_dump(\IPE\Modules\Notes\Model\Note::getColumns());
	}

	public function actionTest() {

		/* @var $finder Finder */
		
		$finder = Contact::find(
						Query::newInstance()
								->select('t.*, count(emailAddresses.id)')
								->joinRelation('emailAddresses', false)								
								->groupBy(array('t.id'))
								->having("count(emailAddresses.id) > 0")
						->where(['!=',['lastName' => null]])
						->andWhere(
								Criteria::newInstance()
									->where(['IN','firstName', ['Merijn', 'Wesley']])
									->orWhere(['emailAddresses.email'=>'test@intermesh.nl'])
								)

		);
		
		/*
		 * SELECT t.*, count(emailAddresses.id) FROM `contactsContact` t
			INNER JOIN `contactsContactEmailAddress` emailAddresses ON (`t`.`id` = `emailAddresses`.`contactId`)
			WHERE
			(
				`t`.`lastName` IS NOT NULL
			)
			AND
			(
				(
					`t`.`firstName` IN ("Merijn", "Wesley")
				)
				OR
				(
					`emailAddresses`.`email` = "test@intermesh.nl"
				)
			)
			AND
			(
				`t`.`deleted` != "1"
			)

			GROUP BY `t`.`id`
			HAVING
			(
				count(emailAddresses.id) > 0
			)
		 */
		
		

		echo $finder->buildSql();
		
		
//		var_dump($finder->aliasMap);
		
		$contacts = $finder->all();
//		var_dump($finder->bindParameters);
		
		var_dump($contacts);
		
		var_dump(\Intermesh\Core\App::debugger()->entries);
	}
	
	
	public function actionImap(){
		
		$account = \Intermesh\Modules\Imap\Model\Account::find()->single();
		
		$account->sync();
		
	}
		

}

<?php
namespace Intermesh\Modules\Tennis\Controller;

use Intermesh\Core\Controller\AbstractController;
use Intermesh\Core\Db\Query;
use Intermesh\Modules\Contacts\Model\Contact;

class SpeelsterkteController extends AbstractController{
	
	private $_url = 'http://publiek.mijnknltb.nl/spelersprofiel.aspx?bondsnummer=';
	
	public function actionUpdate(){
		
		$q = Query::newInstance()
				->joinRelation('customfields')
				->where(['!=',['customfields.Bondsnummer'=>'']]);
//				->andWhere(['firstName' => 'Frank', 'lastName'=>'Pot']);
		
		$contacts = Contact::find($q);
		
		
		
		$httpClient = new \Intermesh\Core\Http\Client();
		
		foreach($contacts as $contact){
			$html = $httpClient->request($this->_url.$contact->customfields->Bondsnummer);
			
			$contact->customfields->{"Speelsterkte enkel"} = $this->_findRating($html, true);			
			$contact->customfields->{"Speelsterkte dubbel"} = $this->_findRating($html, false);
			
//			var_dump($contact->customfields->getAttributes());
			
			if(!$contact->customfields->save()){
				var_dump($contact->customfields->getValidationErrors());
			}
//			break;
		}
		
//		var_dump(\Intermesh\Core\App::debugger()->entries);
		
	}
	
	private function _findRating($html, $single = true){
		
		$str = $single ? "Rating enkel" : "Rating Dubbel";
		$pos = strpos($html, $str);
		if(!$pos){
			return null;
		}
		$tdPos = strpos($html, '<td>', $pos);
		
		if(!$tdPos){
			return null;
		}
		
		$tdPos += 4;
		
		$closeTdPos = strpos($html, '</td>', $tdPos);
		
		if(!$closeTdPos){
			return null;
		}
		
		$rating = substr($html, $tdPos, $closeTdPos - $tdPos);
		
		return str_replace(',', '.', $rating);
	}
	
}
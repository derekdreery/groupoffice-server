<?php
namespace Intermesh\Core\Controller;

interface CrudControllerInterface{	
	
	public function actionCreate($returnAttributes = "");
	
	public function actionRead($id, $returnAttributes = "");
	
	public function actionUpdate($id, $returnAttributes = "");
	
	public function actionDelete($id);
	
}
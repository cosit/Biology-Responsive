<?php 
/******
 * Custom "Simplepie" class created to handle the unique 
 * "<ucfevent:___>" tags found in the events.ucf.edu feed
 ******/
define("SIMPLE_NAMESPACE_UCFEVENT", "http://events.ucf.edu");

class SimplePie_Item_UCFEvent extends SimplePie_Item{
	function get_startdate() {
		$data = $this->get_item_tags(SIMPLE_NAMESPACE_UCFEVENT,'startdate');
		return $data[0]['data'];
	}
	function get_starttime() {
		$data = $this->get_item_tags(SIMPLE_NAMESPACE_UCFEVENT,'startdate');
		$timeLocation = strpos($data[0]['data'], ':');
		if($timeLocation !== false)
			$starttime = substr($data[0]['data'], $timeLocation-2,5);		
		return date('h:i A', strtotime($starttime));
	}

	function get_enddate() {
		$data = $this->get_item_tags(SIMPLE_NAMESPACE_UCFEVENT,'enddate');		
		return $data[0]['data'];
	}

	function get_endtime() {
		$data = $this->get_item_tags(SIMPLE_NAMESPACE_UCFEVENT,'enddate');		
		$timeLocation = strpos($data[0]['data'], ':');
		if($timeLocation !== false)
			$endtime = substr($data[0]['data'], $timeLocation-2,5);	
		return date('h:i A', strtotime($endtime));
	}
	
	function get_location() {
		$data = $this->get_item_tags(SIMPLE_NAMESPACE_UCFEVENT,'location');
		return array( 
			'location' => $data[0]['child']['http://events.ucf.edu']['name'][0]['data'], 
			'mapurl' => $data[0]['child']['http://events.ucf.edu']['mapurl'][0]['data']
		);
	}

}
?>
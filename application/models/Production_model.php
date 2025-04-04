<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Production_model extends CI_Model
{
	public function getMaterialDesc($materialID)
	{
		$this->db->select('Material_desc');
		$this->db->where('Id_material', $materialID);
		$this->db->where('is_active', 1);
		$this->db->limit(1);
		$query = $this->db->get('material_list');
		return $query->result_array();
	}

	public function getProductionPlans()
	{
		return $this->db->query("SELECT * FROM production_plan WHERE status = 'NEW'");
	}

	public function getProductionPlanById($production_plan)
	{
		return $this->db->query("SELECT 
                ppd.id, 
                ppd.Production_plan, 
                ppd.Id_material, 
                ppd.Material_desc, 
                ppd.Material_need, 
                ppd.Uom, 
                ppd.status, 
                pr.Id_request, 
                COALESCE(pr.Qty, 0) AS Qty
            FROM 
                production_plan_detail ppd
            LEFT JOIN 
                production_request pr 
            ON 
                pr.Production_plan_detail_id = ppd.id
            WHERE 
                ppd.Production_plan = '$production_plan'");
	}

	public function updateData($table, $id, $Data)
	{
		$this->db->where('id', $id);
		$this->db->update($table, $Data);
	}

	public function updateDataKanban($table, $id, $Data)
	{
		$this->db->where('id_kanban_box', $id);
		$this->db->update($table, $Data);
	}

	public function insertData($table, $Data)
	{
		return $this->db->insert($table, $Data);
	}

	public function getKanbanList()
	{
		return $this->db->get('kanban_box')->result_array();
	}

	public function getLastKanbanID()
	{
		$this->db->select('id_kanban_box');
		$this->db->from('kanban_box');
		$this->db->order_by('id_kanban_box', 'DESC');
		$this->db->limit(1);

		$query = $this->db->get();

		if ($query->num_rows() > 0) {
			// Return the last ID
			$lastKanbanID = $query->row()->id_kanban_box;
			$prefix = substr($lastKanbanID, 0, 3); // Assuming 'KBA' is always the prefix
			$numericPart = substr($lastKanbanID, 3);

			// Increment the numeric part
			$incrementedNumericPart = (int) $numericPart + 1;

			// If the numeric part reaches 10000000, reset to 1 and increment the prefix
			if ($incrementedNumericPart >= 10000000) {
				$incrementedNumericPart = 1;

				// Increment the last character of the prefix
				$lastChar = substr($prefix, -1);
				$secondChar = substr($prefix, -2, 1);
				$firstChar = substr($prefix, -3, 1);

				// Increment the last character, if it is 'Z', reset to 'A' and increment the second last character
				if ($lastChar === 'Z') {
					$lastChar = 'A';
					$secondChar++;

					// If the second character is 'Z', reset to 'A' and increment the first character
					if ($secondChar === 'Z' + 1) {
						$secondChar = 'A';
						$firstChar++;
					}
				} else {
					$lastChar++;
				}

				// Combine characters to form the new prefix
				$prefix = $firstChar . $secondChar . $lastChar;
			}

			// Format the incremented numeric part
			$formattedNumericPart = str_pad($incrementedNumericPart, strlen($numericPart), '0', STR_PAD_LEFT);

			// Return the next Kanban ID
			return $prefix . $formattedNumericPart;
		} else {
			// Handle the case when the table is empty
			return 'KBA0000001';
		}
	}

	public function getLastIdReturn()
	{
		$this->db->select('id_return');
		$this->db->from('return_warehouse');
		$this->db->order_by('id_return', 'DESC');
		$this->db->limit(1);

		$query = $this->db->get();

		if ($query->num_rows() > 0) {
			// Return the last ID
			$lastReturnID = $query->row()->id_return;
			$prefix = substr($lastReturnID, 0, 3); // Assuming 'RTA' is always the prefix
			$numericPart = substr($lastReturnID, 3);

			// Increment the numeric part
			$incrementedNumericPart = (int) $numericPart + 1;

			// If the numeric part reaches 10000000, reset to 1 and increment the prefix
			if ($incrementedNumericPart >= 10000000) {
				$incrementedNumericPart = 1;

				// Increment the last character of the prefix
				$lastChar = substr($prefix, -1);
				$secondChar = substr($prefix, -2, 1);
				$firstChar = substr($prefix, -3, 1);

				// Increment the last character, if it is 'Z', reset to 'A' and increment the second last character
				if ($lastChar === 'Z') {
					$lastChar = 'A';
					$secondChar++;

					// If the second character is 'Z', reset to 'A' and increment the first character
					if ($secondChar === 'Z' + 1) {
						$secondChar = 'A';
						$firstChar++;
					}
				} else {
					$lastChar++;
				}

				// Combine characters to form the new prefix
				$prefix = $firstChar . $secondChar . $lastChar;
			}

			// Format the incremented numeric part
			$formattedNumericPart = str_pad($incrementedNumericPart, strlen($numericPart), '0', STR_PAD_LEFT);

			// Return the next Kanban ID
			return $prefix . $formattedNumericPart;
		} else {
			// Handle the case when the table is empty
			return 'RTA0000001';
		}
	}

	public function getMaterialList()
	{
		return $this->db->query("SELECT * FROM material_list WHERE is_active = 1")->result_array();
	}

	public function getAllBoms()
	{
		$this->db->distinct();
		$this->db->select('Id_fg');
		$this->db->where('is_active', 1);
		return $this->db->get('bom')->result_array();
	}

	public function getAllBox()
	{
		$this->db->distinct();
		$this->db->select('no_box');
		return $this->db->get('box')->result_array();
	}

	public function getLastProductionPlan()
	{
		$this->db->select('Production_plan');
		$this->db->from('production_plan');
		$this->db->order_by('Production_plan', 'DESC');
		$this->db->limit(1);

		$query = $this->db->get();

		if ($query->num_rows() > 0) {
			// Get the last Production Plan
			$lastProductionPlan = $query->row()->Production_plan; // Adjusted to match the case of the column
			$prefix = substr($lastProductionPlan, 0, 3); // Extract the prefix ('PPA')
			$numericPart = substr($lastProductionPlan, 3); // Extract the numeric part ('0000001')

			// Increment the numeric part
			$incrementedNumericPart = (int) $numericPart + 1;

			// If the numeric part reaches 10000000, reset to 1 and increment the prefix
			if ($incrementedNumericPart >= 10000000) {
				$incrementedNumericPart = 1;

				// Increment the prefix
				$lastChar = substr($prefix, -1);  // Get last character
				$secondChar = substr($prefix, -2, 1);  // Get second character
				$firstChar = substr($prefix, 0, 1);  // Get first character

				// Handle incrementing each character of the prefix
				if ($lastChar === 'Z') {
					$lastChar = 'A';
					if ($secondChar === 'Z') {
						$secondChar = 'A';
						if ($firstChar === 'Z') {
							$firstChar = 'A'; // Wrap around if all are 'Z'
						} else {
							$firstChar++;
						}
					} else {
						$secondChar++;
					}
				} else {
					$lastChar++;
				}

				// Update the prefix
				$prefix = $firstChar . $secondChar . $lastChar;
			}

			// Format the incremented numeric part to maintain the correct number of digits (7 digits)
			$formattedNumericPart = str_pad($incrementedNumericPart, strlen($numericPart), '0', STR_PAD_LEFT);

			// Return the next Production Plan ID
			return $prefix . $formattedNumericPart;
		} else {
			// Handle the case when the table is empty
			return 'PPA0000001';
		}
	}


	public function getNextId($table, $column, $prefixLength = 3, $numericLength = 7)
	{
		// Select the target column
		$this->db->select($column);
		$this->db->from($table);
		$this->db->order_by($column, 'DESC');
		$this->db->limit(1);

		$query = $this->db->get();

		if ($query->num_rows() > 0) {
			// Get the last ID
			$lastId = $query->row()->$column;
			$prefix = substr($lastId, 0, $prefixLength);
			$numericPart = substr($lastId, $prefixLength);

			// Increment the numeric part
			$incrementedNumericPart = (int) $numericPart + 1;

			// If the numeric part reaches a certain threshold, reset to 1 and increment the prefix
			if ($incrementedNumericPart >= pow(10, $numericLength)) {
				$incrementedNumericPart = 1;

				// Increment the prefix
				$lastChar = substr($prefix, -1);
				$secondChar = substr($prefix, -2, 1);
				$firstChar = substr($prefix, -$prefixLength, 1);

				// Increment the last character
				if ($lastChar === 'Z') {
					$lastChar = 'A';
					if ($secondChar === 'Z') {
						$secondChar = 'A';
						if ($firstChar === 'Z') {
							$firstChar = 'A'; // or handle overflow as needed
						} else {
							$firstChar++;
						}
					} else {
						$secondChar++;
					}
				} else {
					$lastChar++;
				}

				// Combine characters to form the new prefix
				$prefix = $firstChar . $secondChar . $lastChar;
			}

			// Format the incremented numeric part
			$formattedNumericPart = str_pad($incrementedNumericPart, $numericLength, '0', STR_PAD_LEFT);

			// Return the next ID
			return $prefix . $formattedNumericPart;
		} else {
			// Handle the case when the table is empty, return the first ID
			$startingPrefix = str_pad('', $prefixLength, 'A');
			return $startingPrefix . str_pad('1', $numericLength, '0', STR_PAD_LEFT);
		}
	}


	public function getLastReqNo()
	{
		// Select the last request number from production_request table
		$this->db->select('Id_request');
		$this->db->from('production_request');
		$this->db->order_by('Id_request', 'DESC');
		$this->db->limit(1);

		$query = $this->db->get();

		if ($query->num_rows() > 0) {
			$lastReqNo = $query->row()->Id_request;

			// Extract prefix and numeric part from the last request number
			$prefix = substr($lastReqNo, 0, 3);
			$numericPart = substr($lastReqNo, 3);

			// Increment numeric part based on rules
			$incrementedNumericPart = (int) $numericPart + 1;

			// Determine the next prefix if numeric part exceeds 999999
			if ($incrementedNumericPart > 999999) {
				$prefix = $this->getNextPrefix($prefix);
				$incrementedNumericPart = 1; // Reset numeric part
			}

			// Format numeric part to 6 digits
			$formattedNumericPart = str_pad($incrementedNumericPart, 6, '0', STR_PAD_LEFT);

			// Construct and return the next request number
			return $prefix . $formattedNumericPart;
		} else {
			// Handle case when table is empty
			return 'PRA000001'; // Default starting request number
		}
	}

	private function getNextPrefix($currentPrefix)
	{
		// Extract characters from the current prefix
		$firstChar = $currentPrefix[0];
		$secondChar = $currentPrefix[1];
		$thirdChar = $currentPrefix[2];

		// Increment the characters based on the rules A-Z
		if ($thirdChar === 'Z') {
			$thirdChar = 'A';
			if ($secondChar === 'Z') {
				$secondChar = 'A';
				if ($firstChar === 'Z') {
					$firstChar = 'A';
				} else {
					$firstChar++;
				}
			} else {
				$secondChar++;
			}
		} else {
			$thirdChar++;
		}

		// Return the next prefix
		return $firstChar . $secondChar . $thirdChar;
	}

	public function getsloc($weight)
	{
		return $this->db->query("SELECT * FROM `storage` WHERE $weight BETWEEN min_loads AND max_loads AND Rack IN ('A', 'B', 'C', 'D', 'E', 'F', 'Gangway A-B', 'Gangway C-D', 'Gangway E-F')")->result_array();
	}

	public function generateFormattedBoxNumber()
	{
		$last_box = $this->db->order_by('id_box', 'DESC')->get('box')->row();

		if (!$last_box) {
			return 'CKA00001';
		}

		$prefix = substr($last_box->no_box, 0, 2);
		$last_char = substr($last_box->no_box, 2, 1);
		$last_number = substr($last_box->no_box, 3);

		$new_number = (int) $last_number + 1;

		if ($new_number > 99999) {
			$new_number = 1;
			$last_char++;
		}

		$formatted_box_number = $prefix . $last_char . str_pad($new_number, 6, '0', STR_PAD_LEFT);

		return $formatted_box_number;
	}

	public function getRequest($production_plan, $Production_plan_detail_id)
	{
		$this->db->where('Production_plan', $production_plan);
		$this->db->where('Production_plan_detail_id', $Production_plan_detail_id);
		$query = $this->db->get('production_request');
		return $query->row_array();
	}

	public function updateDataPP($table, $data, $conditions)
	{
		foreach ($conditions as $key => $value) {
			$this->db->where($key, $value);
		}
		return $this->db->update($table, $data);
	}

	public function getProductionRequest(){
        return $this->db->query("SELECT * FROM `production_plan` WHERE status = 'REJECTED'")->result_array();
    }
}